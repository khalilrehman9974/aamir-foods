<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\StockLedger;
use Illuminate\Http\Request;
use App\Services\CommonService;
use App\Models\CoaDetailAccount;
use Illuminate\Support\Facades\DB;
use App\Models\DefectiveItemsMaster;
use App\Services\StockLedgerService;
use App\Models\DefectiveItemsDetails;
use App\Services\AccountLedgerService;
use App\Services\DefectiveItemsService;
use App\Models\CoaInventoryDetailAccount;
use App\Models\Department;

class DefectiveItemsController extends Controller
{
    protected $commonService;
    protected $defectiveItemsService;
    protected $stockLedgerService;
    protected $accountLedgerService;

    public function __construct(
        CommonService $commonService,
        DefectiveItemsService $defectiveItemsService,
        StockLedgerService $stockLedgerService,
        AccountLedgerService $accountLedgerService
    ) {
        $this->commonService = $commonService;
        $this->defectiveItemsService = $defectiveItemsService;
        $this->stockLedgerService = $stockLedgerService;
        $this->accountLedgerService = $accountLedgerService;
    }

    /*
     * Show page of list of sales.
     * */
    public function index()
    {
        $pageTitle = 'List Of Defective Items';
        $request = request()->all();
        $defectiveItems = $this->defectiveItemsService->search($request);
        $param = request()->param;
        $dropDownData = $this->defectiveItemsService->DropDownData();

        return view('defective_entries.index', compact('defectiveItems', 'dropDownData', 'param', 'request', 'pageTitle'));
    }

    /*
     * Show page of create defective_entries.
     * */
    public function create()
    {

        $pageTitle = 'Add Defective Items';
        $maxId = DefectiveItemsMaster::max('id') + 1;
        $dropDownData = $this->defectiveItemsService->DropDownData();

        return view('defective_entries.create', compact('pageTitle', 'maxId', 'dropDownData'));
    }

    /*
     * Save DefectiveItems into db.
     * @param: @request
     * */
    public function store(Request $request)
    {
        // dd($request);
        $request = $request->except('_token', 'id');
        // DB::beginTransaction();
        // // try {

        //Insert data into defectiveItems tables.
        $defectiveItemsMasterData = $this->defectiveItemsService->prepareDefectiveItemsMasterData($request);
        $defectiveItemsMasterInsert = $this->commonService->findUpdateOrCreate(DefectiveItemsMaster::class, ['id' => ''], $defectiveItemsMasterData);
        $defectiveItemsDetailData = $this->defectiveItemsService->prepareDefectiveItemsDetailData($request, $defectiveItemsMasterInsert->id);
        $this->defectiveItemsService->saveDefectiveItems($defectiveItemsDetailData);

        $stockLedgers = $this->defectiveItemsService->prepareStockLedgerData($request, $defectiveItemsMasterInsert->id);
        $this->defectiveItemsService->saveStockLedger($stockLedgers);
        // $stockLeadgerData = $this->stockLedgerService->prepareAndSaveData($request, $purchaseMasterInsert->id, defectiveItemsService::PURCHASE_TRANSACTION_TYPE,);
        //Insert data into accounts ledger table.
        // $creditAccountData = $this->accountLedgerService->prepareCreditData($request, $purchaseMasterInsert->id, defectiveItemsService::PURCHASE_TRANSACTION_TYPE, defectiveItemsService::PURCHASE_DESCRIPTION);
        // $debitAccountData = $this->accountLedgerService->prepareDebitData($request, $purchaseMasterInsert->id, defectiveItemsService::PURCHASE_TRANSACTION_TYPE, defectiveItemsService::PURCHASE_DESCRIPTION);
        // AccountLedger::insert($creditAccountData);
        // AccountLedger::insert($debitAccountData);
        DB::commit();
        // } catch (\Exception $e) {
        //     DB::rollback();
        //     return redirect('defective/create')->with('error', $e->getMessage());
        // }
        return redirect('defective/list')->with('message', config('constants.add'));
    }

