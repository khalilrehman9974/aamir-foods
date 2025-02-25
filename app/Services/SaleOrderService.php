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
use App\Models\DeliveredToParties;
use App\Models\SaleOrderImages;

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
    public function getSaleOrderMasterById($id)
    {
        return SaleOrder::leftjoin('detail_accounts', 'detail_accounts.account_code', '=', 'sale_order_masters.party_id')
            ->leftjoin('sale_order_masters', 'sale_order_masters.party_id', '=', 'detail_accounts.account_code')
            ->select(

                'detail_accounts.account_code'
            )
            ->where('detail_accounts.account_code', $id)
            ->first();
    }


    /*
     * Search sale record.
     * @queries: $queries
     * @return: object
     * */

    // public function searchSale($request)
    // {
    public function searchSale($request)
    {
        $q = SaleOrder::query();
        // dd($q);
        if (!empty($request['date'])) {
            $q->where('date', $request['date']);
        } elseif (!empty($request['party_id'])) {
            $q->where('party_id', $request['party_id']);
        }
        // $q->where('status', 'Pending');
        // elseif (!empty($request['seller'])) {
        //     $q->where('seller_id', $request['seller']);
        // }

        $saleOrders = $q->with(['parties'])->orderBy('updated_at', 'DESC')->paginate(config('constants.PER_PAGE'));
        return $saleOrders;

        //     $q = SaleOrder::query();
        //     if (!empty($request['param'])) {
        //         $qr = CoaDetailAccount::leftjoin('sale_order_masters', 'sale_order_masters.party_id', '=', 'detail_accounts.account_code')
        //             ->select(

        //                 'detail_accounts.account_code',
        //             )
        //             ->where('detail_accounts.account_name', $request['param'])
        //             ->first();
        //         dd($qr);
        //         $q->with('party')->where('date', 'LIKE', '%' . $request['param'] . '%')
        //         ->orWhere('party_id', 'LIKE', $qr);
        //     }

        //     $saleOrders = $q->orderBy('id', 'ASC')->paginate(config('constants.PER_PAGE'));
        //     return $saleOrders;
        // }
        // // dd($request);
        // if (!empty($request['param'])) {


        //     dd($posts);
        // }

        // // dd($request);
        // $q = SaleOrder::query();
        // $posts = SaleOrder::join('detail_accounts', 'sale_order_masters.party_id', '=', 'detail_accounts.account_code') // Join the posts and users tables
        //     ->where('detail_accounts.account_name', 'like', '%' . $request['param'] . '%') // Filter users by name (or any other condition)
        //     ->select('detail_accounts.account_code') // Select the title column from posts table
        //     ->get();
        // dd($posts);
        // $search = $request['param'];
        // if (!empty($request['param'])) {
        //     $q = SaleOrder::with('party')->where('date', 'like', '%' . $request['param'] . '%')
        //     ->orWhere('total_amount', 'like', '%' . $request['param'] . '%')
        // // ->orWhere('party_id', 'like', '%' . 'account_code' . '%')
        // ->orWhereHas('parties',function($query) use ($search){
        //     $query->where('account_name', 'like',"$search")
        //     ->get();
        // });

        // ->join('detail_accounts', 'sale_order_masters.party_id', '=', 'detail_accounts.account_name') // Join the sale Orders and Detail Account tables
        //     ->where('sale_order_masters.party_id', 'like', $request['param'] ) // Filter Detail Account by name (or any other condition)
        //     ->select('detail_accounts.account_code'); // Select the title column from posts table
        //     // ->get(),
        // }


        // $saleOrders = $q->orderBy('id', 'ASC')->paginate(config('constants.PER_PAGE'));

        // return $saleOrders;
    }



    public function searchApprovedSaleOrder($request)
    {
        $q = SaleOrder::query();
        // dd($q);
        if (!empty($request['date'])) {
            $q->where('date', $request['date']);
        } elseif (!empty($request['party_id'])) {
            $q->where('party_id', $request['party_id']);
        }
        $q->where('status', 'Approved');

        $saleOrders = $q->with(['parties'])->orderBy('updated_at', 'DESC')->paginate(config('constants.PER_PAGE'));
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
            'saleman' => $request['saleman'],
            'belt' => $request['belt'],
            'area' => $request['area'],
            'delivered_to' => $request['delivered_to'] ?? null,
            'status' => $request['status'],
            'total_boray' => $request['total_boray'],
            'total_carton' => $request['total_carton'],
            'total_amount' => $request['total_amount'],
            'created_by' => Auth::user()->id,
            'updated_by' => Auth::user()->id
        ];
    }

    public function DropDownData()
    {
        $result = [
            'parties' => CoaDetailAccount::pluck('account_name', 'id'),
            'saleMans' => SaleMan::pluck('name', 'id'),
            'deliverdToParties' => DeliveredToParties::pluck('party_name', 'id'),
            'products' => CoaInventoryDetailAccount::pluck('name', 'id'),

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
            'packing_type' => $request['packing_type'],
            'measurement_type' => $request['measurement_type'],
            'quantity' => $request['quantity'],
            'dzn' => $request['dzn'],
            'total_dzn' => $request['total_dzn'],
            'rate' => $request['rate'],
            'amount' => $request['amount'],
            'created_by' => Auth::user()->id,
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
                $rec['packing_type'] = $data['packing_type'][$key];
                $rec['measurement_type'] = $data['measurement_type'][$key];
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

    public function prepareSaleOrderImagesData($request, $saleOrderParentId)
    {

        $imagePaths = [];

        if (isset($request['images'])) {
            foreach ($request['images'] as $file) {
                $destinationPath = public_path('images/saleOrder');

                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 0777, true);
                }

                $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $file->move($destinationPath, $filename);

                $imagePaths[] = $filename;
            }
            return [
                'images' => $imagePaths,
                'sale_order_id' => $saleOrderParentId,
            ];
        } else {

            return [
                'sale_order_id' => $saleOrderParentId,
            ];
        }
    }

    public function saveSaleOrderImages($data)
    {
        if (empty($data['images'])) {
            $rec['images'] = null;
            $rec['sale_order_id'] = $data['sale_order_id'];
            SaleOrderImages::create($rec);

        } else {
            foreach ($data['images'] as $key => $value) {
                if (!empty($value)) {
                    $rec['images'] = $value;
                    $rec['sale_order_id'] = $data['sale_order_id'];
                    SaleOrderImages::create($rec);
                }
            }
        }

    }
}
