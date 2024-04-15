<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\CommonService;
use App\Models\SalePurchaseType;
use App\Models\SaleReturnDetail;
use App\Models\SaleReturnMaster;
use Illuminate\Support\Facades\DB;
use App\Services\SaleReturnService;
use App\Http\Requests\StoreSaleReturnRequest;

class SalesReturnController extends Controller
{

    protected $commonService;
    protected $salereturnService;



    public function __construct( CommonService $commonService, SaleReturnService $salereturnService)
    {
        $this->commonService = $commonService;
        $this->salereturnService = $salereturnService;

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

    /*
     * Show page of create sale.
     * */
    public function create()
    {
        $pageTitle = 'Create Sale Returns';
        $dropDownData = $this->salereturnService->DropDownData();
        return view('sale-return.create', compact( 'pageTitle','dropDownData'));
    }

    /*
     * Save sale into db.
     * @param: @request
     * */
    public function store(StoreSaleReturnRequest $request)
    {

        $request = $request->except('_token', 'saleId');
        try {
            DB::beginTransaction();
            //Insert data into sale tables.
            $saleReturnMasterData = $this->salereturnService->prepareSaleReturnMasterData($request);
            $saleReturnMasterInsert = $this->commonService->findUpdateOrCreate(SaleReturnMaster::class, ['id' => ''], $saleReturnMasterData);
            $saleReturnDetailData = $this->salereturnService->prepareSaleReturnDetailData($request, $saleReturnMasterInsert->id);
            $this->salereturnService->saveSaleReturn($saleReturnDetailData);

            //Insert data into stock table.
            // $this->stockService->prepareAndSaveData($request, $saleMasterInsert->id, salereturnService::SALE_TRANSACTION_TYPE);

            //Insert data into accounts ledger table.
            // $debitAccountData = $this->salereturnService->prepareAccountDebitData($request, $saleMasterInsert->id, salereturnService::SALE_TRANSACTION_TYPE, salereturnService::SALE_DESCRIPTION);
            // $creditAccountData = $this->salereturnService->prepareAccountCreditData($request, $saleMasterInsert->id, salereturnService::SALE_TRANSACTION_TYPE, salereturnService::SALE_DESCRIPTION);
            // AccountLedger::insert($debitAccountData);
            // AccountLedger::insert($creditAccountData);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            return redirect('sale-return/create')->with('error', $e->getMessage());
        }
        return redirect('sale-return/sale-return-list')->with('message', config('constants.add'));
    }

    /*
     * Show edit page.
     * */
    public function edit($id)
    {
        $saleReturn = SaleReturnMaster::find($id);
        $type = SalePurchaseType::where('name', 'Sale Return')->pluck('name', 'id');
        $saleReturnDetails = SaleReturnDetail::where('sale_master_id', $id)->get();
        if (empty($sale)) {
            $message = config('constants.wrong');
        }

        return view('sale-return.create', compact('saleReturn',  'type', 'saleReturnDetails'));
    }

    /*
     * update existing resource.
     * @param: $data
     * */
    public function update(StoreSaleReturnRequest $request)
    {
        try {
            DB::beginTransaction();
            $request = request()->all();
            SaleReturnMaster::where('id', $request['saleId'])->delete();
            SaleReturnDetail::where('sale_return_master_id', $request['saleId'])->delete();
            // Stock::where('invoice_id', $request['saleId'])->delete();
            // AccountLedger::where('invoice_id', $request['saleId'])->delete();

            //Save data into relevant tables.
            $saleReturnMasterData = $this->salereturnService->prepareSaleReturnMasterData($request);
            $saleReturnMasterInsert = $this->commonService->findUpdateOrCreate(SaleReturnMaster::class, ['id' => request('id')], $saleReturnMasterData);
            $saleReturnDetailData = $this->salereturnService->prepareSaleReturnDetailData($request, $saleReturnMasterInsert->id);
            $this->salereturnService->saveSaleReturn($saleReturnDetailData);

            //Save data into stock table.
            // $this->stockService->prepareAndSaveData($request, $saleMasterInsert->id, salereturnService::SALE_TRANSACTION_TYPE);
            // $debitAccountData = $this->salereturnService->prepareAccountDebitData($request, $saleMasterInsert->id, salereturnService::SALE_TRANSACTION_TYPE, salereturnService::SALE_DESCRIPTION);
            // $creditAccountData = $this->salereturnService->prepareAccountCreditData($request, $saleMasterInsert->id, salereturnService::SALE_TRANSACTION_TYPE, salereturnService::SALE_DESCRIPTION);
            // AccountLedger::insert($debitAccountData);
            // AccountLedger::insert($creditAccountData);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            return redirect('sale-return/create')->with('error', $e->getMessage());
        }

        return redirect('sale-return/sales-list')->with('message', config('constants.update'));
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
            // $deleteStock = Stock::where('invoice_id', request()->id)->delete();
            // $accountEntryDetail = AccountLedger::where('invoice_id', request()->id)->delete();
            DB::commit();
            // && $deleteStock && $accountEntryDetail
            if ($deleteMaster && $deleteDetail ) {
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
    //         return response()->json(['status' => 'fail', 'message' => salereturnService::SOME_THING_WENT_WRONG]);
    //     }
    // }

}
