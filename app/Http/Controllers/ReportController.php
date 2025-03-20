<?php

namespace App\Http\Controllers;

use App\Models\CoaInventoryDetailAccount;
use App\Models\CoaInventoryMainHead;
use App\Models\StockLedger;
use Illuminate\Http\Request;
use App\Services\StockLedgerService;

class ReportController extends Controller
{
    protected $stockLedgerService;

    public function __construct(StockLedgerService $stockLedgerService)
    {
        $this->stockLedgerService = $stockLedgerService;
    }



    public function viewStockLedger()
    {
        $pageTitle = 'Stock Ledger';
        $dropDownData = $this->stockLedgerService->DropDownData();
        return view('reports.stock-ledger.stock-ledger-view', compact('dropDownData', 'pageTitle'));
    }

    public function getStockLedger(Request $request)
    {
        $title = 'Stock Ledger';
        // StockLedger::truncate();
        // $request = request()->all();
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

        $product = CoaInventoryDetailAccount::where('id',$request['product_id'])->first();

        $stockLedger = StockLedger::where('product_id' ,$request['product_id'])->get();

        $invMainHead = CoaInventoryMainHead::where('id', $product->main_head)->value('name');

        $products = CoaInventoryDetailAccount::where('id',$request['product_id'])->pluck('name','id');
        // dd($products);
        // $invoices = MillWeight::with(['dispatch'])->whereHas('dispatch', function($query) use ($request) {
        //     if (!empty($request['buyer'])) {
        //         $query->where('buyer_id', $request['buyer']);
        //     }
        //     if (!empty($request['seller'])) {
        //         $query->where('seller_id', $request['seller']);
        //     }
        // })->get();

        // $payments = Payment::with(['seller','buyer'])->where('buyer_id', $request['buyer'])->where('seller_id', $request['seller'])->get();
        // $paymentsData = [];
        // $invoicesData = [];
        // foreach($invoices as $invoice){
        //     $invoicesData[] = [
        //         'date' => $invoice->date,
        //         'buyer_id' => $invoice->dispatch->buyer_id,
        //         'seller_id' => $invoice->dispatch->seller_id,
        //         'description' => 'Invoice amount against cotton for lot number '. $invoice->lot_no,
        //         'debit' => $invoice->net_amount,
        //         'credit' => 0
        //     ];
        // }

        // foreach($payments as $payment){
        //     $paymentsData[] = [
        //         'date' => $payment->date,
        //         'buyer_id' => $payment->buyer_id,
        //         'seller_id' => $payment->seller_id,
        //         'description' => 'Payment against cotton for lot number '. $invoice->lot_no,
        //         'debit' => 0,
        //         'credit' => $payment->amount
        //     ];
        // }

        // StockLedger::insert($invoicesData);
        // StockLedger::insert($paymentsData);
        // $totalDebit = StockLedger::sum('debit');
        // $totalCredit = StockLedger::sum('credit');
        // $balance = ($totalDebit ?? 0) - ($totalCredit ?? 0);

        // $ledgerData = StockLedger::paginate(10);

        return view('reports.stock-ledger.stock-ledger', compact('param','products','invMainHead', 'dropDownData','product','stockLedger', 'title'));
    }
}
