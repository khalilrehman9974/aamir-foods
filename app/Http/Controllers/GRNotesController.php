<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Transporter;
use Illuminate\Http\Request;
use App\Models\GRNotesDetail;
use App\Services\CommonService;
use App\Models\CoaDetailAccount;
use App\Services\GRNotesService;
use App\Models\GoodsReceivedNote;
use App\Models\DeliveredToParties;
use Illuminate\Support\Facades\DB;
use App\Models\PurchaseOrderDetail;
use App\Models\PurchaseOrderMaster;
use App\Models\CoaInventoryDetailAccount;
// use App\Http\Requests\Request;

class GRNotesController extends Controller
{
    protected $commonService;
    protected $grNotesService;

    public function __construct(CommonService $commonService, GRNotesService $grNotesService)
    {
        $this->commonService = $commonService;
        $this->grNotesService = $grNotesService;
    }

    public function generate()
    {
        return view('goods-received-notes.generate');
    }

    /*
     * Show page of list of sales.
     * */
    public function index()
    {
        $pageTitle = 'List Of GRN';
        $request = request()->all();
        $notes = $this->grNotesService->searchGRN($request);
        $param = request()->param;
        $dropDownData = $this->grNotesService->DropDownData();

        return view('goods-received-notes.index', compact('notes', 'param', 'dropDownData', 'request', 'pageTitle'));
    }

    /*
     * Show page of create GRN.
     * */
    public function create(Request $request)
    {
        $pageTitle = 'Create GRN';
        $maxid = GoodsReceivedNote::max('id') + 1;
        $purchaseOrder = PurchaseOrderMaster::find($request->id);
        $purchaseOrderDetails = PurchaseOrderDetail::where('purchase_order_master_id', $purchaseOrder->id)->get();
        $parties = CoaDetailAccount::where('id', $purchaseOrder->party_id)->pluck('account_name', 'id');
        $dropDownData = $this->grNotesService->DropDownData();
        return view('goods-received-notes.create', compact('pageTitle', 'purchaseOrderDetails', 'parties', 'dropDownData', 'maxid', 'purchaseOrder'));
    }

    /*
     * Save GRN into db.
     * @param: @request
     * */
    public function store(Request $request)
    {
        // dd($request);

        // DB::beginTransaction();
        // try {
        // $purchaseOrderDetail = PurchaseOrderDetail::where('purchase_order_master_id', $request->purchase_order_no)->get();
        // $grnMaster = GoodsReceivedNote::where('purchase_order_no' ,$request->purchase_order_no)->where('party_id', '4')->pluck('id');
        // $grnDetail = GRNotesDetail::whereIn('master_id', $grnMaster)->get();
        // dd($grnDetail);
        // $poQuantities = $request->po_quantity;     // e.g., [10, 5, 8]
        // $receivedQtys = $request->received_qty;    // e.g., [10, 5, 8]

        // $allMatch = $request->po_quantity === $request->received_qty;

        // foreach ($poQuantities as $index => $poQty) {
        //     if (!isset($receivedQtys[$index]) || $poQty != $receivedQtys[$index]) {
        //         $allMatch = false;
        //         break;
        //     }
        // }

        // if ($allMatch) {
        //     // ✅ All quantities match — do this
        //     // Example:
        //     // $this->markAsComplete($purchaseOrderId);
        // } else {
        //     // ❌ One or more values don’t match — handle it
        // }



        $purchaseOrderDetail = PurchaseOrderDetail::where('purchase_order_master_id', $request->purchase_order_no)->get();

        $grnMasterIds = GoodsReceivedNote::where('purchase_order_no', $request->purchase_order_no)
            ->where('party_id', 4)
            ->pluck('id');

        $grnDetail = GRNotesDetail::whereIn('master_id', $grnMasterIds)->get();

        // ✅ Step 1: Group purchase order quantities by product_id
        $poQuantities = $purchaseOrderDetail->groupBy('product_id')->map(function ($items) {
            return $items->sum('quantity');
        });


        // ✅ Step 2: Group GRN received_qty by product_id
        $grnQuantities = $grnDetail->groupBy('product_id')->map(function ($items) {
            return $items->sum('received_qty');
        });
        // ✅ Step 3: Check if each product in PO has matching received_qty
        $allMatched = true;

        foreach ($poQuantities as $productId => $poQty) {
            $receivedQty = $grnQuantities[$productId] ?? 0;

            if ($poQty != $receivedQty) {
                $allMatched = false;
                break;
            }
        }

        // ✅ Step 4: Take action if all matched
        if ($allMatched) {

        } else {
           
        }


        dd("stop here");
        //Insert data into GRN tables.
        $request = $request->except('_token', 'id');
        $grnMasterData = $this->grNotesService->prepareGRNMasterData($request);
        $grnMasterInsert = $this->commonService->findUpdateOrCreate(GoodsReceivedNote::class, ['id' => ''], $grnMasterData);
        $grnDetailData = $this->grNotesService->prepareGRNDetailData($request, $grnMasterInsert->id);
        $this->grNotesService->saveGRN($grnDetailData);

        // DB::commit();
        // } catch (\Exception $e) {
        //     DB::rollback();
        //     return redirect('grn/create')->with('error', $e->getMessage());
        // }
        return redirect('grn/list')->with('message', config('constants.add'));
    }

