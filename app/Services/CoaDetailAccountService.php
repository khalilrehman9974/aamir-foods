<?php
namespace App\Services;

/*
 * Class CoaDetailAccountService
 * @package App\Services
 * */

use App\Models\CoaDetAccountDetail;
use App\Models\CoaDetailAccount;
use App\Models\CoaSubHead;
use App\Models\CoaSubSubHead;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CoaDetailAccountService {
    const PER_PAGE = 2;

    public function getListOfDetailAccounts($param = null)
    {
        $q = CoaDetailAccount::with('getMainHead','getControlHead', 'getSubHead', 'getSubSubHead');
        if (!empty($param)) {
            $q->where('account_name', 'LIKE', '%' . $param . '%');
        }
        $detailAccounts = $q->orderBy('account_name', 'ASC')->paginate(config('constants.PER_PAGE'));

        return $detailAccounts;
    }

    public function getSubSubHeadsBySubHead($subHead)
    {
        return CoaSubSubHead::where('sub_head', $subHead)->pluck('account_name', 'account_code');
    }

    public function generateDetailAccountCode($subSubHeadCode)
    {
        $getDetailAccount = CoaDetailAccount::where('sub_sub_head', $subSubHeadCode)->max('account_code');
        if ($getDetailAccount) {
            return $getDetailAccount + 1;
        }
        return 1;
//        if ($getDetailAccount) {
//            $detailAccountCode = (int)substr($getDetailAccount->account_code, 3, 6) + 1;
//            return $getDetailAccount->sub_head . $detailAccountCode;
//        }
//        return $subSubHeadCode . config('constants.account_codes.5th_level');
    }

    public function storeAccountData($request)
    {
        DB::beginTransaction();
//        try {
            $mainData = $this->prepareMainAccountData($request);
            $detailData = $this->prepareAdditionalInformationData($request);
            $this->findUpdateOrCreate(CoaDetailAccount::class, ['id' => !empty(request('id')) ? request('id') : null], $mainData);
            $this->findUpdateOrCreate(CoaDetAccountDetail::class, ['id' => !empty(request('id')) ? request('id') : null], $detailData);
            DB::commit();
//        } catch (\Throwable $exception) {
//            DB::rollback();
////            return back()->withError('User with ID: '.$request->user_id.' not found!')->withInput();
//        }
    }


    public function prepareMainAccountData($request)
    {
        return [
            'main_head' => $request->main_head,
            'control_head' => $request->control_head,
            'sub_head' => $request->sub_head,
            'sub_sub_head' => $request->sub_sub_head,
            'account_code' => $request->account_code,
            'account_name' => $request->account_name,
            'created_by' => Auth::user()->id,
            'updated_by' => Auth::user()->id
        ];
    }

    public function prepareAdditionalInformationData($request)
    {
        return [
            'address' => $request->address,
            'cnic' => $request->cnic,
            'contact_no_1' => $request->contact_no_1,
            'contact_no_2' => $request->contact_no_2,
            'email' => $request->email,
            'opening_balance' => $request->opening_balance,
            'credit_limit' => $request->credit_limit,
            'created_by' => Auth::user()->id,
            'updated_by' => Auth::user()->id
        ];
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

        foreach ($data as $property => $value) {
            $object->{$property} = $value;
        }
        $object->save();

        return $object;
    }

}
