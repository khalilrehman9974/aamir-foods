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

        return view('goods-received-notes.index', compact('notes','param', 'dropDownData','request','pageTitle'));
    }

    /*
     * Show page of create GRN.
     * */
    public function create(Request $request)
    {
        $pageTitle = 'Create GRN';
        $maxid = GoodsReceivedNote::max('id') + 1;
        $purchaseOrder = PurchaseOrderMaster::find($request->id);
        $purchaseOrderDetails = PurchaseOrderDetail::where('purchase_order_master_id',$purchaseOrder->id)->get();
        $parties = CoaDetailAccount::where('id', $purchaseOrder->party_id)->pluck('account_name', 'id');
        // dd($purchaseOrderDetails);
        $dropDownData = $this->grNotesService->DropDownData();
        // $note_details = GRNotesDetail::where('goods_received_note_master_id')->get();
        return view('goods-received-notes.create', compact('pageTitle','purchaseOrderDetails','parties','dropDownData','maxid','purchaseOrder'));
    }

    /*
     * Save GRN into db.
     * @param: @request
     * */
    public function store(Request $request)
    {
        // dd($request);

        $request = $request->except('_token', 'id');
        // DB::beginTransaction();
        // try {
            //Insert data into GRN tables.
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

        return view('goods-received-notes.edit', compact('pageTitle' ,'date','maxid','note','parties', 'note_details','dropDownData'));
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
        $products = CoaInventoryDetailAccount::whereIn('id',$productsArray)->pluck('name','id');
        $user = User::where('id',$grnMaster->created_by)->value('name');
        $transporters = Transporter::where('id',$grnMaster->transporter_id)->value('name');

        return view('goods-received-notes.print', compact('title','transporters','products','user','grnMaster','date','grnDetails','party'));
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
            if ($deleteMaster && $deleteDetail ) {
                return $this->commonService->deleteResource(GoodsReceivedNote::class, GRNotesDetail::class);
            }

        } catch (\Exception $e) {
            DB::rollback();
            return redirect('grn/list')->with('error', $e->getMessage());
        }
    }

}