    public function update(Request $request)
    {
        // dd($request);
        // DB::beginTransaction();
        // try {
        $request = request()->all();
        GRNotesDetail::where('master_id', $request['id'])->delete();

        $grnMasterData = $this->grNotesService->prepareGRNMasterData($request);
        $grnMasterInsert = $this->commonService->findUpdateOrCreate(GoodsReceivedNote::class, ['id' => request('id')], $grnMasterData);
        $grnDetailData = $this->grNotesService->prepareGRNDetailData($request, $grnMasterInsert->id);
        $this->grNotesService->saveGRN($grnDetailData);



        //     DB::commit();
        // } catch (\Exception $e) {
        //     DB::rollback();
        //     return redirect('grn/create')->with('error', $e->getMessage());
        // }

        return redirect('grn/list')->with('message', config('constants.update'));
    }

    /*
     * Show edit page.
     * */
    public function edit($id)
    {
        $pageTitle = 'Update GRN';
        $maxid = $id;
        $dropDownData = $this->grNotesService->DropDownData();
        $note = GoodsReceivedNote::find($id);
        $date = Carbon::parse($note->date)->format('d-m-Y');

        $parties = CoaDetailAccount::where('id', $note->party_id)->pluck('account_name', 'id');
        $note_details = GRNotesDetail::where('master_id', $id)->get();
        if (empty($note)) {
            $message = config('constants.wrong');
        }

        return view('goods-received-notes.edit', compact('pageTitle', 'date', 'maxid', 'note', 'parties', 'note_details', 'dropDownData'));
    }

    public function print($id)
    {
        $title = 'Goods Received Notes';
        $grnMaster = GoodsReceivedNote::find($id);
        $date = Carbon::parse($grnMaster->date)->format('d-m-Y');
        $party = CoaDetailAccount::where('id', $grnMaster->party_id)->value('account_name');
        $grnDetails = GRNotesDetail::where('master_id', $grnMaster->id)->get();
        // dd($grnDetails);
        $productsArray = $grnDetails->pluck('product_id')->toArray();
        $products = CoaInventoryDetailAccount::whereIn('id', $productsArray)->pluck('name', 'id');
        $user = User::where('id', $grnMaster->created_by)->value('name');
        $transporters = Transporter::where('id', $grnMaster->transporter_id)->value('name');

        return view('goods-received-notes.print', compact('title', 'transporters', 'products', 'user', 'grnMaster', 'date', 'grnDetails', 'party'));
    }


    /*
     * Delete existing resource.
     * @param: request()->id
     * */
    public function delete()
    {
        DB::beginTransaction();
        try {
            $deleteMaster = GoodsReceivedNote::where('id', request()->id)->delete();
            $deleteDetail = GRNotesDetail::where('goods_received_note_master_id', request()->id)->delete();
            DB::commit();
            if ($deleteMaster && $deleteDetail) {
                return $this->commonService->deleteResource(GoodsReceivedNote::class, GRNotesDetail::class);
            }
        } catch (\Exception $e) {
            DB::rollback();
            return redirect('grn/list')->with('error', $e->getMessage());
        }
    }
}
