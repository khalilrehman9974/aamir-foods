<?php

namespace App\Http\Controllers;


use App\Models\Area;
use App\Models\Sector;
use App\Models\SaleMan;
use App\Models\SaleDetail;
use App\Models\SaleMaster;
use App\Models\StockLedger;
use App\Models\Transporter;
use Illuminate\Http\Request;
use App\Models\AccountLedger;
use App\Services\SaleService;
use App\Services\CommonService;
use App\Models\CoaDetailAccount;
use App\Models\SalePurchaseType;
use App\Models\DeliveredToParties;
use App\Models\DispatchNoteDetail;
use App\Models\DispatchNoteMaster;
use Illuminate\Support\Facades\DB;
use App\Services\StockLedgerService;
use App\Models\DetailAccountProducts;
use App\Services\AccountLedgerService;
use App\Http\Requests\StoreSaleRequest;
use App\Models\CoaInventoryDetailAccount;

class SalesController extends Controller
{

    protected $commonService;
    protected $saleService;
    protected $stockLedgerService;
    protected $accountLedgerService;



    public function __construct(
        CommonService $commonService,
        SaleService $saleService,
        StockLedgerService $stockLedgerService,
        AccountLedgerService $accountLedgerService
    ) {
        $this->commonService = $commonService;
        $this->saleService = $saleService;
        $this->stockLedgerService = $stockLedgerService;
        $this->accountLedgerService = $accountLedgerService;
    }

    /*
     * Show page of list of sales.
     * */
    public function index()
    {
        $pageTitle = 'List Of Sales';
        $request = request()->all();
        $sales = $this->saleService->searchSale($request);

        return view('sales.index', compact('sales', 'request', 'pageTitle'));
    }

    /*
     * Show page of create sale.
     * */
    public function create(Request $request)
    {
        $pageTitle = 'Sales Invoice';
        $dropDownData = $this->commonService->DropDownData();
        $invoiceNo = SaleMaster::max('id') + 1;
        $dispatchNote = DispatchNoteMaster::find($request->id);

        $dispatchNoteDetails = DispatchNoteDetail::where('dispatch_note_master_id', $request->id)->get();
        $parties =CoaDetailAccount::where('id', $dispatchNote->party_id)->pluck('account_name','id');
        $getCommission =CoaDetailAccount::where('id', $dispatchNote->party_id)->get();
        $commissionArray = $getCommission->pluck('commision')->toArray();
        $saleMans =SaleMan::where('id', $dispatchNote->saleman)->pluck('name','id');
        $sectors =Sector::where('id', $dispatchNote->sector)->pluck('name','id');
        $areas =Area::where('id', $dispatchNote->area)->pluck('name','id');
        $transporters =Transporter::where('id', $dispatchNote->transporter_id)->pluck('name','id');

        $getProducts = DetailAccountProducts::where('detail_account_id',$dispatchNote->party_id)->get();
        $productsArray = $getProducts->pluck('product_id')->toArray();
        $products = CoaInventoryDetailAccount::whereIn('id', $productsArray)->pluck('name','id');

        $getPrice = DetailAccountProducts::where('detail_account_id',$dispatchNote->party_id)->whereIn('product_id',$productsArray)->get();
        $pricesArray = $getPrice->pluck('price', 'product_id')->toArray();
        $discountsArray = $getPrice->pluck('discount', 'product_id')->toArray();

        $deliveredToParties =DeliveredToParties::where('id', $dispatchNote->delivered_to)->pluck('party_name','id');

        if (empty($dispatchNote)) {
            abort(404);
        }

        return view('sales.create', compact('pageTitle', 'transporters','commissionArray', 'pricesArray','discountsArray','deliveredToParties', 'products','dispatchNoteDetails', 'areas','sectors','saleMans','parties','dropDownData', 'invoiceNo', 'dispatchNote'));
    }


    public function generate()
    {
        return view('sales.generate');
    }

    /*
     * Save sale into db.
     * @param: @request
     * */
    public function store(Request $request)
    {

        $request = $request->except('_token', 'id');
        //     DB::beginTransaction();
        //    try {


        //Insert data into sale tables.
        $saleMasterData = $this->saleService->prepareSaleMasterData($request);

        $saleMasterInsert = $this->saleService->findUpdateOrCreate(SaleMaster::class, ['id' => ''], $saleMasterData);
        $saleDetailData = $this->saleService->prepareSaleDetailData($request, $saleMasterInsert->id);
        $this->saleService->saveSale($saleDetailData);

        //Insert data into stock table.
        // $this->stockLedgerService->prepareAndSaveData($request, $saleMasterInsert->id, config('contants.SALE_TRANSACTION_TYPE'));

        //Insert data into accounts ledger table.
        // $debitAccountData = $this->saleService->prepareAccountDebitData($request, $saleMasterInsert->id, config('contants.SALE_TRANSACTION_TYPE'), config('contants.SALE_DESCRIPTION'));
        // $creditAccountData = $this->saleService->prepareAccountCreditData($request, $saleMasterInsert->id, config('contants.SALE_TRANSACTION_TYPE'), config('contants.SALE_DESCRIPTION'));
        // AccountLedger::insert($debitAccountData);
        // AccountLedger::insert($creditAccountData);
        // DB::commit();
        // } catch (\Exception $e) {
        //     DB::rollback();
        //     return redirect('sale/create')->with('error', $e->getMessage());
        // }
        return redirect('sale/sales-list')->with('message', config('constants.add'));
    }

