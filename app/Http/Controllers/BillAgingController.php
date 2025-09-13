<?php

namespace App\Http\Controllers;

use App\Models\SaleMan;
use App\Models\SaleMaster;
use Illuminate\Http\Request;
use App\Models\AccountLedger;
use App\Models\CoaDetAccountDetail;
use App\Services\CommonService;
use App\Models\CoaDetailAccount;
use App\Models\Sector;
use App\Models\Zone;
use Illuminate\Support\Facades\DB;
use App\Services\PermissionService;
use App\Services\ChartOfAccountService;
use App\Services\CoaDetailAccountService;

class BillAgingController extends Controller
{

    private $chartOfAccountService;
    private $permissionService;
    private $commonService;
    private $coaDetailAccountService;

    public function __construct(CoaDetailAccountService $coaDetailAccountService, ChartOfAccountService $chartOfAccountService, PermissionService $permissionService, CommonService $commonService)
    {
        $this->chartOfAccountService = $chartOfAccountService;
        $this->permissionService = $permissionService;
        $this->commonService = $commonService;
        $this->coaDetailAccountService = $coaDetailAccountService;
    }


    public function view()
    {
        $pageTitle = 'Bill Aging';
        $dropDownData = $this->coaDetailAccountService->DropDownData();
        return view('reports.bill_aging.bill-aging-list', compact('dropDownData', 'pageTitle'));
    }

    // public function billAgingPrint(Request $request)
    // {
    //     $title = "Bill Aging Report";

    //     $fromDate = $request->filled('from_date') ? date('Y-m-d', strtotime($request->from_date)) : null;
    //     $toDate = $request->filled('to_date') ? date('Y-m-d', strtotime($request->to_date)) : null;

    //     $query = SaleMaster::query();

    //     if ($request->from_date) {
    //         $query->whereDate('date', '>=', $request->from_date);
    //     }

    //     if ($request->to_date) {
    //         $query->whereDate('date', '<=', $request->to_date);
    //     }

    //     if ($request->party_id) {
    //         $query->whereIn('party_id', $request->party_id);
    //     }

    //     // Add other filters (zone, salesman, etc) similarly...

    //     $sales = $query->orderBy('date')->get();


    //     $finalData = [];

    //     foreach ($sales as $partyId => $invoices) {
    //         $detailAccount = CoaDetailAccount::find($partyId);
    //         // $saleMan = SaleMan::find();

    //         // Check sub_sub_head
    //         $subHead = $detailAccount->sub_sub_head;

    //         $ledgerQuery = AccountLedger::where('party_id', $partyId);

    //         $partyBalance = $subHead == 1
    //             ? $ledgerQuery->sum('debit')
    //             : $ledgerQuery->sum('credit');

    //         $rows = [];
    //         $runningTotalBalance = 0;


    //         foreach ($invoices as $index => $invoice) {
    //             $netAmount = $invoice->net_amount;

    //             if ($index == 0) {
    //                 $dueAmount = $netAmount - $partyBalance;
    //             } else {
    //                 $dueAmount = $netAmount - $runningTotalBalance;
    //             }

    //             $runningTotalBalance += $dueAmount;

    //             $rows[] = [
    //                 'invoice_date' => $invoice->sale_date,
    //                 'invoice_number' => $invoice->invoice_no,
    //                 'transporter' => $invoice->transporter ?? 'Auto',
    //                 'credit_days' => $invoice->credit_days,
    //                 'net_amount' => $netAmount,
    //                 'due_amount' => $dueAmount,
    //                 'total_balance' => $runningTotalBalance,
    //                 'salesman' => $invoice->saleman,
    //                 'zone' => $invoice->zone->name ?? '',
    //                 'area' => $invoice->area->name ?? '',
    //                 'account_name' => $detailAccount->account_name,
    //             ];

    //         }

    //         $finalData[$partyId] = $rows;
    //     }

