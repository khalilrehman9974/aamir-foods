<?php

namespace App\Http\Controllers;

use App\Models\SaleMan;
use App\Models\SaleOrder;
use App\Models\PackingType;
use Illuminate\Http\Request;
use App\Models\MeasurementType;
use App\Models\SaleOrderDetail;
use App\Services\CommonService;
use App\Models\CoaDetailAccount;
use App\Services\SaleOrderService;
use Illuminate\Support\Facades\DB;
use App\Events\AamirFoodsNotifications;
use App\Models\Area;
use App\Models\CoaDetailAccountArea;
use App\Models\CoaDetailAccountSectors;
use App\Models\CoaInventoryDetailAccount;
use App\Models\DetailAccountProducts;
use App\Models\Sector;

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
        $dropDownData = $this->saleOrderService->DropDownData();
        $pageTitle = 'List Of Sale Orders';
        $request = request()->all();
        $param = request()->param;
        $saleOrders = $this->saleOrderService->searchSale($request);

        return view('sale-orders.index', compact('saleOrders', 'pageTitle','param','dropDownData'));
    }

    /*
     * Show page of create sale.
     * */
    public function create()
    {
        $pageTitle = 'Create Sale Orders';
        $maxid = SaleOrder::max('id') + 1;
        $dropDownData = $this->saleOrderService->DropDownData();
        $saleOrderDetails = SaleOrderDetail::where('sale_order_master_id')->get();
        return view('sale-orders.create', compact('pageTitle', 'dropDownData', 'saleOrderDetails', 'maxid'));
    }

    /*
     * Save sale into db.
     * @param: @request
     * */
    public function store(Request $request)
    {
        $request = $request->except('_token', 'id');
        DB::beginTransaction();
        try {
        //Insert data into sale tables.
        $saleOrderMasterData = $this->saleOrderService->prepareSaleOrderMasterData($request);
        $saleOrderMasterInsert = $this->saleOrderService->findUpdateOrCreate(SaleOrder::class, ['id' => !empty(request('id')) ? request('id') : null], $saleOrderMasterData);
        $saleOrderDetailData = $this->saleOrderService->prepareSaleOrderDetailData($request, $saleOrderMasterInsert->id);
        $this->saleOrderService->saveSaleOrder($saleOrderDetailData);

        DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            return redirect('sale-order/create')->with('error', $e->getMessage());
        }
        event(new AamirFoodsNotifications($saleOrderMasterData));
        $message = config(
            'constants.add'
        );
        if (request('id')) {
            $message = config('constants.update');
        }
        return redirect('sale-order/list')->with('message', $message);
    }

    /*
     * Show edit page.
     * */
    public function edit($id)
    {

        $pageTitle = 'Update Sale Orders';
        $currentid = $id;
        $saleOrder = SaleOrder::find($id);
        $saleOrderDetails = SaleOrderDetail::where('sale_order_master_id', $id)->get();
        $dropDownData = $this->saleOrderService->DropDownData();
        if (empty($sale)) {
            $message = config('constants.wrong');
        }

        return view('sale-orders.create', compact('saleOrder', 'dropDownData', 'currentid', 'saleOrderDetails', 'pageTitle'));
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
            // && $deleteMaster && $deleteDetail
            if ($deleteMaster && $deleteDetail) {
                $message = config('constants.delete');
                return response()->json(['status' => 'success', 'message' => $message]);
            } else {
                $message = config('constants.wrong');
                return response()->json(['status' => 'fail', 'message' => $message]);
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
        if (empty($saleOrderMaster)) {
            $message = config('constants.wrong');
        }

        return view('sale-orders.view', compact('saleOrderMaster'));
    }

    public function getSaleManDetail($name)
    {
        $fetchSaleMan = CoaDetailAccount::where('account_name', trim($name))->first('saleMan_id');
        $saleManName =  SaleMan::where('id', $fetchSaleMan->saleMan_id)->first('name');

        return response()->json(['status' => 'success', 'name' => $saleManName]);
    }

    public function getSaleManSectorDetail(Request $request)
    {
        $fetchSector = CoaDetailAccountSectors::where('master_account_id', $request->party_id)->get();
        $sectorsArray = $fetchSector->pluck('sector_id')->toArray();
        $data['sectors'] = Sector::whereIn("id", $sectorsArray)->get();
        // if ($fetchSector) {
        //     return response()->json(['status' => 'success', 'sector' => $fetchSector->sector]);
        // }
        return response()->json($data);
    }

    public function getSaleManAreaDetail(Request $request)
    {

        $fetchArea = CoaDetailAccountArea::where('master_account_id', $request->party_id)->where('sector_id', $request->sector_id)->get();
        $areasArray = $fetchArea->pluck('area_id')->toArray();
        $data['areas'] = Area::whereIn("id", $areasArray)->get();

        return response()->json($data);
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

    public function getProducts(Request $request)
    {
        $fetchProducts = DetailAccountProducts::where('detail_account_id', $request->party_id)->get();
        $productsArray = $fetchProducts->pluck('product_id')->toArray();
        $data['products'] = CoaInventoryDetailAccount::whereIn("id", $productsArray)->get();

        return response()->json($data);
    }
}
