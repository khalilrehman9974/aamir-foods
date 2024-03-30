<?php

namespace App\Http\Controllers;

use App\Models\SaleOrder;
use App\Models\SaleOrderDetail;
use App\Services\CommonService;
use App\Services\SaleOrderService;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\SaleOrderRequest;

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

        return view('sale-orders.index', compact('saleOrders', 'request','pageTitle'));
    }

    /*
     * Show page of create sale.
     * */
    public function create()
    {
        $pageTitle = 'Sales Orders';
        $dropDownData = $this->saleOrderService->DropDownData();
        return view('sale-orders.create', compact('pageTitle', 'dropDownData'));
    }

    /*
     * Save sale into db.
     * @param: @request
     * */
    public function store(SaleOrderRequest $request)
    {

        $request = $request->except('_token', 'saleId');
        try {
            DB::beginTransaction();
            //Insert data into sale tables.
            $saleOrderMasterData = $this->saleOrderService->prepareSaleOrderMasterData($request);
            $saleOrderMasterInsert = $this->commonService->findUpdateOrCreate(SaleOrder::class, ['id' => ''], $saleOrderMasterData);
            $saleOrderDetailData = $this->saleOrderService->prepareSaleOrderDetailData($request, $saleOrderMasterInsert->id);
            $this->saleOrderService->saveSaleOrder($saleOrderDetailData);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            return redirect('sale-order/create')->with('error', $e->getMessage());
        }
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
    public function update(SaleOrderRequest $request)
    {
        try {
            DB::beginTransaction();
            $request = request()->all();
            SaleOrder::where('id', $request['id'])->delete();
            SaleOrderDetail::where('sale_order_master_id', $request['id'])->delete();

            //Save data into relevant tables.
            $saleOrderMasterData = $this->saleOrderService->prepareSaleOrderMasterData($request);
            $saleOrderMasterInsert = $this->commonService->findUpdateOrCreate(SaleOrder::class, ['id' => request('productId')], $saleOrderMasterData);
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
        try {
            DB::beginTransaction();
            $deleteMaster = SaleOrder::where('id', request()->id)->delete();
            $deleteDetail = SaleOrderDetail::where('sale_order_master_id', request()->id)->delete();

            DB::commit();
            // && $deleteStock && $accountEntryDetail
            if ($deleteMaster && $deleteDetail ) {
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

}
