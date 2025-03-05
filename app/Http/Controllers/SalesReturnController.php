<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Area;
use App\Models\User;
use App\Models\Sector;
use App\Models\SaleMan;
use App\Models\SaleDetail;
use App\Models\SaleMaster;
use App\Models\StockLedger;
use App\Models\Transporter;
use Illuminate\Http\Request;
use App\Models\AccountLedger;
use App\Models\GRNotesDetail;
use App\Models\SaleManSector;
use App\Services\CommonService;
use App\Models\CoaDetailAccount;
use App\Models\SalePurchaseType;
use App\Models\SaleReturnDetail;
use App\Models\SaleReturnMaster;
use App\Models\GoodsReceivedNote;
use App\Models\DeliveredToParties;
use Illuminate\Support\Facades\DB;
use App\Services\SaleReturnService;
use App\Models\CoaDetailAccountArea;
use App\Services\StockLedgerService;
use App\Models\DetailAccountProducts;
use App\Services\AccountLedgerService;
use App\Models\CoaDetailAccountSectors;
use App\Models\CoaInventoryDetailAccount;
use App\Http\Requests\StoreSaleReturnRequest;

class SalesReturnController extends Controller
{

    protected $commonService;
    protected $salereturnService;
    protected $stockLedgerService;
    protected $accountLedgerService;



    public function __construct( CommonService $commonService,
    SaleReturnService $salereturnService,
    StockLedgerService $stockLedgerService,
    AccountLedgerService $accountLedgerService)
    {
        $this->commonService = $commonService;
        $this->salereturnService = $salereturnService;
        $this->stockLedgerService = $stockLedgerService;
        $this->accountLedgerService = $accountLedgerService;

    }

    /*
     * Show page of list of sales.
     * */
    public function index()
    {
        $pageTitle = 'List Of Sale Returns';
        $request = request()->all();
        $saleReturns = $this->salereturnService->searchSaleReturn($request);

        return view('sale-return.index', compact('saleReturns', 'request','pageTitle'));
    }

    public function generate()
    {
        return view('sale-return.generate');
    }

    /*
     * Show page of create sale.
     * */
    public function create(Request $request)
    {
        $pageTitle = 'Create Sale Return Invoice';
        $dropDownData = $this->salereturnService->DropDownData();
        $invoiceNo = SaleReturnMaster::max('id') + 1;
        $grnMaster = GoodsReceivedNote::where('id',$request->id)->first();

        $grnDetails =GRNotesDetail::where('master_id', $request->id)->get();
        // dd($grnDetails);
        $getParties =CoaDetailAccount::where('id', $grnMaster->party_id)->get();
        $parties = $getParties->pluck('account_name','id');
        $fetchSaleMan = $getParties->pluck('saleMan_id');

        $saleMans =SaleMan::where('id', $fetchSaleMan)->pluck('name','id');

        $partySectors =CoaDetailAccountSectors::where('master_account_id', $grnMaster->party_id)->pluck('sector_id');
        $sectors = Sector::whereIn('id',$partySectors)->pluck('name','id');

        $getSectorId = $sectors->pluck('id');

        $getCommission =CoaDetailAccount::where('id', $grnMaster->party_id)->get();
        $commissionArray = $getCommission->pluck('commision')->toArray();

        $areas =Area::whereIn('sector_id', $getSectorId)->pluck('name','id');

        $getProducts = DetailAccountProducts::where('detail_account_id',$grnMaster->party_id)->get();
        $productsArray = $getProducts->pluck('product_id')->toArray();
        $products = CoaInventoryDetailAccount::whereIn('id', $productsArray)->pluck('name','id');

        $getPrice = DetailAccountProducts::where('detail_account_id',$grnMaster->party_id)->whereIn('product_id',$productsArray)->get();

        $pricesArray = $getPrice->pluck('price', 'product_id')->toArray();
        $discountsArray = $getPrice->pluck('discount', 'product_id')->toArray();

        $deliveredToParties =DeliveredToParties::where('detail_account_id', $grnMaster->party_id)->pluck('party_name','id');



        if (empty($grnMaster)) {
            abort(404);
        }

        return view('sale-return.create', compact('parties','saleMans','sectors','areas','products','deliveredToParties','discountsArray', 'pricesArray','commissionArray','pageTitle','invoiceNo','grnMaster','grnDetails','dropDownData'));
    }



