<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\StockLedger;
use App\Models\Transporter;
use App\Models\PurchaseDetail;
use App\Models\PurchaseMaster;
use App\Models\CoaDetailAccount;
use Illuminate\Support\Facades\Auth;
use App\Models\CoaInventoryDetailAccount;
use PragmaRX\Google2FA\Support\Constants;

class PurchaseService
{
    const PER_PAGE = 10;
    const PURCHASE_TRANSACTION_TYPE = 'purchase';
    const PURCHASE_DESCRIPTION = 'Purchased products';

    protected $commonService;

    public function __construct(CommonService $commonService)
    {
        $this->commonService = $commonService;
    }

    /*
     * Get contract by id.
     * @param $id
     * */
    public function getpurchaseMasterById($id)
    {
        return PurchaseMaster::leftjoin('parties', 'parties.id', '=', 'purchase_masters.party_id')
            ->select(
                'purchase_masters.id as id',
                'purchase_masters.date',
                'purchase_masters.grn_no',
                'purchase_masters.type',
                'purchase_masters.bill_no',
                'purchase_masters.transporter_id',
                'purchase_masters.total_amount',
                'purchase_masters.fare',
                'purchase_masters.carriage_inward',
                'purchase_masters.remarks',
                'parties.name as partyName'
            )
            ->where('purchase_masters.id', $id)
            ->first();
    }

    public function DropDownData()
    {
        $result = [
            'parties' => CoaDetailAccount::pluck('account_name', 'id'),
            'transporters' => Transporter::pluck('name', 'id'),
            'products' => CoaInventoryDetailAccount::pluck('name', 'id'),
        ];

        return $result;
    }

    /*
    * Get contract by id.
    * @param $id
    * */
    public function getPurchaseDetailById($id)
    {
        return PurchaseDetail::leftjoin('purchases', 'purchase_details.product_id', '=', 'purchases.id')
            ->select('purchases.name as purchaseName', 'purchase_details.unit', 'purchase_details.quantity', 'purchase_details.amount', 'purchase_details.rate', 'purchase_details.total_unit')
            ->where('purchase_details.purchase_master_id', $id)
            ->get();
    }

    public function search($request)
    {


        $q = PurchaseMaster::query();

        if (!empty($request['date'])) {
            $formattedDate = date('Y-m-d', strtotime($request['date']));
            $q->where('date', $formattedDate);
        } elseif (!empty($request['party_id'])) {
            $q->where('party_id', $request['party_id']);
        }



        // $q = PurchaseMaster::query();
        // if (!empty($request['param'])) {
        //     $q = PurchaseMaster::with('type','party','transporter')->where('grn_no', 'like', '%' . $request['param'] . '%');
        // }
        $purchases = $q->with('party', 'transporter')->orderBy('id', 'DESC')->paginate(config('constants.PER_PAGE'));

        return $purchases;
    }

    /*
     * Prepare Purchase master data.
     * @param: $request
     * @return Array
     * */
    public function preparePurchaseMasterData($request)
    {
        $session = $this->commonService->getSession();
        return [
            'grn_no' => $request['grn_no'],
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
    public function preparePurchaseDetailData($request, $purchaseParentId)
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
            'purchase_master_id' => $purchaseParentId,

        ];
    }

    /*
     * Save purchase data.
     * @param: $data
     * */
    public function savePurchase($data)
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
                $rec['purchase_master_id'] = $data['purchase_master_id'];
                PurchaseDetail::create($rec);
            }
        }
    }

    public function prepareStockLedgerData($request, $purchaseInvoiceParentId)
    {
        $party = CoaDetailAccount::where('id', $request['party_id'])->first("account_name");

        return [
            'product_id' => $request['product_id'],
            'party_title' =>  $party->account_name,
            'date' => Carbon::parse($request['date'])->format('Y-m-d'),
            'document_no' => 'P/I' . '-' . $purchaseInvoiceParentId,
            'stock_in_bags' => !empty($request['bags']) ? $request['bags'] : 0,
            'stock_in_weight' => !empty($request['measurementType']) ? $request['measurementType'] : 0,
            'stock_in_quantity' =>  $request['quantity'],
            'stock_out_bags' =>  config('constants.ZERO'),
            'stock_out_weight' =>  config('constants.ZERO'),
            'stock_out_quantity' => config('constants.ZERO'),
            'invoice_id' => $purchaseInvoiceParentId,
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
                $rec['stock_in_bags'] = $data['stock_in_bags'][$key] ?? config('constants.ZERO');
                $rec['stock_in_weight'] = $data['stock_in_weight'][$key] ?? config('constants.ZERO');
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
}
