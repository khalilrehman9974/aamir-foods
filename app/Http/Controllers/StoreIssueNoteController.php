<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Department;
use App\Models\PackingType;
use Illuminate\Http\Request;
use App\Models\StoreIssueNote;
use App\Models\MeasurementType;
use App\Services\CommonService;
use Illuminate\Support\Facades\DB;
use App\Models\StoreIssueNoteDetail;
use App\Services\StoreIssueNoteService;
use App\Models\CoaInventoryDetailAccount;

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
        $dropDownData = $this->storeIssueNoteService->DropDownData();

        return view('store-issue-note.index', compact('storeIssueNotes','dropDownData','param', 'pageTitle'));
    }

    public function create() {
        $pageTitle = 'Create Store Issue Note';
        $maxid = StoreIssueNote::max('id')+ 1;
        $dropDownData = $this->storeIssueNoteService->DropDownData();
        $issueNoteDetails = StoreIssueNoteDetail::where('store_issue_notes_id')->get();
        return view('store-issue-note.create', compact('issueNoteDetails','maxid','pageTitle', 'dropDownData'));
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function store(Request $request)
    {
        // dd($request);
        $data = request()->except('id', 'token');
        // DB::beginTransaction();
        // try {
            //Insert data into IssueNote tables.
            $issueNoteMasterData = $this->storeIssueNoteService->prepareIssueNoteMasterData($request);
            $issueNoteMasterInsert = $this->commonService->findUpdateOrCreate(StoreIssueNote::class, ['id' => ''], $issueNoteMasterData);
            $issueNoteDetailData = $this->storeIssueNoteService->prepareIssueNoteDetailData($request, $issueNoteMasterInsert->id);
            $this->storeIssueNoteService->saveIssueNote($issueNoteDetailData);

            // $this->storeIssueNoteService->saveIssueNote($request, $issueNoteMasterInsert->id);

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
        $date = Carbon::parse($issueNote->date)->format('d-m-Y');
        $issueNoteDetails = StoreIssueNoteDetail::where('store_issue_notes_id', $id)->get();
        if (empty($issueNote)) {
            $message = config('constants.wrong');
        }

        return view('store-issue-note.edit', compact('issueNote','date','dropDownData', 'issueNoteDetails', 'pageTitle'));
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
        // DB::beginTransaction();
        // try {
            $request = request()->all();
            StoreIssueNoteDetail::where('store_issue_notes_id', $request['id'])->delete();

            $issueNoteMasterData = $this->storeIssueNoteService->prepareIssueNoteMasterData($request);
            $issueNoteMasterInsert = $this->commonService->findUpdateOrCreate(StoreIssueNote::class, ['id' => request('id')], $issueNoteMasterData);
            $issueNoteDetailData = $this->storeIssueNoteService->prepareIssueNoteDetailData($request, $issueNoteMasterInsert->id);
            $this->storeIssueNoteService->saveIssueNote($issueNoteDetailData);

            $stockLedgers = $this->storeIssueNoteService->prepareLedgerData($request, $issueNoteMasterInsert->id);
            $this->storeIssueNoteService->saveLedger($stockLedgers);

        //     DB::commit();
        // } catch (\Exception $e) {
        //     DB::rollback();
        //     return redirect('store-issue-note/create')->with('error', $e->getMessage());
        // }

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
            $deleteMaster = StoreIssueNote::where('id', request()->id)->delete();
            $deleteDetail = StoreIssueNoteDetail::where('id', request()->id)->delete();
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


    public function print($id)
    {
        $title = 'Store Issue Note';
        $issueNoteMaster = StoreIssueNote::find($id);
        // dd($saleOrder);
        $date = Carbon::parse($issueNoteMaster->date)->format('d-m-Y');
        $toDepartment = Department::where('id', $issueNoteMaster->to_department)->value('name');
        $fromDepartment = Department::where('id', $issueNoteMaster->from_department)->value('name');
        $issueNoteDetails = StoreIssueNoteDetail::where('store_issue_notes_id', $issueNoteMaster->id)->get();
        // dd($issueNoteDetails);
        $productsArray = $issueNoteDetails->pluck('product_id')->toArray();
        $products = CoaInventoryDetailAccount::whereIn('id',$productsArray)->pluck('name','id');
        $user = User::where('id',$issueNoteMaster->created_by)->value('name');


        return view('store-issue-note.print', compact('title','products','user','issueNoteMaster','date','issueNoteDetails','toDepartment','fromDepartment'));
    }

    public function getProductMeasurementType($name)
    {
        $fetchMeasurementType = CoaInventoryDetailAccount::where('id', $name)->first('measurement_type_id');
        $measurement =  MeasurementType::where('id', $fetchMeasurementType->measurement_type_id)->first('name');

        if ($measurement) {
            return response()->json(['status' => 'success', 'name' => $measurement]);
        }
        return response()->json(['status' => 'fail', 'data' => []]);
    }

    public function getProductPackingType($name)
    {
        $fetchPackingType = CoaInventoryDetailAccount::where('id', $name)->first('packing_type_id');
        $packing =  PackingType::where('id', @$fetchPackingType->packing_type_id)->first('name');


        if ($packing) {
            return response()->json(['status' => 'success', 'name' => $packing]);
        }
        return response()->json(['status' => 'fail', 'data' => []]);


    }

    public function getProductSize($name)
    {
        $fetchSize = CoaInventoryDetailAccount::where('id', $name)->first('size');

        if ($fetchSize) {
            return response()->json(['status' => 'success', 'size' => $fetchSize->size]);
        }
        return response()->json(['status' => 'fail', 'data' => []]);
    }
}
