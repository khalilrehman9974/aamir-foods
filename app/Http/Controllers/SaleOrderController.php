<?php

namespace App\Http\Controllers;

use App\Models\CoaDetailAccount;
use App\Models\CoaInventoryDetailAccount;
use App\Models\SaleMan;
use App\Models\SaleOrder;
use Illuminate\Http\Request;
use App\Models\SaleOrderDetail;
use App\Services\CommonService;
use App\Services\SaleOrderService;
use Illuminate\Support\Facades\DB;

class SaleOrderController extends Controller
{
    protected $commonService;
    protected $saleOrderService;



    public function __construct(CommonService $commonService, SaleOrderService $saleOrderService)
    {
        $this->commonService = $commonService;
        $this->saleOrderService = $saleOrderService;
    }

    /*
     * Show page of list of sales.
     * */
    public function index()
    {
        $pageTitle = 'List Of Sale Orders';
        $request = request()->all();
        $saleOrders = $this->saleOrderService->searchSale($request);

        return view('sale-orders.index', compact('saleOrders', 'pageTitle'));
    }

    /*
     * Show page of create sale.
     * */
    public function create()
    {
        $pageTitle = 'Sales Orders';
        $dropDownData = $this->saleOrderService->DropDownData();
        $saleOrders = SaleOrderDetail::where('sale_order_master_id')->get();
        return view('sale-orders.create', compact('pageTitle', 'dropDownData', 'saleOrders'));
    }

    /*
     * Save sale into db.
     * @param: @request
     * */
    public function store(Request $request)
    {

        $request = $request->except('_token', 'id');
        DB::beginTransaction();
        // try {
        //Insert data into sale tables.
        $saleOrderMasterData = $this->saleOrderService->prepareSaleOrderMasterData($request);
        $saleOrderMasterInsert = $this->commonService->findUpdateOrCreate(SaleOrder::class, ['id' => ''], $saleOrderMasterData);
        $saleOrderDetailData = $this->saleOrderService->prepareSaleOrderDetailData($request, $saleOrderMasterInsert->id);
        $this->saleOrderService->saveSaleOrder($saleOrderDetailData);

        DB::commit();
        // } catch (\Exception $e) {
        //     DB::rollback();
        //     return redirect('sale-order/create')->with('error', $e->getMessage());
        // }
        return redirect('sale-order/list')->with('message', config('constants.add'));
    }

    /*
     * Show edit page.
     * */
    public function edit($id)
    {
        $saleOrder = SaleOrder::find($id);
        $saleOrderDetails = SaleOrderDetail::where('sale_order_master_id', $id)->get();
        if (empty($sale)) {
            $message = config('constants.wrong');
        }

        return view('sale-orders.create', compact('saleOrder', 'saleDetails'));
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
            SaleOrder::where('id', $request['id'])->delete();
            SaleOrderDetail::where('sale_order_master_id', $request['id'])->delete();

            //Save data into relevant tables.
            $saleOrderMasterData = $this->saleOrderService->prepareSaleOrderMasterData($request);
            $saleOrderMasterInsert = $this->commonService->findUpdateOrCreate(SaleOrder::class, ['id' => request('id')], $saleOrderMasterData);
            $saleOrderDetailData = $this->saleOrderService->prepareSaleOrderDetailData($request, $saleOrderMasterInsert->id);
            $this->saleOrderService->saveSaleOrder($saleOrderDetailData);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            return redirect('sale-order/create')->with('error', $e->getMessage());
        }

