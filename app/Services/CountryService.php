<?php

namespace App\Services;

use App\Models\Country;
use App\Services\CommonService;
use Symfony\Component\Console\Input\Input;

    /*
     * Class BankService
     * @package App\Services
     * */
    // use Illuminate\Support\Facades\Input;

    class CountryService
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


    public function searchCountry($request)
    {
        $q = Country::query();
        if (!empty($request['param'])) {
            $q = Country::where('name', 'like', '%' . $request['param'] . '%');
        }
        $country = $q->orderBy('id', 'ASC')->paginate(config('constants.PER_PAGE'));

        return $country;
    }




}


