<?php

namespace App\Services;

use App\Models\AccountLedger;
use Carbon\Carbon;
use App\Models\Area;
use App\Models\SaleMan;
use App\Models\SaleDetail;
use App\Models\SaleMaster;
use App\Models\StockLedger;
use App\Models\Transporter;
use App\Models\CoaDetailAccount;
use Illuminate\Support\Facades\Auth;
use App\Models\CoaInventoryDetailAccount;
use App\Models\GeneralJournal;

class SaleService
{
    const PER_PAGE = 10;

    protected $commonService;

    public function __construct(CommonService $commonService)
    {
        $this->commonService = $commonService;
    }

    public function DropDownData()
    {
        $result = [
            'products' => CoaInventoryDetailAccount::pluck('name','id'),
            'saleMans' => SaleMan::pluck('name','id'),
            'areas' => Area::pluck('name','id'),
            'parties' => CoaDetailAccount::pluck('account_name','id'),
            'transporters' => Transporter::pluck('name','id'),
        ];

        return $result;
    }

    /*
     * Get contract by id.
     * @param $id
     * */
    public function getSaleMasterById($id)
    {
        return SaleMaster::leftjoin('parties', 'parties.id', '=', 'sale_masters.party_id')
            ->leftjoin('salemans', 'salemans.id', '=', 'sale_masters.saleman_id')
            ->select(
                'sale_masters.id as id',
                'sale_masters.date',
                'sale_masters.dispatch_note',
                'sale_masters.type_id',
                'sale_masters.bilty_no',
                'sale_masters.remarks',
                'sale_masters.created_at',
                'sale_masters.deliverd_to',
                'sale_masters.updated_at',
                'sale_masters.transporter_id',
                'sale_masters.total_amount',
                'sale_masters.freight',
                'sale_masters.scheme',
                'sale_masters.commission',
                'parties.name as partyName',
                'salemans.id as salemanId'
            )
            ->where('sale_masters.id', $id)
            ->first();
    }

    /*
    * Get contract by id.
    * @param $id
    * */
    public function getSaleDetailById($id)
    {
        return SaleDetail::leftjoin('salemans', 'sale_details.product_id', '=', 'salemans.id')
            ->select('salemans.name as salemanName', 'sale_details.unit', 'sale_details.quantity', 'sale_details.amount', 'sale_details.rate', 'sale_details.total_unit')
            ->where('sale_details.sale_master_id', $id)
            ->get();
    }

    /*
     * Search sale record.
     * @queries: $queries
     * @return: object
     * */
    public function searchSale($request)
    {

        $q = SaleMaster::query();
        if (!empty($request['date'])) {
            $formattedDate = date('Y-m-d', strtotime($request['date']));
            $q->where('date', $formattedDate);
        } elseif (!empty($request['party_id'])) {
            $q->where('party_id', $request['party_id']);
        }

        $saleInvoices = $q->with('party','SaleMan')->orderBy('id', 'DESC')->paginate(config('constants.PER_PAGE'));

        return $saleInvoices;
    }

     /*
    * Store dispatch note data.
    * @param $model
    * @param $where
    * @param $data
    *
    * @return object $object.
    * */
    public function findUpdateOrCreate($model, array $where, array $data)
    {
        $object = $model::firstOrNew($where);

        foreach ($data as $property => $value){
            $object->{$property} = $value;
        }
        $object->save();

        return $object;
    }


