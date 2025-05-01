<?php

namespace App\Http\Controllers;

use App\Models\StockLedger;
use Illuminate\Http\Request;
use App\Services\CommonService;
use Illuminate\Support\Facades\DB;
use App\Services\PermissionService;
use App\Models\CoaInventoryMainHead;
use App\Services\StockLedgerService;
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
                dd($closingDR);
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
}
