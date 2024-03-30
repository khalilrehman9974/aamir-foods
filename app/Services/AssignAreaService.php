<?php

namespace App\Services;

use App\Models\Area;

    /*
     * Class BankService
     * @package App\Services
     * */

use App\Models\User;
use App\Models\AssignArea;
use App\Models\SaleMan;
use Symfony\Component\Console\Input\Input;

    // use Illuminate\Support\Facades\Input;

    class AssignAreaService
{
    const PER_PAGE = 10;
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

        foreach ($data as $property => $value){
            $object->{$property} = $value;
        }
        $object->save();

        return $object;
    }

    public function DropDownData()
    {
        $result = [
            'saleMan' => SaleMan::pluck('name','id'),
            'areas' => Area::pluck('name','id'),

        ];

        return $result;
    }
    // public function searchAssignArea($request)
    // {
    //     $assignAreas = [];
    //     if (!empty($request['param'])) {
    //         $query = AssignArea::with('area','sale_man')->where('sale_mans_id', 'like', '%' . $request['param'] . '%');
    //         $assignAreas = $query->get();
    //     }else{
    //         $assignAreas = AssignArea::get();
    //     }

    //     return $this->commonService->paginate($assignAreas, Self::PER_PAGE);
    // }

    public function searchAssignArea($request)
    {
        $q = AssignArea::query();
        if (!empty($request['param'])) {
            $q = AssignArea::with('area')->where('sale_mans_id', 'like', '%' . $request['param'] . '%');
        }
        $assignAreas = $q->orderBy('sale_mans_id', 'ASC')->paginate(config('constants.PER_PAGE'));

        return $assignAreas;
    }


}


