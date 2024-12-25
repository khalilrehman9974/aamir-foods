<?php

namespace App\Services;

use Illuminate\Support\Carbon;
use App\Models\CoaDetailAccount;
use App\Models\PurchaseOrderDetail;
use App\Models\PurchaseOrderMaster;
use Illuminate\Support\Facades\Auth;
use App\Models\CoaInventoryDetailAccount;

class PurchaseOrderService
{
    const PER_PAGE = 10;

    protected $commonService;

    public function __construct(CommonService $commonService)
    {
        $this->commonService = $commonService;
    }

    public function findUpdateOrCreate($model, array $where, array $data)
    {
        $object = $model::firstOrNew($where);

        foreach ($data as $property => $value) {
            $object->{$property} = $value;
        }
        $object->save();

        return $object;
    }

    /*
     * Get contract by id.
     * @param $id
     * */
    public function getPOrderMasterById($id)
    {
        return PurchaseOrderMaster::select(
                'purchase_order_masters.id ',
                'purchase_order_masters.Name',
                'purchase_order_masters.company_name',
                'purchase_order_masters.date',
                'purchase_order_masters.address',
                'purchase_order_masters.remarks',
                'purchase_order_masters.created_by',
                'purchase_order_masters.grand_total',
                'purchase_order_masters.updated_by',
            )
            ->where('purchase_order_masters.id', $id)
            ->first();
    }


    public function DropDownData()
    {
        $result = [
            'products' => CoaInventoryDetailAccount::pluck('name','id'),
            'parties' => CoaDetailAccount::pluck('account_name','account_code')
        ];

        return $result;
    }

    // /*
    // * Get contract by id.
    // * @param $id
    // * */
    // public function getPOrderDetailById($id)
    // {
    //     return PurchaseOrderDetail::leftjoin('salemans', 'sale_details.product_id', '=', 'salemans.id')
    //         ->select('salemans.name as salemanName', 'sale_details.unit', 'sale_details.quantity', 'sale_details.amount', 'sale_details.rate', 'sale_details.total_unit')
    //         ->where('sale_details.sale_master_id', $id)
    //         ->get();
    // }


    /*
     * Search sale record.
     * @queries: $queries
     * @return: object
     * */
     public function searchPOrder($request)
     {
         $q = PurchaseOrderMaster::query();
         if (!empty($request['param'])) {
             $q = PurchaseOrderMaster::where('contact_person', 'like', '%' . $request['param'] . '%');
         }
         $porders = $q->orderBy('id', 'DESC')->paginate(config('constants.PER_PAGE'));

         return $porders;
     }



    /*
     * Prepare POrder master data.
     * @param: $request
     * @return Array
     * */
    public function preparePOrderMasterData($request)
    {
        $session = $this->commonService->getSession();
        return [
            'date' => Carbon::parse($request['date'])->format('Y-m-d'),
            'party_id' => $request['party_id'],
            'contact_person' => $request['contact_person'],
            'status' => $request['status'],
            'business_id' => $session->business_id,
            'f_year_id' => $session->financial_year,
            'remarks' => $request['remarks'],
            'gross_total' => $request['gross_total'],
            'tax_amount' => $request['tax_amount'],
            'shipping_amount' => $request['shipping_amount'],
            'other_amount' => $request['other_amount'],
            'total_amount' => $request['total_amount'],
            'created_by' => Auth::user()->id,
            'updated_by' => Auth::user()->id
        ];
    }

    /*
     * Prepare POrder detail data.
     * @param: $request
     * @return Array
     * */
    public function preparePOrderDetailData($request, $purchaseOrderParentId)
    {
        return [
            'product_id' => $request['product_id'],
            'packing_type' => $request['packing_type'],
            'measurement_type' => $request['measurement_type'],
            'size' => $request['size'],
            'quantity' => $request['quantity'],
            'price' => $request['price'],
            'amount' => $request['amount'],
            'detail_remarks' => $request['detail_remarks'],
            'purchase_order_master_id' => $purchaseOrderParentId,
        ];
    }

    /*
     * Save POrder data.
     * @param: $data
     * */
    public function savePOrder($data)
    {
        foreach ($data['product_id'] as $key => $value) {
            if (!empty($data['product_id'][$key])) {
                $rec['product_id'] = $data['product_id'][$key];
                $rec['packing_type'] = $data['packing_type'][$key];
                $rec['measurement_type'] = $data['measurement_type'][$key];
                $rec['size'] = $data['size'][$key];
                $rec['quantity'] = $data['quantity'][$key];
                $rec['price'] = $data['price'][$key];
                $rec['amount'] = $data['amount'][$key];
                $rec['detail_remarks'] = $data['detail_remarks'][$key];
                $rec['purchase_order_master_id'] = $data['purchase_order_master_id'];
                PurchaseOrderDetail::create($rec);
            }
        }
    }


}