    //     return view('reports.bill_aging.bill-aging-view', compact('finalData', 'title', 'fromDate', 'toDate'));
    // }

    public function billAgingPrint(Request $request)
    {

        $title = "Bill Aging Report";

        $fromDate = $request->filled('from_date') ? date('Y-m-d', strtotime($request->from_date)) : null;
        $toDate = $request->filled('to_date') ? date('Y-m-d', strtotime($request->to_date)) : null;

        // $query = SaleMaster::with(['area', 'belt', 'SaleMan', 'party'])
        //     ->orwhereBetween('date', [ $fromDate, $toDate]);

        $query = SaleMaster::with(['area', 'belt', 'SaleMan', 'party','transporter'])->whereNull('deleted_at');


        if ($fromDate && $toDate) {
            $query->whereBetween('date', [$fromDate, $toDate]);
        }

        if ($request->filled('party_id')) {
            $query->whereIn('party_id', $request->party_id);
        }

        if ($request->filled('zone_id')) {
            $sectorIds = \App\Models\Sector::whereIn('zone_id', $request->zone_id)->pluck('id')->toArray();
            $query->whereIn('sector', $sectorIds);
        }

        if ($request->filled('belt')) {
            $query->whereIn('sector', $request->belt);
        }

        if ($request->filled('area')) {
            $query->whereIn('area', $request->area);
        }

        if ($request->filled('saleMan_id')) {
            $query->whereIn('saleman', $request->saleMan_id);
        }

        $invoices = $query->orderBy('date')->get();
        dd($invoices);


        // $grouped = $invoices->groupBy('party_id');
        $finalData = [];

        foreach ($invoices as  $partyInvoices) {
            $partyId =  $partyInvoices->party_id;
            $party = CoaDetailAccount::find($partyId);
            if (!$party) continue;

            // Get Party Balance
            $subSubHead = $party->sub_sub_head;
            $ledgerQuery = AccountLedger::where('party_id', $partyId)->get();

            $partyBalance = $subSubHead == 1
                ? $ledgerQuery->sum('debit')
                : $ledgerQuery->sum('credit');

            $rows = [];
            $runningBalance = 0;

            foreach ($partyInvoices as $invoice) {

                $netAmount = $invoice->net_amount;

                if ($index === 0) {
                    $dueAmount = $netAmount - $partyBalance;
                    $totalBalance = $dueAmount;
                } else {
                    $dueAmount = $netAmount - $rows[$index - 1]['total_balance'];
                    $totalBalance = $rows[$index - 1]['total_balance'] + $dueAmount;
                }

                $rows[] = [
                    'invoice_number' => $invoice->id,
                    'invoice_date' => $invoice->date,
                    'credit_days' => $party->credit_days,
                    'net_amount' => $netAmount,
                    'due_amount' => $dueAmount,
                    'total_balance' => $totalBalance,
                    'party_name' => $party->name,
                    'transporter' => $invoice->transporter->name ?? 'qsqs',
                    'zone' => $invoice->zone->name ?? '',
                    'area' => $invoice->area->name ?? '',
                    'salesman' => $invoice->salesman->name ?? '',
                    'belt' => $invoice->belt->name ?? '',
                    'credit_limit' => $party->credit_limit ?? 0,
                ];
            }

            $finalData[] = $rows;
        }

        return view('reports.bill_aging.bill-aging-view', compact('finalData', 'title', 'fromDate', 'toDate'));
    }


    // public function billAgingPrint(Request $request)
    // {
    //     $title = "Bill Aging Report";

    //     $fromDate = $request->filled('from_date') ? date('Y-m-d', strtotime($request->from_date)) : null;
    //     $toDate = $request->filled('to_date') ? date('Y-m-d', strtotime($request->to_date)) : null;

    //     $query = SaleMaster::with([
    //         'area',
    //         'belt',
    //         'SaleMan',
    //         'party',
    //         'transporter',
    //         'belt.zone', // to access zone name via sector
    //     ])->whereNull('deleted_at');

