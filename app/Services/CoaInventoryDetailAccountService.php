<?php

namespace App\Services;

use App\Models\CoaMainHead;
use App\Models\CoaInventorySubHead;
use App\Models\CoaInventorySubSubHead;
use App\Models\CoaInventoryDetailAccount;
use App\Models\MeasurementType;
use App\Models\PackingType;
use App\Models\PriceTag;

/*
 * Class BankService
 * @package App\Services
 * */


// use Illuminate\Support\Facades\Input;

class CoaInventoryDetailAccountService
{
    const PER_PAGE = 10;

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

    public function DropDownData()
    {
        $result = [
            'PriceTags' => PriceTag::pluck('name','id'),
            'MeasurementTypes' => MeasurementType::pluck('name','id'),
            'PackingType' => PackingType::pluck('name','id'),
        ];

        return $result;
    }

    public function getListOfMainHeads()
    {
        return CoaMainHead::orderBy('id', 'ASC')->paginate(config('constants.PER_PAGE'));
    }

    public function getMaxAccountCode()
    {
        return CoaInventoryDetailAccount::max('code') ? CoaInventoryDetailAccount::max('code') + 1 : 1;
    }

    public function getListOfDetailAccounts($param = null)
    {
        $q = CoaInventoryDetailAccount::with('getMainHead', 'getSubHead','getSubSubHead');
        if (!empty($param)) {
            $q->where('name', 'LIKE', '%' . $param . '%');
        }
        $detailAccounts = $q->orderBy('id', 'DESC')->paginate(config('constants.PER_PAGE'));

        return $detailAccounts;
    }

    public function getSubHeadsByMainHead($mainHead)
    {
        return CoaInventorySubHead::where('main_head', $mainHead)->pluck('name', 'id');
    }

    public function getSubSubHeadsBySubHead($subHead)
    {
        return CoaInventorySubSubHead::where('sub_head_id', $subHead)->pluck('name', 'id');
    }
}


