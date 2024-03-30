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
    const PER_PAGE = 10;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title = 'List of Dispatch Notes';
        $pageTitle = 'List of Dispatch Notes';
        $dispatchNotes = DispatchNoteDetail::paginate(Self::PER_PAGE);

        return view('dispatch-note.index', compact('dispatchNotes', 'title', 'pageTitle'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title = 'Create Dispatch Note';
        $dropDownData = $this->dispatchNoteService->DropDownData();

        return view('dispatch-note.create', compact('title', 'dropDownData'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(DispatchNoteStoreRequest $request)
    {
        try {
        DB::beginTransaction();
        //Insert data into Dispatch tables.
        $dispatchMasterData = $this->dispatchNoteService->prepareDispatchMasterData($request);
        $dispatchMasterInsert = $this->commonService->findUpdateOrCreate(DispatchNoteMaster::class, ['id' => ''], $dispatchMasterData);
        $dispatchDetailData = $this->dispatchNoteService->prepareDispatchDetailData($request, $dispatchMasterInsert->id);
        $this->dispatchNoteService->saveDispatch($dispatchDetailData);

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
        $dispatch = DispatchNoteMaster::find($id);
        $purchaseDetails = DispatchNoteDetail::where('dispatch_master_id', $id)->get();
        if (empty($note)) {
            $message = config('constants.wrong');
        }

        return view('dispatch-note.create', compact('dispatch', 'purchaseDetails'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(DispatchNoteStoreRequest $request, $id)
    {
        try {
            DB::beginTransaction();
            $request = request()->all();
            DispatchNoteMaster::where('id', $request['noteId'])->delete();
            DispatchNoteDetail::where('dispatch_master_id', $request['noteId'])->delete();
            // Stock::where('invoice_id', $request['saleId'])->delete();
            // AccountLedger::where('invoice_id', $request['saleId'])->delete();

            //Save data into relevant tables.
            $dispatchMasterData = $this->dispatchNoteService->prepareDispatchMasterData($request);
            $dispatchMasterInsert = $this->commonService->findUpdateOrCreate(DispatchNoteMaster::class, ['id' => request('noteId')], $dispatchMasterData);
            $dispatchDetailData = $this->dispatchNoteService->prepareDispatchDetailData($request, $dispatchMasterInsert->id);
            $this->dispatchNoteService->saveDispatch($dispatchDetailData);

            //Save data into stock table.
            // $this->stockService->prepareAndSaveData($request, $saleMasterInsert->id, purchaseService::SALE_TRANSACTION_TYPE);
            // $debitAccountData = $this->purchaseService->prepareAccountDebitData($request, $saleMasterInsert->id, purchaseService::SALE_TRANSACTION_TYPE, purchaseService::SALE_DESCRIPTION);
            // $creditAccountData = $this->purchaseService->prepareAccountCreditData($request, $saleMasterInsert->id, purchaseService::SALE_TRANSACTION_TYPE, purchaseService::SALE_DESCRIPTION);
            // AccountLedger::insert($debitAccountData);
            // AccountLedger::insert($creditAccountData);
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
        try {
            DB::beginTransaction();
            $deleteMaster = DispatchNoteMaster::where('id', request()->id)->delete();
            $deleteDetail = DispatchNoteDetail::where('dispatch_master_id', request()->id)->delete();
            // $deleteStock = Stock::where('invoice_id', request()->id)->delete();
            // $accountEntryDetail = AccountLedger::where('invoice_id', request()->id)->delete();
            DB::commit();
            // && $deleteStock && $accountEntryDetail
            if ($deleteMaster && $deleteDetail ) {
                return $this->commonService->deleteResource(DispatchNoteMaster::class, DispatchNoteDetail::class);
            }

        } catch (\Exception $e) {
            DB::rollback();
            return redirect('dispatch-note/list')->with('error', $e->getMessage());
        }
    }
}