    //     // Apply date range (required)
    //     if ($fromDate && $toDate) {
    //         $query->whereBetween('date', [$fromDate, $toDate]);
    //     }

    //     // Apply optional filters
    //     if ($request->filled('party_id')) {
    //         $query->whereIn('party_id', $request->party_id);
    //     }

    //     if ($request->filled('zone_id')) {
    //         $sectorIds = \App\Models\Sector::whereIn('zone_id', $request->zone_id)->pluck('id')->toArray();
    //         $query->whereIn('sector', $sectorIds);
    //     }

    //     if ($request->filled('belt')) {
    //         $query->whereIn('sector', $request->belt);
    //     }

    //     if ($request->filled('area')) {
    //         $query->whereIn('area', $request->area);
    //     }

    //     if ($request->filled('saleMan_id')) {
    //         $query->whereIn('saleman', $request->saleMan_id);
    //     }

    //     $invoices = $query->orderBy('party_id')->orderBy('date')->get();
    //     $grouped = $invoices->groupBy('party_id');
    //     $finalData = [];


    //     foreach ($grouped as $partyId => $partyInvoices) {
    //         $party = CoaDetailAccount::find($partyId);
    //         $partyDetailAccount = CoaDetAccountDetail::where('det_account_code', $partyId)->first();

    //         if (!$party) continue;

    //         // Determine party balance
    //         $subSubHead = $party->sub_sub_head;
    //         $ledgerQuery = AccountLedger::where('party_id', $partyId);
    //         $partyBalance = $subSubHead == 1
    //             ? $ledgerQuery->sum('debit')
    //             : $ledgerQuery->sum('credit');

    //         $rows = [];

    //         foreach ($partyInvoices as $index => $invoice) {
    //             $netAmount = $invoice->net_amount;
    //             $invoiceDate = \Carbon\Carbon::parse($invoice->date);
    //             $daysSinceInvoice = $invoiceDate->diffInDays(now());

    //             if ($index === 0) {
    //                 $dueAmount = $netAmount - $partyBalance;
    //                 $totalBalance = $dueAmount;
    //             } else {
    //                 $dueAmount = $netAmount - $rows[$index - 1]['total_balance'];
    //                 $totalBalance = $rows[$index - 1]['total_balance'] + $dueAmount;
    //             }

    //             $rows[] = [
    //                 'invoice_number' => $invoice->id,
    //                 'invoice_date' => $invoice->date,
    //                 'credit_days' => $partyDetailAccount->credit_days,
    //                 'days_since_invoice' => $daysSinceInvoice,
    //                 'net_amount' => $netAmount,
    //                 'due_amount' => $dueAmount,
    //                 'total_balance' => $totalBalance,
    //                 'party_name' => $party->account_name,
    //                 'transporter' => $invoice->transporter->name ?? 'No data',
    //                 'zone' => $invoice->belt->zone->name ?? '',
    //                 'area' => $invoice->Area->name ?? '',
    //                 'salesman' => $invoice->SaleMan->name ?? '',
    //                 'belt' => $invoice->belt->name ?? '',
    //                 'credit_limit' => $partyDetailAccount->credit_limit ?? 0,
    //             ];
    //         }

    //         $finalData[] = $rows;
    //     }

    //     return view('reports.bill_aging.bill-aging-view', compact('finalData', 'title', 'fromDate', 'toDate'));
    // }

    // public function billAgingPrint(Request $request)
    // {
    //     $title = "Bill Aging Report";

    //     $fromDate = $request->filled('from_date') ? date('Y-m-d', strtotime($request->from_date)) : null;
    //     $toDate = $request->filled('to_date') ? date('Y-m-d', strtotime($request->to_date)) : null;

