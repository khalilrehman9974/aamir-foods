<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Area;
use App\Models\User;
use App\Models\Sector;
use App\Models\SaleMan;
use App\Models\PackingType;
use Illuminate\Http\Request;
use App\Models\PurchaseDetail;
use App\Models\PurchaseMaster;
use App\Models\MeasurementType;
use App\Services\CommonService;
use App\Models\CoaDetailAccount;
use App\Models\DeliveredToParties;
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
        $dropDownData = $this->purchaseOrderService->DropDownData();

        return view('purchase-order.index', compact('orders','dropDownData','param', 'request','pageTitle'));
    }

    /*
     * Show page of create sale.
     * */
    public function create()
    {
        $pageTitle = 'Create Purchase Order';
        $poNo = PurchaseOrderMaster::max('id') + 1;
        $dropDownData = $this->purchaseOrderService->DropDownData();
        $purchaseOrderDetails = PurchaseOrderDetail::where('purchase_order_master_id')->get();
        return view('purchase-order.create', compact('pageTitle','purchaseOrderDetails','poNo','dropDownData'));
    }



    /*
     * Save POrder into db.
     * @param: @request
     * */
    public function store(Request $request)
    {
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
        $poNo = $id;
        $dropDownData = $this->purchaseOrderService->DropDownData();
        $purchaseOrder = PurchaseOrderMaster::find($id);
        // dd($purchaseOrder);
        $purchaseOrderDetails = PurchaseOrderDetail::where('purchase_order_master_id', $id)->get();
        if (empty($purchaseOrder)) {
            $message = config('constants.wrong');
        }

        return view('purchase-order.edit', compact('dropDownData','purchaseOrder','poNo','pageTitle','purchaseOrderDetails'));
    }

    public function update(Request $request)
    {
        // dd($request);

        // try {
        //     DB::beginTransaction();
            //Insert data into POrder tables.
            $request = $request->all();
            PurchaseOrderDetail::where('purchase_order_master_id', $request['id'])->delete();
            $pOrderMasterData = $this->purchaseOrderService->preparePOrderMasterData($request);
            $pOrderMasterInsert = $this->purchaseOrderService->findUpdateOrCreate(PurchaseOrderMaster::class, ['id' => request('id')], $pOrderMasterData);
            $pOrderDetailData = $this->purchaseOrderService->preparePOrderDetailData($request, $pOrderMasterInsert->id);
            $this->purchaseOrderService->savePOrder($pOrderDetailData);
            DB::commit();
        // } catch (\Exception $e) {
        //     DB::rollback();
        //     return redirect('purchase-order/create')->with('error', $e->getMessage());
        // }
        return redirect('purchase-order/list')->with('message', config('constants.add'));
    }

    public function print($id)
    {
        $title = 'Purchase Order';
        $purchaseOrder = PurchaseOrderMaster::find($id);
        $date = Carbon::parse($purchaseOrder->date)->format('d-m-Y');
        $party = CoaDetailAccount::where('id', $purchaseOrder->party_id)->value('account_name');
        $purchaseOrderDetails = PurchaseOrderDetail::where('purchase_order_master_id', $purchaseOrder->id)->get();
        // dd($purchaseOrderDetails);
        $productsArray = $purchaseOrderDetails->pluck('product_id')->toArray();
        $products = CoaInventoryDetailAccount::whereIn('id',$productsArray)->pluck('name','id');
        $user = User::where('id',$purchaseOrder->created_by)->value('name');
        $deliverdToParties = DeliveredToParties::where('id',$purchaseOrder->delivered_to)->value('party_name');


        return view('purchase-order.print', compact('title','products','user','deliverdToParties','purchaseOrder','date','purchaseOrderDetails','party'));
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
        $fetchMeasurementType = CoaInventoryDetailAccount::where('id', $name)->first('measurement_type_id');
        $measurement =  MeasurementType::where('id', $fetchMeasurementType->measurement_type_id)->first('name');

        if ($measurement) {
            return response()->json(['status' => 'success', 'name' => $measurement]);
        }
        return response()->json(['status' => 'fail', 'data' => []]);
    }

    public function getProductPackingType($name)
    {
        $fetchPackingType = CoaInventoryDetailAccount::where('id', $name)->first('packing_type_id');
        $packing =  PackingType::where('id', @$fetchPackingType->packing_type_id)->first('name');


        if ($packing) {
            return response()->json(['status' => 'success', 'name' => $packing]);
        }
        return response()->json(['status' => 'fail', 'data' => []]);


    }

    public function getProductSize($name)
    {
        $fetchSize = CoaInventoryDetailAccount::where('id', $name)->first('size');

        if ($fetchSize) {
            return response()->json(['status' => 'success', 'size' => $fetchSize->size]);
        }
        return response()->json(['status' => 'fail', 'data' => []]);
    }


}
