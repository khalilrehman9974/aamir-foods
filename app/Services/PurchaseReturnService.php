<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\Transporter;
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
                'parties.name as partyName',
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
        if (!empty($request['param'])) {
            $q = PurchaseReturnMaster::with( 'party','transporter')
                ->orwhere('date', 'like', '%' . $request['param'] . '%')
                ->orwhere('bill_no', 'like', '%' . $request['param'] . '%');
        }
        $pRorders = $q->orderBy('id', 'ASC')->paginate(config('constants.PER_PAGE'));

        return $pRorders;
    }

    // /*
    //  * Get list of products for selected category and brand.
    //  * @param: $request
    //  * @return Array
    //  * */
    // public function getProductsByCategoryBrand($request)
    // {
    //     return Product::where('brand_id', $request['brandCode'])->get();
    // }

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

    public function prepareAccountCreditData($request, $purchaseParentId, $dataType, $description)
    {
        return [
                'invoice_id' => $purchaseParentId,
            'account_id' => 'PR-00000001',
            'description' => $description . ' '. $purchaseParentId, $dataType,
            'debit' => 0,
            'credit' => $request['totalAmount'],

        ];
    }

    public function prepareAccountDebitData($request, $purchaseParentId, $dataType, $description)
    {
        return [
            'date' => Carbon::parse($request['date'])->format('Y-m-d'),
            'invoice_id' => $purchaseParentId,
            'account_id' => $request['party_id'],
            'description' => $description . ' '. $purchaseParentId, $dataType,
            'debit' => $request['totalAmount'],
            'credit' => 0,

        ];
    }
}
