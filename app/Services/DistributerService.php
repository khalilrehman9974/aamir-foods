<?php

namespace App\Services;



    /*
     * Class BankService
     * @package App\Services
     * */


use App\Models\Distributer;


    // use Illuminate\Support\Facades\Input;

    class DistributerService
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


   

    public function searchDistributer($request)
    {
        $q = Distributer::query();
        if (!empty($request['param'])) {
            $q = Distributer::where('name', 'like', '%' . $request['param'] . '%');
        }
        $distributers = $q->orderBy('name', 'ASC')->paginate(config('constants.PER_PAGE'));

        return $distributers;
    }


}


