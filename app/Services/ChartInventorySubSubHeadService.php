<?php

namespace App\Services;

use App\Models\PriceTag;
use App\Models\CoaInventorySubHead;
use App\Models\CoaInventoryMainHead;
use Illuminate\Support\Facades\Auth;
use App\Models\CoaInventorySubSubHead;
use App\Models\InventorySubSubHeadPriceTagModel;

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

    public function DropDownData()
    {
        $result = [
            'priceTag' => PriceTag::pluck('name','id'),
        ];

        return $result;
    }

    public function prepareAccountMasterData($request)
    {
        return [
            'code' => $request['code'],
            'name' => $request['name'],
            'main_head_id' => $request['main_head_id'],
            'sub_head_id' => $request['sub_head_id'],
            'created_by' => Auth::user()->id,
            'updated_by' => Auth::user()->id
        ];
    }

    public function prepareAccountDetailData($request, $detailAccountMasterInsert)
    {

        return [
            'priceTag' => $request['priceTag'],
            'sub_sub_head_id' => $detailAccountMasterInsert,
        ];
    }

    /*
     * Save sale data.
     * @param: $data
     * */
    public function savePriceTags($data)
    {
        foreach ($data['priceTag'] as $key => $value) {
            if (!empty($data['priceTag'][$key])) {
                $rec['priceTag'] = $data['priceTag'][$key];
                $rec['sub_sub_head_id'] = $data['sub_sub_head_id'];
                InventorySubSubHeadPriceTagModel::create($rec);
            }
        }
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
