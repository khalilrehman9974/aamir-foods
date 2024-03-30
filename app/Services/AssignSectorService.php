<?php

namespace App\Services;

use App\Models\Area;

    /*
     * Class BankService
     * @package App\Services
     * */

use App\Models\User;
use App\Models\AssignSector;
use App\Models\SaleMan;
use App\Models\Sector;
use Symfony\Component\Console\Input\Input;

    // use Illuminate\Support\Facades\Input;

    class AssignSectorService
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
            'sale_mans' => SaleMan::pluck('name','id'),
            'sectors' => Sector::pluck('name','id'),
            // 'parties' => Party::pluck('name','id'),
            // 'accounts' => Account::pluck('name','account_code'),
            //'products' => Product::pluck('name','id'),
        ];

        return $result;
    }
    
    public function search($request)
    {
        $q = AssignSector::query();
        if (!empty($request['param'])) {
            $q = AssignSector::with('sector','sale_man_id')->where('sector_id', 'like', '%' . $request['param'] . '%');
        }
        $assignSector = $q->orderBy('sector_id', 'ASC')->paginate(config('constants.PER_PAGE'));

        return $assignSector;
    }

}


