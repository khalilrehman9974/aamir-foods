<?php

namespace App\Services;


use App\Models\Sector;
use App\Services\CommonService;
use Symfony\Component\Console\Input\Input;

    /*
     * Class BankService
     * @package App\Services
     * */
    // use Illuminate\Support\Facades\Input;

    class SectorService
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


    public function searchSector($request)
    {
        $q = Sector::query();
        if (!empty($request['param'])) {
            $q = Sector::where('name', 'like', '%' . $request['param'] . '%');
        }
        $sectors = $q->orderBy('name', 'ASC')->paginate(config('constants.PER_PAGE'));

        return $sectors;
    }


}


