<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Sector;
use App\Models\SaleMaster;
use App\Models\StockLedger;
use Illuminate\Http\Request;
use App\Services\CommonService;
use App\Models\CoaDetailAccount;
use App\Models\DeliveredToParties;
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
                ->orderBy('date', 'asc')
                ->get();
        } else {

            $fromDate = date('Y-m-d', strtotime($request['from_date']));
            $toDate = date('Y-m-d', strtotime($request['to_date']));
            $stockLedgers = StockLedger::orwhereBetween('date', [$fromDate, $toDate])
                ->orwhere('product_id', $request->product_id) // Adjust if `party_id` is a separate column
                ->orderBy('date', 'asc')
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
        $pageTitle = 'Party Ledger';
        $dropDownData = $this->coaDetailAccountService->DropDownData();
        $mainHeads = $this->chartOfAccountService->getMainHeads();
        $controlHeads = $this->chartOfAccountService->getControlHeads();
        $subHeads = $this->chartOfAccountService->getSubHeads();
        $subSubHeads = $this->chartOfAccountService->getSubSubHeads();
        return view('reports.trial-balance.view', compact('dropDownData', 'pageTitle', 'controlHeads', 'mainHeads', 'subHeads', 'subSubHeads'));
    }

    // public function trialBalancePrint(Request $request)
    // {
    //     $title = 'Trial Balance';
    //     $param = request()->param;
    //     $dropDownData = $this->stockLedgerService->DropDownData();

    //     $dateFrom = $request['from_date'];
    //     $dateTo = $request['to_date'];

    //     $fromDate = !empty($request['from_date']) ? date('Y-m-d', strtotime($request['from_date'])) : null;
    //     $toDate = !empty($request['to_date']) ? date('Y-m-d', strtotime($request['to_date'])) : null;

    //     $accountLedgers = DB::table('account_ledgers')
    //         ->whereBetween('date', [$fromDate, $toDate])

    //         ->orderBy('date', 'asc')
    //         ->get();
    //     $rew = $accountLedgers->pluck('party_id');

    //     // $entries = DB::table('account_ledgers')
    //     //     ->join('detail_accounts', 'account_ledgers.party_id', '=', 'detail_accounts.id')
    //     //     ->select(
    //     //         'detail_accounts.main_head',
    //     //         'detail_accounts.control_head',
    //     //         'detail_accounts.sub_head',
    //     //         'detail_accounts.sub_sub_head',
    //     //         'detail_accounts.account_name',
    //     //         DB::raw("SUM(CASE WHEN date < '$fromDate' THEN debit ELSE 0 END) as opening_debit"),
    //     //         DB::raw("SUM(CASE WHEN date < '$fromDate' THEN credit ELSE 0 END) as opening_credit"),
    //     //         DB::raw("SUM(CASE WHEN date BETWEEN '$fromDate' AND '$toDate' THEN debit ELSE 0 END) as period_debit"),
    //     //         DB::raw("SUM(CASE WHEN date BETWEEN '$fromDate' AND '$toDate' THEN credit ELSE 0 END) as period_credit")
    //     //     )
    //     //     ->groupBy(
    //     //         'detail_accounts.main_head',
    //     //         'detail_accounts.control_head',
    //     //         'detail_accounts.sub_head',
    //     //         'detail_accounts.sub_sub_head',
    //     //         'detail_accounts.account_name'
    //     //     )
    //     //     ->get()
    //     //     ->map(function ($row) {
    //     //         $row->closing_debit = max($row->opening_debit + $row->period_debit - $row->opening_credit - $row->period_credit, 0);
    //     //         $row->closing_credit = max($row->opening_credit + $row->period_credit - $row->opening_debit - $row->period_debit, 0);
    //     //         return $row;
    //     //     });

    //     // // Group by hierarchy
    //     // $grouped = $entries->groupBy('main_head')->map(function ($mainGroup) {
    //     //     return $mainGroup->groupBy('control_head')->map(function ($controlGroup) {
    //     //         return $controlGroup->groupBy('sub_head')->map(function ($subGroup) {
    //     //             return $subGroup->groupBy('sub_sub_head');
    //     //         });
    //     //     });
    //     // });

    //     $entries = DB::table('account_ledgers')
    //     ->join('detail_accounts', 'account_ledgers.party_id', '=', 'detail_accounts.id')
    //     ->leftJoin('coa_det_account_details', 'coa_det_account_details.det_account_code', '=', 'detail_accounts.id')
    //     ->select(
    //         'detail_accounts.main_head',
    //         'detail_accounts.control_head',
    //         'detail_accounts.sub_head',
    //         'detail_accounts.sub_sub_head',
    //         'detail_accounts.account_name',
    //         'coa_det_account_details.opening_balance',

    //         DB::raw("SUM(CASE WHEN date BETWEEN '$fromDate' AND '$toDate' THEN debit ELSE 0 END) as period_debit"),
    //         DB::raw("SUM(CASE WHEN date BETWEEN '$fromDate' AND '$toDate' THEN credit ELSE 0 END) as period_credit")
    //     )
    //     ->groupBy(
    //         'detail_accounts.main_head',
    //         'detail_accounts.control_head',
    //         'detail_accounts.sub_head',
    //         'detail_accounts.sub_sub_head',
    //         'detail_accounts.account_name',
    //         'coa_det_account_details.opening_balance'
    //     )
    //     ->get()
    //     ->map(function ($row) {
    //         $opening = floatval($row->opening_balance);

    //         // ✅ Always add opening_balance to debit, subtract from credit
    //         $debit_balance = $opening + $row->period_debit ;
    //         $credit_balance =$opening - $row->period_credit;

    //         // ✅ Closing balance = difference
    //         $closing_balance = $debit_balance + $credit_balance;

    //         $row->debit_balance = $debit_balance;
    //         $row->credit_balance = $credit_balance;
    //         $row->closing_debit = $closing_balance > 0 ? $closing_balance : 0;
    //         $row->closing_credit = $closing_balance < 0 ? abs($closing_balance) : 0;

    //         return $row;
    //     });
    //     // Now create grouped structure WITH SUMS
    //     $grouped = [];

    //     foreach ($entries as $entry) {
    //         $main = $entry->main_head;
    //         $control = $entry->control_head;
    //         $sub = $entry->sub_head;
    //         $subSub = $entry->sub_sub_head;

    //         // $grouped[$main]['_totals'] = self::addSums($grouped[$main]['_totals'] ?? null, $entry);
    //         if (!isset($grouped[$main])) {
    //             $grouped[$main] = [];
    //         }
    //         $grouped[$main]['_totals'] = self::addSums($grouped[$main]['_totals'] ?? null, $entry);
    //         $grouped[$main]['controls'][$control]['_totals'] = self::addSums($grouped[$main]['controls'][$control]['_totals'] ?? null, $entry);
    //         $grouped[$main]['controls'][$control]['subs'][$sub]['_totals'] = self::addSums($grouped[$main]['controls'][$control]['subs'][$sub]['_totals'] ?? null, $entry);
    //         $grouped[$main]['controls'][$control]['subs'][$sub]['subsubs'][$subSub]['_totals'] = self::addSums($grouped[$main]['controls'][$control]['subs'][$sub]['subsubs'][$subSub]['_totals'] ?? null, $entry);

    //         $grouped[$main]['controls'][$control]['subs'][$sub]['subsubs'][$subSub]['accounts'][] = $entry;
    //     }




    //     return view('reports.trial-balance.trial_balance_view', compact('grouped', 'param', 'accountLedgers', 'dateFrom', 'dateTo', 'dropDownData', 'title'));
    // }

    // private static function addSums($current, $entry)
    // {
    //     $current = $current ?? (object)[
    //         'opening_debit' => 0,
    //         'opening_credit' =>0,
    //         'period_debit' =>  0,
    //         'period_credit' => 0,
    //         'closing_debit' => 0,
    //         'closing_credit' => 0
    //     ];

    //     $current->opening_debit += $entry->opening_debit;
    //     $current->opening_credit += $entry->opening_credit;
    //     $current->period_debit += $entry->period_debit;
    //     $current->period_credit += $entry->period_credit;
    //     $current->closing_debit += $entry->closing_debit;
    //     $current->closing_credit += $entry->closing_credit;

    //     return $current;
    // }


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

    // public function salesReport(Request $request)
    // {
    //     $title = 'Sales Report';
    //     $dropDownData = $this->stockLedgerService->DropDownData();

    //     $dateFrom = $request->from_date;
    //     $dateTo = $request->to_date;
    //     $sector = $request->sector;
    //     $area = $request->area;
    //     $partyId = $request->party_id;
    //     $salesmanId = $request->saleMan_id;
    //     $status = $request->status;

    //     $fromDate = !empty($dateFrom) ? date('Y-m-d', strtotime($dateFrom)) : null;
    //     $toDate = !empty($dateTo) ? date('Y-m-d', strtotime($dateTo)) : null;

    //     // Step 1: Filter Dispatch Note Masters
    //     $masterQuery = DB::table('dispatch_note_masters')->whereNull('deleted_at');

    //     if ($fromDate && $toDate) {
    //         $masterQuery->whereBetween('date', [$fromDate, $toDate]);
    //     }

    //     if (!empty($sector)) {
    //         $masterQuery->where('sector', $sector);
    //     }

    //     if (!empty($area)) {
    //         $masterQuery->where('area', $area);
    //     }

    //     if (!empty($partyId)) {
    //         $masterQuery->where('party_id', $partyId);
    //     }

    //     if (!empty($salesmanId)) {
    //         $masterQuery->where('saleman', $salesmanId);
    //     }

    //     if (!empty($status)) {
    //         $masterQuery->where('status', $status);
    //     }

    //     $dispatchReportMasters = $masterQuery->get();
    //     $dispatchNoteMasterIds = $dispatchReportMasters->pluck('id')->toArray();

    //     // Step 2: Fetch Dispatch Note Details
    //     $dispatchNoteDetails = DB::table('dispatch_note_details')
    //         ->whereIn('dispatch_note_master_id', $dispatchNoteMasterIds)
    //         ->whereNull('deleted_at')
    //         ->get()
    //         ->groupBy('dispatch_note_master_id');

    //     // Initialize grand totals for both packing types
    //     $grandTotals = [
    //         'Boray' => ['total_so_quantity' => 0, 'total_quantity' => 0, 'total_balance' => 0],
    //         'Carton' => ['total_so_quantity' => 0, 'total_quantity' => 0, 'total_balance' => 0],
    //     ];

    //     // Step 3: Attach details to each master
    //     $orders = $dispatchReportMasters->map(function ($master) use ($dispatchNoteDetails, &$grandTotals) {
    //         $details = $dispatchNoteDetails[$master->id] ?? collect();

    //         $packingTotals = [
    //             'Boray' => ['total_so_quantity' => 0, 'total_quantity' => 0, 'total_balance' => 0],
    //             'Carton' => ['total_so_quantity' => 0, 'total_quantity' => 0, 'total_balance' => 0],
    //         ];

    //         $details = $details->map(function ($detail) use (&$packingTotals, &$grandTotals) {
    //             $soQty = $detail->soQuantity ?? 0;
    //             $qty = $detail->quantity ?? 0;
    //             $balance = $soQty - $qty;
    //             $packingType = $detail->packing_type ?? 'Unknown';

    //             $detail->balance = $balance;

    //             // Only process if known packing type
    //             if (in_array($packingType, ['Boray', 'Carton'])) {
    //                 $packingTotals[$packingType]['total_so_quantity'] += $soQty;
    //                 $packingTotals[$packingType]['total_quantity'] += $qty;
    //                 $packingTotals[$packingType]['total_balance'] += $balance;

    //                 $grandTotals[$packingType]['total_so_quantity'] += $soQty;
    //                 $grandTotals[$packingType]['total_quantity'] += $qty;
    //                 $grandTotals[$packingType]['total_balance'] += $balance;
    //             }

    //             return $detail;
    //         });

    //         $master->details = $details;
    //         $master->packing_totals = $packingTotals;

    //         return $master;
    //     });

    //     $products = CoaInventoryDetailAccount::pluck('name', 'id');
    //     $createdUser = Auth::user()->id;
    //     $user = User::where('id', $createdUser)->value('name');

    //     return view('reports.dispatch_report.dispatch-report-view', compact(
    //         'title',
    //         'fromDate',
    //         'toDate',
    //         'sector',
    //         'area',
    //         'products',
    //         'partyId',
    //         'salesmanId',
    //         'dropDownData',
    //         'orders',
    //         'user',
    //         'grandTotals'
    //     ));
    // }

    // public function salesReport(Request $request)
    // {
    //     $query = DB::table('sale_masters as sm')
    //         ->leftJoin('sale_details as sd', 'sm.id', '=', 'sd.sale_master_id')
    //         ->leftJoin('sectors as s', 'sm.sector', '=', 's.id')
    //         ->leftJoin('zones as z', 's.zone_id', '=', 'z.id')
    //         ->leftJoin('countries as c', 'z.country_id', '=', 'c.id')
    //         ->leftJoin('detail_accounts as da', 'sm.party_id', '=', 'da.id')
    //         ->leftJoin('detail_accounts as delivered', 'sm.delivered_to', '=', 'delivered.id')
    //         ->leftJoin('sale_mans as saleman', 'sm.saleman', '=', 'saleman.id')
    //         ->select(
    //             'sm.*',
    //             'da.name as party_name',
    //             'delivered.name as delivered_to_name',
    //             'saleman.name as saleman_name',
    //             's.name as sector_name',
    //             'z.name as zone_name',
    //             'c.name as country_name',
    //             'sd.*'
    //         );

    //     // Filters
    //     if ($request->filled('from_date')) {
    //         $query->whereDate('sm.date', '>=', $request->from_date);
    //     }

    //     if ($request->filled('to_date')) {
    //         $query->whereDate('sm.date', '<=', $request->to_date);
    //     }

    //     if (!empty($request->party_ids)) {
    //         $query->whereIn('sm.party_id', $request->party_ids);
    //     }

    //     if (!empty($request->delivered_to_ids)) {
    //         $query->whereIn('sm.delivered_to', $request->delivered_to_ids);
    //     }

    //     if (!empty($request->saleman_ids)) {
    //         $query->whereIn('sm.saleman', $request->saleman_ids);
    //     }

    //     if (!empty($request->area_ids)) {
    //         $query->whereIn('sm.area', $request->area_ids);
    //     }

    //     if (!empty($request->sector_ids)) {
    //         $query->whereIn('sm.sector', $request->sector_ids);
    //     } elseif (!empty($request->zone_ids)) {
    //         $query->whereIn('s.zone_id', $request->zone_ids);
    //     } elseif (!empty($request->country_ids)) {
    //         $query->whereIn('z.country_id', $request->country_ids);
    //     }

    //     $results = $query->orderBy('sm.date', 'desc')->get();

    //     // Group by Sale Master ID
    //     $grouped = $results->groupBy('id');

    //     return view('reports.sales.index', compact('grouped'));
    // }

    public function salesReportold(Request $request)
    {
        $query = DB::table('sale_masters as sm')
            ->leftJoin('sale_details as sd', 'sm.id', '=', 'sd.sale_master_id')
            ->leftJoin('sectors as s', 'sm.sector', '=', 's.id')
            ->leftJoin('zones as z', 's.zone_id', '=', 'z.id')
            ->leftJoin('countries as c', 'z.country_id', '=', 'c.id')
            ->leftJoin('detail_accounts as da', 'sm.party_id', '=', 'da.id')
            ->leftJoin('delivered_to_parties as delivered', 'sm.delivered_to', '=', 'delivered.id')
            ->leftJoin('sale_mans as saleman', 'sm.saleman', '=', 'saleman.id')
            ->select(
                'sm.id as sale_master_id',
                'sm.date',
                // 'sm.invoice_no',
                'sm.party_id',
                'sm.saleman',
                'sm.sector',
                'sm.area',
                // 'sm.zone',
                // 'sm.country',
                'sm.delivered_to',
                'da.account_name as party_name',
                'delivered.party_name as delivered_to_name',
                'saleman.name as saleman_name',
                's.name as sector_name',
                'z.name as zone_name',
                'c.name as country_name',
                'sd.product_id',
                'sd.packing_type',
                'sd.quantity',
                'sd.rate',
                'sd.amount'
            );

        // Filters
        if ($request->filled('party_id')) {
            $query->whereIn('sm.party_id', $request->party_id);
        }

        if ($request->filled('from_date') && $request->filled('to_date')) {
            $fromDate = date('Y-m-d', strtotime($request->from_date));
            $toDate = date('Y-m-d', strtotime($request->to_date));
            $query->whereBetween('sm.date', [$fromDate, $toDate]);
        }

        if ($request->filled('delivered_to')) {
            $query->whereIn('sm.delivered_to', $request->delivered_to);
        }

        if ($request->filled('saleMan_id')) {
            $query->whereIn('sm.saleman', $request->saleMan_id);
        }

        if ($request->filled('area')) {
            $query->whereIn('sm.area', $request->area);
        }

        if ($request->filled('belt')) {
            $query->whereIn('sm.sector', $request->belt);
        }

        if ($request->filled('zone_id')) {
            $query->whereIn('s.zone_id', $request->zone_id);
        }

        if ($request->filled('country_id')) {
            $query->whereIn('z.country_id', $request->country_id);
        }

        $results = $query->get();

        // Group by Sale Master ID
        $grouped = $results->groupBy('sale_master_id');

        // Get current user name
        $user = Auth::user()->name ?? 'System';

        return view('reports.sales_report.sales-report-view', compact('grouped', 'user'));
    }




    public function salesReportold2(Request $request)
    {
        dd($request);
        $query = SaleMaster::with(['details'])
            ->whereNull('deleted_at');

        // Apply filters
        if ($request->filled('from_date') && $request->filled('to_date')) {
            $query->whereBetween('date', [$request->from_date, $request->to_date]);
        }

        if ($request->filled('party_id')) {

            $query->whereIn('party_id', $request->party_id);
        }

        if ($request->filled('saleMan_id')) {
            $query->whereIn('saleman', $request->saleMan_id);
        }

        if ($request->filled('belt')) {
            $query->whereIn('sector', $request->belt);
        }

        if ($request->filled('area')) {
            $query->whereIn('area', $request->area);
        }

        $orders = $query->orderBy('date', 'desc')->get();
        // Calculate balance and packing totals
        // foreach ($orders as $order) {
        //     $packingTotals = [];

        //     foreach ($order->details as $detail) {
        //         $soQty = $detail->total_dzns ?? 0;
        //         $qty = $detail->quantity ?? 0;
        //         $detail->soQuantity = $soQty;
        //         $detail->balance = $soQty - $qty;

        //         $type = $detail->packing_type;
        //         if (!isset($packingTotals[$type])) {
        //             $packingTotals[$type] = [
        //                 'total_so_quantity' => 0,
        //                 'total_quantity' => 0,
        //                 'total_balance' => 0,
        //             ];
        //         }
        //         $packingTotals[$type]['total_so_quantity'] += $soQty;
        //         $packingTotals[$type]['total_quantity'] += $qty;
        //         $packingTotals[$type]['total_balance'] += ($soQty - $qty);
        //     }

        //     $order->packing_totals = $packingTotals;
        // }

        // Calculate grand totals
        // $grandTotals = [
        //     'Boray' => ['total_so_quantity' => 0, 'total_quantity' => 0, 'total_balance' => 0],
        //     'Carton' => ['total_so_quantity' => 0, 'total_quantity' => 0, 'total_balance' => 0]
        // ];

        // foreach ($orders as $order) {
        //     foreach (['Boray', 'Carton'] as $type) {
        //         if (isset($order->packing_totals[$type])) {
        //             $grandTotals[$type]['total_so_quantity'] += $order->packing_totals[$type]['total_so_quantity'];
        //             $grandTotals[$type]['total_quantity'] += $order->packing_totals[$type]['total_quantity'];
        //             $grandTotals[$type]['total_balance'] += $order->packing_totals[$type]['total_balance'];
        //         }
        //     }
        // }

        // Prepare dropdown data for view
        $dropDownData = [
            'parties' => CoaDetailAccount::pluck('account_name', 'id')->toArray(),
            'areas' => DB::table('areas')->pluck('name', 'id')->toArray(),
            'belts' => DB::table('sectors')->pluck('name', 'id')->toArray(),
            'saleMans' => DB::table('sale_mans')->pluck('name', 'id')->toArray(),
            'products' => CoaInventoryDetailAccount::pluck('name', 'id')->toArray(),
            'transporters' => DB::table('transporters')->pluck('name', 'id')->toArray(),
            'deliveredToParties' => DeliveredToParties::pluck('party_name', 'id')->toArray(),
        ];

        return view('reports.dispatch_report.dispatch-report-view', [
            'orders' => $orders,
            'grandTotals' => $grandTotals,
            'dropDownData' => $dropDownData,
            'user' => auth()->user()->name ?? 'System'
        ]);
    }

    public function salesReport(Request $request)
    {
        $title = 'Sale Invoice Report';
        $dropDownData = $this->stockLedgerService->DropDownData();

        // Parse filters
        $fromDate = $request->filled('from_date') ? date('Y-m-d', strtotime($request->from_date)) : null;
        $toDate = $request->filled('to_date') ? date('Y-m-d', strtotime($request->to_date)) : null;
        $sector = $request->belt;
        $area = $request->area;
        $partyId = $request->party_id;
        $salesmanId = $request->saleMan_id;

        // Step 1: Build sale_masters query
        $masterQuery = DB::table('sale_masters')->whereNull('deleted_at');

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

        // Step 2: Get sale_masters
        $dispatchReportMasters = $masterQuery->get();
        $dispatchNoteMasterIds = $dispatchReportMasters->pluck('id')->toArray();

        // Step 3: Get sale_details and group by sale_master_id
        $dispatchNoteDetails = DB::table('sale_details')
            ->whereIn('sale_master_id', $dispatchNoteMasterIds)
            ->whereNull('deleted_at')
            ->get()
            ->groupBy('sale_master_id');

        // Step 4: Related dispatch_note_masters and sale_order_masters
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

        // Step 5: Related detail_accounts (party info)
        $partyIds = $dispatchReportMasters->pluck('party_id')->filter()->unique()->toArray();

        $detailAccounts = DB::table('detail_accounts')
            ->whereIn('id', $partyIds)
            ->get()
            ->keyBy('id');

        // Step 6: Totals + Attach related data
        $totalBoraySoQuantity = 0;
        $totalCartonSoQuantity = 0;
        $totalBorayDispQuantity = 0;
        $totalCartonDispQuantity = 0;
        $totalNetAmount = 0;

        $orders = $dispatchReportMasters->map(function ($master) use (
            $dispatchNoteDetails,
            $dispatchNoteMasters,
            $saleOrderMasters,
            $detailAccounts,
            &$totalBoraySoQuantity,
            &$totalCartonSoQuantity,
            &$totalBorayDispQuantity,
            &$totalCartonDispQuantity,
            &$totalNetAmount
        ) {
            $details = $dispatchNoteDetails[$master->id] ?? collect();
            $boraySoQty = 0;
            $cartonSoQty = 0;
            $borayDispQty = 0;
            $cartonDispQty = 0;

            foreach ($details as $detail) {
                if ($detail->packing_type === 'Boray') {
                    $boraySoQty += $detail->soQuantity ?? 0;
                    $borayDispQty += $detail->dispQuantity ?? 0;
                } elseif ($detail->packing_type === 'Carton') {
                    $cartonSoQty += $detail->soQuantity ?? 0;
                    $cartonDispQty += $detail->dispQuantity ?? 0;
                }
            }

            // Add to global totals
            $totalBoraySoQuantity += $boraySoQty;
            $totalCartonSoQuantity += $cartonSoQty;
            $totalBorayDispQuantity += $borayDispQty;
            $totalCartonDispQuantity += $cartonDispQty;
            $totalNetAmount += $master->net_amount ?? 0;

            // Attach to invoice
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

        // Step 7: Other data for view
        $products = CoaInventoryDetailAccount::pluck('name', 'id');
        $createdUser = Auth::user()->id;
        $user = User::where('id', $createdUser)->value('name');

        // Step 8: Return the view
        return view('reports.sales_report.sales-report-view', compact(
            'fromDate',
            'toDate',
            'dropDownData',
            'products',
            'title',
            'partyId',
            'orders',
            'user',
            'totalBoraySoQuantity',
            'totalCartonSoQuantity',
            'totalBorayDispQuantity',
            'totalCartonDispQuantity',
            'totalNetAmount'
        ));
    }

    public function productSaleReport()
    {
        $pageTitle = 'Product Sale Report';
        $dropDownData = $this->stockLedgerService->DropDownData();
        return view('reports.product_sales_report.product-sales-report-list', compact('dropDownData', 'pageTitle'));
    }

    public function productSaleReportPrint(Request $request)
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
}