        return redirect('sale-order/list')->with('message', config('constants.update'));
    }

    /*
     * Delete existing resource.
     * @param: request()->id
     * */
    public function delete()
    {
        DB::beginTransaction();
        try {
            $deleteMaster = SaleOrder::where('id', request()->id)->delete();
            $deleteDetail = SaleOrderDetail::where('sale_order_master_id', request()->id)->delete();

            DB::commit();
            // && $deleteStock && $accountEntryDetail
            if ($deleteMaster && $deleteDetail) {
                return $this->commonService->deleteResource(SaleOrder::class, SaleOrderDetail::class);
            }
        } catch (\Exception $e) {
            DB::rollback();
            return redirect('sale-order/list')->with('error', $e->getMessage());
        }
    }

    /*
    * View sale detail.
    * @param: $id
    * */
    public function view($id)
    {
        $saleOrderMaster = $this->saleOrderService->getSaleOrderMasterById($id);
        // $saleDetail = $this->saleOrderService->getSaleDetailById($id);
        if (empty($saleOrderMaster)) {
            $message = config('constants.wrong');
        }

        return view('sale-orders.view', compact('saleOrderMaster'));
    }

    public function getSaleManDetail($name)
    {

        $fetchSaleMan = CoaDetailAccount::where('account_name', trim($name))->first('saleMan_id');
        // dd($fetchSector);
        if ($fetchSaleMan) {
            return response()->json(['status' => 'success', 'saleMan_id' => $fetchSaleMan->saleMan_id]);
        }
        return response()->json(['status' => 'fail', 'data' => []]);

        // $fetchSaleMan = CoaDetailAccount::where('account_name', trim($name))->first('saleMan_id');

        // if ($fetchSaleMan) {
        //     $fetchSaleMan = CoaDetailAccount::with('getSaleMan')->get();
        //     foreach ($fetchSaleMan as $saleMan) {
        //         $saleManName = $saleMan->getSaleMan ? $saleMan->getSaleMan->name : 'Sale Man not found';

        //         return response()->json(['status' => 'success', 'saleMan_id' => $saleManName]);
        //     }
        //     return response()->json(['status' => 'success', 'saleMan_id' => $fetchSaleMan->saleMan_id]);
        // }
        return response()->json(['status' => 'fail', 'data' => []]);
        // $detailAccount = CoaDetailAccount::where('account_name', trim($name))->first('saleMan_id');
        // // dd($detailAccount);
        // if ($detailAccount) {
        //     $detailAccount = CoaDetailAccount::with('getSaleMan')->get();

        //     foreach ($detailAccount as $saleMan) {
        //         $saleManName = $saleMan->getSaleMan ? $saleMan->getSaleMan->name : 'Sale Man not found';

        //         return response()->json(['status' => 'success', 'saleMan_id' => $saleManName]);
        //     }
        // }
        // return response()->json(['status' => 'fail', 'data' => []]);
    }

    public function getSaleManSectorDetail($name)
    {
        $fetchSector = CoaDetailAccount::where('account_name', trim($name))->first('sector');
        // dd($fetchSector);
        if ($fetchSector) {
            return response()->json(['status' => 'success', 'sector' => $fetchSector->sector]);
        }
        return response()->json(['status' => 'fail', 'data' => []]);
    }

    public function getSaleManAreaDetail($name)
    {
        $fetchArea = CoaDetailAccount::where('account_name', trim($name))->first('area');

        if ($fetchArea) {
            return response()->json(['status' => 'success', 'area' => $fetchArea->area]);
        }
        return response()->json(['status' => 'fail', 'data' => []]);
    }

    public function getProductPackingType($name)
    {
        $fetchPackingType = CoaInventoryDetailAccount::where('name', trim($name))->first('packing_type_id');

        if ($fetchPackingType) {
            return response()->json(['status' => 'success', 'packing_type_id' => $fetchPackingType->packing_type_id]);
        }
        return response()->json(['status' => 'fail', 'data' => []]);
    }

    public function getProductMeasurementType($name)
    {
        $fetchMeasurementType = CoaInventoryDetailAccount::where('name', trim($name))->first('measurement_type_id');

        if ($fetchMeasurementType) {
            return response()->json(['status' => 'success', 'measurement_type_id' => $fetchMeasurementType->measurement_type_id]);
        }
        return response()->json(['status' => 'fail', 'data' => []]);
    }

}
