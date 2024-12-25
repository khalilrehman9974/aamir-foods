<?php

namespace App\Http\Controllers;

use App\Models\PackingType;
use Illuminate\Http\Request;
use App\Models\MeasurementType;
use App\Services\CommonService;
use Illuminate\Support\Facades\DB;
use App\Models\PurchaseOrderDetail;
use App\Models\PurchaseOrderMaster;
use App\Services\PurchaseOrderService;
use App\Models\CoaInventoryDetailAccount;

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
        $param = request()->param;

        return view('purchase-order.index', compact('orders','param', 'request','pageTitle'));
    }

    /*
     * Show page of create sale.
     * */
    public function create()
    {
        $pageTitle = 'Create Purchase Order';
        $invoiceNo = PurchaseOrderMaster::max('id') + 1;
        $dropDownData = $this->purchaseOrderService->DropDownData();
        $purchaseOrderDetails = PurchaseOrderDetail::where('purchase_order_master_id')->get();
        return view('purchase-order.create', compact('pageTitle','purchaseOrderDetails','invoiceNo','dropDownData'));
    }

    /*
     * Save POrder into db.
     * @param: @request
     * */
    public function store(Request $request)
    {
        // dd($request);
        $request = $request->except('_token', 'id');
        // try {
        //     DB::beginTransaction();
            //Insert data into POrder tables.
            $pOrderMasterData = $this->purchaseOrderService->preparePOrderMasterData($request);
            $pOrderMasterInsert = $this->purchaseOrderService->findUpdateOrCreate(PurchaseOrderMaster::class, ['id' => ''], $pOrderMasterData);
            $pOrderDetailData = $this->purchaseOrderService->preparePOrderDetailData($request, $pOrderMasterInsert->id);
            $this->purchaseOrderService->savePOrder($pOrderDetailData);
            DB::commit();
        // } catch (\Exception $e) {
        //     DB::rollback();
        //     return redirect('purchase-order/create')->with('error', $e->getMessage());
        // }
        return redirect('purchase-order/list')->with('message', config('constants.add'));
    }

    /*
     * Show edit page.
     * */
    public function edit($id)
    {
        $pageTitle = 'Update Purchase Order';
        $purchaseOrder = PurchaseOrderMaster::find($id);
        $purchaseOrderDetails = PurchaseOrderDetail::where('purchase_order_master_id', $id)->get();
        if (empty($purchaseOrder)) {
            $message = config('constants.wrong');
        }

        return view('purchase-order.create', compact('purchaseOrder','pageTitle','purchaseOrderDetails'));
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

    public function getProductMeasurementType($name)
    {
        $fetchMeasurementType = CoaInventoryDetailAccount::where('name', trim($name))->first('measurement_type_id');
        $measurement =  MeasurementType::where('id', $fetchMeasurementType->measurement_type_id)->first('name');

        return response()->json(['status' => 'success', 'name' => $measurement]);
    }

    public function getProductPackingType($name)
    {
        $fetchPackingType = CoaInventoryDetailAccount::where('name', trim($name))->first('packing_type_id');
        $packing =  PackingType::where('id', @$fetchPackingType->packing_type_id)->first('name');

        return response()->json(['status' => 'success', 'name' => $packing]);
    }

    public function getProductSize($name)
    {
        $fetchSize = CoaInventoryDetailAccount::where('name', trim($name))->first('size');

        if ($fetchSize) {
            return response()->json(['status' => 'success', 'size' => $fetchSize->size]);
        }
        return response()->json(['status' => 'fail', 'data' => []]);
    }


}
