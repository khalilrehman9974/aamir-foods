<?php

namespace App\Services;

/*
 * Class ChartOfAccountService
 * @package App\Services
 * */

use App\Models\PriceTag;
use App\Models\CoaSubHead;
use App\Models\CoaMainHead;
use App\Models\CoaSubSubHead;
use App\Models\ChartOfAccount;
use App\Models\CoaControlHead;
use App\Models\CoaDetailAccount;
use Illuminate\Support\Facades\DB;
use App\Models\CoaDetAccountDetail;
use Illuminate\Support\Facades\Auth;
use App\Models\InventorySubSubHeadPriceTagModel;

class ChartOfAccountService
{
    const PER_PAGE = 2;

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

    /*
        * Get list of accounts.
        * @param $request
        *
        * @return array $data.
        * */
    public function getAccounts()
    {
        return ChartOfAccount::pluck('name', 'id');
    }

    public function getMainHeads()
    {
        return CoaMainHead::pluck('account_name', 'id');
    }

    public function getControlHeads()
    {
        return CoaControlHead::pluck('account_name', 'account_code');
    }

    public function DropDownData()
    {
        $result = [
            'priceTag' => PriceTag::pluck('name','id'),
            'mainHeads' => CoaMainHead::pluck('account_name', 'id'),
            'controlHeads' => CoaControlHead::pluck('account_name', 'id'),
            'subHeads' => CoaSubHead::pluck('account_name', 'id'),
        ];

        return $result;
    }

    public function getSubHeads()
    {
        return CoaSubHead::pluck('account_name', 'account_code');
    }

    public function getSubSubHeads()
    {
        return CoaSubSubHead::pluck('account_name', 'account_code');
    }

    public function generateControlAccountCode($mainAccountCode)
    {
        $getMainHeadAccount = CoaControlHead::where('id', $mainAccountCode)->first();
        if ($getMainHeadAccount) {
            $controlAccountCode = (int)substr($getMainHeadAccount->account_code, 3, 6) + 1;
            return $mainAccountCode . $controlAccountCode;
        } else {
            return $mainAccountCode . '201';
        }
    }

    public function getListOfControlHeads($request)
    {

        $q = CoaControlHead::query();

        if (!empty($request['mainHead_id'])) {
            $q->where('main_head', $request['mainHead_id']);
        } elseif (!empty($request['account_name'])) {
            $q->where('account_name', $request['account_name']);
        }

        $controlHeads = $q->with('getMainAccountHead')->orderBy('id', 'DESC')->paginate(config('constants.PER_PAGE'));
        return $controlHeads;



    }

    public function prepareAccountMasterData($request)
    {
        return [
            'account_code' => $request['account_code'],
            'account_name' => $request['account_name'],
            'main_head' => $request['main_head'],
            'control_head' => $request['control_head'],
            'sub_head' => $request['sub_head'],
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


    public function getListOfSubHeads($request)
    {

        $q = CoaSubHead::query();

        if (!empty($request['mainHead_id'])) {
            $q->where('main_head', $request['mainHead_id']);
        } elseif (!empty($request['controlHead_id'])) {
            $q->where('control_head', $request['controlHead_id']);
        } elseif (!empty($request['account_name'])) {
            $q->where('account_name', $request['account_name']);
        }
        $subHeads = $q->with('getMainHead', 'getControlHead')->orderBy('id', 'DESC')->paginate(config('constants.PER_PAGE'));
        return $subHeads;

    }

    public function getListOfSubSubHeads($request)
    {
        $q = CoaSubSubHead::query();

        if (!empty($request['mainHead_id'])) {
            $q->where('main_head', $request['mainHead_id']);
        } elseif (!empty($request['controlHead_id'])) {
            $q->where('control_head', $request['controlHead_id']);
        } elseif (!empty($request['subHead_id'])) {
            $q->where('sub_head', $request['subHead_id']);
        } elseif (!empty($request['account_name'])) {
            $q->where('account_name', $request['account_name']);
        }
        $subSubHeads = $q->with('getMainHead', 'getControlHead', 'getSubHead')->orderBy('id', 'DESC')->paginate(config('constants.PER_PAGE'));
        return $subSubHeads;

    }

    public function generateSubHeadAccountCode($controlHeadCode)
    {
        $getControlHeadAccount = CoaSubHead::where('control_head', $controlHeadCode)->first();
        // dd($getControlHeadAccount);
        if ($getControlHeadAccount) {
            $controlAccountCode = (int)substr($getControlHeadAccount->account_code, 3, 6) + 1;

            return $getControlHeadAccount->main_head . $controlAccountCode;
        }
        return $controlHeadCode . config('constants.account_codes.3rd_level');
    }

    public function getControlHeadsForMainHead($mainHead)
    {
        return CoaControlHead::where('main_head', $mainHead)->pluck('account_name', 'id');//change here
    }

    public function getSubHeadsForControlHead($controlHead)
    {
        return CoaSubHead::where('control_head', $controlHead)->pluck('account_name', 'id');//change here
    }

    public function getSubSubHeadsBySubHead($subHead)
    {
        return CoaSubSubHead::where('sub_head', $subHead)->pluck('account_name', 'id'); //change here
    }

    public function generateSubSubHeadAccountCode($subHeadCode)
    {
        $getSubSubAccount = CoaSubSubHead::where('sub_head', $subHeadCode)->first();
//        if ($getSubSubAccount) {
//            $subAccountCode = (int)substr($getSubSubAccount->account_code, 3, 6) + 1;
//            return $getSubSubAccount->sub_head . $subAccountCode;
//        }
        $subCode = CoaSubHead::where('account_code', $subHeadCode)->first();
        return $subCode->account_code . config('constants.account_codes.4th_level');
    }

}

