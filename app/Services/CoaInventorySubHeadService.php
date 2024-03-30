<?php

namespace App\Services;

use App\Models\CoaInventoryMainHead;
use App\Models\CoaInventorySubHead;

/*
 * Class CoaInventorySubHeadService
 * @package App\Services
 * */

class CoaInventorySubHeadService
{
    const PER_PAGE = 10;

    /*
    * Store Inventory Sub data.
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

    public function getMaxAccountCode()
    {
        return CoaInventorySubHead::max('code') ? CoaInventorySubHead::max('code') + 1 : 1;
    }

    public function getMainHeads()
    {
        return CoaInventoryMainHead::pluck('name', 'code');
    }

    public function getListOfSubHeads($param = null)
    {
        $q = CoaInventorySubHead::with('getMainAccountHead');
        if (!empty($param)) {
            $q->where('name', 'LIKE', '%' . $param . '%');
        }
        $subHeads = $q->orderBy('name', 'ASC')->paginate(config('constants.PER_PAGE'));

        return $subHeads;
    }


}


