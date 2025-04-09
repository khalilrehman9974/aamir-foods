<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\StockLedger;
use App\Models\Transporter;
use App\Models\AccountLedger;
use App\Models\CoaDetailAccount;
use App\Models\PurchaseReturnDetail;
use App\Models\PurchaseReturnMaster;
use Illuminate\Support\Facades\Auth;
use App\Models\CoaInventoryDetailAccount;

class PurchaseReturnService
{
    const PER_PAGE = 10;
    const PURCHASE_RETURN_TRANSACTION_TYPE = 'purchase_return';
    const PURCHASE_RETURN_DESCRIPTION = 'Purchased Return Products';

    protected $commonService;

    public function __construct(CommonService $commonService)
    {
        $this->commonService = $commonService;
    }


    public function DropDownData()
    {
        $result = [
            'products' => CoaInventoryDetailAccount::pluck('name','id'),
            // 'saleMans' => SaleMan::pluck('name','id'),
            // 'areas' => Area::pluck('name','id'),
            'parties' => CoaDetailAccount::pluck('account_name','id'),
            'transporters' => Transporter::pluck('name','id'),
        ];

        return $result;
    }

    /*
     * Get contract by id.
     * @param $id
     * */
    public function getpurchaseReturnMasterById($id)
    {
        return PurchaseReturnMaster::leftjoin('parties', 'parties.id', '=', 'purchase_return_masters.party_id')
            ->select(
                'purchase_return_masters.id as id',
                'purchase_return_masters.date',
                'purchase_return_masters.grn_no',
                'purchase_return_masters.type',
                'purchase_return_masters.bill_no',
                'purchase_return_masters.transporter_id',
                'purchase_return_masters.total_amount',
                'purchase_return_masters.fare',
                'purchase_return_masters.carriage_inward',
                'purchase_return_masters.remarks',
                'purchase_return_masters.created_at',
                'purchase_return_masters.updated_at',
                'parties.name as partyName'
            )
            ->where('purchase_return_masters.id', $id)
            ->first();
    }

    /*
    * Get contract by id.
    * @param $id
    * */
    public function getPurchaseReturnDetailById($id)
    {
        return PurchaseReturnDetail::leftjoin('purchases', 'purchase_details.product_id', '=', 'purchases.id')
            ->select('purchases.name as purchaseName', 'purchase_details.unit', 'purchase_details.quantity', 'purchase_details.amount', 'purchase_details.rate', 'purchase_details.total_unit')
            ->where('purchase_details.purchase_return_master_id', $id)
            ->get();
    }

    /*
     * Search Purchase record.
     * @queries: $queries
     * @return: object
     * */
    public function searchPurchaseReturn($request)
    {

        $q = PurchaseReturnMaster::query();

        if (!empty($request['date'])) {
            $formattedDate = date('Y-m-d', strtotime($request['date']));
            $q->where('date', $formattedDate);
        } elseif (!empty($request['party_id'])) {
            $q->where('party_id', $request['party_id']);
        }

        $pRorders = $q->with( 'party','transporter')->orderBy('id', 'DESC')->paginate(config('constants.PER_PAGE'));

        return $pRorders;
    }

    /*
     * Prepare Purchase master data.
     * @param: $request
     * @return Array
     * */
    public function preparePurchaseReturnMasterData($request)
    {
        $session = $this->commonService->getSession();
        return [
            'purchase_invoice_no' => $request['purchase_invoice_no'],
            'purchase_order_no' => $request['purchase_order_no'],
            'date' => Carbon::parse($request['date'])->format('Y-m-d'),
            'party_id' => $request['party_id'],
            'transporter_id' => $request['transporter_id'],
            'supplier_bill_no' => $request['supplier_bill_no'],
            'unloaded_by' => $request['unloaded_by'],
            'business_id' => $session->business_id,
            'f_year_id' => $session->financial_year,
            'remarks' => $request['remarks'],
            'gross_bill' => $request['gross_bill'],
            'carriage' => $request['carriage'],
            'total_quantity' => $request['total_quantity'],
            'tax' => $request['tax'],
            'net_amount' => $request['net_amount'],
            'created_by' => Auth::user()->id,
            'updated_by' => Auth::user()->id
        ];
    }