    //     $query = SaleMaster::with([
    //         'area',
    //         'belt.zone', // get zone through belt
    //         'SaleMan',
    //         'party',
    //         'transporter'
    //     ])->whereNull('deleted_at');

    //     // Required date range
    //     if ($fromDate && $toDate) {
    //         $query->whereBetween('date', [$fromDate, $toDate]);
    //     }

    //     // Optional filters
    //     if ($request->filled('party_id')) {
    //         $query->whereIn('party_id', $request->party_id);
    //     }

    //     if ($request->filled('zone_id')) {
    //         $sectorIds = \App\Models\Sector::whereIn('zone_id', $request->zone_id)->pluck('id')->toArray();
    //         $query->whereIn('sector', $sectorIds);
    //     }

    //     if ($request->filled('belt')) {
    //         $query->whereIn('sector', $request->belt);
    //     }

    //     if ($request->filled('area')) {
    //         $query->whereIn('area', $request->area);
    //     }

    //     if ($request->filled('saleMan_id')) {
    //         $query->whereIn('saleman', $request->saleMan_id);
    //     }

    //     $invoices = $query->orderBy('date')->get();
    //     $finalData = [];

    //     foreach ($invoices as $invoice) {
    //         $party = $invoice->party;
    //         $partyDetailAccount = \App\Models\CoaDetAccountDetail::where('det_account_code', $invoice->party_id)->first();

    //         if (!$party || !$partyDetailAccount) {
    //             continue;
    //         }

    //         // Calculate party balance
    //         $subSubHead = $party->sub_sub_head;
    //         $ledgerQuery = \App\Models\AccountLedger::where('party_id', $invoice->party_id);
    //         $partyBalance = $subSubHead == 1
    //             ? $ledgerQuery->sum('debit')
    //             : $ledgerQuery->sum('credit');

    //         $netAmount = $invoice->net_amount;
    //         $invoiceDate = \Carbon\Carbon::parse($invoice->date);
    //         $daysSinceInvoice = $invoiceDate->diffInDays(now());

    //         // Due & total balance logic
    //         $dueAmount = $netAmount - $partyBalance;
    //         $totalBalance = $dueAmount;

    //         $finalData[] = [
    //             'invoice_number' => $invoice->id,
    //             'invoice_date' => $invoice->date,
    //             'credit_days' => $partyDetailAccount->credit_days ?? 0,
    //             'days_since_invoice' => $daysSinceInvoice,
    //             'net_amount' => $netAmount,
    //             'due_amount' => $dueAmount,
    //             'total_balance' => $totalBalance,
    //             'party_name' => $party->account_name,
    //             'credit_limit' => $partyDetailAccount->credit_limit ?? 0,
    //             'transporter' => $invoice->transporter->name ?? 'No data',
    //             'zone' => $invoice->belt->zone->name ?? '',
    //             'belt' => $invoice->belt->name ?? '',
    //             'area' => $invoice->area->name ?? '',
    //             'salesman' => $invoice->SaleMan->name ?? '',
    //         ];
    //     }

    //     return view('reports.bill_aging.bill-aging-view', compact('finalData', 'title', 'fromDate', 'toDate'));
    // }

    // public function billAgingPrint(Request $request)
    // {
    //     $title = "Bill Aging Report";

    //     $fromDate = $request->filled('from_date') ? date('Y-m-d', strtotime($request->from_date)) : null;
    //     $toDate = $request->filled('to_date') ? date('Y-m-d', strtotime($request->to_date)) : null;

    //     $query = SaleMaster::with([
    //         'Area',
    //         'Belt',
    //         'SaleMan',
    //         'party',
    //         'transporter',
    //         'Belt.zone'
    //     ])->whereNull('deleted_at');

    //     if ($fromDate && $toDate) {
    //         $query->whereBetween('date', [$fromDate, $toDate]);
    //     }

    //     if ($request->filled('party_id')) {
    //         $query->whereIn('party_id', $request->party_id);
    //     }

