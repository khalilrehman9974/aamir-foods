<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Area;
use App\Models\Sector;
use App\Models\SaleMan;
use App\Models\SaleOrder;
use App\Models\PackingType;
use App\Models\SaleManArea;
use Illuminate\Http\Request;
use App\Models\SaleManSector;
use App\Models\MeasurementType;
use App\Models\SaleOrderDetail;
use App\Models\SaleOrderImages;
use App\Services\CommonService;
use App\Models\CoaDetailAccount;
use App\Models\DeliveredToParties;
use App\Services\SaleOrderService;
use Illuminate\Support\Facades\DB;
use App\Models\CoaDetailAccountArea;
use App\Models\DetailAccountProducts;
use App\Events\AamirFoodsNotifications;
use App\Models\CoaDetailAccountSectors;
use App\Models\CoaInventoryDetailAccount;
use App\Models\User;

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

    public function approvedlist()
    {
        $dropDownData = $this->saleOrderService->DropDownData();
        $pageTitle = 'List Of Approved Sale Orders';
        $request = request()->all();
        $param = request()->param;
        $saleOrders = $this->saleOrderService->searchApprovedSaleOrder($request);

        return view('sale-orders.approvedlist', compact('saleOrders', 'pageTitle','param','dropDownData'));
    }

    /*
     * Show page of create sale.
     * */
    public function create()
    {
        $pageTitle = 'Create Sale Orders';
        $maxid = SaleOrder::max('id') + 1;
        $dropDownData = $this->saleOrderService->DropDownData();

        return view('sale-orders.create', compact('pageTitle', 'dropDownData', 'maxid'));
    }

    /*
     * Save sale into db.
     * @param: @request
     * */
    public function store(Request $request)
    {

        $request =$request->except('_token', 'id');
        DB::beginTransaction();
        try {
        //Insert data into sale tables.
        $saleOrderMasterData = $this->saleOrderService->prepareSaleOrderMasterData($request);
        $saleOrderMasterInsert = $this->saleOrderService->findUpdateOrCreate(SaleOrder::class, ['id' => !empty(request('id')) ? request('id') : null], $saleOrderMasterData);
        $saleOrderDetailData = $this->saleOrderService->prepareSaleOrderDetailData($request, $saleOrderMasterInsert->id);
        $this->saleOrderService->saveSaleOrder($saleOrderDetailData);

        $saleOrderImages = $this->saleOrderService->prepareSaleOrderImagesData($request, $saleOrderMasterInsert->id);
        $this->saleOrderService->saveSaleOrderImages($saleOrderImages);

        DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            return redirect('sale-order/create')->with('error', $e->getMessage());
        }
        // event(new AamirFoodsNotifications($saleOrderMasterData));
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
        $date = Carbon::parse($saleOrder->date)->format('d-m-Y');
        $fetchSaleManId= CoaDetailAccount::where('id', $saleOrder->party_id)->first('saleMan_id');
        $getSaleman = SaleMan::where('id', $fetchSaleManId->saleMan_id)->get();
        $saleMans = $getSaleman->pluck('name','id');
        // dd($saleMans);
        $images= SaleOrderImages::where('sale_order_id', $id)->get();
        // dd($images);
        $fetchImages = $images->pluck('images')->toArray();


        $fetchSectors  = SaleManSector::where('master_id', $saleOrder->saleman)->get();
        $sectorsArray = $fetchSectors->pluck('sector_id')->toArray();
        $fetchSectorId = Sector::whereIn('id',$sectorsArray)->get();
        $sectors = $fetchSectorId->pluck('name','id')->toArray();

        $fetchAreas = SaleManArea::where('sector_id', $saleOrder->belt)->get();
        $areasArray = $fetchAreas->pluck('area_id')->toArray();
        $fetchAreaId = Area::whereIn('id',$areasArray)->get();
        $areas = $fetchAreaId->pluck('name','id')->toArray();
        $deliverdToParties = DeliveredToParties::where('detail_account_id', $saleOrder->party_id)->pluck('party_name','id');
        // dd($deliverdToParties);
        $saleOrderDetails = SaleOrderDetail::where('sale_order_master_id', $id)->get();
        $dropDownData = $this->saleOrderService->DropDownData();
        $getProducts = DetailAccountProducts::where('detail_account_id',$saleOrder->party_id)->pluck('product_id');
        $products = CoaInventoryDetailAccount::whereIn('id',$getProducts)->pluck('name','id');
        if (empty($saleOrder)) {
            $message = config('constants.wrong');
        }

        return view('sale-orders.edit', compact('saleOrder','date','products', 'dropDownData','deliverdToParties', 'areas','sectors','images', 'saleMans', 'currentid', 'saleOrderDetails', 'pageTitle'));
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

            $saleOrderImages = $this->saleOrderService->prepareSaleOrderImagesData($request, $saleOrderMasterInsert->id);
            $this->saleOrderService->saveSaleOrderImages($saleOrderImages);

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

    public function print($id)
    {
        $title = 'Sale Order';
        $saleOrder = SaleOrder::find($id);
        // dd($saleOrder);
        $date = Carbon::parse($saleOrder->date)->format('d-m-Y');
        $party = CoaDetailAccount::where('id', $saleOrder->party_id)->value('account_name');
        $saleMan = SaleMan::where('id', $saleOrder->saleman)->value('name');
        $belt = Sector::where('id', $saleOrder->belt)->value('name');
        $area = Area::where('id', $saleOrder->area)->value('name');
        $saleOrderDetails = SaleOrderDetail::where('sale_order_master_id', $saleOrder->id)->get();
        $productsArray = $saleOrderDetails->pluck('product_id')->toArray();
        $products = CoaInventoryDetailAccount::whereIn('id',$productsArray)->pluck('name','id');
        $user = User::where('id',$saleOrder->created_by)->value('name');
        $deliverdToParties = DeliveredToParties::where('id',$saleOrder->delivered_to)->value('party_name');


        return view('sale-orders.print', compact('title','products','user','deliverdToParties','saleOrder','date','saleOrderDetails','party','saleMan','belt','area'));
    }

    public function getSaleManDetail(Request $request)
    {
        $fetchSaleMan = CoaDetailAccount::where('id',$request->party_id)->first('saleMan_id');
        $data['saleManName'] =  SaleMan::where('id', $fetchSaleMan->saleMan_id)->get();


        return response()->json($data);
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

    public function getDeliveredToParties(Request $request)
    {

        $fetchParties = DeliveredToParties::where('detail_account_id', $request->party_id)->get();
        $partiesArray = $fetchParties->pluck('id')->toArray();
        $data['parties'] = DeliveredToParties::whereIn("id", $partiesArray)->get();

        return response()->json($data);
    }

    public function getProductMeasurementType($name)
    {
        $fetchMeasurementType = CoaInventoryDetailAccount::where('id', $name)->first('measurement_type_id');
        $measurement =  MeasurementType::where('id', $fetchMeasurementType->measurement_type_id)->first('name');
        return response()->json(['status' => 'success', 'name' => $measurement]);
    }

    public function getProductPackingType($name)
    {
        $fetchPackingType = CoaInventoryDetailAccount::where('id', $name)->first('packing_type_id');
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

    public function getProductRate($product, $partyId)
    {

        $fetchProductRate = DetailAccountProducts::where('detail_account_id', $partyId)->where('product_id', $product)->value('price');

        return response()->json(['status' => 'success', 'name' => $fetchProductRate]);
    }

}