    /*
     * Prepare sale master data.
     * @param: $request
     * @return Array
     * */
    public function prepareSaleMasterData($request)
    {

        $session = $this->commonService->getSession();
        return [
            'dispatch_note_number' => $request['dispatch_note_number'],
            'sale_order_number' => $request['sale_order_number'],
            'date' => Carbon::parse($request['date'])->format('Y-m-d'),
            'party_id' => $request['party_id'],
            'saleman' => $request['saleman'],
            'sector' => $request['sector'],
            'area' => $request['area'],
            'delivered_to' => $request['delivered_to'] ?? null,
            'transporter_id' => $request['transporter_id'],
            'vehicle_no' => $request['vehicle_no'],
            'driver_name' => $request['driver_name'],
            'bilty_no' => $request['bilty_no'],
            'business_id' => $session->business_id,
            'f_year_id' => $session->financial_year,
            'remarks' => $request['remarks'],
            'total_boray' => $request['total_boray'],
            'total_carton' => $request['total_carton'],
            'gross_bill' => $request['gross_bill'],
            'carriage' => $request['carriage'],
            'totaldiscount' => $request['totaldiscount'],
            'commission' => $request['commission'],
            'net_amount' => $request['net_amount'],
            'created_by' => Auth::user()->id,
            'updated_by' => Auth::user()->id

        ];
    }

    /*
     * Prepare sale detail data.
     * @param: $request
     * @return Array
     * */
    public function prepareSaleDetailData($request, $saleParentId)
    {

        return [
            'product_id' => $request['product_id'],
            'packing_type' => $request['packing_type'],
            'measurement_type' => $request['measurement_type'],
            'quantity' => $request['quantity'],
            'soQuantity' => $request['soQuantity'],
            'dispQuantity' => $request['dispQuantity'],
            'dzns' => $request['dzns'],
            'total_dzns' => $request['total_dzns'],
            'rate' => $request['rate'],
            'discount' => $request['discount'],
            'amount' => $request['amount'],
            'sale_master_id' => $saleParentId,
        ];
    }

    /*
     * Save sale data.
     * @param: $data
     * */
    public function saveSale($data)
    {
        foreach ($data['product_id'] as $key => $value) {
            if (!empty($data['product_id'][$key])) {
                $rec['product_id'] = $data['product_id'][$key];
                $rec['packing_type'] = $data['packing_type'][$key];
                $rec['measurement_type'] = $data['measurement_type'][$key];
                $rec['quantity'] = $data['quantity'][$key];
                $rec['soQuantity'] = $data['soQuantity'][$key];
                $rec['dispQuantity'] = $data['dispQuantity'][$key];
                $rec['dzns'] = $data['dzns'][$key];
                $rec['total_dzns'] = $data['total_dzns'][$key];
                $rec['rate'] = $data['rate'][$key];
                $rec['discount'] = $data['discount'][$key];
                $rec['amount'] = $data['amount'][$key];
                $rec['sale_master_id'] = $data['sale_master_id'];
                SaleDetail::create($rec);
            }
        }
    }

    public function prepareStockLedgerData($request, $saleInvoiceParentId)
    {
        $party = CoaDetailAccount::where('id', $request['party_id'])->first("account_name");

        return [
            'product_id' => $request['product_id'],
            'party_title' =>  $party->account_name,
            'date' => Carbon::parse($request['date'])->format('Y-m-d'),
            'document_no' => 'S/I' . '-' . $saleInvoiceParentId,
            'stock_in_bags' =>  config('constants.ZERO'),
            'stock_in_weight' =>  config('constants.ZERO'),
            'stock_in_quantity' =>  config('constants.ZERO'),
            'stock_out_bags' =>  config('constants.ZERO'),
            'stock_out_weight' =>  config('constants.ZERO'),
            'stock_out_quantity' => $request['quantity'],
            'invoice_id' => $saleInvoiceParentId,
            'rate' => config('constants.ZERO'),
            'created_by' => Auth::user()->id,
            'updated_by' => Auth::user()->id
        ];
    }

    /*
     * Save StockLedger data.
     * @param: $data
     * */
    public function saveStockLedger($data)
    {
        foreach ($data['product_id'] as $key => $value) {
            if (!empty($data['product_id'][$key])) {
                $rec['product_id'] = $data['product_id'][$key];
                $rec['date'] = $data['date'];
                $rec['party_title'] = $data['party_title'];
                $rec['stock_in_bags'] = $data['stock_in_bags'];
                $rec['stock_in_weight'] = $data['stock_in_weight'];
                $rec['stock_in_quantity'] = $data['stock_in_quantity'];
                $rec['stock_out_bags'] = $data['stock_out_bags'];
                $rec['stock_out_weight'] = $data['stock_out_weight'];
                $rec['stock_out_quantity'] = $data['stock_out_quantity'][$key];
                $rec['document_no'] = $data['document_no'];
                $rec['rate'] = $data['rate'];
                $rec['invoice_id'] = $data['invoice_id'];
                $rec['created_by'] = $data['created_by'];
                $rec['updated_by'] = $data['updated_by'];
                StockLedger::create($rec);
            }
        }
    }

