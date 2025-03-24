<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\StockLedger;
use App\Models\Transporter;
use Illuminate\Http\Request;
use App\Models\AccountLedger;
use App\Models\PurchaseDetail;
use App\Models\PurchaseMaster;
use App\Services\CommonService;
use App\Models\CoaDetailAccount;
use App\Models\SalePurchaseType;
use Illuminate\Support\Facades\DB;
use App\Models\PurchaseReturnDetail;
use App\Models\PurchaseReturnMaster;
use App\Services\StockLedgerService;
use App\Services\AccountLedgerService;
use App\Services\purchaseReturnService;
use App\Models\CoaInventoryDetailAccount;

class PurchaseReturnController extends Controller
{
    protected $commonService;
    protected $purchaseReturnService;
    protected $stockLedgerService;
    protected $accountLedgerService;



    public function __construct(
        CommonService $commonService,
        PurchaseReturnService $purchaseReturnService,
        StockLedgerService $stockLedgerService,
        AccountLedgerService $accountLedgerService
    ) {
        $this->commonService = $commonService;
        $this->purchaseReturnService = $purchaseReturnService;
        $this->stockLedgerService = $stockLedgerService;
        $this->accountLedgerService = $accountLedgerService;
    }

    /*
     * Show page of list of sales.
     * */
    public function index()
    {
        $pageTitle = 'List Of Purchase Returns';
        $request = request()->all();
        $purchaseReturns = $this->purchaseReturnService->searchPurchaseReturn($request);
        $param = request()->param;
        $dropDownData = $this->purchaseReturnService->DropDownData();

        return view('purchase-return.index', compact('purchaseReturns', 'dropDownData', 'param', 'request', 'pageTitle'));
    }


    public function generate()
    {
        return view('purchase-return.generate');
    }


    /*
     * Show page of create Purchase Return.
     * */
    public function create(Request $request)
    {
        $pageTitle = 'Create Purchase Return';
        $purchaseMaster = PurchaseMaster::find($request->id);
        // dd($purchaseMaster);
        $maxId = PurchaseReturnMaster::max('id') + 1;
        $parties = CoaDetailAccount::where('id', $purchaseMaster->party_id)->pluck('account_name', 'id');
        $transporters = Transporter::where('id', $purchaseMaster->transporter_id)->pluck('name', 'id');
        $purchaseDetails = PurchaseDetail::where('purchase_master_id', $purchaseMaster->id)->get();
        $dropDownData = $this->purchaseReturnService->DropDownData();
        // $purchaseReturnDetails = PurchaseReturnDetail::where('purchase_return_master_id')->get();
        return view('purchase-return.create', compact('pageTitle', 'purchaseDetails', 'transporters', 'parties', 'maxId', 'purchaseMaster', 'dropDownData'));
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
        // try {
        //Insert data into purchase tables.
        $purchaseReturnMasterData = $this->purchaseReturnService->preparePurchaseReturnMasterData($request);
        $purchaseReturnMasterInsert = $this->commonService->findUpdateOrCreate(PurchaseReturnMaster::class, ['id' => ''], $purchaseReturnMasterData);
        $purchasereturnDetailData = $this->purchaseReturnService->preparePurchaseReturnDetailData($request, $purchaseReturnMasterInsert->id);
        $this->purchaseReturnService->savePurchaseReturn($purchasereturnDetailData);


        //Insert data into stock table.
        $stockLedgers = $this->purchaseReturnService->prepareStockLedgerData($request, $purchaseReturnMasterInsert->id);
        $this->purchaseReturnService->saveStockLedger($stockLedgers);
        // $this->stockLedgerService->prepareAndSaveData($request, $purchaseReturnMasterInsert->id, config('contants.PURCHASE_RETURN_TRANSACTION_TYPE'));
        // //Insert data into accounts ledger table.
        // $debitAccountData = $this->purchaseReturnService->prepareAccountCreditData($request, $purchaseReturnMasterInsert->id, config('contants.PURCHASE_RETURN_TRANSACTION_TYPE'), config('contants.PURCHASE_RETURN_DESCRIPTION'));
        // $creditAccountData = $this->purchaseReturnService->prepareAccountDebitData($request, $purchaseReturnMasterInsert->id, config('contants.PURCHASE_RETURN_TRANSACTION_TYPE'), config('contants.PURCHASE_RETURN_DESCRIPTION'));
        // AccountLedger::insert($debitAccountData);
        // AccountLedger::insert($creditAccountData);

        //     DB::commit();
        // } catch (\Exception $e) {
        //     DB::rollback();
        //     return redirect('purchase-return/create')->with('error', $e->getMessage());
        // }
        return redirect('purchase-return/list')->with('message', config('constants.add'));
    }

