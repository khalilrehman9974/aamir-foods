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
        if (!empty($request['param'])) {
            $q = StoreReturnMaster::with('product')->where('return_to', 'like', '%' . $request['param'] . '%');
        }
        $storeReturns = $q->orderBy('return_to', 'ASC')->paginate(config('constants.PER_PAGE'));

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
        return[
            'return_to' => $request['return_to'],
            'business_id' => $session->business_id,
            'f_year_id' => $session->financial_year,
            'return_by' => $request['return_by'],
            'product_id' => $request['product_id'],
            'remarks' => $request['remarks'],
            'created_by' => Auth::user()->id,
            'updated_by' => Auth::user()->id
        ];
    }

    /*
     * Prepare dispatch detail data.
     * @param: $request
     * @return Array
     * */
    public function saveStoreReturn($request, $storeReturnMasterParentId)
    {
        $data = $request;
        foreach ($data["date"] as $key => $value) {
                $rec['date'] = Carbon::parse($data['date'][$key])->format("Y-m-d") ;
                $rec['description'] = $data['description'][$key];
                $rec['quantity'] = $data['quantity'][$key];
                $rec['created_by'] = Auth::Id();
                $rec['updated_by'] = Auth::Id();
                $rec['store_return_master_id'] = $storeReturnMasterParentId;
                StoreReturnDetail::create($rec);
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
