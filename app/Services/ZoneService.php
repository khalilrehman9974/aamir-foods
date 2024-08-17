<?php

namespace App\Services;

use App\Models\Country;
use App\Models\Zone;

    /*
     * Class BankService
     * @package App\Services
     * */


    // use Illuminate\Support\Facades\Input;

    class ZoneService
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


    public function searchZone($request)
    {
        $q = Zone::query();
        if (!empty($request['param'])) {
            $q = Zone::with('country')->where('name', 'like', '%' . $request['param'] . '%');
        }
        $zones = $q->orderBy('name', 'ASC')->paginate(config('constants.PER_PAGE'));

        return $zones;
    }


    public function DropDownData()
    {
        $result = [
            'countries' => Country::pluck('name','id'),
        ];

        return $result;
    }


}


