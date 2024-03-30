<?php

namespace App\Services;

/*
 * Class ChartOfAccountService
 * @package App\Services
 * */

use App\Models\ChartOfAccount;
use App\Models\CoaControlHead;
use App\Models\CoaDetAccountDetail;
use App\Models\CoaDetailAccount;
use App\Models\CoaMainHead;
use App\Models\CoaSubHead;
use App\Models\CoaSubSubHead;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
        return CoaMainHead::pluck('account_name', 'account_code');
    }

    public function getControlHeads()
    {
        return CoaControlHead::pluck('account_name', 'account_code');
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
        $getMainHeadAccount = CoaControlHead::where('main_head', $mainAccountCode)->first();
        if ($getMainHeadAccount) {
            $controlAccountCode = (int)substr($getMainHeadAccount->account_code, 3, 6) + 1;
            return $mainAccountCode . $controlAccountCode;
        } else {
            return $mainAccountCode . '201';
        }
    }

    public function getListOfControlHeads($param = null)
    {
        $q = CoaControlHead::with('getMainAccountHead');
        if (!empty($param)) {
            $q->where('account_name', 'LIKE', '%' . $param . '%');
        }
        $controlHeads = $q->orderBy('account_name', 'ASC')->paginate(config('constants.PER_PAGE'));

        return $controlHeads;
    }

    public function getListOfSubHeads($param = null)
    {
        $q = CoaSubHead::with('getMainHead', 'getControlHead');
        if (!empty($param)) {
            $q->where('account_name', 'LIKE', '%' . $param . '%');
        }
        $subHeads = $q->orderBy('account_name', 'ASC')->paginate(config('constants.PER_PAGE'));

        return $subHeads;
    }

    public function getListOfSubSubHeads($param = null)
    {
        $q = CoaSubSubHead::with('getMainHead', 'getControlHead', 'getSubHead');
        if (!empty($param)) {
            $q->where('account_name', 'LIKE', '%' . $param . '%');
        }
        $subSubHeads = $q->orderBy('account_name', 'ASC')->paginate(config('constants.PER_PAGE'));

        return $subSubHeads;
    }

    public function generateSubHeadAccountCode($controlHeadCode)
    {
        $getControlHeadAccount = CoaSubHead::where('control_head', $controlHeadCode)->first();
        if ($getControlHeadAccount) {
            $controlAccountCode = (int)substr($getControlHeadAccount->account_code, 3, 6) + 1;
            return $getControlHeadAccount->main_head . $controlAccountCode;
        }
        return $controlHeadCode . config('constants.account_codes.3rd_level');
    }

    public function getControlHeadsForMainHead($mainHead)
    {
        return CoaControlHead::where('main_head', $mainHead)->pluck('account_name', 'account_code');
    }

    public function getSubHeadsForControlHead($controlHead)
    {
        return CoaSubHead::where('control_head', $controlHead)->pluck('account_name', 'account_code');
    }

    public function getSubSubHeadsBySubHead($subHead)
    {
        return CoaSubSubHead::where('sub_head', $subHead)->pluck('account_name', 'account_code');
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