    /*
     * Show edit page.
     * */
    public function edit($id)
    {
        $pageTitle = 'Update Record';
        $defectiveItems = DefectiveItemsMaster::find($id);
        $date = Carbon::parse($defectiveItems->date)->format('d-m-Y');
        $dropDownData = $this->defectiveItemsService->DropDownData();
        $defectiveItemsDetails = DefectiveItemsDetails::where('master_id', $defectiveItems->id)->get();
        if (empty($defectiveItems)) {
            $message = config('constants.wrong');
        }

        return view('defective_entries.edit', compact('pageTitle', 'date', 'defectiveItems', 'dropDownData', 'defectiveItemsDetails'));
    }

    public function update(Request $request)
    {
        // dd($request);
        // try {
        //     DB::beginTransaction();
        $request = request()->all();
        DefectiveItemsDetails::where('master_id', $request['id'])->delete();
        StockLedger::where('invoice_id', $request['id'])->delete();

        // AccountLedger::where('invoice_id', $request['id'])->delete();

        //Save data into relevant tables.
        $defectiveItemsMasterData = $this->defectiveItemsService->prepareDefectiveItemsMasterData($request);
        $defectiveItemsMasterInsert = $this->commonService->findUpdateOrCreate(DefectiveItemsMaster::class, ['id' => request('id')], $defectiveItemsMasterData);
        $defectiveItemsDetailData = $this->defectiveItemsService->prepareDefectiveItemsDetailData($request, $defectiveItemsMasterInsert->id);
        $this->defectiveItemsService->saveDefectiveItems($defectiveItemsDetailData);

        //Save data into stock table.
        $stockLedgers = $this->defectiveItemsService->prepareStockLedgerData($request, $defectiveItemsMasterInsert->id);
        $this->defectiveItemsService->saveStockLedger($stockLedgers);
        // $this->stockLedgerService->prepareAndSaveData($request, $purchaseMasterInsert->id, defectiveItemsService::PURCHASE_TRANSACTION_TYPE);
        // $creditAccountData = $this->accountLedgerService->prepareCreditData($request, $purchaseMasterInsert->id, defectiveItemsService::PURCHASE_TRANSACTION_TYPE, defectiveItemsService::PURCHASE_DESCRIPTION);
        // $debitAccountData = $this->accountLedgerService->prepareDebitData($request, $purchaseMasterInsert->id, defectiveItemsService::PURCHASE_TRANSACTION_TYPE, defectiveItemsService::PURCHASE_DESCRIPTION);
        // AccountLedger::insert($creditAccountData);
        // AccountLedger::insert($debitAccountData);
        //     DB::commit();
        // } catch (\Exception $e) {
        //     DB::rollback();
        //     return redirect('defective/list')->with('error', $e->getMessage());
        // }

        return redirect('defective/list')->with('message', config('constants.update'));
    }

    public function print($id)
    {
        $title = 'Defective Items';
        $defectiveItems = DefectiveItemsMaster::find($id);
        $date = Carbon::parse($defectiveItems->date)->format('d-m-Y');
        $defectiveItemsDetails = DefectiveItemsDetails::where('master_id', $defectiveItems->id)->get();
        $productsArray = $defectiveItemsDetails->pluck('product_id')->toArray();
        $departmentsArray = $defectiveItemsDetails->pluck('from_department')->toArray();
        $products = CoaInventoryDetailAccount::whereIn('id', $productsArray)->pluck('name', 'id');
        $departments = Department::whereIn('id', $departmentsArray)->pluck('name', 'id');
        $user = User::where('id', $defectiveItems->created_by)->value('name');

        return view('defective_entries.print', compact('title', 'products', 'user','departments', 'defectiveItems', 'date', 'defectiveItemsDetails'));
    }

}
