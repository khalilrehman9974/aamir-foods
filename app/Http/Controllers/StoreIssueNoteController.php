<?php

namespace App\Http\Controllers;

use App\Models\StoreIssueNote;
use App\Services\CommonService;
use Illuminate\Support\Facades\DB;
use App\Models\StoreIssueNoteDetail;
use App\Services\StoreIssueNoteService;
use Illuminate\Http\Request;

class StoreIssueNoteController extends Controller
{
    protected $commonService;
    private $storeIssueNoteService;

    public function __construct(CommonService $commonService, StoreIssueNoteService $storeIssueNoteService){
        $this->storeIssueNoteService = $storeIssueNoteService;
        $this->commonService = $commonService;
    }

    public function index() {
        $pageTitle = 'List of Store Issue Note';
        $request = request()->all();
        $storeIssueNotes = $this->storeIssueNoteService->search($request);
        $param = request()->param;
        
        return view('store-issue-note.index', compact('storeIssueNotes','param', 'pageTitle'));
    }

    public function create() {
        $pageTitle = 'Create Store Issue Note';
        $dropDownData = $this->storeIssueNoteService->DropDownData();
        $issueNoteDetails = StoreIssueNoteDetail::where('store_issue_notes_id')->get();
        return view('store-issue-note.create', compact('issueNoteDetails','pageTitle', 'dropDownData'));
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function store(Request $request) {
        $data = request()->except('id', 'token');
        DB::beginTransaction();
        // try {
            //Insert data into IssueNote tables.
            $issueNoteMasterData = $this->storeIssueNoteService->prepareIssueNoteMasterData($request);
            $issueNoteMasterInsert = $this->commonService->findUpdateOrCreate(StoreIssueNote::class, ['id' => ''], $issueNoteMasterData);
            $this->storeIssueNoteService->saveIssueNote($request, $issueNoteMasterInsert->id);

            DB::commit();
        // } catch (\Exception $e) {
        //     DB::rollback();
        //     return redirect('store-issue-note/create')->with('error', $e->getMessage());
        // }
        return redirect('store-issue-note/list')->with('message', config('constants.add'));
    }

     /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $pageTitle = 'Update Store Issue Note';
        $dropDownData = $this->storeIssueNoteService->DropDownData();
        $issueNote = StoreIssueNote::find($id);
        $issueNoteDetails = StoreIssueNoteDetail::where('store_issue_notes_id', $id)->get();
        if (empty($issueNote)) {
            $message = config('constants.wrong');
        }

        return view('store-issue-note.create', compact('issueNote','dropDownData', 'issueNoteDetails', 'pageTitle'));
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

            $issueNoteMasterData = $this->storeIssueNoteService->prepareIssueNoteMasterData($request);
            $issueNoteMasterInsert = $this->storeIssueNoteService->findUpdateOrCreate(StoreIssueNote::class, ['id' => request('id')], $issueNoteMasterData);
            $this->storeIssueNoteService->saveIssueNote($request, $issueNoteMasterInsert->id);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            return redirect('store-issue-note/create')->with('error', $e->getMessage());
        }

        return redirect('store-issue-note/list')->with('message', config('constants.update'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // try {
            $deleteMaster = StoreIssueNote::where('id', request()->id)->delete();
            $deleteDetail = StoreIssueNoteDetail::where('id', request()->id)->delete();
            // $deleteStock = Stock::where('invoice_id', request()->id)->delete();
            // $accountEntryDetail = AccountLedger::where('invoice_id', request()->id)->delete();
            DB::commit();
            // && $deleteStock && $accountEntryDetail
            if ($deleteMaster && $deleteDetail ) {
                return $this->commonService->deleteResource(StoreIssueNote::class, StoreIssueNoteDetail::class);
            }

        // } catch (\Exception $e) {
        //     DB::rollback();
        //     return redirect('store-issue-note/list')->with('error', $e->getMessage());
        // }
    }
}