    //     if ($request->filled('zone_id')) {
    //         $sectorIds = \App\Models\Sector::whereIn('zone_id', $request->zone_id)->pluck('id')->toArray();
    //         $query->whereIn('sector', $sectorIds);
    //     }

    //     if ($request->filled('belt')) {
    //         $query->whereIn('sector', $request->belt);
    //     }

    //     if ($request->filled('area')) {
    //         $query->whereIn('area', $request->area);
    //     }

    //     if ($request->filled('saleMan_id')) {
    //         $query->whereIn('saleman', $request->saleMan_id);
    //     }

    //     $invoices = $query->orderBy('sector')->orderBy('party_id')->orderBy('date')->get();

    //     $groupedInvoices = [];

    //     $grouped = $invoices->groupBy(function ($item) {
    //         return $item->sector . '_' . $item->party_id;
    //     });

    //     foreach ($grouped as $groupKey => $invoiceGroup) {
    //         $firstInvoice = $invoiceGroup->first();
    //         $partyId = $firstInvoice->party_id;
    //         $sectorId = $firstInvoice->sector;

    //         $party = CoaDetailAccount::find($partyId);
    //         $partyDetailAccount = CoaDetAccountDetail::where('det_account_code', $partyId)->first();

    //         if (!$party) continue;

    //         $subSubHead = $party->sub_sub_head;
    //         $ledgerQuery = AccountLedger::where('party_id', $partyId);
    //         $partyBalance = $subSubHead == 1
    //             ? $ledgerQuery->sum('debit')
    //             : $ledgerQuery->sum('credit');

    //         $rows = [];

    //         foreach ($invoiceGroup as $index => $invoice) {
    //             $netAmount = $invoice->net_amount;
    //             $invoiceDate = \Carbon\Carbon::parse($invoice->date);
    //             $daysSinceInvoice = $invoiceDate->diffInDays(now());

    //             if ($index === 0) {
    //                 $dueAmount = $netAmount - $partyBalance;
    //                 $totalBalance = $dueAmount;
    //             } else {
    //                 $dueAmount = $netAmount - $rows[$index - 1]['total_balance'];
    //                 $totalBalance = $rows[$index - 1]['total_balance'] + $dueAmount;
    //             }

    //             $rows[] = [
    //                 'invoice_number' => $invoice->id,
    //                 'invoice_date' => $invoiceDate->format('d-M-Y'),
    //                 'credit_days' => $partyDetailAccount->credit_days ?? 0,
    //                 'days_since_invoice' => $daysSinceInvoice,
    //                 'net_amount' => $netAmount,
    //                 'due_amount' => $dueAmount,
    //                 'total_balance' => $totalBalance,
    //                 'party_name' => $party->account_name,
    //                 'transporter' => $invoice->transporter->name ?? 'No data',
    //                 'zone' => $invoice->Belt->zone->name ?? '',
    //                 'area' => $invoice->Area->name ?? '',
    //                 'salesman' => $invoice->SaleMan->name ?? '',
    //                 'belt' => $invoice->Belt->name ?? '',
    //             ];
    //         }

    //         $groupedInvoices[] = [
    //             'party' => $party->account_name,
    //             'sector' => $sector->name ?? '',
    //             'credit_limit' => $partyDetailAccount->credit_limit ?? 0,
    //             'salesman' => $invoice->SaleMan->name ?? '',
    //             'zone' => $invoice->Belt->zone->name ?? '',
    //             'area' => $invoice->Area->name ?? '',
    //             'belt' => $invoice->Belt->name ?? '',
    //             'credit_days' => $partyDetailAccount->credit_days ?? 0,
    //             'invoices' => $rows,
    //         ];
    //     }

    //     return view('reports.bill_aging.bill-aging-view', compact(
    //         'groupedInvoices',
    //         'totals',
    //         'title',
    //         'fromDate',
    //         'toDate'
    //     ));
    // }
}
