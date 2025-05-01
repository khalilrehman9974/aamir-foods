<?php

namespace App\Services;

use App\Models\PriceTag;
use App\Models\CoaSubHead;
use App\Models\CoaMainHead;
use App\Models\PackingType;
use App\Models\CoaSubSubHead;
use App\Models\CoaControlHead;
use App\Models\MeasurementType;
use App\Models\CoaDetailAccount;
use App\Models\CoaInventorySubHead;
use Illuminate\Support\Facades\Auth;
use App\Models\CoaInventorySubSubHead;
use App\Models\CoaInventoryDetailAccount;
use App\Models\DetailAccountProducts;

/*
 * Class BankService
 * @package App\Services
 * */


// use Illuminate\Support\Facades\Input;

class CoaInventoryDetailAccountService
{
    protected $commonService;

    public function __construct(CommonService $commonService)
    {
        $this->commonService = $commonService;
    }

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
        $q = CoaInventoryDetailAccount::with('getMainHead','getControlHead', 'getSubHead','getSubSubHead','priceTag');
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

    // public function getSubSubHeadsBySubHead($subHead)
    // {
    //     return CoaInventorySubSubHead::where('sub_head_id', $subHead)->pluck('name', 'id');
    // }


    public function prepareCoaDetailAccountData($request)
    {
        $session = $this->commonService->getSession();

        return [
            'main_head' => $request['coa_main_head'],
            'control_head' => $request['control_head'],
            'sub_head' => $request['sub_head'],
            'sub_sub_head' => $request['sub_sub_head'],
            'account_code' => $request['code'],
            'account_name' => $request['name'],
            'business_id' => $session->business_id,
            'f_year_id' => $session->financial_year,
            'created_by' => Auth::user()->id,
            'updated_by' => Auth::user()->id,
            'created_at' => now(),
            'updated_at' => now()
        ];
    }

    public function prepareCoaDetailAccountDetailData($request)
    {

        $openingStock = $request['opening_stock'];
        $stockRate = $request['stock_rate'];
        $openingBalance = ($openingStock > 0 && $stockRate > 0) ? $openingStock * $stockRate : 0;

        $maxid = CoaDetailAccount::max('id');

        return [
            'det_account_code' => $maxid,
            'address' => null,
            'email' => null,
            'cnic' => null,
            'contact_no_1' => null,
            'remarks' => null,
            'opening_balance' => $openingBalance,
            'credit_limit' => null,
            'credit_days' => null,
            'contact_no_2' => null,
            'created_by' => Auth::user()->id,
            'updated_by' => Auth::user()->id,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    public function prepareUpdatedCoaDetailAccountDetailData($request, $party)
    {

        $openingStock = $request['opening_stock'];
        $stockRate = $request['stock_rate'];
        $openingBalance = ($openingStock > 0 && $stockRate > 0) ? $openingStock * $stockRate : 0;

        // $maxid = $party->id;
        $maxid = $party->value('id');

        return [
            'det_account_code' => $maxid,
            'address' => null,
            'email' => null,
            'cnic' => null,
            'contact_no_1' => null,
            'remarks' => null,
            'opening_balance' => $openingBalance,
            'credit_limit' => null,
            'credit_days' => null,
            'contact_no_2' => null,
            'created_by' => Auth::user()->id,
            'updated_by' => Auth::user()->id,
            'created_at' => now(),
            'updated_at' => now(),
        ];
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

    public function prepareProductAssingData($request, $masterId)
    {

        $partyIds = CoaDetailAccount::whereNull('deleted_at')->pluck('id')->toArray();

        return [
            'detail_account_id' => $partyIds,
            'master_price_tag' => $request['priceTag_id'],
            'master_third_level' => $request['sub_sub_head'],
            'product_id' => $masterId,
            'price' => $request['rate'],
            'discount' => config('constants.ZERO'),
            'scheme' => config('constants.ZERO'),
            'created_at' => now(),
            'updated_at' => now()
        ];
    }

    public function ProductAssing($data)
    {
        foreach ($data['detail_account_id'] as $key => $value) {
            if (!empty($data['detail_account_id'][$key])) {
                $rec['detail_account_id'] = $data['detail_account_id'][$key];
                $rec['master_price_tag'] = $data['master_price_tag'];
                $rec['master_third_level'] = $data['master_third_level'];
                $rec['product_id'] = $data['product_id'];
                $rec['price'] = $data['price'];
                $rec['discount'] = $data['discount'];
                $rec['scheme'] = $data['scheme'];

                DetailAccountProducts::create($rec);
            }
        }
    }
}


