<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\StockLedger;
use Illuminate\Http\Request;
use App\Services\CommonService;
use Illuminate\Support\Facades\DB;
use App\Services\PermissionService;
use App\Models\CoaInventoryMainHead;
use App\Services\StockLedgerService;
use Illuminate\Support\Facades\Auth;
use App\Services\ChartOfAccountService;
use App\Models\CoaInventoryDetailAccount;
use App\Services\CoaDetailAccountService;

class ReportController extends Controller
{
    protected $stockLedgerService;
    private $chartOfAccountService;
    private $permissionService;
    private $commonService;
    private $coaDetailAccountService;

    public function __construct(StockLedgerService $stockLedgerService, CoaDetailAccountService $coaDetailAccountService, ChartOfAccountService $chartOfAccountService, PermissionService $permissionService, CommonService $commonService)
    {
        $this->stockLedgerService = $stockLedgerService;
        $this->chartOfAccountService = $chartOfAccountService;
        $this->permissionService = $permissionService;
        $this->commonService = $commonService;
        $this->coaDetailAccountService = $coaDetailAccountService;
    }



    public function viewStockLedger()
    {
        $pageTitle = 'Stock Ledger';
        $dropDownData = $this->stockLedgerService->DropDownData();
        return view('reports.stock-ledger.stock-ledger-view', compact('dropDownData', 'pageTitle'));
    }

    public function viewPartyAccountLedger()
    {
        $pageTitle = 'Party Ledger';
        $dropDownData = $this->stockLedgerService->DropDownData();
        return view('reports.account-ledger.partyAccount-ledger-view', compact('dropDownData', 'pageTitle'));
    }

    public function getStockLedger(Request $request)
    {
        $title = 'Stock Ledger';
        $param = request()->param;
        $dropDownData = $this->stockLedgerService->DropDownData();

        if (!empty($request['product_id'])) {

            $products = CoaInventoryDetailAccount::where('id', $request['product_id'])->get();

            $fromDate = date('Y-m-d', strtotime($request['from_date']));
            $toDate = date('Y-m-d', strtotime($request['to_date']));
            $stockLedgers = StockLedger::orwhereBetween('date', [$fromDate, $toDate])
                ->orwhere('product_id', $request->product_id) // Adjust if `party_id` is a separate column
                ->orderBy('date', 'DESC')
                ->get();
        } else {

            $fromDate = date('Y-m-d', strtotime($request['from_date']));
            $toDate = date('Y-m-d', strtotime($request['to_date']));
            $stockLedgers = StockLedger::orwhereBetween('date', [$fromDate, $toDate])
                ->orwhere('product_id', $request->product_id) // Adjust if `party_id` is a separate column
                ->orderBy('date', 'DESC')
                ->get();
        }

        $product = CoaInventoryDetailAccount::where('id', $request['product_id'])->first();

        $stockLedger = StockLedger::where('product_id', $request['product_id'])->get();

        $invMainHead = CoaInventoryMainHead::where('id', $product->main_head)->value('name');

        $products = CoaInventoryDetailAccount::where('id', $request['product_id'])->pluck('name', 'id');

        return view('reports.stock-ledger.stock-ledger', compact('param', 'products', 'invMainHead', 'dropDownData', 'product', 'stockLedger', 'title'));
    }


    public function getPartyAccountLedger(Request $request)
    {
        $title = 'Party Ledger';
        $param = request()->param;
        $dropDownData = $this->stockLedgerService->DropDownData();

        $fromDate = !empty($request['from_date']) ? date('Y-m-d', strtotime($request['from_date'])) : null;
        $toDate = !empty($request['to_date']) ? date('Y-m-d', strtotime($request['to_date'])) : null;

        $accountLedgers = DB::table('account_ledgers')
            ->where('party_id', $request->party_id)
            ->whereNull('deleted_at')
            ->when($fromDate && $toDate, function ($query) use ($fromDate, $toDate) {
                return $query->whereBetween('date', [$fromDate, $toDate]);
            })
            ->orderByRaw("CASE WHEN document_number = 'OPENING BALANCE' THEN 0 ELSE 1 END")
            ->orderBy('date', 'asc')
            ->get();

        $party = DB::selectOne("SELECT * FROM detail_accounts WHERE id = ?  AND deleted_at IS NULL", [$request['party_id']]);

        $partyDetailAccount = DB::selectOne(
            "SELECT * FROM coa_det_account_details WHERE det_account_code = ? AND deleted_at IS NULL",
            [$party->id]
        );


        $subSubHead = DB::selectOne(
            "SELECT account_name FROM coa_sub_sub_heads WHERE id = ? AND deleted_at IS NULL",
            [$party->sub_sub_head]
        )?->account_name ?? null;

        $subHead = DB::selectOne(
            "SELECT account_name FROM coa_sub_heads WHERE id = ? AND deleted_at IS NULL",
            [$party->sub_head]
        )?->account_name ?? null;

        $controlHead = DB::selectOne(
            "SELECT account_name FROM coa_control_heads WHERE id = ? AND deleted_at IS NULL",
            [$party->control_head]
        )?->account_name ?? null;

        $mainHead = DB::selectOne(
            "SELECT account_name FROM coa_main_heads WHERE id = ? AND deleted_at IS NULL",
            [$party->main_head]
        )?->account_name ?? null;

        $saleMan = DB::selectOne(
            "SELECT name FROM sale_mans WHERE id = ? AND deleted_at IS NULL",
            [$party->saleMan_id]
        )?->name ?? null;


        $fetchBelts = DB::select("SELECT sector_id FROM coa_detail_account_sectors WHERE master_account_id = ?  AND deleted_at IS NULL", [$party->id]);
        $beltIds = collect($fetchBelts)->pluck('sector_id');
        $sectors = [];
        if ($beltIds->isNotEmpty()) {
            $placeholders = implode(',', array_fill(0, count($beltIds), '?'));
            $sectorsResult = DB::select("SELECT name FROM sectors WHERE id IN ($placeholders)", $beltIds->all());
            $sectors = collect($sectorsResult)->pluck('name');
        }

        $fetchAreas = DB::select("SELECT area_id FROM coa_detail_account_areas WHERE master_account_id = ?  AND deleted_at IS NULL", [$party->id]);
        $areaIds = collect($fetchAreas)->pluck('area_id');
        $areas = [];
        if ($areaIds->isNotEmpty()) {
            $placeholder = implode(',', array_fill(0, count($areaIds), '?'));
            $areasResult = DB::select("SELECT name FROM areas WHERE id IN ($placeholder)", $areaIds->all());
            $areas = collect($areasResult)->pluck('name');
        }

        // $transporterArray = $accountLedgers->pluck('transporter_id');
        // $transporters = Transporter::whereIn('id', $transporterArray)->pluck('name', 'id');

        $transporterArray = $accountLedgers->pluck('transporter_id')->filter()->unique()->values();
        $transporters = [];

        if ($transporterArray->isNotEmpty()) {
            $placeholder2 = implode(',', array_fill(0, count($transporterArray), '?'));
            $results = DB::select("SELECT id, name FROM transporters WHERE id IN ($placeholder2)", $transporterArray->all());
            $transporters = collect($results)->pluck('name', 'id');
        }

        return view('reports.account-ledger.partyAccount-ledger', compact('param', 'saleMan', 'transporters', 'areas', 'accountLedgers', 'sectors', 'mainHead', 'controlHead', 'subHead', 'subSubHead', 'partyDetailAccount', 'party', 'dropDownData', 'title'));
    }

