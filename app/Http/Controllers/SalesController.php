<?php

namespace App\Http\Controllers;


use App\Models\SaleDetail;
use App\Models\SaleMaster;
use App\Models\StockLedger;
use App\Models\AccountLedger;
use App\Services\SaleService;
use App\Services\CommonService;
use App\Models\SalePurchaseType;
use App\Models\DispatchNoteMaster;
use Illuminate\Support\Facades\DB;
use App\Services\StockLedgerService;
use App\Services\AccountLedgerService;
use App\Http\Requests\StoreSaleRequest;

class SalesController extends Controller
{

    protected $commonService;
    protected $saleService;
    protected $stockLedgerService;
    protected $accountLedgerService;



    public function __construct(CommonService $commonService,
    SaleService $saleService,
    StockLedgerService $stockLedgerService,
    AccountLedgerService $accountLedgerService)
    {
        $this->commonService = $commonService;
        $this->saleService = $saleService;
        $this->stockLedgerService = $stockLedgerService;
        $this->accountLedgerService = $accountLedgerService;

    }

    /*
     * Show page of list of sales.
     * */
    public function index()
    {
        $pageTitle = 'List Of Sales';
        $request = request()->all();
        $sales = $this->saleService->searchSale($request);

        return view('sales.index', compact('sales', 'request','pageTitle'));
    }

    /*
     * Show page of create sale.
     * */
    public function create()
    {
        $pageTitle = 'Sales';
        $type = SalePurchaseType::where('name', 'Sale')->pluck('name', 'id');
        $dropDownData = $this->commonService->DropDownData();
        $invoiceNo = SaleMaster::max('id') + 1;
//        dd($dropDownData);

        return view('sales.create', compact( 'type','pageTitle', 'dropDownData', 'invoiceNo'));
    }

    /*
     * Save sale into db.
     * @param: @request
     * */
    public function store(StoreSaleRequest $request)
    {
        $request = $request->except('_token', 'saleId');
        $session = $this->commonService->getSession();
        DB::beginTransaction();
       try {
            $request['business_id'] = $session->business_id;
            $request['f_year_id'] = $session->financial_year;
            $request['type_id'] = $session->financial_year;
            //Insert data into sale tables.
            $saleMasterData = $this->saleService->prepareSaleMasterData($request);
            $saleMasterInsert = $this->commonService->findUpdateOrCreate(SaleMaster::class, ['id' => ''], $saleMasterData);
            $saleDetailData = $this->saleService->prepareSaleDetailData($request, $saleMasterInsert->id);
            $this->saleService->saveSale($saleDetailData);

            //Insert data into stock table.
            $this->stockLedgerService->prepareAndSaveData($request, $saleMasterInsert->id, config('contants.SALE_TRANSACTION_TYPE'));

            //Insert data into accounts ledger table.
            $debitAccountData = $this->saleService->prepareAccountDebitData($request, $saleMasterInsert->id, config('contants.SALE_TRANSACTION_TYPE'), config('contants.SALE_DESCRIPTION'));
            $creditAccountData = $this->saleService->prepareAccountCreditData($request, $saleMasterInsert->id, config('contants.SALE_TRANSACTION_TYPE'), config('contants.SALE_DESCRIPTION'));
            AccountLedger::insert($debitAccountData);
            AccountLedger::insert($creditAccountData);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            return redirect('sale/create')->with('error', $e->getMessage());
        }
        return redirect('sale/sales-list')->with('message', config('constants.add'));
    }

    /*
     * Show edit page.
     * */
    public function edit($id)
    {
        $sale = SaleMaster::find($id);
        $type = SalePurchaseType::where('name', 'Sale')->pluck('name', 'id');
        $saleDetails = SaleDetail::where('sale_master_id', $id)->get();
        if (empty($sale)) {
            $message = config('constants.wrong');
        }

        return view('sales.create', compact('sale',  'type', 'saleDetails'));
    }

    /*
     * update existing resource.
     * @param: $data
     * */
    public function update(StoreSaleRequest $request)
    {
        try {
            DB::beginTransaction();
            $request = request()->all();
            SaleMaster::where('id', $request['id'])->delete();
            SaleDetail::where('sale_master_id', $request['saleId'])->delete();
            // Stock::where('invoice_id', $request['saleId'])->delete();
            // AccountLedger::where('invoice_id', $request['saleId'])->delete();

            //Save data into relevant tables.
            $saleMasterData = $this->saleService->prepareSaleMasterData($request);
            $saleMasterInsert = $this->commonService->findUpdateOrCreate(SaleMaster::class, ['id' => request('id')], $saleMasterData);
            $saleDetailData = $this->saleService->prepareSaleDetailData($request, $saleMasterInsert->id);
            $this->saleService->saveSale($saleDetailData);

            //Save data into stock table.
            $this->stockLedgerService->prepareAndSaveData($request, $saleMasterInsert->id, config('contants.SALE_TRANSACTION_TYPE'));
            $debitAccountData = $this->saleService->prepareAccountDebitData($request, $saleMasterInsert->id, config('contants.SALE_TRANSACTION_TYPE'), config('contants.SALE_DESCRIPTION'));
            $creditAccountData = $this->saleService->prepareAccountCreditData($request, $saleMasterInsert->id, config('contants.SALE_TRANSACTION_TYPE'), config('contants.SALE_DESCRIPTION'));
            AccountLedger::insert($debitAccountData);
            AccountLedger::insert($creditAccountData);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            return redirect('sale/create')->with('error', $e->getMessage());
        }

        return redirect('sale/sales-list')->with('message', config('constants.update'));
    }

    /*
     * Delete existing resource.
     * @param: request()->id
     * */
    public function delete()
    {
        try {
            DB::beginTransaction();
            $deleteMaster = SaleMaster::where('id', request()->id)->delete();
            $deleteDetail = SaleDetail::where('sale_master_id', request()->id)->delete();
            $deleteStock = StockLedger::where('invoice_id', request()->id)->delete();
            $accountEntryDetail = AccountLedger::where('invoice_id', request()->id)->delete();
            DB::commit();
            // && $deleteStock && $accountEntryDetail
            if ($deleteMaster && $deleteDetail && $deleteStock && $accountEntryDetail) {
                return response()->json(['status' => 'success', 'message' => config('constants.delete')]);
            } else {
                return response()->json(['status' => 'fail', 'message' => config('constants.wrong')]);
            }

        } catch (\Exception $e) {
            DB::rollback();
            return redirect('sale/sales-list')->with('error', $e->getMessage());
        }
    }

    /*
    * View sale detail.
    * @param: $id
    * */
    public function view($id)
    {
        $saleMaster = $this->saleService->getSaleMasterById($id);
        $saleDetail = $this->saleService->getSaleDetailById($id);
        if (empty($saleMaster)) {
            $message = config('constants.wrong');
        }

        return view('sales.view', compact('saleMaster', 'saleDetail'));
    }


    // /*
    //  * Get product detail
    //  * @param: request('productCode')
    //  * */
    // public function getProductDetail()
    // {
    //     $product = $this->productService->getById(request('productCode'));
    //     if ($product) {
    //         return json_encode(['status' => 'success', 'data' => $product]);
    //     } else {
    //         return response()->json(['status' => 'fail', 'message' => SaleService::SOME_THING_WENT_WRONG]);
    //     }
    // }

    public function getDispatchNote()
    {
        return $dispatchNote = DispatchNoteMaster::with('items')->get();
    }
}
