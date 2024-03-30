<?php

namespace App\Services;

use App\Models\Area;
use App\Models\Business;
use Symfony\Component\Console\Input\Input;

    /*
     * Class BankService
     * @package App\Services
     * */

    use App\Models\Donor;
use App\Models\Sector;

    // use Illuminate\Support\Facades\Input;

    class BusinessService
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
   
    public function searchBusiness($request)
    {
        $q = Business::query();
        if (!empty($request['param'])) {
            $q = Business::where('name', 'like', '%' . $request['param'] . '%');
        }
        $areas = $q->orderBy('name', 'ASC')->paginate(config('constants.PER_PAGE'));

        return $areas;
    }


}