    public function view()
    {
        $pageTitle = 'Trial Balance';
        $dropDownData = $this->coaDetailAccountService->DropDownData();
        $mainHeads = $this->chartOfAccountService->getMainHeads();
        $controlHeads = $this->chartOfAccountService->getControlHeads();
        $subHeads = $this->chartOfAccountService->getSubHeads();
        $subSubHeads = $this->chartOfAccountService->getSubSubHeads();
        return view('reports.trial-balance.view', compact('dropDownData', 'pageTitle', 'controlHeads', 'mainHeads', 'subHeads', 'subSubHeads'));
    }


    public function trialBalancePrint(Request $request)
    {
        $title = 'Trial Balance';
        $param = request()->param;
        $dropDownData = $this->stockLedgerService->DropDownData();

        $dateFrom = $request['from_date'];
        $dateTo = $request['to_date'];

        $fromDate = !empty($request['from_date']) ? date('Y-m-d', strtotime($request['from_date'])) : null;
        $toDate = !empty($request['to_date']) ? date('Y-m-d', strtotime($request['to_date'])) : null;

        $entries = DB::table('account_ledgers')
            ->join('detail_accounts', 'account_ledgers.party_id', '=', 'detail_accounts.id')
            ->leftJoin('coa_det_account_details', 'detail_accounts.id', '=', 'coa_det_account_details.det_account_code')
            ->whereBetween('account_ledgers.date', [$fromDate, $toDate])
            ->select(
                'detail_accounts.id as party_id',
                'detail_accounts.main_head',
                'detail_accounts.control_head',
                'detail_accounts.sub_head',
                'detail_accounts.sub_sub_head',
                'detail_accounts.account_name',
                DB::raw("COALESCE(coa_det_account_details.opening_balance, 0) as opening_balance"),
                DB::raw("SUM(account_ledgers.debit) as total_debit"),
                DB::raw("SUM(account_ledgers.credit) as total_credit")
            )
            ->groupBy(
                'detail_accounts.id',
                'detail_accounts.main_head',
                'detail_accounts.control_head',
                'detail_accounts.sub_head',
                'detail_accounts.sub_sub_head',
                'detail_accounts.account_name',
                'coa_det_account_details.opening_balance'
            )
            ->get()
            ->map(function ($row) {
                $debitBalance = $row->opening_balance + $row->total_debit;
                $creditBalance = $row->opening_balance - $row->total_credit;
                $closingBalance = $debitBalance - $creditBalance;

                $row->debit_balance = $debitBalance;
                $row->credit_balance = $creditBalance;
                $row->closing_dr = $closingBalance > 0 ? $closingBalance : 0;
                $row->closing_cr = $closingBalance < 0 ? abs($closingBalance) : 0;
                $closingDR = $row->closing_dr;
                return $row;
            });

        // Group hierarchy
        $grouped = $entries->groupBy('main_head')->map(function ($mainGroup, $mainHead) {
            $mainTotals = collect([
                'debit_balance' => 0,
                'credit_balance' => 0,
                'closing_dr' => 0,
                'closing_cr' => 0
            ]);

            $controlGroups = $mainGroup->groupBy('control_head')->map(function ($controlGroup, $controlHead) use (&$mainTotals) {
                $controlTotals = collect([
                    'debit_balance' => 0,
                    'credit_balance' => 0,
                    'closing_dr' => 0,
                    'closing_cr' => 0
                ]);

                $subGroups = $controlGroup->groupBy('sub_head')->map(function ($subGroup, $subHead) use (&$controlTotals) {
                    $subTotals = collect([
                        'debit_balance' => 0,
                        'credit_balance' => 0,
                        'closing_dr' => 0,
                        'closing_cr' => 0
                    ]);

                    $subSubGroups = $subGroup->groupBy('sub_sub_head')->map(function ($subSubGroup, $subSubHead) use (&$subTotals) {
                        $subSubTotals = collect([
                            'debit_balance' => 0,
                            'credit_balance' => 0,
                            'closing_dr' => 0,
                            'closing_cr' => 0
                        ]);

                        foreach ($subSubGroup as $account) {
                            $subSubTotals = $subSubTotals->merge([
                                'debit_balance' => $subSubTotals['debit_balance'] + $account->debit_balance,
                                'credit_balance' => $subSubTotals['credit_balance'] + $account->credit_balance,
                                'closing_dr' => $subSubTotals['closing_dr'] + $account->closing_dr,
                                'closing_cr' => $subSubTotals['closing_cr'] + $account->closing_cr,
                            ]);
                        }

                        $subTotals = $subTotals->merge($subSubTotals);
                        return [
                            'name' => $subSubHead,
                            'totals' => $subSubTotals,
                            'accounts' => $subSubGroup
                        ];
                    });

                    $controlTotals = $controlTotals->merge($subTotals);
                    return [
                        'name' => $subHead,
                        'totals' => $subTotals,
                        'sub_sub_heads' => $subSubGroups
                    ];
                });

                $mainTotals = $mainTotals->merge($controlTotals);
                return [
                    'name' => $controlHead,
                    'totals' => $controlTotals,
                    'sub_heads' => $subGroups
                ];
            });

            return [
                'name' => $mainHead,
                'totals' => $mainTotals,
                'control_heads' => $controlGroups
            ];
        });

        return view('reports.trial-balance.trial_balance_view', compact('grouped', 'param', 'entries', 'dateFrom', 'dateTo', 'dropDownData', 'title'));
    }

    public function productOrderSheetView()
    {
        $pageTitle = 'Product Order Sheet';
        $dropDownData = $this->stockLedgerService->DropDownData();
        return view('reports.product-order-sheet.product-order-sheet-view', compact('dropDownData', 'pageTitle'));
    }

