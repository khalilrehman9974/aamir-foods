<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Area;
use App\Models\User;
use App\Models\Sector;
use App\Models\SaleMan;
use App\Models\SaleDetail;
use App\Models\SaleMaster;
use App\Models\StockLedger;
use App\Models\Transporter;
use Illuminate\Http\Request;
use App\Models\AccountLedger;
use App\Services\SaleService;
use App\Services\CommonService;
use App\Models\CoaDetailAccount;
use App\Models\DeliveredToParties;
use App\Models\DispatchNoteDetail;
use App\Models\DispatchNoteMaster;
use Illuminate\Support\Facades\DB;
use App\Services\StockLedgerService;
use App\Models\DetailAccountProducts;
use App\Services\AccountLedgerService;
use App\Http\Requests\StoreSaleRequest;
use App\Models\CoaInventoryDetailAccount;
use App\Models\GeneralJournal;

class SalesController extends Controller
{
    protected $commonService;
    protected $saleService;
    protected $stockLedgerService;
    protected $accountLedgerService;

    public function __construct(
        CommonService $commonService,
        SaleService $saleService,
        StockLedgerService $stockLedgerService,
        AccountLedgerService $accountLedgerService
    ) {
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
        $param = request()->param;
        $sales = $this->saleService->searchSale($request);
        $dropDownData = $this->saleService->DropDownData();

        return view('sales.index', compact('sales', 'param', 'dropDownData', 'request', 'pageTitle'));
    }

    /*
     * Show page of create sale.
     * */
    public function create(Request $request)
    {
        $pageTitle = 'Sales Invoice';
        $dropDownData = $this->commonService->DropDownData();
        $invoiceNo = SaleMaster::max('id') + 1;
        $dispatchNote = DispatchNoteMaster::find($request->id);
        $dispatchNoteDetails = DispatchNoteDetail::where('dispatch_note_master_id', $request->id)->get();
        $parties = CoaDetailAccount::where('id', $dispatchNote->party_id)->pluck('account_name', 'id');
        $getCommission = CoaDetailAccount::where('id', $dispatchNote->party_id)->get();
        $commissionArray = $getCommission->pluck('commision')->toArray();
        $saleMans = SaleMan::where('id', $dispatchNote->saleman)->pluck('name', 'id');
        $sectors = Sector::where('id', $dispatchNote->sector)->pluck('name', 'id');
        $areas = Area::where('id', $dispatchNote->area)->pluck('name', 'id');
        $transporters = Transporter::where('id', $dispatchNote->transporter_id)->pluck('name', 'id');

        $getProducts = DetailAccountProducts::where('detail_account_id', $dispatchNote->party_id)->get();
        $productsArray = $getProducts->pluck('product_id')->toArray();
        $products = CoaInventoryDetailAccount::whereIn('id', $productsArray)->pluck('name', 'id');

        $getPrice = DetailAccountProducts::where('detail_account_id', $dispatchNote->party_id)->whereIn('product_id', $productsArray)->get();
        $pricesArray = $getPrice->pluck('price', 'product_id')->toArray();
        $discountsArray = $getPrice->pluck('discount', 'product_id')->toArray();

        $deliveredToParties = DeliveredToParties::where('id', $dispatchNote->delivered_to)->pluck('party_name', 'id');

        if (empty($dispatchNote)) {
            abort(404);
        }

        return view('sales.create', compact('pageTitle', 'transporters', 'commissionArray', 'pricesArray', 'discountsArray', 'deliveredToParties', 'products', 'dispatchNoteDetails', 'areas', 'sectors', 'saleMans', 'parties', 'dropDownData', 'invoiceNo', 'dispatchNote'));
    }


    public function generate()
    {
        return view('sales.generate');
    }

