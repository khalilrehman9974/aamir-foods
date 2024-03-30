<?php

namespace App\Services;

use App\Models\CoaInventoryMainHead;

/*
 * Class CoaInventoryMainHeadService
 * @package App\Services
 * */


class CoaInventoryMainHeadService
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
        return CoaInventoryMainHead::orderBy('id', 'ASC')->paginate(config('constants.PER_PAGE'));
    }

    public function getMaxAccountCode()
    {
        return CoaInventoryMainHead::max('code') ? CoaInventoryMainHead::max('code') + 1 : 1;
    }

}


