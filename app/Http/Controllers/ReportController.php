<?php

namespace App\Http\Controllers;

use App\Models\AccountLedger;
use App\Models\Area;
use App\Models\CoaControlHead;
use App\Models\CoaDetAccountDetail;
use App\Models\CoaDetailAccount;
use App\Models\CoaDetailAccountArea;
use App\Models\CoaDetailAccountSectors;
use App\Models\CoaInventoryDetailAccount;
use App\Models\CoaInventoryMainHead;
use App\Models\CoaMainHead;
use App\Models\CoaSubHead;
use App\Models\CoaSubSubHead;
use App\Models\SaleMan;
use App\Models\Sector;
use App\Models\StockLedger;
use App\Models\Transporter;
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

        $product = CoaInventoryDetailAccount::where('id',$request['product_id'])->first();

        $stockLedger = StockLedger::where('product_id' ,$request['product_id'])->get();

        $invMainHead = CoaInventoryMainHead::where('id', $product->main_head)->value('name');

        $products = CoaInventoryDetailAccount::where('id',$request['product_id'])->pluck('name','id');

        return view('reports.stock-ledger.stock-ledger', compact('param','products','invMainHead', 'dropDownData','product','stockLedger', 'title'));
    }


    public function getPartyAccountLedger(Request $request)
    {
        $title = 'Party Ledger';
        $param = request()->param;
        $dropDownData = $this->stockLedgerService->DropDownData();

        if (!empty($request['party_id'])) {

            $parties = CoaDetailAccount::where('id', $request['party_id'])->get();

            $fromDate = date('Y-m-d', strtotime($request['from_date']));
            $toDate = date('Y-m-d', strtotime($request['to_date']));
            $partyAccountLedger = AccountLedger::orwhereBetween('date', [$fromDate, $toDate])
                ->orwhere('party_id', $request->party_id) // Adjust if `party_id` is a separate column
                ->orderBy('date', 'asc')
                ->get();
        }
        else
        {

            $fromDate = date('Y-m-d', strtotime($request['from_date']));
            $toDate = date('Y-m-d', strtotime($request['to_date']));
            $partyAccountLedger = AccountLedger::orwhereBetween('date', [$fromDate, $toDate])
                ->orwhere('party_id', $request->party_id) // Adjust if `party_id` is a separate column
                ->orderBy('date', 'asc')
                ->get();
        }

        $party = CoaDetailAccount::where('id',$request['party_id'])->first();
        $partyDetailAccount =CoaDetAccountDetail::where('det_account_code', $party->id )->first();
        $subSubHead = CoaSubSubHead::where('id', $party->sub_sub_head)->value('account_name');
        $subHead = CoaSubHead::where('id', $party->sub_head)->value('account_name');
        $controlHead = CoaControlHead::where('id', $party->control_head)->value('account_name');
        $mainHead = CoaMainHead::where('id', $party->main_head)->value('account_name');
        $saleMan = SaleMan::where('id', $party->saleMan_id)->value('name');

        $fetchBelts =CoaDetailAccountSectors::where('master_account_id',$party->id )->pluck('sector_id');
        $sectors = Sector::whereIn('id',$fetchBelts )->pluck('name');

        $fetchAreas =CoaDetailAccountArea::where('master_account_id',$party->id )->pluck('area_id');
        $areas = Area::whereIn('id',$fetchAreas )->pluck('name');
        // $searchParty = CoaDetailAccount::where('id', $request['party_id'])->value('id');
        $accountLedgers = AccountLedger::where('party_id' ,$request['party_id'])->get();
        $transporterArray = $accountLedgers->pluck('transporter_id');
        $transporters = Transporter::whereIn('id',$transporterArray )->pluck('name','id');

        return view('reports.account-ledger.partyAccount-ledger', compact('param','transporters','saleMan','areas','accountLedgers','sectors','mainHead','controlHead','subHead','subSubHead','partyDetailAccount','party', 'dropDownData', 'title'));
    }
}