    /*
     * Save sale into db.
     * @param: @request
     * */
    public function store(Request $request)
    {

        $request = $request->except('_token', 'id');
        DB::beginTransaction();
        try {

            //Insert data into sale tables.
            $saleMasterData = $this->saleService->prepareSaleMasterData($request);

            $saleMasterInsert = $this->saleService->findUpdateOrCreate(SaleMaster::class, ['id' => ''], $saleMasterData);
            $saleDetailData = $this->saleService->prepareSaleDetailData($request, $saleMasterInsert->id);
            $this->saleService->saveSale($saleDetailData);

            //Insert data into stock table.
            $stockLedgers = $this->saleService->prepareStockLedgerData($request, $saleMasterInsert->id);
            $this->saleService->saveStockLedger($stockLedgers);

            $debitAccountData = $this->saleService->prepareAccountDebitData($request, $saleMasterInsert->id);
            AccountLedger::insert($debitAccountData);

            $creditAccountData = $this->saleService->prepareAccountCreditData($request, $saleMasterInsert->id);
            $this->saleService->saveCreditAccountData($creditAccountData);

            $generalJournalDebitData = $this->saleService->prepareGeneralJournalDebitData($request, $saleMasterInsert->id);
            GeneralJournal::insert($generalJournalDebitData);

            $generalJournalCreditData = $this->saleService->prepareGeneralJournalCreditData($request, $saleMasterInsert->id);
            $this->saleService->saveGeneralJournalCreditData($generalJournalCreditData);

            $commissionAccountData = $this->saleService->prepareCommissionAccountCreditData($request, $saleMasterInsert->id);
            AccountLedger::insert($commissionAccountData);

            $commissionAccountDebitData = $this->saleService->prepareCommissionAccountDebitData($request, $saleMasterInsert->id);
            AccountLedger::insert($commissionAccountDebitData);

            $generalJournalcommissionCreditEntry = $this->saleService->prepareGeneralJournalCommissionCreditData($request, $saleMasterInsert->id);
            GeneralJournal::insert($generalJournalcommissionCreditEntry);

            $generalJournalcommissionDebitEntry = $this->saleService->prepareGeneralJournalCommissionDebitData($request, $saleMasterInsert->id);
            GeneralJournal::insert($generalJournalcommissionDebitEntry);

            $generalJournalDiscountCreditEntry = $this->saleService->prepareGeneralJournalDiscountCreditData($request, $saleMasterInsert->id);
            GeneralJournal::insert($generalJournalDiscountCreditEntry);

            $generalJournalDiscountDebitEntry = $this->saleService->prepareGeneralJournalDiscountDebitData($request, $saleMasterInsert->id);
            GeneralJournal::insert($generalJournalDiscountDebitEntry);

            $generalJournalCarriageCreditEntry = $this->saleService->prepareGeneralJournalCarriageCreditData($request, $saleMasterInsert->id);
            GeneralJournal::insert($generalJournalCarriageCreditEntry);

            $generalJournalCarriageDebitEntry = $this->saleService->prepareGeneralJournalCarriageDebitData($request, $saleMasterInsert->id);
            GeneralJournal::insert($generalJournalCarriageDebitEntry);

            $discountAccountData = $this->saleService->prepareDiscountAccountCreditData($request, $saleMasterInsert->id);
            AccountLedger::insert($discountAccountData);

            $discountAccountDebitData = $this->saleService->prepareDiscountAccountDebitData($request, $saleMasterInsert->id);
            AccountLedger::insert($discountAccountDebitData);

            $carriageAccountData = $this->saleService->prepareCarriageAccountCreditData($request, $saleMasterInsert->id);
            AccountLedger::insert($carriageAccountData);

            $carriageAccountDebitData = $this->saleService->prepareCarriageAccountDebitData($request, $saleMasterInsert->id);
            AccountLedger::insert($carriageAccountDebitData);
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
        $pageTitle = 'Update Sales Invoice';
        $currentInvoice = $id;
        $sale = SaleMaster::find($id);
        $date = Carbon::parse($sale->date)->format('d-m-Y');
        $parties = CoaDetailAccount::where('id', $sale->party_id)->pluck('account_name', 'id');
        $saleMans = SaleMan::where('id', $sale->saleman)->pluck('name', 'id');
        $sectors = Sector::where('id', $sale->sector)->pluck('name', 'id');
        $areas = Area::where('id', $sale->area)->pluck('name', 'id');
        $transporters = Transporter::where('id', $sale->transporter_id)->pluck('name', 'id');
        $deliveredToParties = DeliveredToParties::where('id', $sale->delivered_to)->pluck('party_name', 'id');
        $saleDetails = SaleDetail::where('sale_master_id', $id)->get();

        $getProducts = DetailAccountProducts::where('detail_account_id', $sale->party_id)->get();
        $productsArray = $getProducts->pluck('product_id')->toArray();
        $products = CoaInventoryDetailAccount::whereIn('id', $productsArray)->pluck('name', 'id');

        $getPrice = DetailAccountProducts::where('detail_account_id', $sale->party_id)->whereIn('product_id', $productsArray)->get();
        $pricesArray = $getPrice->pluck('price', 'product_id')->toArray();
        $discountsArray = $getPrice->pluck('discount', 'product_id')->toArray();


        if (empty($sale)) {
            $message = config('constants.wrong');
        }

        return view('sales.edit', compact('currentInvoice', 'date', 'pricesArray', 'deliveredToParties', 'transporters', 'areas', 'sectors', 'saleMans', 'parties', 'sale', 'pageTitle', 'products', 'saleDetails'));
    }

    /*
     * update existing resource.
     * @param: $data
     * */
    public function update(Request $request)
    {
        try {
            DB::beginTransaction();
            $request = request()->all();
            SaleDetail::where('sale_master_id', $request['id'])->delete();
            $documentNo = 'S/I' . '-' . $request['id'];
            StockLedger::where('document_no', $documentNo)->where('invoice_id', $request['id'])->delete();
            AccountLedger::where('document_number', $documentNo)->where('invoice_id', $request['id'])->delete();
            GeneralJournal::where('document_number', $documentNo)->where('invoice_id', $request['id'])->delete();

            //Save data into relevant tables.
            $saleMasterData = $this->saleService->prepareSaleMasterData($request);
            $saleMasterInsert = $this->commonService->findUpdateOrCreate(SaleMaster::class, ['id' => request('id')], $saleMasterData);
            $saleDetailData = $this->saleService->prepareSaleDetailData($request, $saleMasterInsert->id);
            $this->saleService->saveSale($saleDetailData);

            //Save data into stock table.
            $stockLedgers = $this->saleService->prepareStockLedgerData($request, $saleMasterInsert->id);
            $this->saleService->saveStockLedger($stockLedgers);

            $debitAccountData = $this->saleService->prepareAccountDebitData($request, $saleMasterInsert->id);
            AccountLedger::insert($debitAccountData);

            $generalJournalDebitData = $this->saleService->prepareGeneralJournalDebitData($request, $saleMasterInsert->id);
            GeneralJournal::insert($generalJournalDebitData);

            $generalJournalCreditData = $this->saleService->prepareGeneralJournalCreditData($request, $saleMasterInsert->id);
            $this->saleService->saveGeneralJournalCreditData($generalJournalCreditData);

            $creditAccountData = $this->saleService->prepareAccountCreditData($request, $saleMasterInsert->id);
            $this->saleService->saveCreditAccountData($creditAccountData);

            $generalJournalcommissionCreditEntry = $this->saleService->prepareGeneralJournalCommissionCreditData($request, $saleMasterInsert->id);
            GeneralJournal::insert($generalJournalcommissionCreditEntry);

            $generalJournalcommissionDebitEntry = $this->saleService->prepareGeneralJournalCommissionDebitData($request, $saleMasterInsert->id);
            GeneralJournal::insert($generalJournalcommissionDebitEntry);

            $generalJournalDiscountCreditEntry = $this->saleService->prepareGeneralJournalDiscountCreditData($request, $saleMasterInsert->id);
            GeneralJournal::insert($generalJournalDiscountCreditEntry);

            $generalJournalDiscountDebitEntry = $this->saleService->prepareGeneralJournalDiscountDebitData($request, $saleMasterInsert->id);
            GeneralJournal::insert($generalJournalDiscountDebitEntry);

            $generalJournalCarriageCreditEntry = $this->saleService->prepareGeneralJournalCarriageCreditData($request, $saleMasterInsert->id);
            GeneralJournal::insert($generalJournalCarriageCreditEntry);

            $generalJournalCarriageDebitEntry = $this->saleService->prepareGeneralJournalCarriageDebitData($request, $saleMasterInsert->id);
            GeneralJournal::insert($generalJournalCarriageDebitEntry);

            $commissionAccountData = $this->saleService->prepareCommissionAccountCreditData($request, $saleMasterInsert->id);
            AccountLedger::insert($commissionAccountData);

            $commissionAccountDebitData = $this->saleService->prepareCommissionAccountDebitData($request, $saleMasterInsert->id);
            AccountLedger::insert($commissionAccountDebitData);

            $discountAccountData = $this->saleService->prepareDiscountAccountCreditData($request, $saleMasterInsert->id);
            AccountLedger::insert($discountAccountData);

            $discountAccountDebitData = $this->saleService->prepareDiscountAccountDebitData($request, $saleMasterInsert->id);
            AccountLedger::insert($discountAccountDebitData);

            $carriageAccountData = $this->saleService->prepareCarriageAccountCreditData($request, $saleMasterInsert->id);
            AccountLedger::insert($carriageAccountData);

            $carriageAccountDebitData = $this->saleService->prepareCarriageAccountDebitData($request, $saleMasterInsert->id);
            AccountLedger::insert($carriageAccountDebitData);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            return redirect('sale/create')->with('error', $e->getMessage());
        }

        return redirect('sale/sales-list')->with('message', config('constants.update'));
    }

    public function print($id)
    {
        $title = 'Sale Invoice';
        $saleMaster = SaleMaster::find($id);
        $date = Carbon::parse($saleMaster->date)->format('d-m-Y');
        $party = CoaDetailAccount::where('id', $saleMaster->party_id)->value('account_name');
        $saleMan = SaleMan::where('id', $saleMaster->saleman)->value('name');
        $belt = Sector::where('id', $saleMaster->sector)->value('name');
        $area = Area::where('id', $saleMaster->area)->value('name');
        $saleDetails = SaleDetail::where('sale_master_id', $saleMaster->id)->get();
        $productsArray = $saleDetails->pluck('product_id')->toArray();
        $products = CoaInventoryDetailAccount::whereIn('id', $productsArray)->pluck('name', 'id');
        $user = User::where('id', $saleMaster->created_by)->value('name');
        $deliverdToParties = DeliveredToParties::where('id', $saleMaster->delivered_to)->value('party_name');
        $transporters = Transporter::where('id', $saleMaster->transporter_id)->value('name');

        return view('sales.print', compact('title', 'products', 'transporters', 'user', 'deliverdToParties', 'saleMaster', 'date', 'saleDetails', 'party', 'saleMan', 'belt', 'area'));
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