    public function prepareAccountDebitData($request, $saleParentId)
    {
        $party = CoaDetailAccount::where('id', $request['party_id'])->value('account_name');

        return [
            'date' => Carbon::parse($request['date'])->format('Y-m-d'),
            'invoice_id' => $saleParentId,
            'party_id' =>   $request['party_id'],
            'document_number' => 'S/I' . '-' . $saleParentId,
            'rate' => config('constants.ZERO'),
            'bilty_no' => null,
            'transporter_id' => null,
            'total_quantity' => config('constants.ZERO'),
            'measurementType' => config('constants.ZERO'),
            'bags' => config('constants.ZERO'),
            'description' => 'Sales To'. ' ' . $party . '<br>' .  $request['remarks'],
            'debit' => $request['gross_bill'],
            'credit' => 0,
            'created_at' => now(),
            'updated_at' => now() ,
        ];
    }

    public function prepareGeneralJournalDebitData($request, $saleParentId)
    {

        $party = CoaDetailAccount::where('id', $request['party_id'])->value('account_name');
        $session = $this->commonService->getSession();
        return [
            'date' => Carbon::parse($request['date'])->format('Y-m-d'),
            'invoice_id' => $saleParentId,
            'document_number' => 'S/I' . '-' . $saleParentId,
            'business_id' => $session->business_id,
            'f_year_id' => $session->financial_year,
            'description' => $party,
            'narration' => 'Debit Sale Of:'. ' ' . $party,
            'debit' => $request['gross_bill'],
            'credit' => 0,
            'created_at' => now(),
            'updated_at' => now() ,
        ];
    }