    /*
     * Prepare Purchase detail data.
     * @param: $request
     * @return Array
     * */
    public function preparePurchaseReturnDetailData($request, $purchaseParentId)
    {
        return [
            'product_id' => $request['product_id'],
            'packing_type' => $request['packing_type'],
            'measurement_type' => $request['measurement_type'],
            'size' => $request['size'],
            'bags' => $request['bags'],
            'measurementType' => $request['measurementType'],
            'quantity' => $request['quantity'],
            'price' => $request['price'],
            'amount' => $request['amount'],
            'purchase_return_master_id' => $purchaseParentId,

        ];
    }

    /*
     * Save purchase data.
     * @param: $data
     * */
    public function savePurchaseReturn($data)
    {
        foreach ($data['product_id'] as $key => $value) {
            if (!empty($data['product_id'][$key])) {
                $rec['product_id'] = $data['product_id'][$key];
                $rec['packing_type'] = $data['packing_type'][$key];
                $rec['measurement_type'] = $data['measurement_type'][$key];
                $rec['size'] = $data['size'][$key];
                $rec['bags'] = $data['bags'][$key];
                $rec['measurementType'] = $data['measurementType'][$key];
                $rec['quantity'] = $data['quantity'][$key];
                $rec['price'] = $data['price'][$key];
                $rec['amount'] = $data['amount'][$key];
                $rec['purchase_return_master_id'] = $data['purchase_return_master_id'];
                PurchaseReturnDetail::create($rec);
            }
        }
    }

    public function prepareStockLedgerData($request, $purchaseReturnInvoiceParentId)
    {
        $party = CoaDetailAccount::where('id', $request['party_id'])->first("account_name");

        return [
            'product_id' => $request['product_id'],
            'party_title' =>  $party->account_name,
            'date' => Carbon::parse($request['date'])->format('Y-m-d'),
            'document_no' => 'P/R/I' . '-' . $purchaseReturnInvoiceParentId,
            'stock_in_quantity' =>  config('constants.ZERO'),
            'stock_in_bags' =>  config('constants.ZERO'),
            'stock_in_weight' =>  config('constants.ZERO'),
            'stock_out_bags' => !empty($request['bags']) ? $request['bags'] : 0,
            'stock_out_weight' => !empty($request['measurementType']) ? $request['measurementType'] : 0,
            'stock_out_quantity' => $request['quantity'],
            'invoice_id' => $purchaseReturnInvoiceParentId,
            'rate' => $request['price'],
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
        // dd($data);
        foreach ($data['product_id'] as $key => $value) {
            if (!empty($data['product_id'][$key])) {
                $rec['product_id'] = $data['product_id'][$key];
                $rec['date'] = $data['date'];
                $rec['party_title'] = $data['party_title'];
                $rec['stock_in_bags'] = $data['stock_in_bags'];
                $rec['stock_in_weight'] = $data['stock_in_weight'];
                $rec['stock_in_quantity'] = $data['stock_in_quantity'];
                $rec['stock_out_bags'] = $data['stock_out_bags'][$key] ?? config('constants.ZERO');
                $rec['stock_out_weight'] = $data['stock_out_weight'][$key] ?? config('constants.ZERO');
                $rec['stock_out_quantity'] = $data['stock_out_quantity'][$key];
                $rec['document_no'] = $data['document_no'];
                $rec['rate'] = $data['rate'][$key];
                $rec['invoice_id'] = $data['invoice_id'];
                $rec['created_by'] = $data['created_by'];
                $rec['updated_by'] = $data['updated_by'];
                StockLedger::create($rec);
            }
        }
    }


    public function prepareAccountDebitData($request, $purchaseParentId)
    {

        $party = CoaDetailAccount::where('id', $request['party_id'])->value('account_name');

        return [
           'date' => Carbon::parse($request['date'])->format('Y-m-d'),
            'invoice_id' => $purchaseParentId,
            'party_id' =>  $request['party_id'],
            'document_number' => 'P/R/I' . '-' . $purchaseParentId,
            'rate' => config('constants.ZERO'),
            'bilty_no' => null,
            'transporter_id' => null,
            'total_quantity' => config('constants.ZERO'),
            'measurementType' => config('constants.ZERO'),
            'bags' => config('constants.ZERO'),
            'description' => 'Purchase Return to'. ' ' . $party . '<br>' .  $request['remarks'],
            'debit' => config('constants.ZERO'),
            'credit' =>  $request['gross_bill'],
            'created_at' => now(),
            'updated_at' => now() ,
        ];
    }

    public function prepareAccountCreditData($request, $purchaseParentId)
    {

        $remarks = $request['remarks'];
        $description = 'Entry Through Product';
        $productArray = $request['product_id'];
        $product = CoaInventoryDetailAccount::whereIn('id', $productArray)->pluck('name')->toarray();
        $partyId = CoaDetailAccount::whereIn('account_name', $product)->pluck('id');

        return [
            'date' => Carbon::parse($request['date'])->format('Y-m-d'),
            'invoice_id' => $purchaseParentId,
            'party_id' =>  $partyId,
            'document_number' => 'P/R/I' . '-' . $purchaseParentId,
            'rate' =>$request['price'],
            'bilty_no' => $request['supplier_bill_no'],
            'transporter_id' => $request['transporter_id'],
            'total_quantity' => $request['quantity'],
            'measurementType' => $request['measurementType'],
            'bags' => $request['bags'],
            'description' => $description . '<br>' .$remarks ,
            'debit' => $request['amount'],
            'credit' => config('constants.ZERO'),
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
                $rec['debit'] = $data['debit'][$key];
                $rec['credit'] = $data['credit'];
                $rec['created_at'] = now();
                $rec['updated_at'] = now();
                AccountLedger::create($rec);
            }
        }
    }



