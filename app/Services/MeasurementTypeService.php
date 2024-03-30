<?php

namespace App\Services;


use App\Models\MeasurementType;
use App\Services\CommonService;
use Symfony\Component\Console\Input\Input;

    /*
     * Class BankService
     * @package App\Services
     * */
    // use Illuminate\Support\Facades\Input;

    class MeasurementTypeService
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


    public function searchMeasurementType($request)
    {
        $q = MeasurementType::query();
        if (!empty($request['param'])) {
            $q = MeasurementType::where('name', 'like', '%' . $request['param'] . '%');
        }
        $measurementTypes = $q->orderBy('name', 'ASC')->paginate(config('constants.PER_PAGE'));

        return $measurementTypes;
    }


}


