<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\StockLedger;
use App\Models\Transporter;
use Illuminate\Http\Request;
use App\Models\AccountLedger;
use App\Models\GRNotesDetail;
use App\Models\PurchaseDetail;
use App\Models\PurchaseMaster;
use App\Services\CommonService;
use App\Models\CoaDetailAccount;
use App\Models\GoodsReceivedNote;
use App\Services\PurchaseService;
use Illuminate\Support\Facades\DB;
use App\Services\StockLedgerService;
use App\Services\AccountLedgerService;

class PurchaseController extends Controller
{
    protected $commonService;
    protected $purchaseService;
    protected $stockLedgerService;
    protected $accountLedgerService;

    public function __construct(CommonService $commonService,
    PurchaseService $purchaseService,
    StockLedgerService $stockLedgerService,
    AccountLedgerService $accountLedgerService)
    {
        $this->commonService = $commonService;
        $this->purchaseService = $purchaseService;
        $this->stockLedgerService = $stockLedgerService;
        $this->accountLedgerService = $accountLedgerService;

    }

    /*
     * Show page of list of sales.
     * */
    public function index()
    {
        $pageTitle = 'List Of Purchases';
        $request = request()->all();
        $purchases = $this->purchaseService->search($request);

        return view('purchases.index', compact('purchases', 'request','pageTitle'));
    }

    /*
     * Show page of create Purchase.
     * */
    public function create(Request $request)
    {
        // dd($request);

        $pageTitle = 'Create Purchase Invoice';
        $grnMaster = GoodsReceivedNote::find($request->id);
        $parties = CoaDetailAccount::where('id', $grnMaster->party_id)->pluck('account_name', 'id');
        $transporters = Transporter::where('id', $grnMaster->transporter_id)->pluck('name', 'id');
        $grnDetails = GRNotesDetail::where('master_id',$grnMaster->id)->get();
        $maxId = PurchaseMaster::max('id') + 1;
        // dd($grnMaster);
        $dropDownData = $this->purchaseService->DropDownData();
        $purchaseDetails = PurchaseDetail::where('purchase_master_id')->get();

        if (empty($grnMaster)) {
            abort(404);
        }
        return view('purchases.create', compact('pageTitle','grnMaster','transporters','grnDetails','parties','maxId','purchaseDetails','dropDownData'));
    }

    public function generate()
    {
        return view('purchases.generate');
    }

    /*
     * Save Purchase into db.
     * @param: @request
     * */
    public function store(Request $request)
    {
        // dd($request);
        // $request = $request->except('_token', 'id');
        // DB::beginTransaction();
        // // try {

            //Insert data into purchase tables.
            $purchaseMasterData = $this->purchaseService->preparePurchaseMasterData($request);
            $purchaseMasterInsert = $this->commonService->findUpdateOrCreate(PurchaseMaster::class, ['id' => ''], $purchaseMasterData);
            $purchaseDetailData = $this->purchaseService->preparePurchaseDetailData($request, $purchaseMasterInsert->id);
            $this->purchaseService->savePurchase($purchaseDetailData);

            // $stockLeadgerData = $this->stockLedgerService->prepareAndSaveData($request, $purchaseMasterInsert->id, PurchaseService::PURCHASE_TRANSACTION_TYPE,);
            //Insert data into accounts ledger table.
            // $creditAccountData = $this->accountLedgerService->prepareCreditData($request, $purchaseMasterInsert->id, PurchaseService::PURCHASE_TRANSACTION_TYPE, PurchaseService::PURCHASE_DESCRIPTION);
            // $debitAccountData = $this->accountLedgerService->prepareDebitData($request, $purchaseMasterInsert->id, PurchaseService::PURCHASE_TRANSACTION_TYPE, PurchaseService::PURCHASE_DESCRIPTION);
            // AccountLedger::insert($creditAccountData);
            // AccountLedger::insert($debitAccountData);
            DB::commit();
        // } catch (\Exception $e) {
        //     DB::rollback();
        //     return redirect('purchase/create')->with('error', $e->getMessage());
        // }
        return redirect('purchase/list')->with('message', config('constants.add'));
    }

    /*
     * Show edit page.
     * */
    public function edit($id)
    {
        $pageTitle = 'Update Purchase';
        $purchase = PurchaseMaster::find($id);
        $date = Carbon::parse($purchase->date)->format('d-m-Y');
        $parties = CoaDetailAccount::where('id', $purchase->party_id)->pluck('account_name', 'id');
        $transporters = Transporter::where('id', $purchase->transporter_id)->pluck('name', 'id');
        $dropDownData = $this->purchaseService->DropDownData();
        $purchaseDetails = PurchaseDetail::where('purchase_master_id', $purchase->id)->get();
        if (empty($purchase)) {
            $message = config('constants.wrong');
        }

        return view('purchases.edit', compact('pageTitle','date','parties','transporters','purchase','dropDownData', 'purchaseDetails'));
    }

    public function update(Request $request)
    {
        // dd($request);
        // try {
        //     DB::beginTransaction();
            $request = request()->all();
            // GRNotesDetail::where('master_id', $request['id'])->delete();
            PurchaseDetail::where('purchase_master_id', $request['id'])->delete();
            // StockLedger::where('invoice_id', $request['id'])->delete();
            // AccountLedger::where('invoice_id', $request['id'])->delete();

            //Save data into relevant tables.
            $purchaseMasterData = $this->purchaseService->preparePurchaseMasterData($request);
            $purchaseMasterInsert = $this->commonService->findUpdateOrCreate(PurchaseMaster::class, ['id' => request('id')], $purchaseMasterData);
            $purchaseDetailData = $this->purchaseService->preparePurchaseDetailData($request, $purchaseMasterInsert->id);
            $this->purchaseService->savePurchase($purchaseDetailData);
            //Save data into stock table.
            // $this->stockLedgerService->prepareAndSaveData($request, $purchaseMasterInsert->id, PurchaseService::PURCHASE_TRANSACTION_TYPE);
            // $creditAccountData = $this->accountLedgerService->prepareCreditData($request, $purchaseMasterInsert->id, PurchaseService::PURCHASE_TRANSACTION_TYPE, PurchaseService::PURCHASE_DESCRIPTION);
            // $debitAccountData = $this->accountLedgerService->prepareDebitData($request, $purchaseMasterInsert->id, PurchaseService::PURCHASE_TRANSACTION_TYPE, PurchaseService::PURCHASE_DESCRIPTION);
            // AccountLedger::insert($creditAccountData);
            // AccountLedger::insert($debitAccountData);
        //     DB::commit();
        // } catch (\Exception $e) {
        //     DB::rollback();
        //     return redirect('purchase/purchase-list')->with('error', $e->getMessage());
        // }

        return redirect('purchase/list')->with('message', config('constants.update'));
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
