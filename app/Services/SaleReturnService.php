<?php

namespace App\Services;


use Carbon\Carbon;
use App\Models\SaleMan;
use App\Models\StockLedger;
use App\Models\Transporter;
use App\Models\AccountLedger;
use App\Models\GeneralJournal;
use App\Models\CoaDetailAccount;
use App\Models\SaleReturnDetail;
use App\Models\SaleReturnMaster;
use App\Models\DeliveredToParties;
use Illuminate\Support\Facades\Auth;
use App\Models\CoaInventoryDetailAccount;
use Symfony\Component\Mailer\Transport\Transports;

class SaleReturnService
{
    const PER_PAGE = 10;

    protected $commonService;

    public function __construct(CommonService $commonService)
    {
        $this->commonService = $commonService;
    }

    /*
     * Get contract by id.
     * @param $id
     * */
    public function getSaleReturnMasterById($id)
    {
        return SaleReturnMaster::leftjoin('parties', 'parties.id', '=', 'sale_return_masters.party_id')
            ->leftjoin('salemans', 'salemans.id', '=', 'sale_return_masters.saleman_id')
            ->select(
                'sale_return_masters.id as id',
                'sale_return_masters.date',
                'sale_return_masters.dispatch_note',
                'sale_return_masters.type_id',
                'sale_return_masters.bilty_no',
                'sale_return_masters.remarks',
                'sale_return_masters.created_at',
                'sale_return_masters.deliverd_to',
                'sale_return_masters.updated_at',
                'sale_return_masters.transporter_id',
                'sale_return_masters.total_amount',
                'sale_return_masters.freight',
                'sale_return_masters.scheme',
                'sale_return_masters.commission',
                'parties.name as partyName',
                'salemans.id as salemanId'
            )
            ->where('sale_return_masters.id', $id)
            ->first();
    }


    public function DropDownData()
    {
        $result = [
            'saleMans' => SaleMan::pluck('name', 'id'),
            'parties' => CoaDetailAccount::pluck('account_name', 'id'),
            'DeliveredToParties' => DeliveredToParties::pluck('party_name', 'id'),
            'Transporters' => Transporter::pluck('name', 'id'),
            'products' => CoaInventoryDetailAccount::pluck('name', 'id'),
        ];

        return $result;
    }

    /*
    * Get contract by id.
    * @param $id
    * */
    public function getSaleReturnDetailById($id)
    {
        return SaleReturnDetail::leftjoin('salemans', 'sale_details.product_id', '=', 'salemans.id')
            ->select('salemans.name as salemanName', 'sale_details.unit', 'sale_details.quantity', 'sale_details.amount', 'sale_details.rate', 'sale_details.total_unit')
            ->where('sale_details.sale_master_id', $id)
            ->get();
    }

    /*
     * Search sale record.
     * @queries: $queries
     * @return: object
     * */

    public function searchSaleReturn($request)
    {

        $q = SaleReturnMaster::query();

        if (!empty($request['date'])) {
            $formattedDate = date('Y-m-d', strtotime($request['date']));
            $q->where('date', $formattedDate);
        } elseif (!empty($request['party_id'])) {
            $q->where('party_id', $request['party_id']);
        }

        $saleInvoices = $q->with('party', 'SaleMan')->orderBy('id', 'DESC')->paginate(config('constants.PER_PAGE'));

        return $saleInvoices;
    }

