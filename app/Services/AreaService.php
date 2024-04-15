<?php

namespace App\Services;

use App\Models\Area;
use Symfony\Component\Console\Input\Input;

    /*
     * Class BankService
     * @package App\Services
     * */
use App\Models\Sector;

    // use Illuminate\Support\Facades\Input;

    class AreaService
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

        foreach ($data as $property => $value){
            $object->{$property} = $value;
        }
        $object->save();

        return $object;
    }

    // public function searchArea($request)
    // {
    //     $areas = [];
    //     if (!empty($request['param'])) {
    //         $query = Area::with('sector')->where('name', 'like', '%' . $request['param'] . '%');
    //         $areas = $query->get();
    //     }else{
    //         $areas = Area::get();
    //     }

    //     return $this->commonService->paginate($areas, Self::PER_PAGE);
    // }

    // public function searchArea($param = null)
    // {
    //     $q = Area::with('Sector');
    //     if (!empty($param)) {
    //         $q->where('name', 'LIKE', '%' . $param . '%');
    //     }
    //     $areas = $q->orderBy('name', 'ASC')->paginate(config('constants.PER_PAGE'));

    //     return $areas;
    // }


    public function searchArea($request)
    {
        $q = Area::query();
        if (!empty($request['param'])) {
            $q = Area::with('sector')->where('name', 'like', '%' . $request['param'] . '%');
        }
        $areas = $q->orderBy('name', 'ASC')->paginate(config('constants.PER_PAGE'));

        return $areas;
    }


    public function DropDownData()
    {
        $result = [
            'sectors' => Sector::pluck('name','id'),
        ];

        return $result;
    }


}