    public function prepareCarriageAccountDebitData($request, $purchaseParentId)
    {

        $partyName = 'Carriage Inwards / Builty Exp';
        $party = CoaDetailAccount::where('account_name', $partyName)->value('id');
        $mainPartyName = CoaDetailAccount::where('id', $request['party_id'])->value('account_name');

        return [
            'date' => Carbon::parse($request['date'])->format('Y-m-d'),
            'invoice_id' => $purchaseParentId,
            'party_id' =>  $party,
            'document_number' => 'P/R/I' . '-' . $purchaseParentId,
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
            'updated_at' => now(),
        ];
    }

    public function prepareCarriageAccountCreditData($request, $purchaseParentId)
    {

        $party = CoaDetailAccount::where('id', $request['party_id'])->value('account_name');

        return [
            'date' => Carbon::parse($request['date'])->format('Y-m-d'),
            'invoice_id' => $purchaseParentId,
            'party_id' =>  $request['party_id'],
            'document_number' => 'P/R/I' . '-' . $purchaseParentId,
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

    public function prepareTaxAccountDebitData($request, $purchaseParentId)
    {

        $partyName = 'Tax Paid .';
        $party = CoaDetailAccount::where('account_name', $partyName)->value('id');
        $mainPartyName = CoaDetailAccount::where('id', $request['party_id'])->value('account_name');

        return [
            'date' => Carbon::parse($request['date'])->format('Y-m-d'),
            'invoice_id' => $purchaseParentId,
            'party_id' =>  $party,
            'document_number' => 'P/R/I' . '-' . $purchaseParentId,
            'rate' => config('constants.ZERO'),
            'bilty_no' => null,
            'transporter_id' => null,
            'total_quantity' => config('constants.ZERO'),
            'measurementType' => config('constants.ZERO'),
            'bags' => config('constants.ZERO'),
            'description' => 'Tax '. ' ' . $mainPartyName . '<br>' .  $request['remarks'],
            'debit' => $request['tax'],
            'credit' => config('constants.ZERO'),
            'created_at' => now(),
            'updated_at' => now() ,
        ];
    }

    public function prepareTaxAccountCreditData($request, $purchaseParentId)
    {

        $party = CoaDetailAccount::where('id', $request['party_id'])->value('account_name');

        return [
            'date' => Carbon::parse($request['date'])->format('Y-m-d'),
            'invoice_id' => $purchaseParentId,
            'party_id' =>  $request['party_id'],
            'document_number' => 'P/R/I' . '-' . $purchaseParentId,
            'rate' => config('constants.ZERO'),
            'bilty_no' => null,
            'transporter_id' => null,
            'total_quantity' => config('constants.ZERO'),
            'measurementType' => config('constants.ZERO'),
            'bags' => config('constants.ZERO'),
            'description' => 'Return Tax of'. ' ' . $party . '<br>' .  $request['remarks'],
            'debit' => config('constants.ZERO'),
            'credit' => $request['tax'],
            'created_at' => now(),
            'updated_at' => now() ,
        ];
    }
}
