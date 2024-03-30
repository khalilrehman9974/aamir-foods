<?php

namespace App\Services;

use App\Models\PurchaseReturnDetail;
use App\Models\PurchaseReturnMaster;
use Illuminate\Support\Facades\Auth;

class PurchaseReturnService
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
    public function getpurchaseReturnMasterById($id)
    {
        return PurchaseReturnMaster::leftjoin('parties', 'parties.id', '=', 'purchase_return_masters.party_id')
            ->select(
                'purchase_return_masters.id as id',
                'purchase_return_masters.date',
                'purchase_return_masters.grn_no',
                'purchase_return_masters.type_id',
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
            ->where('purchase_details.purchase_master_id', $id)
            ->get();
    }

    /*
     * Search Purchase record.
     * @queries: $queries
     * @return: object
     * */
    public function searchPurchaseReturn($request)
    {
        $query = PurchaseReturnMaster::groupBy(
            'purchase_return_masters.id',
            'purchase_return_masters.date',
            'purchase_return_masters.grn_no',
            'purchase_return_masters.type_id',
            'purchase_return_masters.party_id',
            'purchase_return_masters.bill_no',
            'purchase_return_masters.remarks',
            'purchase_return_masters.created_at',
            'purchase_return_masters.updated_at',
            'purchase_return_masters.transporter_id',
            'purchase_return_masters.total_amount',
            'purchase_return_masters.fare',
            'purchase_return_masters.carriage_inward',
        );
        if (!empty($request['param'])) {
            $query = $query->where('purchase_return_masters.id', "=", $request['param']);
            //            $query = $query->orwhere('parties.name', "% like %", $request['param']);
        }
        //        $query->select('purchase_return_masters.id','purchase_return_masters.date','purchase_return_masters.amount','purchase_return_masters.quantity');
        $purchases = $query->orderBy('id', 'DESC')->get();

        return $this->commonService->paginate($purchases, Self::PER_PAGE);
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
        return [
            'grn_no' => $request['grn_no'],
            'date' => $request['date'],
            'type_id' => $request['type_id'],
            'party_id' => $request['party_id'],
            'bill_no' => $request['bill_no'],
            'transporter_id' => $request['transporter_id'],
            'business_id' => $request['business_id'],
            'f_year_id' => $request['f_year_id'],
            'remarks' => $request['remarks'],
            'total_amount' => array_sum($request['total_amount']),
            'fare' => $request['fare'],
            'carriage_inward' => $request['carriage_inward'],
            $data['created_by'] = Auth::user()->id,
            $data['updated_by'] = Auth::user()->id
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
            'quantity' => $request['quantity'],
            'unit' => $request['unit'],
            'total_unit' => $request['total_unit'],
            'rate' => $request['rate'],
            'amount' => $request['amount'],
            'purchase_master_id' => $purchaseParentId,
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
                $rec['unit'] = $data['unit'][$key];
                $rec['quantity'] = $data['quantity'][$key];
                $rec['rate'] = $data['rate'][$key];
                $rec['amount'] = $data['amount'][$key];
                $rec['total_unit'] = $data['total_unit'][$key];
                $rec['purchase_master_id'] = $data['purchase_master_id'];
                PurchaseReturnDetail::create($rec);
            }
        }
    }

    // public function prepareAccountCreditData($request, $saleParentId, $dataType, $description)
    // {
    //     return [
    //         'date' => Carbon::parse($request['date'])->format('Y-m-d'),
    //         'invoice_id' => $saleParentId,
    //         'account_id' => 'S-00000001',
    //         'description' => $description . ' '. $saleParentId,
    //         'transaction_type' => $dataType,
    //         'debit' => 0,
    //         'credit' => $request['totalAmount'],
    //     ];
    // }

    // public function prepareAccountDebitData($request, $saleParentId, $dataType, $description)
    // {
    //     return [
    //         'date' => Carbon::parse($request['date'])->format('Y-m-d'),
    //         'invoice_id' => $saleParentId,
    //         'account_id' => $request['customer_id'],
    //         'description' => $description . ' '. $saleParentId,
    //         'transaction_type' => $dataType,
    //         'debit' => $request['totalAmount'],
    //         'credit' => 0,
    //     ];
    // }
}
