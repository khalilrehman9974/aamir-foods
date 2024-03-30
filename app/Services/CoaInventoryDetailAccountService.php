<?php

namespace App\Services;

use App\Models\CoaInventoryDetailAccount;
use App\Models\CoaInventorySubHead;
use App\Models\CoaMainHead;

/*
 * Class BankService
 * @package App\Services
 * */

use App\Models\Donor;

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
        $q = CoaInventoryDetailAccount::with('getMainHead', 'getSubHead');
        if (!empty($param)) {
            $q->where('name', 'LIKE', '%' . $param . '%');
        }
        $detailAccounts = $q->orderBy('id', 'DESC')->paginate(config('constants.PER_PAGE'));

        return $detailAccounts;
    }

    public function getSubHeadsByMainHead($mainHead)
    {
        return CoaInventorySubHead::where('main_head', $mainHead)->pluck('name', 'code');
    }
}


