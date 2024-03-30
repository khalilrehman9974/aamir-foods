<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\CommonService;
use Illuminate\Support\Facades\DB;
use App\Models\PurchaseOrderMaster;
use App\Services\PurchaseOrderService;
use App\Http\Requests\PurchaseOrderRequest;
use App\Models\PurchaseOrderDetail;

class PurchaseOrderController extends Controller
{
    protected $commonService;
    protected $purchaseOrderService;



    public function __construct(CommonService $commonService, PurchaseOrderService $purchaseOrderService)
    {
        $this->commonService = $commonService;
        $this->purchaseOrderService = $purchaseOrderService;

    }

    /*
     * Show page of list of sales.
     * */
    public function index()
    {
        $pageTitle = 'List Of Purchase Orders';
        $request = request()->all();
        $orders = $this->purchaseOrderService->searchPOrder($request);

        return view('purchase-order.index', compact('orders', 'request','pageTitle'));
    }

    /*
     * Show page of create sale.
     * */
    public function create()
    {
        $pageTitle = 'Create Purchase Order';
        $dropDownData = $this->purchaseOrderService->DropDownData();
        return view('purchase-order.create', compact('pageTitle','dropDownData'));
    }

    /*
     * Save POrder into db.
     * @param: @request
     * */
    public function store(PurchaseOrderRequest $request)
    {

        $request = $request->except('_token', 'id');
        try {
            DB::beginTransaction();
            //Insert data into POrder tables.
            $pOrderMasterData = $this->purchaseOrderService->preparePOrderMasterData($request);
            $pOrderMasterInsert = $this->commonService->findUpdateOrCreate(PurchaseOrderMaster::class, ['id' => ''], $pOrderMasterData);
            $pOrderDetailData = $this->purchaseOrderService->preparePOrderDetailData($request, $pOrderMasterInsert->id);
            $this->purchaseOrderService->savePOrder($pOrderDetailData);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            return redirect('purchase-order/create')->with('error', $e->getMessage());
        }
        return redirect('purchase-order/list')->with('message', config('constants.add'));
    }

    /*
     * Show edit page.
     * */
    public function edit($id)
    {
        $purchaseOrder = PurchaseOrderMaster::find($id);
        $pOrderDetails = PurchaseOrderDetail::where('purchase_order_master_id', $id)->get();
        if (empty($purchaseOrder)) {
            $message = config('constants.wrong');
        }

        return view('purchase-order.create', compact('purchaseOrder',  'type', 'pOrderDetails'));
    }

    /*
     * Delete existing resource.
     * @param: request()->id
     * */
    public function delete()
    {
        try {
            DB::beginTransaction();
            $deleteMaster = PurchaseOrderMaster::where('id', request()->id)->delete();
            $deleteDetail = PurchaseOrderDetail::where('purchase_order_master_id', request()->id)->delete();
            DB::commit();
            // && $deleteStock && $accountEntryDetail
            if ($deleteMaster && $deleteDetail ) {
                return $this->commonService->deleteResource(PurchaseOrderMaster::class, PurchaseOrderDetail::class);
            }

        } catch (\Exception $e) {
            DB::rollback();
            return redirect('purchase-order/list')->with('error', $e->getMessage());
        }
    }


}
