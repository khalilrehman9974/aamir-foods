<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GRNotesDetail;
use App\Services\CommonService;
use App\Services\GRNotesService;
use App\Models\GoodsReceivedNote;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\GRNotesRequest;

class GRNotesController extends Controller
{
    protected $commonService;
    protected $grNotesService;

    public function __construct(CommonService $commonService, GRNotesService $grNotesService)
    {
        $this->commonService = $commonService;
        $this->grNotesService = $grNotesService;

    }

    /*
     * Show page of list of sales.
     * */
    public function index()
    {
        $pageTitle = 'List Of GRN';
        $request = request()->all();
        $notes = $this->grNotesService->searchGRN($request);

        return view('goods-received-notes.index', compact('notes', 'request','pageTitle'));
    }

    /*
     * Show page of create GRN.
     * */
    public function create()
    {
        $pageTitle = 'Create GRN';
        $dropDownData = $this->grNotesService->DropDownData();
        return view('goods-received-notes.create', compact('pageTitle','dropDownData'));
    }

    /*
     * Save GRN into db.
     * @param: @request
     * */
    public function store(GRNotesRequest $request)
    {

        $request = $request->except('_token', 'id');
        try {
            DB::beginTransaction();
            //Insert data into GRN tables.
            $grnMasterData = $this->grNotesService->prepareGRNMasterData($request);
            $grnMasterInsert = $this->commonService->findUpdateOrCreate(GoodsReceivedNote::class, ['id' => ''], $grnMasterData);
            $grnDetailData = $this->grNotesService->prepareGRNDetailData($request, $grnMasterInsert->id);
            $this->grNotesService->saveGRN($grnDetailData);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            return redirect('grn/create')->with('error', $e->getMessage());
        }
        return redirect('grn/list')->with('message', config('constants.add'));
    }

    /*
     * Show edit page.
     * */
    public function edit($id)
    {
        $dropDownData = $this->grNotesService->DropDownData();
        $note = GoodsReceivedNote::find($id);
        $note_details = GRNotesDetail::where('grn_master_id', $id)->get();
        if (empty($note)) {
            $message = config('constants.wrong');
        }

        return view('goods-received-notes.create', compact('note','type', 'note_details','dropDownData'));
    }


    /*
     * Delete existing resource.
     * @param: request()->id
     * */
    public function delete()
    {
        try {
            DB::beginTransaction();
            $deleteMaster = GoodsReceivedNote::where('id', request()->id)->delete();
            $deleteDetail = GRNotesDetail::where('grn_master_id', request()->id)->delete();
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
