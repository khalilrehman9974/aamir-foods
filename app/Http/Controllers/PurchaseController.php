<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PurchaseDetail;
use App\Models\PurchaseMaster;
use App\Services\CommonService;
use App\Models\SalePurchaseType;
use App\Services\PurchaseService;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\StorePurchaseRequest;

class PurchaseController extends Controller
{
    protected $commonService;
    protected $purchaseService;

    public function __construct(CommonService $commonService, PurchaseService $purchaseService)
    {
        $this->commonService = $commonService;
        $this->purchaseService = $purchaseService;

    }

    /*
     * Show page of list of sales.
     * */
    public function index()
    {
        $pageTitle = 'List Of Purchases';
        $request = request()->all();
        $purchases = $this->purchaseService->searchPurchase($request);

        return view('purchases.index', compact('purchases', 'request','pageTitle'));
    }

    /*
     * Show page of create Purchase.
     * */
    public function create()
    {
        $pageTitle = 'Create Purchase';
        $type = SalePurchaseType::where('name', 'Purchase')->pluck('name', 'id');
        return view('purchases.create', compact( 'type','pageTitle'));
    }

    /*
     * Save Purchase into db.
     * @param: @request
     * */
    public function store(StorePurchaseRequest $request)
    {

        $request = $request->except('_token', 'id');
        try {
            DB::beginTransaction();
            //Insert data into purchase tables.
            $purchaseMasterData = $this->purchaseService->preparePurchaseMasterData($request);
            $purchaseMasterInsert = $this->commonService->findUpdateOrCreate(PurchaseMaster::class, ['id' => ''], $purchaseMasterData);
            $purchaseDetailData = $this->purchaseService->preparePurchaseDetailData($request, $purchaseMasterInsert->id);
            $this->purchaseService->savePurchase($purchaseDetailData);

            //Insert data into stock table.
            // $this->stockService->prepareAndSaveData($request, $saleMasterInsert->id, purchaseService::SALE_TRANSACTION_TYPE);

            //Insert data into accounts ledger table.
            // $debitAccountData = $this->purchaseService->prepareAccountDebitData($request, $saleMasterInsert->id, purchaseService::SALE_TRANSACTION_TYPE, purchaseService::SALE_DESCRIPTION);
            // $creditAccountData = $this->purchaseService->prepareAccountCreditData($request, $saleMasterInsert->id, purchaseService::SALE_TRANSACTION_TYPE, purchaseService::SALE_DESCRIPTION);
            // AccountLedger::insert($debitAccountData);
            // AccountLedger::insert($creditAccountData);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            return redirect('purchase/create')->with('error', $e->getMessage());
        }
        return redirect('purchase/purchase-list')->with('message', config('constants.add'));
    }

    /*
     * Show edit page.
     * */
    public function edit($id)
    {
        $purchase = PurchaseMaster::find($id);
        $type = SalePurchaseType::where('name', 'Purchase')->pluck('name', 'id');
        $purchaseDetails = PurchaseDetail::where('purchase_master_id', $id)->get();
        if (empty($purchase)) {
            $message = config('constants.wrong');
        }

        return view('purchases.create', compact('purchase','type', 'purchaseDetails'));
    }

    /*
     * Delete existing resource.
     * @param: request()->id
     * */
    public function delete()
    {
        try {
            DB::beginTransaction();
            $deleteMaster = PurchaseMaster::where('id', request()->id)->delete();
            $deleteDetail = PurchaseDetail::where('purchase_master_id', request()->id)->delete();
            // $deleteStock = Stock::where('invoice_id', request()->id)->delete();
            // $accountEntryDetail = AccountLedger::where('invoice_id', request()->id)->delete();
            DB::commit();
            // && $deleteStock && $accountEntryDetail
            if ($deleteMaster && $deleteDetail ) {
                return $this->commonService->deleteResource(PurchaseMaster::class, PurchaseDetail::class);
            }

        } catch (\Exception $e) {
            DB::rollback();
            return redirect('purchase/list')->with('error', $e->getMessage());
        }
    }

    /*
    * View sale detail.
    * @param: $id
    * */
    public function view($id)
    {
        $PurchaseMaster = $this->purchaseService->getpurchaseMasterById($id);
        $PurchaseDetail = $this->purchaseService->getPurchaseDetailById($id);
        if (empty($PurchaseMaster)) {
            $message = config('constants.wrong');
        }

        return view('purchases.view', compact('PurchaseMaster', 'PurchaseDetail'));
    }

}
