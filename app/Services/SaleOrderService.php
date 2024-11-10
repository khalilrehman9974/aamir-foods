<?php

namespace App\Services;

use Illuminate\Support\Carbon;
use App\Models\SaleMan;
use App\Models\SaleOrder;
use App\Models\Transporter;
use App\Models\SaleOrderDetail;
use App\Models\CoaDetailAccount;
use Illuminate\Support\Facades\Auth;
use App\Models\CoaInventoryDetailAccount;

class SaleOrderService
{
    protected $commonService;

    public function __construct(CommonService $commonService)
    {
        $this->commonService = $commonService;
    }

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
     * Get contract by id.
     * @param $id
     * */
    public function getSaleOrderMasterById($id)
    {
        return SaleOrder::leftjoin('parties', 'parties.id', '=', 'sale_order_masters.party_id')
            ->leftjoin('salemans', 'salemans.id', '=', 'sale_order_masters.saleman_id')
            ->select(
                'sale_order_masters.id as id',
                'sale_order_masters.date',
                'sale_order_masters.bilty_no',
                'sale_order_masters.remarks',
                'sale_order_masters.created_at',
                'sale_order_masters.deliverd_to',
                'sale_order_masters.updated_at',
                'sale_order_masters.transporter_id',
                'sale_order_masters.total_amount',
                'sale_order_masters.freight',
                'sale_order_masters.scheme',
                'sale_order_masters.commission',
                'parties.name as partyName',
                'salemans.id as salemanId'
            )
            ->where('sale_order_masters.id', $id)
            ->first();
    }


    /*
     * Search sale record.
     * @queries: $queries
     * @return: object
     * */

     public function searchSale($request)
    {
        $q = SaleOrder::query();
        if (!empty($request['param'])) {
            $q = SaleOrder::with('party')

            ->Where('party', 'like', '%' . $request['param'] . '%');

        }
        $saleOrders = $q->orderBy('party_id', 'ASC')->paginate(config('constants.PER_PAGE'));

        return $saleOrders;
    }


    /*
     * Prepare sale master data.
     * @param: $request
     * @return Array
     * */
    public function prepareSaleOrderMasterData($request)
    {
        $session = $this->commonService->getSession();
        return [
            // 'date' => $request['date'],
            'date' => Carbon::parse($request['date'])->format('Y-m-d'),
            'party_id' => $request['party_id'],
            'business_id' => $session->business_id,
            'f_year_id' => $session->financial_year,
            'remarks' => $request['remarks'],
            'total_amount' => $request['total_amount'],
            'created_by'=> Auth::user()->id,
            'updated_by' => Auth::user()->id
        ];
    }

    public function DropDownData()
    {
        $result = [
            'parties' => CoaDetailAccount::pluck('account_name','id'),
            'saleMans' => SaleMan::pluck('name','id'),
            'transporters' => Transporter::pluck('name','id'),
            'products' => CoaInventoryDetailAccount::pluck('name','id'),

        ];

        return $result;
    }

    /*
     * Prepare sale detail data.
     * @param: $request
     * @return Array
     * */
    public function prepareSaleOrderDetailData($request, $saleOrderParentId)
    {
        return [
            'product_id' => $request['product_id'],
            'quantity' => $request['quantity'],
            'dzn' => $request['dzn'],
            'total_dzn' => $request['total_dzn'],
            'rate' => $request['rate'],
            'amount' => $request['amount'],
            'created_by'=> Auth::user()->id,
            'updated_by' => Auth::user()->id,
            'sale_order_master_id' => $saleOrderParentId,
        ];
    }

    /*
     * Save sale data.
     * @param: $data
     * */
    public function saveSaleOrder($data)
    {
        foreach ($data['product_id'] as $key => $value) {
            if (!empty($data['product_id'][$key])) {
                $rec['product_id'] = $data['product_id'][$key];
                $rec['quantity'] = $data['quantity'][$key];
                $rec['dzn'] = $data['dzn'][$key];
                $rec['total_dzn'] = $data['total_dzn'][$key];
                $rec['rate'] = $data['rate'][$key];
                $rec['amount'] = $data['amount'][$key];
                $rec['created_by'] = Auth::user()->id;
                $rec['updated_by'] = Auth::user()->id;
                $rec['sale_order_master_id'] = $data['sale_order_master_id'];
                SaleOrderDetail::create($rec);
            }
        }
    }

}
