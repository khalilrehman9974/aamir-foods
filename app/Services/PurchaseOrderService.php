<?php

namespace App\Services;

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
            'products' => CoaInventoryDetailAccount::pluck('name','id')
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
             $q = PurchaseOrderMaster::where('name', 'like', '%' . $request['param'] . '%');
         }
         $porders = $q->orderBy('name', 'ASC')->paginate(config('constants.PER_PAGE'));

         return $porders;
     }



    /*
     * Prepare POrder master data.
     * @param: $request
     * @return Array
     * */
    public function preparePOrderMasterData($request)
    {

        return [
            'Name' => $request['Name'],
            'company_name' => $request['company_name'],
            'date' => $request['date'],
            'address' => $request['address'],
            'remarks' => $request['remarks'],
            'grand_total' => $request['grand_total'],
            $data['created_by'] = Auth::user()->id,
            $data['updated_by'] = Auth::user()->id
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
            'total_quantity' => $request['total_quantity'],
            'Schedule_date' => $request['Schedule_date'],
            'Schedule_quantity' => $request['Schedule_quantity'],
            'Delivery_date' => $request['Delivery_date'],
            'Delivery_quantity' => $request['Delivery_quantity'],
            'price' => $request['price'],
            $data['created_by'] = Auth::user()->id,
            $data['updated_by'] = Auth::user()->id,
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
                $rec['total_quantity'] = $data['total_quantity'][$key];
                $rec['Schedule_date'] = $data['Schedule_date'][$key];
                $rec['Schedule_quantity'] = $data['Schedule_quantity'][$key];
                $rec['Delivery_date'] = $data['Delivery_date'][$key];
                $rec['Delivery_quantity'] = $data['Delivery_quantity'][$key];
                $rec['price'] = $data['price'][$key];
                $rec['created_by'] = Auth::user()->id;
                $rec['updated_by'] = Auth::user()->id;
                $rec['purchase_order_master_id'] = $data['purchase_order_master_id'];
                PurchaseOrderDetail::create($rec);
            }
        }
    }


}