    public function searchSaleReturn2($request)
    {
        $q = SaleReturnMaster::query();
        if (!empty($request['param'])) {
            $q = SaleReturnMaster::with('party', 'SaleMan')->where('date', 'like', '%' . $request['param'] . '%')
                ->orWhere('sale_order_number', 'like', '%' . $request['param'] . '%')
                ->orWhere('party_id', 'like', '%' . $request['param'] . '%')
                ->orWhere('saleman', 'like', '%' . $request['param'] . '%')
                ->orWhere('area', 'like', '%' . $request['param'] . '%')
                ->orWhere('vehicle_no', 'like', '%' . $request['param'] . '%')
                ->orWhere('bility_no', 'like', '%' . $request['param'] . '%')
                ->orWhere('driver_name', 'like', '%' . $request['param'] . '%')
                ->orWhere('total_boray', 'like', '%' . $request['param'] . '%')
                ->orWhere('total_carton', 'like', '%' . $request['param'] . '%')
                ->orWhere('sector', 'like', '%' . $request['param'] . '%');
        }
        $saleInvoices = $q->orderBy('id', 'ASC')->paginate(config('constants.PER_PAGE'));

        return $saleInvoices;
    }


    /*
     * Prepare sale master data.
     * @param: $request
     * @return Array
     * */
    public function prepareSaleReturnMasterData($request)
    {
        $session = $this->commonService->getSession();
        return [

            'grn_no' => $request['grn_no'],
            'sale_invoice_number' => $request['sale_invoice_number'],
            'date' => Carbon::parse($request['date'])->format('Y-m-d'),
            'party_id' => $request['party_id'],
            'sale_return_number' => $request['party_id'],
            'bilty_no' => $request['bilty_no'],
            'deliverd_to' => $request['deliverd_to'] ?? null,
            'saleman' => $request['saleman'],
            'sector' => $request['sector'],
            'area' => $request['area'],
            'transporter_id' => $request['transporter_id'],
            'driver_name' => $request['driver_name'],
            'business_id' => $session->business_id,
            'f_year_id' => $session->financial_year,
            'remarks' => $request['remarks'],
            'gross_amount' => $request['gross_amount'],
            'boray_amount' => $request['boray_amount'],
            'carton_amount' => $request['carton_amount'],
            'scheme' => $request['scheme'],
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
    public function prepareSaleReturnDetailData($request, $saleParentId)
    {
        return [
            'product_id' => $request['product_id'],
            'packing_type' => $request['packing_type'],
            'measurement_type' => $request['measurement_type'],
            'quantity' => $request['quantity'],
            'dzns' => $request['dzns'],
            'total_dzns' => $request['total_dzns'],
            'rate' => $request['rate'],
            'amount' => $request['amount'],
            'sale_return_master_id' => $saleParentId,
        ];
    }

    /*
     * Save sale data.
     * @param: $data
     * */
    public function saveSaleReturn($data)
    {
        foreach ($data['product_id'] as $key => $value) {
            if (!empty($data['product_id'][$key])) {
                $rec['product_id'] = $data['product_id'][$key];
                $rec['packing_type'] = $data['packing_type'][$key];
                $rec['measurement_type'] = $data['measurement_type'][$key];
                $rec['dzns'] = $data['dzns'][$key];
                $rec['quantity'] = $data['quantity'][$key];
                $rec['rate'] = $data['rate'][$key];
                $rec['amount'] = $data['amount'][$key];
                $rec['total_dzns'] = $data['total_dzns'][$key];
                $rec['sale_return_master_id'] = $data['sale_return_master_id'];
                SaleReturnDetail::create($rec);
            }
        }
    }

    public function prepareStockLedgerData($request, $saleReturnInvoiceParentId)
    {
        $party = CoaDetailAccount::where('id', $request['party_id'])->first("account_name");

        return [
            'product_id' => $request['product_id'],
            'party_title' =>  $party->account_name,
            'date' => Carbon::parse($request['date'])->format('Y-m-d'),
            'document_no' => 'S/R' . '-' . $saleReturnInvoiceParentId,
            'stock_in_bags' =>  config('constants.ZERO'),
            'stock_in_weight' =>  config('constants.ZERO'),
            'stock_in_quantity' =>  $request['quantity'],
            'stock_out_bags' =>  config('constants.ZERO'),
            'stock_out_weight' =>  config('constants.ZERO'),
            'stock_out_quantity' => config('constants.ZERO'),
            'invoice_id' => $saleReturnInvoiceParentId,
            'rate' => $request['rate'],
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
                $rec['stock_in_quantity'] = $data['stock_in_quantity'][$key];
                $rec['stock_out_bags'] = $data['stock_out_bags'];
                $rec['stock_out_weight'] = $data['stock_out_weight'];
                $rec['stock_out_quantity'] = $data['stock_out_quantity'];
                $rec['document_no'] = $data['document_no'];
                $rec['rate'] = $data['rate'][$key];
                $rec['invoice_id'] = $data['invoice_id'];
                $rec['created_by'] = $data['created_by'];
                $rec['updated_by'] = $data['updated_by'];
                StockLedger::create($rec);
            }
        }
    }




    public function prepareAccountDebitData($request, $saleParentId)
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
            'document_number' => 'S/R' . '-' . $saleParentId,
            'rate' => $request['rate'],
            'bilty_no' => $request['bilty_no'],
            'transporter_id' => $request['transporter_id'],
            'total_quantity' => $request['total_dzns'],
            'measurementType' => $request['dzns'],
            'bags' => $request['quantity'],
            'description' => $description . '<br>' . $remarks,
            'credit' => config('constants.ZERO'),
            'debit' => $request['amount'],
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    public function saveDebitAccountData($data)
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
                $rec['debit'] = $data['debit'][$key];
                $rec['credit'] = $data['credit'];
                $rec['created_at'] = now();
                $rec['updated_at'] = now();
                AccountLedger::create($rec);
            }
        }
    }


    public function prepareAccountCreditData($request, $saleParentId)
    {
        $party = CoaDetailAccount::where('id', $request['party_id'])->value('account_name');

        return [
            'date' => Carbon::parse($request['date'])->format('Y-m-d'),
            'invoice_id' => $saleParentId,
            'party_id' =>   $request['party_id'],
            'document_number' => 'S/R' . '-' . $saleParentId,
            'rate' => config('constants.ZERO'),
            'bilty_no' => null,
            'transporter_id' => null,
            'total_quantity' => config('constants.ZERO'),
            'measurementType' => config('constants.ZERO'),
            'bags' => config('constants.ZERO'),
            'description' => 'Sales Return From' . ' ' . $party . '<br>' .  $request['remarks'],
            'debit' => $request['net_amount'],
            'credit' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    // public function saveCreditAccountData($data)
    // {
    //     foreach ($data['party_id'] as $key => $value) {
    //         if (!empty($data['party_id'][$key])) {
    //             $rec['party_id'] = $data['party_id'][$key];
    //             $rec['date'] = $data['date'];
    //             $rec['invoice_id'] = $data['invoice_id'];
    //             $rec['document_number'] = $data['document_number'];
    //             $rec['rate'] = $data['rate'][$key];
    //             $rec['bilty_no'] = $data['bilty_no'];
    //             $rec['transporter_id'] = $data['transporter_id'];
    //             $rec['total_quantity'] = $data['total_quantity'][$key];
    //             $rec['measurementType'] = $data['measurementType'][$key];
    //             $rec['bags'] = $data['bags'][$key];
    //             $rec['description'] = $data['description'];
    //             $rec['debit'] = $data['debit'];
    //             $rec['credit'] = $data['credit'][$key];
    //             $rec['created_at'] = now();
    //             $rec['updated_at'] = now();
    //             AccountLedger::create($rec);
    //         }
    //     }
    // }

    public function prepareCommissionAccountCreditData($request, $saleParentId)
    {

        $partyName = 'Commission On Sales.';
        $party = CoaDetailAccount::where('account_name', $partyName)->value('id');
        $mainPartyName = CoaDetailAccount::where('id', $request['party_id'])->value('account_name');


        return [
            'date' => Carbon::parse($request['date'])->format('Y-m-d'),
            'invoice_id' => $saleParentId,
            'party_id' =>  $party,
            'document_number' => 'S/R' . '-' . $saleParentId,
            'rate' => config('constants.ZERO'),
            'bilty_no' => null,
            'transporter_id' => null,
            'total_quantity' => config('constants.ZERO'),
            'measurementType' => config('constants.ZERO'),
            'bags' => config('constants.ZERO'),
            'description' => 'Commission Of' . ' ' . $mainPartyName . '<br>' .  $request['remarks'],
            'debit' => config('constants.ZERO'),
            'credit' => $request['commission'],
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    public function prepareGeneralJournalCommissionCreditData($request, $saleParentId)
    {

        $partyName = 'Commission On Sales.';
        // $party = CoaDetailAccount::where('id', $request['party_id'])->value('account_name');
        $session = $this->commonService->getSession();
        return [
            'date' => Carbon::parse($request['date'])->format('Y-m-d'),
            'invoice_id' => $saleParentId,
            'document_number' => 'S/R' . '-' . $saleParentId,
            'business_id' => $session->business_id,
            'f_year_id' => $session->financial_year,
            'description' => $partyName,
            'narration' => 'Credit :' . ' ' . $partyName,
            'debit' => config('constants.ZERO'),
            'credit' => $request['commission'],
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    public function prepareGeneralJournalCommissionDebitData($request, $saleParentId)
    {

        $party = CoaDetailAccount::where('id', $request['party_id'])->value('account_name');
        $session = $this->commonService->getSession();
        return [
            'date' => Carbon::parse($request['date'])->format('Y-m-d'),
            'invoice_id' => $saleParentId,
            'document_number' => 'S/R' . '-' . $saleParentId,
            'business_id' => $session->business_id,
            'f_year_id' => $session->financial_year,
            'description' => $party,
            'narration' => 'Credit Commission of:' . ' ' . $party,
            'debit' =>  $request['commission'],
            'credit' => config('constants.ZERO'),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    public function prepareGeneralJournalDiscountCreditData($request, $saleParentId)
    {

        $partyName = 'Discounts on Sales.';
        // $party = CoaDetailAccount::where('id', $request['party_id'])->value('account_name');
        $session = $this->commonService->getSession();
        return [
            'date' => Carbon::parse($request['date'])->format('Y-m-d'),
            'invoice_id' => $saleParentId,
            'document_number' => 'S/R' . '-' . $saleParentId,
            'business_id' => $session->business_id,
            'f_year_id' => $session->financial_year,
            'description' => $partyName,
            'narration' => 'Credit :' . ' ' . $partyName,
            'debit' => config('constants.ZERO'),
            'credit' => $request['scheme'],
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    public function prepareGeneralJournalDiscountDebitData($request, $saleParentId)
    {

        $party = CoaDetailAccount::where('id', $request['party_id'])->value('account_name');
        $session = $this->commonService->getSession();
        return [
            'date' => Carbon::parse($request['date'])->format('Y-m-d'),
            'invoice_id' => $saleParentId,
            'document_number' => 'S/R' . '-' . $saleParentId,
            'business_id' => $session->business_id,
            'f_year_id' => $session->financial_year,
            'description' => $party,
            'narration' => 'Credit Commission of:' . ' ' . $party,
            'debit' =>  $request['scheme'],
            'credit' => config('constants.ZERO'),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    public function prepareCommissionAccountDebitData($request, $saleParentId)
    {

        $party = CoaDetailAccount::where('id', $request['party_id'])->value('account_name');

        return [
            'date' => Carbon::parse($request['date'])->format('Y-m-d'),
            'invoice_id' => $saleParentId,
            'party_id' =>  $request['party_id'],
            'document_number' => 'S/R' . '-' . $saleParentId,
            'rate' => config('constants.ZERO'),
            'bilty_no' => null,
            'transporter_id' => null,
            'total_quantity' => config('constants.ZERO'),
            'measurementType' => config('constants.ZERO'),
            'bags' => config('constants.ZERO'),
            'description' => 'Commission Of' . ' ' . $party . '<br>' .  $request['remarks'],
            'debit' => $request['commission'],
            'credit' => config('constants.ZERO'),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    public function prepareDiscountAccountCreditData($request, $saleParentId)
    {

        $mainPartyName = CoaDetailAccount::where('id', $request['party_id'])->value('account_name');
        $partyName = 'Discounts on Sales.';
        $party = CoaDetailAccount::where('account_name', $partyName)->value('id');


        return [
            'date' => Carbon::parse($request['date'])->format('Y-m-d'),
            'invoice_id' => $saleParentId,
            'party_id' =>   $party,
            'document_number' => 'S/R' . '-' . $saleParentId,
            'rate' => config('constants.ZERO'),
            'bilty_no' => null,
            'transporter_id' => null,
            'total_quantity' => config('constants.ZERO'),
            'measurementType' => config('constants.ZERO'),
            'bags' => config('constants.ZERO'),
            'description' => 'Discount  to ' . ' ' . $mainPartyName . '<br>' .  $request['remarks'],
            'debit' => config('constants.ZERO'),
            'credit' => $request['scheme'],
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    public function prepareDiscountAccountDebitData($request, $saleParentId)
    {
        $party = CoaDetailAccount::where('id', $request['party_id'])->value('account_name');

        return [
            'date' => Carbon::parse($request['date'])->format('Y-m-d'),
            'invoice_id' => $saleParentId,
            'party_id' =>  $request['party_id'],
            'document_number' => 'S/R' . '-' . $saleParentId,
            'rate' => config('constants.ZERO'),
            'bilty_no' => null,
            'transporter_id' => null,
            'total_quantity' => config('constants.ZERO'),
            'measurementType' => config('constants.ZERO'),
            'bags' => config('constants.ZERO'),
            'description' => 'Discount To' . ' ' . $party . '<br>' .  $request['remarks'],
            'debit' => $request['scheme'],
            'credit' => config('constants.ZERO'),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }



    public function prepareGeneralJournalCreditData($request, $saleParentId)
    {

        $party = CoaDetailAccount::where('id', $request['party_id'])->value('account_name');
        $session = $this->commonService->getSession();
        return [
            'date' => Carbon::parse($request['date'])->format('Y-m-d'),
            'invoice_id' => $saleParentId,
            'document_number' => 'S/R' . '-' . $saleParentId,
            'business_id' => $session->business_id,
            'f_year_id' => $session->financial_year,
            'description' => $party,
            'narration' => 'Credit Sale Of:' . ' ' . $party,
            'debit' => config('constants.ZERO'),
            'credit' => $request['gross_amount'],
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    public function prepareGeneralJournalDebitData($request, $saleParentId)
    {

        $productArray = $request['product_id'];
        $product = CoaInventoryDetailAccount::whereIn('id', $productArray)->pluck('name')->toarray();
        $party = CoaDetailAccount::whereIn('account_name', $product)->pluck('id');
        $session = $this->commonService->getSession();
        $partyName = CoaDetailAccount::where('id', $request['party_id'])->value('account_name');
        return [
            'date' => Carbon::parse($request['date'])->format('Y-m-d'),
            'invoice_id' => $saleParentId,
            'document_number' => 'S/R' . '-' . $saleParentId,
            'business_id' => $session->business_id,
            'f_year_id' => $session->financial_year,
            'description' => $product,
            'narration' => 'Debit Sale Of:' . ' ' . $partyName,
            'debit' => $request['amount'],
            'credit' =>  config('constants.ZERO'),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    public function saveGeneralJournalDebitData($data)
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
                $rec['debit'] = $data['debit'][$key];
                $rec['credit'] = $data['credit'];
                $rec['created_at'] = now();
                $rec['updated_at'] = now();
                GeneralJournal::create($rec);
            }
        }
    }
}