    /*
     * Save sale into db.
     * @param: @request
     * */
    public function store(Request $request)
    {
        // dd($request);

        $request = $request->except('_token', 'id');
        // DB::beginTransaction();
        // try {

            //Insert data into sale tables.
            $saleReturnMasterData = $this->salereturnService->prepareSaleReturnMasterData($request);
            $saleReturnMasterInsert = $this->commonService->findUpdateOrCreate(SaleReturnMaster::class, ['id' => ''], $saleReturnMasterData);
            $saleReturnDetailData = $this->salereturnService->prepareSaleReturnDetailData($request, $saleReturnMasterInsert->id);
            $this->salereturnService->saveSaleReturn($saleReturnDetailData);

            // Insert data into stock table.
            // $this->stockLedgerService->prepareAndSaveData($request, $saleReturnMasterInsert->id, config('constants.SALE_RETURN_TRANSACTION_TYPE'));

            // Insert data into accounts ledger table.
            // $debitAccountData = $this->salereturnService->prepareAccountDebitData($request, $saleReturnMasterInsert->id, config('constants.SALE_RETURN_TRANSACTION_TYPE'), config('constants.SALE_RETURN_DESCRIPTION'));
            // $creditAccountData = $this->salereturnService->prepareAccountCreditData($request, $saleReturnMasterInsert->id, config('constants.SALE_RETURN_TRANSACTION_TYPE'), config('constants.SALE_RETURN_DESCRIPTION'));
            // AccountLedger::insert($debitAccountData);
            // AccountLedger::insert($creditAccountData);
        //     DB::commit();
        // } catch (\Exception $e) {
        //     DB::rollback();
        //     return redirect('sale-return/create')->with('error', $e->getMessage());
        // }
        return redirect('sale-return/sales-return-list')->with('message', config('constants.add'));
    }

    /*
     * Show edit page.
     * */
    public function edit($id)
    {
        $pageTitle = 'Update Sale Return Invoice';
        $saleReturn = SaleReturnMaster::find($id);
        $date = Carbon::parse($saleReturn->date)->format('d-m-Y');
        $saleReturnDetails = SaleReturnDetail::where('sale_return_master_id', $id)->get();

        $dropDownData = $this->salereturnService->DropDownData();
        $currentInvoice = $id;
        $parties =CoaDetailAccount::where('id', $saleReturn->party_id)->pluck('account_name','id');

        $partySectors =CoaDetailAccountSectors::where('master_account_id', $saleReturn->party_id)->pluck('sector_id');
        $sectors = Sector::whereIn('id',$partySectors)->pluck('name','id');
        $sectorIds = Sector::whereIn('id',$partySectors)->pluck('id');

        $getCommission =CoaDetailAccount::where('id', $saleReturn->party_id)->get();
        $commissionArray = $getCommission->pluck('commision')->toArray();
        $saleMans =SaleMan::where('id', $saleReturn->saleman)->pluck('name','id');

        $areaArray =CoaDetailAccountArea::where('master_account_id', $saleReturn->party_id)->where('sector_id', $saleReturn->sector)->get();
        $fetchAreaIds= $areaArray->pluck('area_id')->ToArray();
        $areas = Area::whereIn("id", $fetchAreaIds)->pluck('name','id');

        $transporters =Transporter::where('id', $saleReturn->transporter_id)->pluck('name','id');

        $getProducts = DetailAccountProducts::where('detail_account_id',$saleReturn->party_id)->get();
        $productsArray = $getProducts->pluck('product_id')->toArray();
        $products = CoaInventoryDetailAccount::pluck('name','id');

        $getPrice = DetailAccountProducts::where('detail_account_id',$saleReturn->party_id)->whereIn('product_id',$productsArray)->get();
        $pricesArray = $getPrice->pluck('price', 'product_id')->toArray();
        $discountsArray = $getPrice->pluck('discount', 'product_id')->toArray();

        $deliveredToParties =DeliveredToParties::where('id', $saleReturn->delivered_to)->pluck('party_name','id');



        if (empty($saleReturn)) {
            $message = config('constants.wrong');
        }

        return view('sale-return.edit', compact('saleReturn','currentInvoice','date', 'saleReturnDetails', 'parties','saleMans','sectors','areas','products','deliveredToParties','discountsArray', 'pricesArray','commissionArray','transporters','pageTitle','dropDownData'));
    }

