<?php

namespace App\Services;

/*
     * Class StoreIssueNoteService
     * @package App\Services
     * */

use Carbon\Carbon;
use App\Models\StoreReturnMaster;
use App\Models\StoreReturnDetail;
use Illuminate\Support\Facades\Auth;
use App\Models\CoaInventoryDetailAccount;
use App\Models\Department;

class StoreReturnService
{
    protected $commonService;

    public function __construct(CommonService $commonService)
    {
        $this->commonService = $commonService;
    }
    /*
    * Store company data.
    * @param $model
    * @param $where
    * @param $data
    *
    * @return object $object.
    * */
    public function findUpdateOrCreate($model, array $where, array $data)
    {
        $object = $model::firstOrNew($where);

        foreach ($data as $property => $value) {
            $object->{$property} = $value;
        }
        $object->save();

        return $object;
    }

    public function DropDownData()
    {
        $result = [
            'products' => CoaInventoryDetailAccount::pluck('name', 'id'),
            'departments' => Department::pluck('name','id')
        ];

        return $result;
    }

    /*
     * Get contract by id.
     * @param $id
     * */
    public function getIssueNoteMasterById($id)
    {
        return StoreReturnMaster::leftjoin('products', 'product.id', '=', 'store_return_masters.product_id')
            ->select(
                'store_return_masters.id as id',
                'store_return_masters.return_to',
                'store_return_masters.return_by',
                'store_return_masters.remarks',
                'products.name as productName',
            )
            ->where('store_return_masters.id', $id)
            ->first();
    }

    /*
     * Search storeReturns record.
     * @queries: $queries
     * @return: object
     * */
    public function search($request)
    {
        $q = StoreReturnMaster::query();

        if (!empty($request['date'])) {
            $formattedDate = date('Y-m-d', strtotime($request['date']));
            $q->where('date', $formattedDate);
        } elseif (!empty($request['to_department'])) {
            $q->where('to_department', $request['to_department']);
        }
        $storeReturns = $q->with('fromDepartment','toDepartment')->orderBy('id', 'DESC')->paginate(config('constants.PER_PAGE'));




        return $storeReturns;
    }


    /*
     * Prepare IssueNote master data.
     * @param: $request
     * @return Array
     * */
    public function prepareStoreReturnMasterData($request)
    {
        $session = $this->commonService->getSession();
        return [
            'date' => Carbon::parse($request['date'])->format('Y-m-d'),
            'receiver_name' => $request['receiver_name'],
            'from_department' => $request['from_department'],
            'business_id' => $session->business_id,
            'f_year_id' => $session->financial_year,
            'to_department' => $request['to_department'],
            'created_by' => Auth::user()->id,
            'updated_by' => Auth::user()->id
        ];
    }

    public function preparestoreReturnDetailData($request, $returnNoteParentId)
    {
        return [
            'product_id' => $request['product_id'],
            'packing_type' => $request['packing_type'],
            'measurement_type' => $request['measurement_type'],
            'size' => $request['size'],
            'bags' => $request['bags'],
            'avg_weight' => $request['avg_weight'],
            'total_qty' => $request['total_qty'],
            'remarks' => $request['remarks'],
            'created_by' => Auth::user()->id,
            'updated_by' => Auth::user()->id,
            'store_return_master_id' => $returnNoteParentId,
        ];
    }


    /*
     * Prepare dispatch detail data.
     * @param: $request
     * @return Array
     * */
    public function saveStoreReturn($data)
    {
        foreach ($data['product_id'] as $key => $value) {
            if (!empty($data['product_id'][$key])) {
                $rec['product_id'] = $data['product_id'][$key];
                $rec['packing_type'] = $data['packing_type'][$key];
                $rec['measurement_type'] = $data['measurement_type'][$key];
                $rec['size'] = $data['size'][$key];
                $rec['bags'] = $data['bags'][$key];
                $rec['avg_weight'] = $data['avg_weight'][$key];
                $rec['total_qty'] = $data['total_qty'][$key];
                $rec['remarks'] = $data['remarks'][$key];
                $rec['created_by'] = Auth::user()->id;
                $rec['updated_by'] = Auth::user()->id;
                $rec['store_return_master_id'] = $data['store_return_master_id'];
                StoreReturnDetail::create($rec);
            }
        }
    }

    /*
     * Save dispatch data.
     * @param: $data
     * */
    // public function saveStoreReturn($data)
    // {
    //     foreach ($data['date'] as $key => $value) {
    //         if (!empty($data['date'][$key]))
    //         {
    //             $rec['date'] = $data['date'][$key];
    //             $rec['description'] = $data['description'][$key];
    //             $rec['quantity'] = $data['quantity'][$key];
    //             $rec['created_by'] = Auth::user()->id;
    //             $rec['updated_by'] = Auth::user()->id;
    //             $rec['store_return_master_id'] = $data['store_return_master_id'];
    //             StoreReturnDetail::create($rec);
    //         }
    //     }
    // }
}
