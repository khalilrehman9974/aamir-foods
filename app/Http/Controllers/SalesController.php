<?php

namespace App\Http\Controllers;


use App\Http\Requests\StoreSaleRequest;
use App\Models\DispatchNoteDetail;
use App\Models\DispatchNoteMaster;
use App\Models\Product;
use App\Models\SaleDetail;
use App\Models\SaleMan;
use App\Models\SaleMaster;
use App\Models\SalePurchaseType;
use App\Services\AccountLedgerService;
use App\Services\CommonService;
use App\Services\SaleService;
use App\Services\StockService;
use App\Models\Stock;
use Illuminate\Support\Facades\DB;

class SalesController extends Controller
{

    protected $commonService;
    protected $saleService;



    public function __construct(CommonService $commonService, SaleService $saleService)
    {
        $this->commonService = $commonService;
        $this->saleService = $saleService;

    }

    /*
     * Show page of list of sales.
     * */
    public function index()
    {
        $pageTitle = 'List Of Sales';
        $request = request()->all();
        $sales = $this->saleService->searchSale($request);

        return view('sales.index', compact('sales', 'request','pageTitle'));
    }

    /*
     * Show page of create sale.
     * */
    public function create()
    {
        $pageTitle = 'Sales';
        $type = SalePurchaseType::where('name', 'Sale')->pluck('name', 'id');
        $dropDownData = $this->commonService->DropDownData();
        $invoiceNo = SaleMaster::max('id') + 1;
//        dd($dropDownData);

        return view('sales.create', compact( 'type','pageTitle', 'dropDownData', 'invoiceNo'));
    }

    /*
     * Save sale into db.
     * @param: @request
     * */
    public function store(StoreSaleRequest $request)
    {
        $request = $request->except('_token', 'saleId');
        $session = $this->commonService->getSession();
//        try {
//            DB::beginTransaction();
//            dd($session->business_id);
            $request['business_id'] = $session->business_id;
            $request['f_year_id'] = $session->financial_year;
            $request['type_id'] = $session->financial_year;
            dd($request);
            //Insert data into sale tables.
            $saleMasterData = $this->saleService->prepareSaleMasterData($request);
            $saleMasterInsert = $this->commonService->findUpdateOrCreate(SaleMaster::class, ['id' => ''], $saleMasterData);
            $saleDetailData = $this->saleService->prepareSaleDetailData($request, $saleMasterInsert->id);
            $this->saleService->saveSale($saleDetailData);

            //Insert data into stock table.
            // $this->stockService->prepareAndSaveData($request, $saleMasterInsert->id, SaleService::SALE_TRANSACTION_TYPE);

            //Insert data into accounts ledger table.
            // $debitAccountData = $this->saleService->prepareAccountDebitData($request, $saleMasterInsert->id, SaleService::SALE_TRANSACTION_TYPE, SaleService::SALE_DESCRIPTION);
            // $creditAccountData = $this->saleService->prepareAccountCreditData($request, $saleMasterInsert->id, SaleService::SALE_TRANSACTION_TYPE, SaleService::SALE_DESCRIPTION);
            // AccountLedger::insert($debitAccountData);
            // AccountLedger::insert($creditAccountData);
           /* DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            return redirect('sale/create')->with('error', $e->getMessage());
        }*/
        return redirect('sale/sales-list')->with('message', config('constants.add'));
    }

    /*
     * Show edit page.
     * */
    public function edit($id)
    {
        $sale = SaleMaster::find($id);
        // $products = Product::where('brand_id', $sale->brand_id)->pluck('name', 'id');
        $type = SalePurchaseType::where('name', 'Sale')->pluck('name', 'id');
        $saleDetails = SaleDetail::where('sale_master_id', $id)->get();
        if (empty($sale)) {
            $message = config('constants.wrong');
        }

        return view('sales.create', compact('sale',  'type', 'saleDetails'));
    }

    /*
     * update existing resource.
     * @param: $data
     * */
    public function update(StoreSaleRequest $request)
    {
        try {
            DB::beginTransaction();
            $request = request()->all();
            SaleMaster::where('id', $request['saleId'])->delete();
            SaleDetail::where('sale_master_id', $request['saleId'])->delete();
            // Stock::where('invoice_id', $request['saleId'])->delete();
            // AccountLedger::where('invoice_id', $request['saleId'])->delete();

            //Save data into relevant tables.
            $saleMasterData = $this->saleService->prepareSaleMasterData($request);
            $saleMasterInsert = $this->commonService->findUpdateOrCreate(SaleMaster::class, ['id' => request('productId')], $saleMasterData);
            $saleDetailData = $this->saleService->prepareSaleDetailData($request, $saleMasterInsert->id);
            $this->saleService->saveSale($saleDetailData);

            //Save data into stock table.
            // $this->stockService->prepareAndSaveData($request, $saleMasterInsert->id, SaleService::SALE_TRANSACTION_TYPE);
            // $debitAccountData = $this->saleService->prepareAccountDebitData($request, $saleMasterInsert->id, SaleService::SALE_TRANSACTION_TYPE, SaleService::SALE_DESCRIPTION);
            // $creditAccountData = $this->saleService->prepareAccountCreditData($request, $saleMasterInsert->id, SaleService::SALE_TRANSACTION_TYPE, SaleService::SALE_DESCRIPTION);
            // AccountLedger::insert($debitAccountData);
            // AccountLedger::insert($creditAccountData);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            return redirect('sale/create')->with('error', $e->getMessage());
        }

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
            // $deleteStock = Stock::where('invoice_id', request()->id)->delete();
            // $accountEntryDetail = AccountLedger::where('invoice_id', request()->id)->delete();
            DB::commit();
            // && $deleteStock && $accountEntryDetail
            if ($deleteMaster && $deleteDetail ) {
                return $this->commonService->deleteResource(SaleMaster::class, SaleDetail::class);
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
}
