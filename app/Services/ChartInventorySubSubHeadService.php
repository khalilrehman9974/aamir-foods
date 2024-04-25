<?php

namespace App\Services;

use App\Models\CoaInventorySubHead;
use App\Models\CoaInventoryMainHead;
use App\Models\CoaInventorySubSubHead;

/*
 * Class ChartInventorySubSubHeadService
 * @package App\Services
 * */

class ChartInventorySubSubHeadService
{
    public function findUpdateOrCreate($model, array $where, array $data)
    {
        $object = $model::firstOrNew($where);

        foreach ($data as $property => $value) {
            $object->{$property} = $value;
        }
        $object->save();

        return $object;
    }

    public function getMaxSubSubHeadCode()
    {
        return CoaInventorySubSubHead::max('code') ? CoaInventorySubSubHead::max('code') + 1 : 1;
    }

    public function getMainHeads()
    {
        return CoaInventoryMainHead::pluck('name', 'id');
    }

    public function getSubHeads()
    {
        return CoaInventorySubHead::pluck('name', 'id');
    }

    public function getSubHeadsByMainHead($mainHead)
    {
        return CoaInventorySubHead::where('main_head', $mainHead)->pluck('name', 'id');
    }

    public function getListOfSubSubHeads($param = null)
    {
        $q = CoaInventorySubSubHead::with('getSubHead', 'getMainHead');
        if (!empty($param)) {
            $q->where('name', 'LIKE', '%' . $param . '%');
        }
        $subSubHeads = $q->orderBy('name', 'ASC')->paginate(config('constants.PER_PAGE'));

        return $subSubHeads;
    }
}