    /*
     * update existing resource.
     * @param: $data
     * */
    public function update(Request $request)
    {

        // DB::beginTransaction();
        // try {
            $request = request()->all();
            // SaleReturnMaster::where('id', $request['id'])->delete();
            SaleReturnDetail::where('sale_return_master_id', $request['id'])->delete();
            // StockLedger::where('invoice_id', $request['id'])->delete();
            // AccountLedger::where('invoice_id', $request['id'])->delete();

            //Save data into relevant tables.
            $saleReturnMasterData = $this->salereturnService->prepareSaleReturnMasterData($request);
            $saleReturnMasterInsert = $this->commonService->findUpdateOrCreate(SaleReturnMaster::class, ['id' => request('id')], $saleReturnMasterData);
            $saleReturnDetailData = $this->salereturnService->prepareSaleReturnDetailData($request, $saleReturnMasterInsert->id);
            $this->salereturnService->saveSaleReturn($saleReturnDetailData);

            //Save data into stock table.
            // $this->stockLedgerService->prepareAndSaveData($request, $saleReturnMasterInsert->id, config('constants.SALE_RETURN_TRANSACTION_TYPE'));
            // $debitAccountData = $this->salereturnService->prepareAccountDebitData($request, $saleReturnMasterInsert->id, config('constants.SALE_RETURN_TRANSACTION_TYPE'), config('constants.SALE_RETURN_DESCRIPTION'));
            // $creditAccountData = $this->salereturnService->prepareAccountCreditData($request, $saleReturnMasterInsert->id, config('constants.SALE_RETURN_TRANSACTION_TYPE'), config('constants.SALE_RETURN_DESCRIPTION'));
            // AccountLedger::insert($debitAccountData);
            // AccountLedger::insert($creditAccountData);
            // DB::commit();
        // } catch (\Exception $e) {
        //     DB::rollback();
        //     return redirect('sale-return/create')->with('error', $e->getMessage());
        // }

        return redirect('sale-return/sales-return-list')->with('message', config('constants.update'));
    }

    public function print($id)
    {
        $title = 'Sale Return Invoice';
        $saleReturnMaster = SaleReturnMaster::find($id);
        $date = Carbon::parse($saleReturnMaster->date)->format('d-m-Y');
        $party = CoaDetailAccount::where('id', $saleReturnMaster->party_id)->value('account_name');
        $saleMan = SaleMan::where('id', $saleReturnMaster->saleman)->value('name');
        $belt = Sector::where('id', $saleReturnMaster->sector)->value('name');
        $area = Area::where('id', $saleReturnMaster->area)->value('name');
        $saleReturnDetails = SaleReturnDetail::where('sale_return_master_id', $saleReturnMaster->id)->get();
        // dd($saleReturnDetails);
        $productsArray = $saleReturnDetails->pluck('product_id')->toArray();
        $products = CoaInventoryDetailAccount::whereIn('id',$productsArray)->pluck('name','id');
        $user = User::where('id',$saleReturnMaster->created_by)->value('name');
        $deliverdToParties = DeliveredToParties::where('id',$saleReturnMaster->delivered_to)->value('party_name');
        $transporters = Transporter::where('id',$saleReturnMaster->transporter_id)->value('name');

        return view('sale-return.print', compact('title','products','transporters','user','deliverdToParties','saleReturnMaster','date','saleReturnDetails','party','saleMan','belt','area'));
    }

    /*
     * Delete existing resource.
     * @param: request()->id
     * */
    public function delete()
    {
        try {
            DB::beginTransaction();
            $deleteMaster = SaleReturnMaster::where('id', request()->id)->delete();
            $deleteDetail = SaleReturnDetail::where('sale_master_id', request()->id)->delete();
            $deleteStock = StockLedger::where('invoice_id', request()->id)->delete();
            $accountEntryDetail = AccountLedger::where('invoice_id', request()->id)->delete();
            DB::commit();
            //
            if ($deleteMaster && $deleteDetail && $deleteStock && $accountEntryDetail) {
                return $this->commonService->deleteResource(SaleReturnMaster::class, SaleReturnDetail::class);
            }

        } catch (\Exception $e) {
            DB::rollback();
            return redirect('sale-return/sale-return-list')->with('error', $e->getMessage());
        }
    }

    /*
    * View sale detail.
    * @param: $id
    * */
    public function view($id)
    {
        $saleMaster = $this->salereturnService->getSaleReturnMasterById($id);
        $saleDetail = $this->salereturnService->getSaleReturnDetailById($id);
        if (empty($saleMaster)) {
            $message = config('constants.wrong');
        }

        return view('sale-return.view', compact('saleMaster', 'saleDetail'));
    }


    public function getSaleManAreaDetail(Request $request)
    {
        $areasArray = CoaDetailAccountArea::where('master_account_id', $request->party_id)->where('sector_id', $request->sector_id)->get();
        $fetchAreaIds= $areasArray->pluck('area_id')->ToArray();
        $data['areas'] = Area::whereIn("id", $fetchAreaIds)->get();
        return response()->json($data);
    }

}
