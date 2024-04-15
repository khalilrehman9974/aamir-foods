<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\CommonService;
use App\Models\DispatchNoteDetail;
use App\Models\DispatchNoteMaster;
use Illuminate\Support\Facades\DB;
use App\Services\DispatchNoteService;
use App\Http\Requests\DispatchNoteStoreRequest;

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

        return view('dispatch-note.index', compact('dispatchNotes','pageTitle', 'param'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $pageTitle = 'Create Dispatch Note';
        $dropDownData = $this->dispatchNoteService->DropDownData();
        $dispatchNotes = DispatchNoteDetail::where('dispatch_note_master_id')->get();

        return view('dispatch-note.create', compact('pageTitle', 'dropDownData', 'dispatchNotes'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = request()->except('id', 'token');
        DB::beginTransaction();
        // try {
            //Insert data into Dispatch tables.
            $dispatchMasterData = $this->dispatchNoteService->prepareDispatchMasterData($request);
            $dispatchMasterInsert = $this->dispatchNoteService->findUpdateOrCreate(DispatchNoteMaster::class, ['id' => ''], $dispatchMasterData);
            $dispatchDetailData = $this->dispatchNoteService->prepareDispatchDetailData($request, $dispatchMasterInsert->id);
            $this->dispatchNoteService->saveDispatch($dispatchDetailData);

            DB::commit();
        // } catch (\Exception $e) {
        //     DB::rollback();
        //     return redirect('dispatch-note/create')->with('error', $e->getMessage());
        // }
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
        $note = DispatchNoteMaster::find($id);
        // dd($note);
        $dropDownData = $this->dispatchNoteService->DropDownData();
        $dispatchNotes = DispatchNoteDetail::where('dispatch_note_master_id', $id)->get();
        if (empty($note)) {
            $message = config('constants.wrong');
        }

        return view('dispatch-note.create', compact('pageTitle', 'dropDownData', 'note', 'dispatchNotes'));
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
}