    /*
     * Show edit page.
     * */
    public function edit($id)
    {
        $pageTitle = 'Update Purchase Return';
        $currentId = $id;

        $purchaseReturn = PurchaseReturnMaster::find($id);
        $date = Carbon::parse($purchaseReturn->date)->format('d-m-Y');
        $purchaseReturnDetails = PurchaseReturnDetail::where('purchase_return_master_id', $purchaseReturn->id)->get();
        $dropDownData = $this->purchaseReturnService->DropDownData();
        $parties = CoaDetailAccount::where('id', $purchaseReturn->party_id)->pluck('account_name', 'id');
        $transporters = Transporter::where('id', $purchaseReturn->transporter_id)->pluck('name', 'id');


        if (empty($purchaseReturn)) {
            $message = config('constants.wrong');
        }

        return view('purchase-return.edit', compact('purchaseReturn', 'transporters', 'parties', 'date', 'currentId', 'dropDownData', 'pageTitle', 'purchaseReturnDetails'));
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
        PurchaseReturnDetail::where('purchase_return_master_id', $request['id'])->delete();
        $documentNo = 'P/R/I' . '-' . $request['id'];
        StockLedger::where('document_no', $documentNo)->where('invoice_id', $request['id'])->delete();

        //Save data into relevant tables.

        $purchaseReturnMasterData = $this->purchaseReturnService->preparePurchaseReturnMasterData($request);
        $purchaseReturnMasterInsert = $this->commonService->findUpdateOrCreate(PurchaseReturnMaster::class, ['id' => request('id')], $purchaseReturnMasterData);
        $purchasereturnDetailData = $this->purchaseReturnService->preparePurchaseReturnDetailData($request, $purchaseReturnMasterInsert->id);
        $this->purchaseReturnService->savePurchaseReturn($purchasereturnDetailData);

        $stockLedgers = $this->purchaseReturnService->prepareStockLedgerData($request, $purchaseReturnMasterInsert->id);
        $this->purchaseReturnService->saveStockLedger($stockLedgers);
        // $this->stockLedgerService->prepareAndSaveData($request, $purchaseMasterInsert->id, config('contants.PURCHASE_RETURN_TRANSACTION_TYPE'));
        // $debitAccountData = $this->purchaseReturnService->prepareAccountCreditData($request, $purchaseMasterInsert->id, config('contants.PURCHASE_RETURN_TRANSACTION_TYPE'), config('contants.PURCHASE_RETURN_DESCRIPTION'));
        // $creditAccountData = $this->purchaseReturnService->prepareAccountDebitData($request, $purchaseMasterInsert->id, config('contants.PURCHASE_RETURN_TRANSACTION_TYPE'), config('contants.PURCHASE_RETURN_DESCRIPTION'));
        // AccountLedger::insert($debitAccountData);
        // AccountLedger::insert($creditAccountData);

        //     DB::commit();
        // } catch (\Exception $e) {
        //     DB::rollback();
        //     return redirect('purchase-return/create')->with('error', $e->getMessage());
        // }

        return redirect('purchase-return/list')->with('message', config('constants.update'));
    }

    public function print($id)
    {
        $title = 'Purchase Return Invoice';
        $purchasereturnMaster = PurchaseReturnMaster::find($id);
        $date = Carbon::parse($purchasereturnMaster->date)->format('d-m-Y');
        $party = CoaDetailAccount::where('id', $purchasereturnMaster->party_id)->value('account_name');
        $purchaseReturnDetails = PurchaseReturnDetail::where('purchase_return_master_id', $purchasereturnMaster->id)->get();
        // dd($purchasereturnMaster);
        $productsArray = $purchaseReturnDetails->pluck('product_id')->toArray();
        $products = CoaInventoryDetailAccount::whereIn('id', $productsArray)->pluck('name', 'id');
        $user = User::where('id', $purchasereturnMaster->created_by)->value('name');
        $transporters = Transporter::where('id', $purchasereturnMaster->transporter_id)->value('name');

        return view('purchase-return.print', compact('title', 'transporters', 'products', 'user', 'purchasereturnMaster', 'date', 'purchaseReturnDetails', 'party'));
    }


    /*
     * Delete existing resource.
     * @param: request()->id
     * */
    public function delete()
    {
        try {
            $deleteMaster = PurchaseReturnMaster::where('id', request()->id)->delete();
            $deleteDetail = PurchaseReturnDetail::where('purchase_return_master_id', request()->id)->delete();

            DB::commit();
            // && $deleteStock && $accountEntryDetail
            if ($deleteMaster && $deleteDetail) {
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