    public function productOrderSheetPrint(Request $request)
    {
        $title = 'Product Order Sheet';
        $dropDownData = $this->stockLedgerService->DropDownData();

        $dateFrom = $request->from_date;
        $dateTo = $request->to_date;
        $status = $request->status;
        $productId = $request->product_id;

        $fromDate = !empty($dateFrom) ? date('Y-m-d', strtotime($dateFrom)) : null;
        $toDate = !empty($dateTo) ? date('Y-m-d', strtotime($dateTo)) : null;

        // Base query for sale_order_masters
        $masterQuery = DB::table('sale_order_masters')
            ->where('status', $status)
            ->whereNull('deleted_at');

        if (!empty($fromDate) && !empty($toDate)) {
            $masterQuery->whereBetween('date', [$fromDate, $toDate]);
        }

        $saleOrderMasterIds = $masterQuery->pluck('id')->toArray();

        // Query for sale_order_details
        $detailsQuery = DB::table('sale_order_details')
            ->whereIn('sale_order_master_id', $saleOrderMasterIds)
            ->whereNull('deleted_at');

        if (!empty($productId)) {
            $detailsQuery->where('product_id', $productId);
        }

        $saleOrderDetails = $detailsQuery->get();

        $totalsByPackingType = [
            'Carton' => 0,
            'Boray' => 0,
        ];

        foreach ($saleOrderDetails as $detail) {
            $type = $detail->packing_type;
            $qty = $detail->quantity;

            if (isset($totalsByPackingType[$type])) {
                $totalsByPackingType[$type] += $qty;
            }
        }



        if ($productId) {
            // If user selected a product
            $productsRaw = DB::select("
        SELECT id, name
        FROM coa_inventory_detail_accounts
        WHERE deleted_at IS NULL AND id = ?
    ", [$productId]);
        } else {
            // If no specific product is selected — fetch all undeleted
            $productsRaw = DB::select("
        SELECT id, name
        FROM coa_inventory_detail_accounts
        WHERE deleted_at IS NULL
    ");
        }

        $products = collect($productsRaw)->pluck('name', 'id');
        $createdUser = Auth::user()->id;

        $user = User::where('id', $createdUser)->value('name');


        return view('reports.product-order-sheet.product-order-sheet', compact(
            'fromDate',
            'toDate',
            'dropDownData',
            'status',
            'title',
            'productId',
            'user',
            'products',
            'saleOrderDetails',
            'totalsByPackingType'
        ));
    }

    public function orderSheetView()
    {
        $pageTitle = 'Order Sheet';
        $dropDownData = $this->stockLedgerService->DropDownData();
        return view('reports.order-sheet.order-sheet-view', compact('dropDownData', 'pageTitle'));
    }

    public function orderSheetPrint(Request $request)
    {
        $title = 'Sale Order Sheet';
        $dropDownData = $this->stockLedgerService->DropDownData();

        $dateFrom = $request->from_date;
        $dateTo = $request->to_date;
        $sector = $request->belt;
        $area = $request->area;
        $partyId = $request->party_id;
        $salesmanId = $request->saleMan_id;
        $status = $request->status;

        $fromDate = !empty($dateFrom) ? date('Y-m-d', strtotime($dateFrom)) : null;
        $toDate = !empty($dateTo) ? date('Y-m-d', strtotime($dateTo)) : null;

        // Step 1: Get Sale Order Masters based on filters
        $masterQuery = DB::table('sale_order_masters')
            ->whereNull('deleted_at');

        if (!empty($fromDate) && !empty($toDate)) {
            $masterQuery->whereBetween('date', [$fromDate, $toDate]);
        }

        if (!empty($sector)) {
            $masterQuery->where('belt', $sector);
        }

        if (!empty($area)) {
            $masterQuery->where('area', $area);
        }

        if (!empty($partyId)) {
            $masterQuery->where('party_id', $partyId);
        }

        if (!empty($salesmanId)) {
            $masterQuery->where('saleman', $salesmanId);
        }

        if (!empty($status)) {
            $masterQuery->where('status', $status);
        }

        $saleOrderMasters = $masterQuery->get();

        // Step 2: Fetch related details for each master
        $saleOrderMasterIds = $saleOrderMasters->pluck('id')->toArray();

        $saleOrderDetails = DB::table('sale_order_details')
            ->whereIn('sale_order_master_id', $saleOrderMasterIds)
            ->whereNull('deleted_at')
            ->get()
            ->groupBy('sale_order_master_id');

        $orders = $saleOrderMasters->map(function ($master) use ($saleOrderDetails) {
            $master->details = $saleOrderDetails[$master->id] ?? collect();
            return $master;
        });

        $products = CoaInventoryDetailAccount::pluck('name', 'id');
        $createdUser = Auth::user()->id;
        $user = User::where('id', $createdUser)->value('name');

        return view('reports.order-sheet.order-sheet', compact(
            'title',
            'fromDate',
            'toDate',
            'sector',
            'area',
            'products',
            'partyId',
            'salesmanId',
            'dropDownData',
            'orders',
            'user'
        ));
    }

    public function dispatchReportView()
    {
        $pageTitle = 'Dispatch Report';
        $dropDownData = $this->stockLedgerService->DropDownData();
        return view('reports.dispatch_report.dispatch-report-list', compact('dropDownData', 'pageTitle'));
    }

    public function dispatchReport(Request $request)
    {
        $title = 'Dispatch Report';
        $dropDownData = $this->stockLedgerService->DropDownData();

        $dateFrom = $request->from_date;
        $dateTo = $request->to_date;
        $sector = $request->sector;
        $area = $request->area;
        $partyId = $request->party_id;
        $salesmanId = $request->saleMan_id;
        $status = $request->status;

        $fromDate = !empty($dateFrom) ? date('Y-m-d', strtotime($dateFrom)) : null;
        $toDate = !empty($dateTo) ? date('Y-m-d', strtotime($dateTo)) : null;

        // Step 1: Filter Dispatch Note Masters
        $masterQuery = DB::table('dispatch_note_masters')->whereNull('deleted_at');

        if ($fromDate && $toDate) {
            $masterQuery->whereBetween('date', [$fromDate, $toDate]);
        }

        if (!empty($sector)) {
            $masterQuery->where('sector', $sector);
        }

        if (!empty($area)) {
            $masterQuery->where('area', $area);
        }

        if (!empty($partyId)) {
            $masterQuery->where('party_id', $partyId);
        }

        if (!empty($salesmanId)) {
            $masterQuery->where('saleman', $salesmanId);
        }

        if (!empty($status)) {
            $masterQuery->where('status', $status);
        }

        $dispatchReportMasters = $masterQuery->get();
        $dispatchNoteMasterIds = $dispatchReportMasters->pluck('id')->toArray();

        // Step 2: Fetch Dispatch Note Details
        $dispatchNoteDetails = DB::table('dispatch_note_details')
            ->whereIn('dispatch_note_master_id', $dispatchNoteMasterIds)
            ->whereNull('deleted_at')
            ->get()
            ->groupBy('dispatch_note_master_id');

        // Initialize grand totals for both packing types
        $grandTotals = [
            'Boray' => ['total_so_quantity' => 0, 'total_quantity' => 0, 'total_balance' => 0],
            'Carton' => ['total_so_quantity' => 0, 'total_quantity' => 0, 'total_balance' => 0],
        ];

        // Step 3: Attach details to each master
        $orders = $dispatchReportMasters->map(function ($master) use ($dispatchNoteDetails, &$grandTotals) {
            $details = $dispatchNoteDetails[$master->id] ?? collect();

            $packingTotals = [
                'Boray' => ['total_so_quantity' => 0, 'total_quantity' => 0, 'total_balance' => 0],
                'Carton' => ['total_so_quantity' => 0, 'total_quantity' => 0, 'total_balance' => 0],
            ];

            $details = $details->map(function ($detail) use (&$packingTotals, &$grandTotals) {
                $soQty = $detail->soQuantity ?? 0;
                $qty = $detail->quantity ?? 0;
                $balance = $soQty - $qty;
                $packingType = $detail->packing_type ?? 'Unknown';

                $detail->balance = $balance;

                // Only process if known packing type
                if (in_array($packingType, ['Boray', 'Carton'])) {
                    $packingTotals[$packingType]['total_so_quantity'] += $soQty;
                    $packingTotals[$packingType]['total_quantity'] += $qty;
                    $packingTotals[$packingType]['total_balance'] += $balance;

                    $grandTotals[$packingType]['total_so_quantity'] += $soQty;
                    $grandTotals[$packingType]['total_quantity'] += $qty;
                    $grandTotals[$packingType]['total_balance'] += $balance;
                }

                return $detail;
            });

            $master->details = $details;
            $master->packing_totals = $packingTotals;

            return $master;
        });

        $products = CoaInventoryDetailAccount::pluck('name', 'id');
        $createdUser = Auth::user()->id;
        $user = User::where('id', $createdUser)->value('name');

        return view('reports.dispatch_report.dispatch-report-view', compact(
            'title',
            'fromDate',
            'toDate',
            'sector',
            'area',
            'products',
            'partyId',
            'salesmanId',
            'dropDownData',
            'orders',
            'user',
            'grandTotals'
        ));
    }

    public function salesReportView()
    {
        $pageTitle = 'Sales Report';
        $dropDownData = $this->stockLedgerService->DropDownData();
        return view('reports.sales_report.sales-report-list', compact('dropDownData', 'pageTitle'));
    }

    public function salesReport(Request $request)
    {
        $title = 'Sale Invoice Report';
        $dropDownData = $this->stockLedgerService->DropDownData();

        $fromDate = $request->filled('from_date') ? date('Y-m-d', strtotime($request->from_date)) : null;
        $toDate = $request->filled('to_date') ? date('Y-m-d', strtotime($request->to_date)) : null;

        $filters = [
            'party_id' => $request->input('party_id', []),
            'sector' => $request->input('belt', []),
            'area' => $request->input('area', []),
            'saleman' => $request->input('saleMan_id', []),
            'delivered_to' => $request->input('delivered_to', []),
            'transporter_id' => $request->input('transporter_id', []),
        ];

        $zoneFilter = ['zones' => $request->input('zone_id', [])];

        if (!empty($zoneFilter['zones'])) {
            $zoneIds = is_array($zoneFilter['zones']) ? $zoneFilter['zones'] : [$zoneFilter['zones']];
            $sectorIdsFromZones = DB::table('sectors')
                ->whereIn('zone_id', $zoneIds)
                ->pluck('id')
                ->toArray();

            if (!empty($filters['sector'])) {
                $filters['sector'] = array_intersect($filters['sector'], $sectorIdsFromZones);
            } else {
                $filters['sector'] = $sectorIdsFromZones;
            }
        }

        // Base query
        $masterQuery = DB::table('sale_masters')->whereNull('deleted_at');

        if ($fromDate && $toDate) {
            $masterQuery->whereBetween('date', [$fromDate, $toDate]);
        }

        foreach ($filters as $column => $values) {
            if (!empty($values)) {
                $masterQuery->whereIn($column, (array) $values);
            }
        }

        // Clone base query to calculate totals across all pages
        $allMasterQuery = clone $masterQuery;
        $allMasters = $allMasterQuery->get();
        $allMasterIds = $allMasters->pluck('id')->toArray();

        $allDetails = DB::table('sale_details')
            ->whereIn('sale_master_id', $allMasterIds)
            ->whereNull('deleted_at')
            ->get();

        $totalBoraySoQuantity = $allDetails->where('packing_type', 'Boray')->sum('soQuantity');
        $totalCartonSoQuantity = $allDetails->where('packing_type', 'Carton')->sum('soQuantity');
        $totalBorayDispQuantity = $allDetails->where('packing_type', 'Boray')->sum('dispQuantity');
        $totalCartonDispQuantity = $allDetails->where('packing_type', 'Carton')->sum('dispQuantity');
        $totalNetAmount = $allMasters->sum('net_amount');

        // Paginated results for current page
        $perPage = 50;
        $dispatchReportMasters = $masterQuery->paginate($perPage)->appends($request->all());

        $dispatchNoteMasterIds = $dispatchReportMasters->pluck('id')->toArray();
        $dispatchNoteDetails = DB::table('sale_details')
            ->whereIn('sale_master_id', $dispatchNoteMasterIds)
            ->whereNull('deleted_at')
            ->get()
            ->groupBy('sale_master_id');

        $dispatchNoteNumbers = $dispatchReportMasters->pluck('dispatch_note_number')->filter()->unique()->toArray();
        $saleOrderNumbers = $dispatchReportMasters->pluck('sale_order_number')->filter()->unique()->toArray();

        $dispatchNoteMasters = DB::table('dispatch_note_masters')
            ->whereIn('id', $dispatchNoteNumbers)
            ->get()
            ->keyBy('id');

        $saleOrderMasters = DB::table('sale_order_masters')
            ->whereIn('id', $saleOrderNumbers)
            ->get()
            ->keyBy('id');

        $partyIds = $dispatchReportMasters->pluck('party_id')->filter()->unique()->toArray();
        $detailAccounts = DB::table('detail_accounts')
            ->whereIn('id', $partyIds)
            ->get()
            ->keyBy('id');

        // Enrich each master record with related data and details
        $orders = $dispatchReportMasters->map(function ($master) use (
            $dispatchNoteDetails,
            $dispatchNoteMasters,
            $saleOrderMasters,
            $detailAccounts
        ) {
            $details = $dispatchNoteDetails[$master->id] ?? collect();
            $boraySoQty = $cartonSoQty = $borayDispQty = $cartonDispQty = 0;

            foreach ($details as $detail) {
                if ($detail->packing_type === 'Boray') {
                    $boraySoQty += $detail->soQuantity ?? 0;
                    $borayDispQty += $detail->dispQuantity ?? 0;
                } elseif ($detail->packing_type === 'Carton') {
                    $cartonSoQty += $detail->soQuantity ?? 0;
                    $cartonDispQty += $detail->dispQuantity ?? 0;
                }
            }

            $master->boray_so_quantity = $boraySoQty;
            $master->carton_so_quantity = $cartonSoQty;
            $master->boray_disp_quantity = $borayDispQty;
            $master->carton_disp_quantity = $cartonDispQty;
            $master->details = $details;
            $master->dispatch_note_master = $dispatchNoteMasters[$master->dispatch_note_number] ?? null;
            $master->sale_order_master = $saleOrderMasters[$master->sale_order_number] ?? null;
            $master->party = $detailAccounts[$master->party_id] ?? null;

            return $master;
        });

        $products = CoaInventoryDetailAccount::pluck('name', 'id');
        $user = User::find(Auth::id())->name;

        return view('reports.sales_report.sales-report-view', compact(
            'fromDate',
            'toDate',
            'dropDownData',
            'products',
            'title',
            'orders',
            'user',
            'totalBoraySoQuantity',
            'totalCartonSoQuantity',
            'totalBorayDispQuantity',
            'totalCartonDispQuantity',
            'totalNetAmount',
            'filters',
            'dispatchReportMasters' // paginated data
        ));
    }


    public function productSaleReport()
    {
        $pageTitle = 'Product Sale Report';
        $dropDownData = $this->stockLedgerService->DropDownData();
        return view('reports.product_sales_report.product-sales-report-list', compact('dropDownData', 'pageTitle'));
    }

    public function productSaleReportPrintold(Request $request)
    {
        $title = 'Product Sale Report';
        $dropDownData = $this->stockLedgerService->DropDownData();

        $dateFrom = $request->from_date;
        $dateTo = $request->to_date;
        $status = $request->status;
        $productId = $request->product_id;

        $fromDate = !empty($dateFrom) ? date('Y-m-d', strtotime($dateFrom)) : null;
        $toDate = !empty($dateTo) ? date('Y-m-d', strtotime($dateTo)) : null;

        // Base query for sale_masters
        $masterQuery = DB::table('sale_masters')
            ->whereNull('deleted_at');

        if (!empty($fromDate) && !empty($toDate)) {
            $masterQuery->whereBetween('date', [$fromDate, $toDate]);
        }

        $saleMasterIds = $masterQuery->pluck('id')->toArray();

        // Query for sale_details
        $detailsQuery = DB::table('sale_details')
            ->whereIn('sale_master_id', $saleMasterIds)
            ->whereNull('deleted_at');

        if (!empty($productId)) {
            $detailsQuery->where('product_id', $productId);
        }

        $saleOrderDetails = $detailsQuery->get();

        // Initialize packing type totals
        $totalsByPackingType = [
            'Carton' => 0,
            'Boray' => 0,
        ];

        // Load all relevant products (either filtered or all)
        $productsRaw = DB::table('coa_inventory_detail_accounts')
            ->whereNull('deleted_at')
            ->when($productId, function ($query) use ($productId) {
                $query->where('id', $productId);
            })
            ->get();

        $products = $productsRaw->pluck('name', 'id');

        // Product-wise totals
        $productTotals = [];
        foreach ($saleOrderDetails as $detail) {
            $prodId = $detail->product_id;
            $amount = $detail->amount ?? 0;
            $qty = $detail->quantity ?? 0;

            // Total by packing type
            if (!empty($detail->packing_type) && isset($totalsByPackingType[$detail->packing_type])) {
                $totalsByPackingType[$detail->packing_type] += $qty;
            }

            if (!isset($productTotals[$prodId])) {
                $productName = $products[$prodId] ?? 'Unknown';
                $productTotals[$prodId] = [
                    'name' => $productName,
                    'quantity' => 0,
                    'amount' => 0,
                ];
            }

            $productTotals[$prodId]['quantity'] += $qty;
            $productTotals[$prodId]['amount'] += $amount;
        }

        // Grand totals
        $totalQuantity = array_sum(array_column($productTotals, 'quantity'));
        $totalAmount = array_sum(array_column($productTotals, 'amount'));

        $createdUser = Auth::user()->id;
        $user = User::where('id', $createdUser)->value('name');

        return view('reports.product_sales_report.product-sales-report-view', compact(
            'fromDate',
            'toDate',
            'dropDownData',
            'status',
            'title',
            'productId',
            'user',
            'products',
            'saleOrderDetails',
            'totalsByPackingType',
            'productTotals',
            'totalQuantity',
            'totalAmount'
        ));
    }

    public function productSaleReportPrint(Request $request)
    {
        $title = 'Product Sale Report';
        $dropDownData = $this->stockLedgerService->DropDownData();

        // Parse date range
        $fromDate = $request->filled('from_date') ? date('Y-m-d', strtotime($request->from_date)) : null;
        $toDate = $request->filled('to_date') ? date('Y-m-d', strtotime($request->to_date)) : null;

        // Normalize filters to arrays
        $productIds = is_array($request->product_id) ? $request->product_id : (array) $request->product_id;
        $categories = is_array($request->level_4) ? $request->level_4 : (array) $request->level_4;
        $priceTags  = is_array($request->price_tag) ? $request->price_tag : (array) $request->price_tag;

        // Step 1: Get Sale Master IDs in the date range
        $masterQuery = DB::table('sale_masters')->whereNull('deleted_at');
        if ($fromDate && $toDate) {
            $masterQuery->whereBetween('date', [$fromDate, $toDate]);
        }
        $saleMasterIds = $masterQuery->pluck('id')->toArray();

        // Step 2: Filter products by product_id, category, and priceTag_id
        $productsRaw = DB::table('coa_inventory_detail_accounts')
            ->whereNull('deleted_at')
            ->when(!empty($productIds), fn($q) => $q->whereIn('id', $productIds))
            ->when(!empty($categories), fn($q) => $q->whereIn('sub_sub_head', $categories))
            ->when(!empty($priceTags), fn($q) => $q->whereIn('priceTag_id', $priceTags))
            ->get();

        $filteredProductIds = $productsRaw->pluck('id')->toArray();

        // Step 3: Fetch sale details with filtered master and product IDs
        $detailsQuery = DB::table('sale_details')
            ->whereIn('sale_master_id', $saleMasterIds)
            ->whereNull('deleted_at');

        if (!empty($filteredProductIds)) {
            $detailsQuery->whereIn('product_id', $filteredProductIds);
        }

        $saleOrderDetails = $detailsQuery->get();

        // Step 4: Initialize packing type totals
        $totalsByPackingType = [
            'Carton' => 0,
            'Boray' => 0,
        ];

        // Step 5: Load product names for reference
        $products = DB::table('coa_inventory_detail_accounts')
            ->whereNull('deleted_at')
            ->pluck('name', 'id');

        // Step 6: Calculate product-wise totals
        $productTotals = [];
        foreach ($saleOrderDetails as $detail) {
            $prodId = $detail->product_id;
            $qty = $detail->quantity ?? 0;
            $amount = $detail->amount ?? 0;

            // Packing type total
            if (!empty($detail->packing_type) && isset($totalsByPackingType[$detail->packing_type])) {
                $totalsByPackingType[$detail->packing_type] += $qty;
            }

            // Product total
            if (!isset($productTotals[$prodId])) {
                $productTotals[$prodId] = [
                    'name' => $products[$prodId] ?? 'Unknown',
                    'quantity' => 0,
                    'amount' => 0,
                ];
            }

            $productTotals[$prodId]['quantity'] += $qty;
            $productTotals[$prodId]['amount'] += $amount;
        }

        // Step 7: Calculate grand totals
        $totalQuantity = array_sum(array_column($productTotals, 'quantity'));
        $totalAmount = array_sum(array_column($productTotals, 'amount'));

        // Step 8: Get the user who generated the report
        $user = Auth::user()?->name ?? 'Unknown';

        // Step 9: Return view with data
        return view('reports.product_sales_report.product-sales-report-view', compact(
            'fromDate',
            'toDate',
            'dropDownData',
            'title',
            'productIds',
            'categories',
            'priceTags',
            'user',
            'products',
            'saleOrderDetails',
            'totalsByPackingType',
            'productTotals',
            'totalQuantity',
            'totalAmount'
        ));
    }

    public function salesReturnReportView()
    {
        $pageTitle = 'Sales Return Report';
        $dropDownData = $this->stockLedgerService->DropDownData();
        return view('reports.salesReturn_report.salesReturn-report-list', compact('dropDownData', 'pageTitle'));
    }


    public function salesReturnReport(Request $request)
    {
        $title = 'Sale Return Report';
        $dropDownData = $this->stockLedgerService->DropDownData();

        $fromDate = $request->filled('from_date') ? date('Y-m-d', strtotime($request->from_date)) : null;
        $toDate = $request->filled('to_date') ? date('Y-m-d', strtotime($request->to_date)) : null;

        $filters = [
            'party_id' => $request->input('party_id', []),
            'country' => $request->input('country_id', []),
            'sector' => $request->input('sector', []),
            'area' => $request->input('area', []),
            'saleman' => $request->input('saleman', []),
            'delivered_to' => $request->input('deliverd_to', []),
            'transporter_id' => $request->input('transporter_id', []),
        ];

        // Zone-sector filtering logic
        $zoneFilter = ['zones' => $request->input('zone_id', [])];
        if (!empty($zoneFilter['zones'])) {
            $zoneIds = (array) $zoneFilter['zones'];
            $sectorIdsFromZones = DB::table('sectors')
                ->whereIn('zone_id', $zoneIds)
                ->pluck('id')
                ->toArray();

            $filters['sector'] = !empty($filters['sector'])
                ? array_intersect($filters['sector'], $sectorIdsFromZones)
                : $sectorIdsFromZones;
        }

        // Base query
        $masterQuery = DB::table('sale_return_masters')->whereNull('deleted_at');
        if ($fromDate && $toDate) {
            $masterQuery->whereBetween('date', [$fromDate, $toDate]);
        }

        foreach ($filters as $column => $values) {
            if (!empty($values)) {
                $masterQuery->whereIn($column, (array) $values);
            }
        }

        // Clone for grand totals
        $allMasterQuery = clone $masterQuery;
        $allMasters = $allMasterQuery->get();
        $allMasterIds = $allMasters->pluck('id')->toArray();

        $allDetails = DB::table('sale_return_details')
            ->whereIn('sale_return_master_id', $allMasterIds)
            ->whereNull('deleted_at')
            ->get();

        $totalBorayQuantity = $allDetails->where('packing_type', 'Boray')->sum('quantity');
        $totalCartonQuantity = $allDetails->where('packing_type', 'Carton')->sum('quantity');
        $totalNetAmount = $allMasters->sum('net_amount');

        // Paginated results
        $perPage = 50;
        $dispatchReportMasters = $masterQuery->paginate($perPage)->appends($request->all());
        $currentPageIds = $dispatchReportMasters->pluck('id')->toArray();

        $details = DB::table('sale_return_details')
            ->whereIn('sale_return_master_id', $currentPageIds)
            ->whereNull('deleted_at')
            ->get()
            ->groupBy('sale_return_master_id');

        // Related data
        $grnIds = $dispatchReportMasters->pluck('grn_no')->filter()->unique()->toArray();
        $saleInvoiceIds = $dispatchReportMasters->pluck('sale_invoice_number')->filter()->unique()->toArray();
        $grnMasters = DB::table('goods_received_note_masters')
            ->whereIn('id', $grnIds)
            ->get()
            ->keyBy('id');

        $saleMasters = DB::table('sale_masters')
            ->whereIn('id', $saleInvoiceIds)
            ->get()
            ->keyBy('id');
        $partyIds = $dispatchReportMasters->pluck('party_id')->filter()->unique()->toArray();
        $detailAccounts = DB::table('detail_accounts')
            ->whereIn('id', $partyIds)
            ->get()
            ->keyBy('id');

        // Final order mapping
        $orders = $dispatchReportMasters->map(function ($master) use (
            $details,
            $grnMasters,
            $saleMasters,
            $detailAccounts
        ) {
            $entryDetails = $details[$master->id] ?? collect();

            $borayQty = $entryDetails->where('packing_type', 'Boray')->sum('quantity');
            $cartonQty = $entryDetails->where('packing_type', 'Carton')->sum('quantity');

            $master->boray_quantity = $borayQty;
            $master->carton_quantity = $cartonQty;
            $master->details = $entryDetails;
            $master->grn_master = $grnMasters[$master->grn_no] ?? null;
            $master->sale_master = $saleMasters[$master->sale_invoice_number] ?? null;
            $master->party = $detailAccounts[$master->party_id] ?? null;

            return $master;
        });

        // For product names
        $products = CoaInventoryDetailAccount::pluck('name', 'id');
        $user = User::find(Auth::id())->name;

        return view('reports.salesReturn_report.salesReturn-report-view', compact(
            'fromDate',
            'toDate',
            'dropDownData',
            'products',
            'title',
            'orders',
            'user',
            'totalBorayQuantity',
            'totalCartonQuantity',
            'totalNetAmount',
            'filters',
            'dispatchReportMasters'
        ));
    }

    public function purchaseReportView()
    {
        $pageTitle = 'Purchase Report';
        $dropDownData = $this->stockLedgerService->DropDownData();
        return view('reports.purchase_report.purchase-report-list', compact('dropDownData', 'pageTitle'));
    }


    public function purchaseReport(Request $request)
    {
        $title = 'Purchase Report';
        $dropDownData = $this->stockLedgerService->DropDownData();

        $fromDate = $request->filled('from_date') ? date('Y-m-d', strtotime($request->from_date)) : null;
        $toDate = $request->filled('to_date') ? date('Y-m-d', strtotime($request->to_date)) : null;

        $filters = [
            'party_id' => $request->input('party_id', []),
            'transporter_id' => $request->input('transporter_id', []),
        ];


        // Base query
        $masterQuery = DB::table('purchase_masters')->whereNull('deleted_at');
        if ($fromDate && $toDate) {
            $masterQuery->whereBetween('date', [$fromDate, $toDate]);
        }

        foreach ($filters as $column => $values) {
            if (!empty($values)) {
                $masterQuery->whereIn($column, (array) $values);
            }
        }

        // Clone for grand totals
        $allMasterQuery = clone $masterQuery;
        $allMasters = $allMasterQuery->get();
        $allMasterIds = $allMasters->pluck('id')->toArray();

        $allDetails = DB::table('purchase_details')
            ->whereIn('purchase_master_id', $allMasterIds)
            ->whereNull('deleted_at')
            ->get();
        $totalNetAmount = $allMasters->sum('net_amount');

        // Paginated results
        $perPage = 50;
        $purchaseMasters = $masterQuery->paginate($perPage)->appends($request->all());
        $currentPageIds = $purchaseMasters->pluck('id')->toArray();

        $details = DB::table('purchase_details')
            ->whereIn('purchase_master_id', $currentPageIds)
            ->whereNull('deleted_at')
            ->get()
            ->groupBy('purchase_master_id');

        // Related data
        $grnIds = $purchaseMasters->pluck('grn_no')->filter()->unique()->toArray();
        $purchaseOrders = $purchaseMasters->pluck('purchase_order_no')->filter()->unique()->toArray();
        $grnMasters = DB::table('goods_received_note_masters')
            ->whereIn('id', $grnIds)
            ->get()
            ->keyBy('id');

        $pOMasters = DB::table('purchase_order_masters')
            ->whereIn('id', $purchaseOrders)
            ->get()
            ->keyBy('id');
        $partyIds = $purchaseMasters->pluck('party_id')->filter()->unique()->toArray();
        $detailAccounts = DB::table('detail_accounts')
            ->whereIn('id', $partyIds)
            ->get()
            ->keyBy('id');

        // Final order mapping
        $orders = $purchaseMasters->map(function ($master) use (
            $details,
            $grnMasters,
            $pOMasters,
            $detailAccounts
        ) {
            $entryDetails = $details[$master->id] ?? collect();

            $master->details = $entryDetails;
            $master->grn_master = $grnMasters[$master->grn_no] ?? null;
            $master->poMasters = $pOMasters[$master->purchase_order_no] ?? null;
            $master->party = $detailAccounts[$master->party_id] ?? null;

            return $master;
        });

        // For product names
        $products = CoaInventoryDetailAccount::pluck('name', 'id');
        $user = User::find(Auth::id())->name;

        return view('reports.purchase_report.purchase-report-view', compact(
            'fromDate',
            'toDate',
            'dropDownData',
            'products',
            'title',
            'orders',
            'user',
            'totalNetAmount',
            'filters',
            'purchaseMasters'
        ));
    }


    public function purchaseReturnReportView()
    {
        $pageTitle = 'Purchase Return Report';
        $dropDownData = $this->stockLedgerService->DropDownData();
        return view('reports.purchaseReturn_report.purchaseReturn-report-list', compact('dropDownData', 'pageTitle'));
    }


    public function purchaseReturnReport(Request $request)
    {
        $title = 'Purchase Return Report';
        $dropDownData = $this->stockLedgerService->DropDownData();

        $fromDate = $request->filled('from_date') ? date('Y-m-d', strtotime($request->from_date)) : null;
        $toDate = $request->filled('to_date') ? date('Y-m-d', strtotime($request->to_date)) : null;

        $filters = [
            'party_id' => $request->input('party_id', []),
            'transporter_id' => $request->input('transporter_id', []),
        ];


        // Base query
        $masterQuery = DB::table('purchase_return_masters')->whereNull('deleted_at');
        if ($fromDate && $toDate) {
            $masterQuery->whereBetween('date', [$fromDate, $toDate]);
        }

        foreach ($filters as $column => $values) {
            if (!empty($values)) {
                $masterQuery->whereIn($column, (array) $values);
            }
        }

        // Clone for grand totals
        $allMasterQuery = clone $masterQuery;
        $allMasters = $allMasterQuery->get();
        $allMasterIds = $allMasters->pluck('id')->toArray();

        $allDetails = DB::table('purchase_return_details')
            ->whereIn('purchase_return_master_id', $allMasterIds)
            ->whereNull('deleted_at')
            ->get();
        $totalNetAmount = $allMasters->sum('net_amount');

        // Paginated results
        $perPage = 50;
        $purchaseMasters = $masterQuery->paginate($perPage)->appends($request->all());
        $currentPageIds = $purchaseMasters->pluck('id')->toArray();

        $details = DB::table('purchase_return_details')
            ->whereIn('purchase_return_master_id', $currentPageIds)
            ->whereNull('deleted_at')
            ->get()
            ->groupBy('purchase_return_master_id');

        // Related data
        $grnIds = $purchaseMasters->pluck('purchase_invoice_no')->filter()->unique()->toArray();
        $purchaseOrders = $purchaseMasters->pluck('purchase_order_no')->filter()->unique()->toArray();
        $purchaseInvoiceMasters = DB::table('purchase_masters')
            ->whereIn('id', $grnIds)
            ->get()
            ->keyBy('id');

        $pOMasters = DB::table('purchase_order_masters')
            ->whereIn('id', $purchaseOrders)
            ->get()
            ->keyBy('id');
        $partyIds = $purchaseMasters->pluck('party_id')->filter()->unique()->toArray();
        $detailAccounts = DB::table('detail_accounts')
            ->whereIn('id', $partyIds)
            ->get()
            ->keyBy('id');

        // Final order mapping
        $orders = $purchaseMasters->map(function ($master) use (
            $details,
            $purchaseInvoiceMasters,
            $pOMasters,
            $detailAccounts
        ) {
            $entryDetails = $details[$master->id] ?? collect();

            $master->details = $entryDetails;
            $master->purchase_masters = $purchaseInvoiceMasters[$master->purchase_invoice_no] ?? null;
            $master->poMasters = $pOMasters[$master->purchase_order_no] ?? null;
            $master->party = $detailAccounts[$master->party_id] ?? null;

            return $master;
        });

        // For product names
        $products = CoaInventoryDetailAccount::pluck('name', 'id');
        $user = User::find(Auth::id())->name;

        return view('reports.purchaseReturn_report.purchaseReturn-report-view', compact(
            'fromDate',
            'toDate',
            'dropDownData',
            'products',
            'title',
            'orders',
            'user',
            'totalNetAmount',
            'filters',
            'purchaseMasters'
        ));
    }

    public function purchaseOrderReportView()
    {
        $pageTitle = 'Purchase Order Report';
        $dropDownData = $this->stockLedgerService->DropDownData();
        return view('reports.purchase_order_report.purchaseOrder-report-list', compact('dropDownData', 'pageTitle'));
    }


    public function purchaseOrderReport(Request $request)
    {
        $title = 'Purchase Order Report';
        $dropDownData = $this->stockLedgerService->DropDownData();

        $fromDate = $request->filled('from_date') ? date('Y-m-d', strtotime($request->from_date)) : null;
        $toDate = $request->filled('to_date') ? date('Y-m-d', strtotime($request->to_date)) : null;

        $filters = [
            'party_id' => $request->input('party_id', []),
            'status' => $request->input('status', []),
        ];

        // Base query
        $masterQuery = DB::table('purchase_order_masters')->whereNull('deleted_at');
        if ($fromDate && $toDate) {
            $masterQuery->whereBetween('date', [$fromDate, $toDate]);
        }

        foreach ($filters as $column => $values) {
            if (!empty($values)) {
                $masterQuery->whereIn($column, (array) $values);
            }
        }

        // Clone for grand totals
        $allMasterQuery = clone $masterQuery;
        $allMasters = $allMasterQuery->get();
        $allMasterIds = $allMasters->pluck('id')->toArray();

        $allDetails = DB::table('purchase_order_details')
            ->whereIn('purchase_order_master_id', $allMasterIds)
            ->whereNull('deleted_at')
            ->get();

        $totalNetAmount = $allMasters->sum('total_amount');

        // Paginated results
        $perPage = 50;
        $purchaseOrderMasters = $masterQuery->paginate($perPage)->appends($request->all());
        $currentPageIds = $purchaseOrderMasters->pluck('id')->toArray();

        $details = DB::table('purchase_order_details')
            ->whereIn('purchase_order_master_id', $currentPageIds)
            ->whereNull('deleted_at')
            ->get()
            ->groupBy('purchase_order_master_id');

        // Related data
        $partyIds = $purchaseOrderMasters->pluck('party_id')->filter()->unique()->toArray();
        $detailAccounts = DB::table('detail_accounts')
            ->whereIn('id', $partyIds)
            ->get()
            ->keyBy('id');

        // Final order mapping with per-order total quantity
        $orders = $purchaseOrderMasters->map(function ($master) use (
            $details,
            $detailAccounts
        ) {
            $entryDetails = $details[$master->id] ?? collect();
            $master->details = $entryDetails;
            $master->party = $detailAccounts[$master->party_id] ?? null;

            // ✅ Add total quantity for this master
            $master->total_quantity = $entryDetails->sum('quantity');

            return $master;
        });

        // ✅ Grand total quantity of visible (paginated) orders
        $totalVisibleQuantity = $orders->sum('total_quantity');

        // Product names
        $products = CoaInventoryDetailAccount::pluck('name', 'id');
        $user = User::find(Auth::id())->name;

        return view('reports.purchase_order_report.purchaseOrder-report-view', compact(
            'fromDate',
            'toDate',
            'dropDownData',
            'products',
            'title',
            'orders',
            'user',
            'totalNetAmount',
            'filters',
            'purchaseOrderMasters',
            'totalVisibleQuantity' // ✅ available in the view
        ));
    }

    public function grnReportView()
    {
        $pageTitle = 'GRN Report';
        $dropDownData = $this->stockLedgerService->DropDownData();
        return view('reports.grn_report.grn-report-list', compact('dropDownData', 'pageTitle'));
    }

    public function grnReport(Request $request)
    {
        $title = 'GRN Report';
        $dropDownData = $this->stockLedgerService->DropDownData();

        $fromDate = $request->filled('from_date') ? date('Y-m-d', strtotime($request->from_date)) : null;
        $toDate = $request->filled('to_date') ? date('Y-m-d', strtotime($request->to_date)) : null;

        $filters = [
            'party_id' => $request->input('party_id', []),
            'transporter_id' => $request->input('transporter_id', []),
            'purchase_order_no' => $request->input('purchase_order_no', []),
        ];

        // Base query
        $masterQuery = DB::table('goods_received_note_masters')->whereNull('deleted_at');
        if ($fromDate && $toDate) {
            $masterQuery->whereBetween('date', [$fromDate, $toDate]);
        }

        foreach ($filters as $column => $values) {
            if (!empty($values)) {
                $masterQuery->whereIn($column, (array) $values);
            }
        }

        // Clone for grand totals
        $allMasterQuery = clone $masterQuery;
        $allMasters = $allMasterQuery->get();
        $allMasterIds = $allMasters->pluck('id')->toArray();

        $allDetails = DB::table('goods_received_note_details')
            ->whereIn('master_id', $allMasterIds)
            ->whereNull('deleted_at')
            ->get();

        $totalNetAmount = $allMasters->sum('net_amount');

        // Paginated results
        $perPage = 50;
        $grnMasters = $masterQuery->paginate($perPage)->appends($request->all());
        $currentPageIds = $grnMasters->pluck('id')->toArray();

        $details = DB::table('goods_received_note_details')
            ->whereIn('master_id', $currentPageIds)
            ->whereNull('deleted_at')
            ->get()
            ->groupBy('master_id');

        // Related data
        $purchaseOrders = $grnMasters->pluck('purchase_order_no')->filter()->unique()->toArray();
        $pOMasters = DB::table('purchase_order_masters')
            ->whereIn('id', $purchaseOrders)
            ->get()
            ->keyBy('id');

        $partyIds = $grnMasters->pluck('party_id')->filter()->unique()->toArray();
        $detailAccounts = DB::table('detail_accounts')
            ->whereIn('id', $partyIds)
            ->get()
            ->keyBy('id');

        // Final order mapping with total_quantity
        $orders = $grnMasters->map(function ($master) use (
            $details,
            $pOMasters,
            $detailAccounts
        ) {
            $entryDetails = $details[$master->id] ?? collect();

            $master->details = $entryDetails;
            $master->poMasters = $pOMasters[$master->purchase_order_no] ?? null;
            $master->party = $detailAccounts[$master->party_id] ?? null;

            return $master;
        });

        // ✅ Total quantity for paginated (visible) GRNs
        $totalVisibleQuantity = $orders->sum('total_quantity');

        // Product names
        $products = CoaInventoryDetailAccount::pluck('name', 'id');
        $user = User::find(Auth::id())->name;

        return view('reports.grn_report.grn-report-view', compact(
            'fromDate',
            'toDate',
            'dropDownData',
            'products',
            'title',
            'orders',
            'user',
            'totalNetAmount',
            'filters',
            'grnMasters',
            'totalVisibleQuantity' // ✅ passed to the view
        ));
    }
}
