<?php

namespace App\Services;

use App\Models\CoaMainHead;

/*
 * Class CoaMainHeadService
 * @package App\Services
 * */

class CoaMainHeadService
{
    const PER_PAGE = 10;

    /*
    * Store Main Head data.
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
        return CoaMainHead::max('account_code') ? CoaMainHead::max('account_code') + 100 : '100';
    }
}


