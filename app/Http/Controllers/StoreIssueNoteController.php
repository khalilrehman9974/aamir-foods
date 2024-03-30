<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StoreIssueNote;
use App\Services\CommonService;
use Illuminate\Support\Facades\DB;
use App\Models\StoreIssueNoteDetail;
use App\Services\StoreIssueNoteService;
use App\Http\Requests\StoreIssueNoteRequest;

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
        $issueNotes = StoreIssueNote::all();

        return view('store-issue-note.index', compact('issueNotes', 'pageTitle'));
    }

    public function create() {
        $pageTitle = 'Create Store Issue Note';
        $dropDownData = $this->storeIssueNoteService->DropDownData();
        return view('store-issue-note.create', compact('pageTitle', 'dropDownData'));
    }

    public function store(StoreIssueNoteRequest $request) {
        try {
            DB::beginTransaction();
            //Insert data into IssueNote tables.
            $issueNoteMasterData = $this->storeIssueNoteService->prepareIssueNoteMasterData($request);
            $issueNoteMasterInsert = $this->commonService->findUpdateOrCreate(StoreIssueNote::class, ['id' => ''], $issueNoteMasterData);
            $issueNoteDetailData = $this->storeIssueNoteService->prepareIssueNoteDetailData($request, $issueNoteMasterInsert->id);
            $this->storeIssueNoteService->saveIssueNote($issueNoteDetailData);

            //Insert data into stock table.
            // $this->stockService->prepareAndSaveData($request, $saleMasterInsert->id, purchaseService::SALE_TRANSACTION_TYPE);

            //Insert data into accounts ledger table.
            // $debitAccountData = $this->purchaseService->prepareAccountDebitData($request, $saleMasterInsert->id, purchaseService::SALE_TRANSACTION_TYPE, purchaseService::SALE_DESCRIPTION);
            // $creditAccountData = $this->purchaseService->prepareAccountCreditData($request, $saleMasterInsert->id, purchaseService::SALE_TRANSACTION_TYPE, purchaseService::SALE_DESCRIPTION);
            // AccountLedger::insert($debitAccountData);
            // AccountLedger::insert($creditAccountData);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            return redirect('store-issue-note/create')->with('error', $e->getMessage());
        }
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
        $issueNote = StoreIssueNote::find($id);
        $issueNoteDetails = StoreIssueNoteDetail::where('store_issue_notes_id', $id)->get();
        if (empty($issueNote)) {
            $message = config('constants.wrong');
        }

        return view('store-issue-note.create', compact('issueNote', 'issueNoteDetails'));
    }

     /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(StoreIssueNoteRequest $request, $id)
    {
        try {
            DB::beginTransaction();
            $request = request()->all();
            StoreIssueNote::where('id', $request['issue_noteId'])->delete();
            StoreIssueNoteDetail::where('store_issue_notes_id', $request['noteId'])->delete();
            // Stock::where('invoice_id', $request['saleId'])->delete();
            // AccountLedger::where('invoice_id', $request['saleId'])->delete();

            //Save data into relevant tables.
            $issueNoteMasterData = $this->storeIssueNoteService->prepareIssueNoteMasterData($request);
            $issueNoteMasterInsert = $this->commonService->findUpdateOrCreate(StoreIssueNote::class, ['id' => request('noteId')], $issueNoteMasterData);
            $issueNoteDetailData = $this->storeIssueNoteService->prepareIssueNoteDetailData($request, $issueNoteMasterInsert->id);
            $this->storeIssueNoteService->saveIssueNote($issueNoteDetailData);

            //Save data into stock table.
            // $this->stockService->prepareAndSaveData($request, $saleMasterInsert->id, purchaseService::SALE_TRANSACTION_TYPE);
            // $debitAccountData = $this->purchaseService->prepareAccountDebitData($request, $saleMasterInsert->id, purchaseService::SALE_TRANSACTION_TYPE, purchaseService::SALE_DESCRIPTION);
            // $creditAccountData = $this->purchaseService->prepareAccountCreditData($request, $saleMasterInsert->id, purchaseService::SALE_TRANSACTION_TYPE, purchaseService::SALE_DESCRIPTION);
            // AccountLedger::insert($debitAccountData);
            // AccountLedger::insert($creditAccountData);
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
        try {
            DB::beginTransaction();
            $deleteMaster = StoreIssueNote::where('id', request()->id)->delete();
            $deleteDetail = StoreIssueNoteDetail::where('store_issue_notes_id', request()->id)->delete();
            // $deleteStock = Stock::where('invoice_id', request()->id)->delete();
            // $accountEntryDetail = AccountLedger::where('invoice_id', request()->id)->delete();
            DB::commit();
            // && $deleteStock && $accountEntryDetail
            if ($deleteMaster && $deleteDetail ) {
                return $this->commonService->deleteResource(StoreIssueNote::class, StoreIssueNoteDetail::class);
            }

        } catch (\Exception $e) {
            DB::rollback();
            return redirect('store-issue-note/list')->with('error', $e->getMessage());
        }
    }
}