    public function prepareGeneralJournalCreditData($request, $saleParentId)
    {

        $productArray = $request['product_id'];
        $product = CoaInventoryDetailAccount::whereIn('id', $productArray)->pluck('name')->toarray();
        $party = CoaDetailAccount::whereIn('account_name', $product)->pluck('id');
        $session = $this->commonService->getSession();
        $partyName = CoaDetailAccount::where('id', $request['party_id'])->value('account_name');
        // $description = $request['quantity'].$request['packing_type'].$request['product_id'].$request['rate'].$request['measurement_type'].['Sold To'].[$party].['@'].$request['amount'];
        return [
            'date' => Carbon::parse($request['date'])->format('Y-m-d'),
            'invoice_id' => $saleParentId,
            'document_number' => 'S/I' . '-' . $saleParentId,
           'business_id' => $session->business_id,
            'f_year_id' => $session->financial_year,
           'description' => $product,
            'narration' => 'Credit Sale Of:'. ' ' . $partyName,
            'debit' => config('constants.ZERO'),
            'credit' => $request['amount'],
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    public function saveGeneralJournalCreditData($data)
    {
        foreach ($data['description'] as $key => $value) {
            if (!empty($data['description'][$key])) {
                $rec['description'] = $data['description'][$key];
                $rec['date'] = $data['date'];
                $rec['invoice_id'] = $data['invoice_id'];
                $rec['document_number'] = $data['document_number'];
                $rec['business_id'] = $data['business_id'];
                $rec['f_year_id'] = $data['f_year_id'];
                $rec['narration'] = $data['narration'];
                $rec['debit'] = $data['debit'];
                $rec['credit'] = $data['credit'][$key];
                $rec['created_at'] = now();
                $rec['updated_at'] = now();
                GeneralJournal::create($rec);
            }
        }
    }

    public function prepareAccountCreditData($request, $saleParentId)
    {
        $remarks = $request['remarks'];
        $description = 'Entry Through Product';
        $productArray = $request['product_id'];
        $product = CoaInventoryDetailAccount::whereIn('id', $productArray)->pluck('name')->toarray();
        $party = CoaDetailAccount::whereIn('account_name', $product)->pluck('id');
        return [
            'date' => Carbon::parse($request['date'])->format('Y-m-d'),
            'invoice_id' => $saleParentId,
            'party_id' =>  $party,
            'document_number' => 'S/I' . '-' . $saleParentId,
            'rate' =>$request['rate'],
            'bilty_no' => $request['bilty_no'],
            'transporter_id' => $request['transporter_id'],
            'total_quantity' => $request['total_dzns'],
            'measurementType' => $request['dzns'],
            'bags' => $request['quantity'],
            'description' => $description . '<br>' .$remarks ,
            'debit' => config('constants.ZERO'),
            'credit' => $request['amount'],
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    public function saveCreditAccountData($data)
    {
        foreach ($data['party_id'] as $key => $value) {
            if (!empty($data['party_id'][$key])) {
                $rec['party_id'] = $data['party_id'][$key];
                $rec['date'] = $data['date'];
                $rec['invoice_id'] = $data['invoice_id'];
                $rec['document_number'] = $data['document_number'];
                $rec['rate'] = $data['rate'][$key];
                $rec['bilty_no'] = $data['bilty_no'];
                $rec['transporter_id'] = $data['transporter_id'];
                $rec['total_quantity'] = $data['total_quantity'][$key];
                $rec['measurementType'] = $data['measurementType'][$key];
                $rec['bags'] = $data['bags'][$key];
                $rec['description'] = $data['description'];
                $rec['debit'] = $data['debit'];
                $rec['credit'] = $data['credit'][$key];
                $rec['created_at'] = now();
                $rec['updated_at'] = now();
                AccountLedger::create($rec);
            }
        }
    }

    public function prepareCommissionAccountCreditData($request, $saleParentId)
    {
        $party = CoaDetailAccount::where('id', $request['party_id'])->value('account_name');
        return [
            'date' => Carbon::parse($request['date'])->format('Y-m-d'),
            'invoice_id' => $saleParentId,
            'party_id' =>  $request['party_id'],
            'document_number' => 'S/I' . '-' . $saleParentId,
            'rate' => config('constants.ZERO'),
            'bilty_no' => null,
            'transporter_id' => null,
            'total_quantity' => config('constants.ZERO'),
            'measurementType' => config('constants.ZERO'),
            'bags' => config('constants.ZERO'),
            'description' => 'Commission Of'. ' ' . $party . '<br>' .  $request['remarks'],
            'debit' => config('constants.ZERO'),
            'credit' => $request['commission'],
            'created_at' => now(),
            'updated_at' => now() ,
        ];
    }

    public function prepareGeneralJournalCommissionCreditData($request, $saleParentId)
    {
        $party = CoaDetailAccount::where('id', $request['party_id'])->value('account_name');
        $session = $this->commonService->getSession();
        return [
            'date' => Carbon::parse($request['date'])->format('Y-m-d'),
            'invoice_id' => $saleParentId,
            'document_number' => 'S/I' . '-' . $saleParentId,
            'business_id' => $session->business_id,
            'f_year_id' => $session->financial_year,
            'description' => $party ,
            'narration' => 'Commission Of'. ' ' . $party ,
            'debit' => config('constants.ZERO'),
            'credit' => $request['commission'],
            'created_at' => now(),
            'updated_at' => now() ,
        ];
    }

    public function prepareGeneralJournalCommissionDebitData($request, $saleParentId)
    {

        $partyName = 'Commission On Sales.';
        $session = $this->commonService->getSession();
        return [
            'date' => Carbon::parse($request['date'])->format('Y-m-d'),
            'invoice_id' => $saleParentId,
            'document_number' => 'S/I' . '-' . $saleParentId,
            'business_id' => $session->business_id,
            'f_year_id' => $session->financial_year,
            'description' => $partyName ,
            'narration' => 'Debit'. ' ' . $partyName ,
            'debit' => $request['commission'],
            'credit' => config('constants.ZERO'),
            'created_at' => now(),
            'updated_at' => now() ,
        ];
    }

    public function prepareGeneralJournalDiscountCreditData($request, $saleParentId)
    {
        $party = CoaDetailAccount::where('id', $request['party_id'])->value('account_name');
        $session = $this->commonService->getSession();
        return [
            'date' => Carbon::parse($request['date'])->format('Y-m-d'),
            'invoice_id' => $saleParentId,
            'document_number' => 'S/I' . '-' . $saleParentId,
            'business_id' => $session->business_id,
            'f_year_id' => $session->financial_year,
            'description' => $party ,
            'narration' => 'Discount Of'. ' ' . $party ,
            'debit' => config('constants.ZERO'),
            'credit' => $request['totaldiscount'],
            'created_at' => now(),
            'updated_at' => now() ,
        ];
    }

    public function prepareGeneralJournalDiscountDebitData($request, $saleParentId)
    {

        $partyName = 'Discounts on Sales.';
        $session = $this->commonService->getSession();
        return [
            'date' => Carbon::parse($request['date'])->format('Y-m-d'),
            'invoice_id' => $saleParentId,
            'document_number' => 'S/I' . '-' . $saleParentId,
            'business_id' => $session->business_id,
            'f_year_id' => $session->financial_year,
            'description' => $partyName ,
            'narration' => 'Debit'. ' ' . $partyName ,
            'debit' => $request['totaldiscount'],
            'credit' => config('constants.ZERO'),
            'created_at' => now(),
            'updated_at' => now() ,
        ];
    }

    public function prepareGeneralJournalCarriageCreditData($request, $saleParentId)
    {
        $party = CoaDetailAccount::where('id', $request['party_id'])->value('account_name');
        $session = $this->commonService->getSession();
        return [
            'date' => Carbon::parse($request['date'])->format('Y-m-d'),
            'invoice_id' => $saleParentId,
            'document_number' => 'S/I' . '-' . $saleParentId,
            'business_id' => $session->business_id,
            'f_year_id' => $session->financial_year,
            'description' => $party ,
            'narration' => 'Carriage Of'. ' ' . $party ,
            'debit' => config('constants.ZERO'),
            'credit' => $request['carriage'],
            'created_at' => now(),
            'updated_at' => now() ,
        ];
    }

    public function prepareGeneralJournalCarriageDebitData($request, $saleParentId)
    {

        $partyName = 'Carriage Outward.';
        $session = $this->commonService->getSession();
        return [
            'date' => Carbon::parse($request['date'])->format('Y-m-d'),
            'invoice_id' => $saleParentId,
            'document_number' => 'S/I' . '-' . $saleParentId,
            'business_id' => $session->business_id,
            'f_year_id' => $session->financial_year,
            'description' => $partyName ,
            'narration' => 'Debit'. ' ' . $partyName ,
            'debit' => $request['carriage'],
            'credit' => config('constants.ZERO'),
            'created_at' => now(),
            'updated_at' => now() ,
        ];
    }


    public function prepareCommissionAccountDebitData($request, $saleParentId)
    {

        $partyName = 'Commission On Sales.';
        $party = CoaDetailAccount::where('account_name', $partyName)->value('id');
        $mainPartyName = CoaDetailAccount::where('id', $request['party_id'])->value('account_name');

        return [
            'date' => Carbon::parse($request['date'])->format('Y-m-d'),
            'invoice_id' => $saleParentId,
            'party_id' =>  $party,
            'document_number' => 'S/I' . '-' . $saleParentId,
            'rate' => config('constants.ZERO'),
            'bilty_no' => null,
            'transporter_id' => null,
            'total_quantity' => config('constants.ZERO'),
            'measurementType' => config('constants.ZERO'),
            'bags' => config('constants.ZERO'),
            'description' => 'Commission Of'. ' ' . $mainPartyName . '<br>' .  $request['remarks'],
            'debit' => $request['commission'],
            'credit' => config('constants.ZERO'),
            'created_at' => now(),
            'updated_at' => now() ,
        ];
    }

    public function prepareCarriageAccountCreditData($request, $saleParentId)
    {
        $party = CoaDetailAccount::where('id', $request['party_id'])->value('account_name');
        return [
            'date' => Carbon::parse($request['date'])->format('Y-m-d'),
            'invoice_id' => $saleParentId,
            'party_id' => $request['party_id'],
            'document_number' => 'S/I' . '-' . $saleParentId,
            'rate' => config('constants.ZERO'),
            'bilty_no' => null,
            'transporter_id' => null,
            'total_quantity' => config('constants.ZERO'),
            'measurementType' => config('constants.ZERO'),
            'bags' => config('constants.ZERO'),
            'description' => 'Carriage Of'. ' ' . $party . '<br>' .  $request['remarks'],
            'debit' => config('constants.ZERO'),
            'credit' => $request['carriage'],
            'created_at' => now(),
            'updated_at' => now() ,
        ];
    }

    public function prepareCarriageAccountDebitData($request, $saleParentId)
    {
        $mainPartyName = CoaDetailAccount::where('id', $request['party_id'])->value('account_name');
        $partyName = 'Carriage Outward.';
        $party = CoaDetailAccount::where('account_name', $partyName)->value('id');
        return [
            'date' => Carbon::parse($request['date'])->format('Y-m-d'),
            'invoice_id' => $saleParentId,
            'party_id' =>  $party,
            'document_number' => 'S/I' . '-' . $saleParentId,
            'rate' => config('constants.ZERO'),
            'bilty_no' => null,
            'transporter_id' => null,
            'total_quantity' => config('constants.ZERO'),
            'measurementType' => config('constants.ZERO'),
            'bags' => config('constants.ZERO'),
            'description' => 'Carriage Of'. ' ' . $mainPartyName . '<br>' .  $request['remarks'],
            'debit' => $request['carriage'],
            'credit' => config('constants.ZERO'),
            'created_at' => now(),
            'updated_at' => now() ,
        ];
    }

    public function prepareDiscountAccountCreditData($request, $saleParentId)
    {
        $party = CoaDetailAccount::where('id', $request['party_id'])->value('account_name');
        return [
            'date' => Carbon::parse($request['date'])->format('Y-m-d'),
            'invoice_id' => $saleParentId,
            'party_id' =>   $request['party_id'],
            'document_number' => 'S/I' . '-' . $saleParentId,
            'rate' => config('constants.ZERO'),
            'bilty_no' => null,
            'transporter_id' => null,
            'total_quantity' => config('constants.ZERO'),
            'measurementType' => config('constants.ZERO'),
            'bags' => config('constants.ZERO'),
            'description' => 'Discount  to '. ' ' . $party . '<br>' .  $request['remarks'],
            'debit' => config('constants.ZERO'),
            'credit' => $request['totaldiscount'],
            'created_at' => now(),
            'updated_at' => now() ,
        ];
    }

    public function prepareDiscountAccountDebitData($request, $saleParentId)
    {
        $mainPartyName = CoaDetailAccount::where('id', $request['party_id'])->value('account_name');
        $partyName = 'Discounts on Sales.';
        $party = CoaDetailAccount::where('account_name', $partyName)->value('id');

        return [
            'date' => Carbon::parse($request['date'])->format('Y-m-d'),
            'invoice_id' => $saleParentId,
            'party_id' =>  $party,
            'document_number' => 'S/I' . '-' . $saleParentId,
            'rate' => config('constants.ZERO'),
            'bilty_no' => null,
            'transporter_id' => null,
            'total_quantity' => config('constants.ZERO'),
            'measurementType' => config('constants.ZERO'),
            'bags' => config('constants.ZERO'),
            'description' => 'Discount To'. ' ' . $mainPartyName . '<br>' .  $request['remarks'],
            'debit' => $request['totaldiscount'],
            'credit' => config('constants.ZERO'),
            'created_at' => now(),
            'updated_at' => now() ,
        ];
    }

    
}
