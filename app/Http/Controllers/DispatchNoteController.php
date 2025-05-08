<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Area;
use App\Models\User;
use App\Models\Sector;
use App\Models\SaleMan;
use App\Models\SaleOrder;
use App\Models\PackingType;
use Illuminate\Http\Request;
use App\Models\MeasurementType;
use App\Models\SaleOrderDetail;
use App\Services\CommonService;
use App\Models\CoaDetailAccount;
use App\Models\DeliveredToParties;
use App\Models\DispatchNoteDetail;
use App\Models\DispatchNoteImages;
use App\Models\DispatchNoteMaster;
use Illuminate\Support\Facades\DB;
use App\Models\DetailAccountProducts;
use App\Services\DispatchNoteService;
use App\Models\CoaInventoryDetailAccount;
use App\Models\StockLedger;
use App\Models\Transporter;

class DispatchNoteController extends Controller
{
    protected $commonService;
    protected $dispatchNoteService;
    public function __construct(CommonService $commonService, DispatchNoteService $dispatchNoteService)
    {
        $this->commonService = $commonService;
        $this->dispatchNoteService = $dispatchNoteService;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pageTitle = 'List of Dispatch Notes';
        $request = request()->all();
        $param = request()->param;
        $dispatchNotes = $this->dispatchNoteService->searchDispatch($request);
        $dropDownData = $this->dispatchNoteService->DropDownData();

        return view('dispatch-note.index', compact('dispatchNotes', 'dropDownData', 'pageTitle', 'param'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $pageTitle = 'Create Dispatch Note';
        $maxid = DispatchNoteMaster::count('sale_order_number', $request->id) + 1;
        $dropDownData = $this->dispatchNoteService->DropDownData();
        $sale_Order = SaleOrder::find($request->id);
        // dd($sale_Order['status']);
        if ($sale_Order['status'] === 'Pending') {
            return back()->with('message', 'This Sale Order status is pending. Please update the status.');
        } else {
            $parties = CoaDetailAccount::where('id', $sale_Order->party_id)->pluck('account_name', 'id');
            $saleMans = SaleMan::where('id', $sale_Order->saleman)->pluck('name', 'id');
            $sectors = Sector::where('id', $sale_Order->belt)->pluck('name', 'id');
            $areas = Area::where('id', $sale_Order->area)->pluck('name', 'id');
            $getProducts = DetailAccountProducts::where('detail_account_id', $sale_Order->party_id)->get();
            $productsArray = $getProducts->pluck('product_id')->toArray();
            $products = CoaInventoryDetailAccount::whereIn('id', $productsArray)->pluck('name', 'id');

            $deliveredToParties = DeliveredToParties::where('id', $sale_Order->delivered_to)->pluck('party_name', 'id');
            $saleOrderDetails = SaleOrderDetail::where('sale_order_master_id', $sale_Order->id)->get();
        }


        if (empty($sale_Order)) {
            abort(404);
        }

        return view('dispatch-note.create', compact('pageTitle', 'maxid', 'products', 'deliveredToParties', 'areas', 'sectors', 'saleMans', 'dropDownData', 'parties', 'saleOrderDetails', 'sale_Order'));
    }

    public function generate()
    {
        return view('dispatch-note.generate');
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        DB::beginTransaction();
        try {
            $saleOrder = SaleOrder::where('id', $request->sale_order_number)->first();
            $updateSaleOrderStatus = $this->dispatchNoteService->prepareSOMasterData($saleOrder);
            $dispatchMasterInsert = $this->commonService->findUpdateOrCreate(SaleOrder::class, ['id' => $saleOrder->id], $updateSaleOrderStatus);
            $request = $request->except('_token', 'id');
            //Insert data into Dispatch tables.
            $dispatchMasterData = $this->dispatchNoteService->prepareDispatchMasterData($request);
            $dispatchMasterInsert = $this->dispatchNoteService->findUpdateOrCreate(DispatchNoteMaster::class, ['id' => ''], $dispatchMasterData);
            $dispatchDetailData = $this->dispatchNoteService->prepareDispatchDetailData($request, $dispatchMasterInsert->id);
            $this->dispatchNoteService->saveDispatch($dispatchDetailData);

            $dispatchNoteImages = $this->dispatchNoteService->prepareDispatchNoteImagesData($request, $dispatchMasterInsert->id);
            $this->dispatchNoteService->saveDispatchNoteImages($dispatchNoteImages);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            return redirect('dispatch-note/create')->with('error', $e->getMessage());
        }
        return redirect('dispatch-note/list')->with('message', config('constants.add'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

        $pageTitle = 'Update Dispatch Note';
        $currentid = $id;
        $note = DispatchNoteMaster::find($id);
        $date = Carbon::parse($note->date)->format('d-m-Y');
        $dropDownData = $this->dispatchNoteService->DropDownData();
        $dispatchNotes = DispatchNoteDetail::where('dispatch_note_master_id', $id)->get();
        $parties = CoaDetailAccount::where('id', $note->party_id)->pluck('account_name', 'id');
        $saleMans = SaleMan::where('id', $note->saleman)->pluck('name', 'id');
        $images = DispatchNoteImages::where('dispatch_note_id', $id)->get();
        $sectors = Sector::where('id', $note->sector)->pluck('name', 'id');
        $areas = Area::where('id', $note->area)->pluck('name', 'id');
        $deliveredToParties = DeliveredToParties::where('id', $note->delivered_to)->pluck('party_name', 'id');

        $getProducts = DetailAccountProducts::where('detail_account_id', $note->party_id)->get();
        $productsArray = $getProducts->pluck('product_id')->toArray();
        $products = CoaInventoryDetailAccount::whereIn('id', $productsArray)->pluck('name', 'id');

        if (empty($note)) {
            $message = config('constants.wrong');
        }

        return view('dispatch-note.edit', compact('pageTitle', 'date', 'dropDownData', 'products', 'deliveredToParties', 'parties', 'sectors', 'areas', 'images', 'currentid', 'note', 'saleMans', 'dispatchNotes'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {

        DB::beginTransaction();
        try {
            $request = request()->all();
            $dispatchMasterData = $this->dispatchNoteService->prepareDispatchMasterData($request);
            $dispatchMasterInsert = $this->commonService->findUpdateOrCreate(DispatchNoteMaster::class, ['id' => request('id')], $dispatchMasterData);
            $dispatchDetailData = $this->dispatchNoteService->prepareDispatchDetailData($request, $dispatchMasterInsert->id);
            $this->dispatchNoteService->saveDispatch($dispatchDetailData);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            return redirect('dispatch-note/create')->with('error', $e->getMessage());
        }

        return redirect('dispatch-note/list')->with('message', config('constants.update'));
    }

    public function print($id)
    {
        $title = 'Dispatch Note';
        $dispatchNote = DispatchNoteMaster::find($id);
        $date = Carbon::parse($dispatchNote->date)->format('d-m-Y');
        $party = CoaDetailAccount::where('id', $dispatchNote->party_id)->value('account_name');
        $saleMan = SaleMan::where('id', $dispatchNote->saleman)->value('name');
        $belt = Sector::where('id', $dispatchNote->sector)->value('name');
        $area = Area::where('id', $dispatchNote->area)->value('name');

        $dispatchNoteDetails = DispatchNoteDetail::where('dispatch_note_master_id', $dispatchNote->id)->get();
        $productsArray = $dispatchNoteDetails->pluck('product_id')->toArray();
        $products = CoaInventoryDetailAccount::whereIn('id', $productsArray)->pluck('name', 'id');
        $user = User::where('id', $dispatchNote->created_by)->value('name');
        $deliverdToParties = DeliveredToParties::where('id', $dispatchNote->delivered_to)->value('party_name');
        $transporters = Transporter::where('id', $dispatchNote->transporter_id)->value('name');

        return view('dispatch-note.print', compact('title', 'products', 'transporters', 'user', 'deliverdToParties', 'dispatchNote', 'date', 'dispatchNoteDetails', 'party', 'saleMan', 'belt', 'area'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        return $this->commonService->deleteResource(DispatchNoteMaster::class);
    }

    public function getSaleOrderData($name)
    {
        // Fetch master data based on sale order Number
        $orderMasterData = SaleOrder::with('details')->where('id', 'like', "%{$name}%")->first();

        if ($orderMasterData) {
            return response()->json($orderMasterData);
        }
        return response()->json(['message' => 'No data found'], 404);
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
}
