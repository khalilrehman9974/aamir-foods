<?php

namespace App\Http\Controllers;

use App\Services\CommonService;
use App\Models\SalePurchaseType;
use Illuminate\Support\Facades\DB;
use App\Models\PurchaseReturnDetail;
use App\Models\PurchaseReturnMaster;
use App\Services\purchaseReturnService;
use App\Http\Requests\StorePurchaseReturn;

class PurchaseReturnController extends Controller
{
    protected $commonService;
    protected $purchaseReturnService;



    public function __construct(CommonService $commonService, PurchaseReturnService $purchaseReturnService)
    {
        $this->commonService = $commonService;
        $this->purchaseReturnService = $purchaseReturnService;

    }

    /*
     * Show page of list of sales.
     * */
    public function index()
    {
        $pageTitle = 'List Of Purchase Returns';
        $request = request()->all();
        $purchaseReturns = $this->purchaseReturnService->searchPurchaseReturn($request);

        return view('purchase-return.index', compact('purchaseReturns', 'request','pageTitle'));
    }

    /*
     * Show page of create Purchase Return.
     * */
    public function create()
    {
        $pageTitle = 'Create Purchase Return';
        $type = SalePurchaseType::where('name', 'Purchase Return')->pluck('name', 'id');
        return view('purchase-return.create', compact( 'type','pageTitle'));
    }

    /*
     * Save Purchase into db.
     * @param: @request
     * */
    public function store(StorePurchaseReturn $request)
    {

        $request = $request->except('_token', 'purchaseReturnId');
        try {
            DB::beginTransaction();
            //Insert data into purchase tables.
            $purchaseReturnMasterData = $this->purchaseReturnService->preparePurchaseReturnMasterData($request);
            $purchaseReturnMasterInsert = $this->commonService->findUpdateOrCreate(PurchaseReturnMaster::class, ['id' => ''], $purchaseReturnMasterData);
            $purchasereturnDetailData = $this->purchaseReturnService->preparePurchaseReturnDetailData($request, $purchaseReturnMasterInsert->id);
            $this->purchaseReturnService->savePurchaseReturn($purchasereturnDetailData);

            //Insert data into stock table.
            // $this->stockService->prepareAndSaveData($request, $saleMasterInsert->id, purchaseReturnService::SALE_TRANSACTION_TYPE);

            //Insert data into accounts ledger table.
            // $debitAccountData = $this->purchaseReturnService->prepareAccountDebitData($request, $saleMasterInsert->id, purchaseReturnService::SALE_TRANSACTION_TYPE, purchaseReturnService::SALE_DESCRIPTION);
            // $creditAccountData = $this->purchaseReturnService->prepareAccountCreditData($request, $saleMasterInsert->id, purchaseReturnService::SALE_TRANSACTION_TYPE, purchaseReturnService::SALE_DESCRIPTION);
            // AccountLedger::insert($debitAccountData);
            // AccountLedger::insert($creditAccountData);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            return redirect('purchase-return/create')->with('error', $e->getMessage());
        }
        return redirect('purchase-return/list')->with('message', config('constants.add'));
    }

    /*
     * Show edit page.
     * */
    public function edit($id)
    {
        $purchaseReturn = PurchaseReturnMaster::find($id);
        $type = SalePurchaseType::where('name', 'Purchase Return')->pluck('name', 'id');
        $purchaseReturnDetails = PurchaseReturnDetail::where('purchase_master_id', $id)->get();
        if (empty($purchaseReturn)) {
            $message = config('constants.wrong');
        }

        return view('purchase-return.create', compact('purchaseReturn','type', 'purchaseReturnDetails'));
    }

    /*
     * update existing resource.
     * @param: $data
     * */
    public function update(StorePurchaseReturn $request)
    {
        try {
            DB::beginTransaction();
            $request = request()->all();
            PurchaseReturnMaster::where('id', $request['saleId'])->delete();
            PurchaseReturnDetail::where('purchase_return_master_id', $request['purchaseReturnId'])->delete();
            // Stock::where('invoice_id', $request['saleId'])->delete();
            // AccountLedger::where('invoice_id', $request['saleId'])->delete();

            //Save data into relevant tables.
            $purchaseMasterData = $this->purchaseReturnService->preparePurchaseReturnMasterData($request);
            $purchaseMasterInsert = $this->commonService->findUpdateOrCreate(PurchaseReturnMaster::class, ['id' => request('productId')], $purchaseMasterData);
            $purchaseDetailData = $this->purchaseReturnService->preparePurchaseReturnDetailData($request, $purchaseMasterInsert->id);
            $this->purchaseReturnService->savePurchaseReturn($purchaseDetailData);

            //Save data into stock table.
            // $this->stockService->prepareAndSaveData($request, $saleMasterInsert->id, purchaseReturnService::SALE_TRANSACTION_TYPE);
            // $debitAccountData = $this->purchaseReturnService->prepareAccountDebitData($request, $saleMasterInsert->id, purchaseReturnService::SALE_TRANSACTION_TYPE, purchaseReturnService::SALE_DESCRIPTION);
            // $creditAccountData = $this->purchaseReturnService->prepareAccountCreditData($request, $saleMasterInsert->id, purchaseReturnService::SALE_TRANSACTION_TYPE, purchaseReturnService::SALE_DESCRIPTION);
            // AccountLedger::insert($debitAccountData);
            // AccountLedger::insert($creditAccountData);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            return redirect('purchase-return/create')->with('error', $e->getMessage());
        }

        return redirect('purchase-return/list')->with('message', config('constants.update'));
    }

    /*
     * Delete existing resource.
     * @param: request()->id
     * */
    public function delete()
    {
        try {
            DB::beginTransaction();
            $deleteMaster = PurchaseReturnMaster::where('id', request()->id)->delete();
            $deleteDetail = PurchaseReturnDetail::where('purchase_return_master_id', request()->id)->delete();
            // $deleteStock = Stock::where('invoice_id', request()->id)->delete();
            // $accountEntryDetail = AccountLedger::where('invoice_id', request()->id)->delete();
            DB::commit();
            // && $deleteStock && $accountEntryDetail
            if ($deleteMaster && $deleteDetail ) {
                return $this->commonService->deleteResource(PurchaseReturnMaster::class, PurchaseReturnDetail::class);
            }

        } catch (\Exception $e) {
            DB::rollback();
            return redirect('purchase-return/list')->with('error', $e->getMessage());
        }
    }

    /*
    * View purchaseReturn detail.
    * @param: $id
    * */
    public function view($id)
    {
        $PurchaseReturnMaster = $this->purchaseReturnService->getpurchaseReturnMasterById($id);
        $PurchaseReturnDetail = $this->purchaseReturnService->getPurchaseReturnDetailById($id);
        if (empty($PurchaseReturnMaster)) {
            $message = config('constants.wrong');
        }

        return view('purchases.view', compact('PurchaseReturnMaster', 'PurchaseReturnDetail'));
    }

}
