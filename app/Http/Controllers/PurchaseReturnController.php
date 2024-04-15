<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\CommonService;
use App\Models\SalePurchaseType;
use Illuminate\Support\Facades\DB;
use App\Models\PurchaseReturnDetail;
use App\Models\PurchaseReturnMaster;
use App\Services\purchaseReturnService;

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
        $param = request()->param;
        return view('purchase-return.index', compact('purchaseReturns','param', 'request','pageTitle'));
    }

    /*
     * Show page of create Purchase Return.
     * */
    public function create()
    {
        $pageTitle = 'Create Purchase Return';
        $dropDownData = $this->purchaseReturnService->DropDownData();
        $purchaseReturnDetails = PurchaseReturnDetail::where('purchase_return_master_id')->get();
        return view('purchase-return.create', compact('pageTitle', 'purchaseReturnDetails','dropDownData'));
    }

    /*
     * Save Purchase into db.
     * @param: @request
     * */
    public function store(Request $request)
    {

        $request = $request->except('_token', 'id');
        DB::beginTransaction();
        try {
            //Insert data into purchase tables.
            $purchaseReturnMasterData = $this->purchaseReturnService->preparePurchaseReturnMasterData($request);
            $purchaseReturnMasterInsert = $this->commonService->findUpdateOrCreate(PurchaseReturnMaster::class, ['id' => ''], $purchaseReturnMasterData);
            $purchasereturnDetailData = $this->purchaseReturnService->preparePurchaseReturnDetailData($request, $purchaseReturnMasterInsert->id);
            $this->purchaseReturnService->savePurchaseReturn($purchasereturnDetailData);

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
        $purchaseReturnDetails = PurchaseReturnDetail::where('purchase_return_master_id', $id)->get();
        $dropDownData = $this->purchaseReturnService->DropDownData();
        if (empty($purchaseReturn)) {
            $message = config('constants.wrong');
        }

        return view('purchase-return.create', compact('purchaseReturn','dropDownData', 'purchaseReturnDetails'));
    }

    /*
     * update existing resource.
     * @param: $data
     * */
    public function update(Request $request)
    {
        DB::beginTransaction();
        try {
            $request = request()->all();
            PurchaseReturnMaster::where('id', $request['id'])->delete();
            PurchaseReturnDetail::where('purchase_return_master_id', $request['id'])->delete();

            //Save data into relevant tables.
            $purchaseMasterData = $this->purchaseReturnService->preparePurchaseReturnMasterData($request);
            $purchaseMasterInsert = $this->commonService->findUpdateOrCreate(PurchaseReturnMaster::class, ['id' => request('id')], $purchaseMasterData);
            $purchaseDetailData = $this->purchaseReturnService->preparePurchaseReturnDetailData($request, $purchaseMasterInsert->id);
            $this->purchaseReturnService->savePurchaseReturn($purchaseDetailData);


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
            $deleteMaster = PurchaseReturnMaster::where('id', request()->id)->delete();
            $deleteDetail = PurchaseReturnDetail::where('purchase_return_master_id', request()->id)->delete();

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
