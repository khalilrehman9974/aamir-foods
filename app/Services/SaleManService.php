<?php

namespace App\Services;



/*
     * Class BankService
     * @package App\Services
     * */

use App\Models\Donor;
use App\Models\SaleMan;


// use Illuminate\Support\Facades\Input;

class SaleManService
{

    const PER_PAGE = 2;

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

    // public function searchSaleMan($params)
    // {
    //     $q = SaleMan::query();
    //     if (!empty($param['name']))
    //     {
    //         $q->where('name', 'LIKE', '%'. $param['name'] . '%');
    //     }

    //     $area = $q->orderBy('name', 'ASC')->paginate(SaleMan::PER_PAGE);
    //     return $area;
    // }

    public function searchSaleMan($request)
    {
        $q = SaleMan::query();
        if (!empty($request['param'])) {
            $q = SaleMan::where('name', 'like', '%' . $request['param'] . '%');
        }
        $saleMans = $q->orderBy('name', 'ASC')->paginate(config('constants.PER_PAGE'));

        return $saleMans;
    }




    // public function search($params)
    // {
    //     $q = SaleMan::query();
    //     if (!empty($param['name']))
    //     {
    //         $q->where('name', 'LIKE', '%'. $param['name'] . '%');
    //     }



    //     $saleMan = $q->orderBy('name', 'ASC')->paginate(SaleMan::PER_PAGE);
    //     return $saleMan;
    // }

}