    /*
     * Show edit page.
     * */
    public function edit($id)
    {
        $pageTitle = 'Update Sales Invoice';
        $currentInvoice = $id;
        $sale = SaleMaster::find($id);
        // dd($sale);
        $parties =CoaDetailAccount::where('id', $sale->party_id)->pluck('account_name','id');
        $saleMans =SaleMan::where('id', $sale->saleman)->pluck('name','id');
        $sectors =Sector::where('id', $sale->sector)->pluck('name','id');
        $areas =Area::where('id', $sale->area)->pluck('name','id');
        $transporters =Transporter::where('id', $sale->transporter_id)->pluck('name','id');
        $deliveredToParties =DeliveredToParties::where('id', $sale->delivered_to)->pluck('party_name','id');
        $saleDetails = SaleDetail::where('sale_master_id', $id)->get();


        // dd($saleDetails);
        $getProducts = DetailAccountProducts::where('detail_account_id',$sale->party_id)->get();
        $productsArray = $getProducts->pluck('product_id')->toArray();
        $products = CoaInventoryDetailAccount::whereIn('id', $productsArray)->pluck('name','id');

        $getPrice = DetailAccountProducts::where('detail_account_id',$sale->party_id)->whereIn('product_id',$productsArray)->get();
        $pricesArray = $getPrice->pluck('price', 'product_id')->toArray();
        $discountsArray = $getPrice->pluck('discount', 'product_id')->toArray();


        if (empty($sale)) {
            $message = config('constants.wrong');
        }

        return view('sales.edit', compact('currentInvoice','pricesArray','deliveredToParties','transporters','areas','sectors','saleMans','parties','sale','pageTitle','products','saleDetails'));
    }

    /*
     * update existing resource.
     * @param: $data
     * */
    public function update(Request $request)
    {
        // dd($request);
        // try {
        //     DB::beginTransaction();
            $request = request()->all();
            SaleDetail::where('sale_master_id', $request['id'])->delete();
            // Stock::where('invoice_id', $request['saleId'])->delete();
            // AccountLedger::where('invoice_id', $request['saleId'])->delete();

            //Save data into relevant tables.
            $saleMasterData = $this->saleService->prepareSaleMasterData($request);
            $saleMasterInsert = $this->commonService->findUpdateOrCreate(SaleMaster::class, ['id' => request('id')], $saleMasterData);
            $saleDetailData = $this->saleService->prepareSaleDetailData($request, $saleMasterInsert->id);
            $this->saleService->saveSale($saleDetailData);

            //Save data into stock table.
            // $this->stockLedgerService->prepareAndSaveData($request, $saleMasterInsert->id, config('contants.SALE_TRANSACTION_TYPE'));
            // $debitAccountData = $this->saleService->prepareAccountDebitData($request, $saleMasterInsert->id, config('contants.SALE_TRANSACTION_TYPE'), config('contants.SALE_DESCRIPTION'));
            // $creditAccountData = $this->saleService->prepareAccountCreditData($request, $saleMasterInsert->id, config('contants.SALE_TRANSACTION_TYPE'), config('contants.SALE_DESCRIPTION'));
            // AccountLedger::insert($debitAccountData);
            // AccountLedger::insert($creditAccountData);
        //     DB::commit();
        // } catch (\Exception $e) {
        //     DB::rollback();
        //     return redirect('sale/create')->with('error', $e->getMessage());
        // }

        return redirect('sale/sales-list')->with('message', config('constants.update'));
    }

    /*
     * Delete existing resource.
     * @param: request()->id
     * */
    public function delete()
    {
        try {
            DB::beginTransaction();
            $deleteMaster = SaleMaster::where('id', request()->id)->delete();
            $deleteDetail = SaleDetail::where('sale_master_id', request()->id)->delete();
            $deleteStock = StockLedger::where('invoice_id', request()->id)->delete();
            $accountEntryDetail = AccountLedger::where('invoice_id', request()->id)->delete();
            DB::commit();
            // && $deleteStock && $accountEntryDetail
            if ($deleteMaster && $deleteDetail && $deleteStock && $accountEntryDetail) {
                return response()->json(['status' => 'success', 'message' => config('constants.delete')]);
            } else {
                return response()->json(['status' => 'fail', 'message' => config('constants.wrong')]);
            }
        } catch (\Exception $e) {
            DB::rollback();
            return redirect('sale/sales-list')->with('error', $e->getMessage());
        }
    }

    /*
    * View sale detail.
    * @param: $id
    * */
    public function view($id)
    {
        $saleMaster = $this->saleService->getSaleMasterById($id);
        $saleDetail = $this->saleService->getSaleDetailById($id);
        if (empty($saleMaster)) {
            $message = config('constants.wrong');
        }

        return view('sales.view', compact('saleMaster', 'saleDetail'));
    }


    // /*
    //  * Get product detail
    //  * @param: request('productCode')
    //  * */
    // public function getProductDetail()
    // {
    //     $product = $this->productService->getById(request('productCode'));
    //     if ($product) {
    //         return json_encode(['status' => 'success', 'data' => $product]);
    //     } else {
    //         return response()->json(['status' => 'fail', 'message' => SaleService::SOME_THING_WENT_WRONG]);
    //     }
    // }

    public function getDispatchNote()
    {
        return $dispatchNote = DispatchNoteMaster::with('items')->get();
    }

    public function getProductRates($name)
    {
        dd($name);

    }
}
