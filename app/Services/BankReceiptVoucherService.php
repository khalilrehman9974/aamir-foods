<?php

namespace App\Services;

use App\Issuance;
use App\Models\tblacc_code;
use App\Models\tblbrv;
use App\Models\tblbrv_details;
use App\Models\tblbrvDetails_temp;
use Illuminate\Support\Facades\Auth;

class BankReceiptVoucherService
{
    const PER_PAGE = 10;
    const BRV_SAVED = 'BRV saved successfully';
    const BRVTEMP_SAVED = 'BRV SAVED AS DRAFT SUCCESSFULLY';
    const BRV_UPDATED = 'BRV updated successfully';
    const BRV_DELETED = 'BRV is deleted successfully';
    const SOME_THING_WENT_WRONG = 'Oops!Something went wrong';
    protected $commonService;

    public function __construct(CommonService $commonService)
    {
        $this->commonService = $commonService;
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
    public function getacc_namebyid($request)
    {
        return tblacc_code::where('id', $request['account_name'])->get();
    }

    public function gettblbrvByid($id)
    {
        return tblbrv::leftjoin('tblbanks', 'tblbanks.id', '=', 'tblbrv.bank_id')
            ->leftjoin('tblcust_sup', 'tblcust_sup.cust_sup_id', '=','tblbrv.cust_id' )
            ->select('tblbrv.id as id', 'tblbrv.br_date', 'tblcust_sup.id as tblcust_supid',
                'tblbrv.type as type',
                'tblbrv.ref_no as ref_no')
            ->where('tblbrv.id', $id)
            ->first();
    }

    /*
    * Get contract by id.
    * @param $id
    * */
    // public function gettblbrv_detailByid($id)
    // {
    //     return tblbrv_details::leftjoin('br_id', 'tblbrv_details.br_id', '=', 'tblbrv.br_id')
    //          ->leftjoin('account_code', 'tblbrv_details.account_code', '=', 'tblbrv_details.account_code')
    //         ->select('tblbrv_details.remarks', 'tblbrv_details.debit_amount','tblbrv_details.status')
    //         ->where('tblbrv_details.br_id', $id)
    //         ->get();
    // }
    public function searchbrv($request)
    {

        $query = tblbrv::select('tblbrv.id', 'br_date', 'bank_id', 'cust_id', 'tblcust_sup.cust_sup_name', 'tblbrv.type','tblbanks.name',
            'ref_no', 'tblbrv.created_at','tblbrv.status','tblbrv.updated_at')
            ->leftjoin('tblcust_sup', 'tblcust_sup.cust_sup_id', '=' ,'tblbrv.cust_id')
            ->leftjoin('tblbanks', 'tblbanks.id', '=' ,'tblbrv.bank_id');
        if (!empty($request['param'])) {
            $query = $query->where('cust_sup_name', "=", $request['param']);
        }
        if (!empty($request['param'])) {
            $query = $query->where('name', "=", $request['param']);


        }
        $brv = $query->orderBy('id', 'DESC')->get();
        // dd($brv);
        return $this->commonService->paginate($brv, Self::PER_PAGE);
    }


    public function preparetblbrvData($request)
    {
        return [
            'br_date' => $request['br_date'],
            'bank_id' => $request['bank_id'],
            'cust_id' => $request['cust_id'],
            'type' =>    $request['type'],
            'ref_no' => $request['ref_no'],
            'status' => config('constants.active')
        ];
    }

    public function preparetblbrv_detailData($request, $tblbrvid)
    {
        return [
            'account_code' => $request['account_code'],
            'remarks' => $request['remarks'],
            'debit_amount' =>  array_sum($request['debit_amount']),
            'status' => config('constants.active'),
            'br_id' => $tblbrvid,
        ];

    }
    public function saveBrv($data)
    {

        tblbrv_details::where('br_id', $data['br_id'])->delete();
        foreach ($data['account_code'] as $key => $value) {
            if (!empty($data['account_code'][$key])) {
                $request['account_code'] = $data['account_code'][$key];
                $request['remarks'] = $data['remarks'][$key];
                $request['debit_amount'] = $data['debit_amount'];
                $request['status'] = config('constants.active');
                $request['br_id'] = $data['br_id'];
                tblbrv_details::create($request);
            }
        }
    }


    // public function updateBrv($data)
    // {
    //     foreach ($data['account_code'] as $key => $value) {
    //         if (!empty($data['account_code'][$key])) {
    //             $request['account_code'] = $data['account_code'][$key];
    //             $request['remarks'] = $data['remarks'][$key];
    //             $request['debit_amount'] = $data['debit_amount'];
    //             $request['status'] = config('constants.active');
    //             $request['br_id'] = $data['br_id'];
    //             tblbrv_details::update($request);
    //         }
    //     }
    // }

    public function saveBrv_temp($data)
    {
        foreach ($data['account_code'] as $key => $value) {
            if (!empty($data['account_code'][$key])) {
                $request['account_code'] = $data['account_code'][$key];
                $request['remarks'] = $data['remarks'][$key];
                $request['debit_amount'] = $data['debit_amount'];
                $request['status'] = config('constants.active');
                $request['br_id'] = $data['br_id'];
                tblbrvDetails_temp::create($request);
            }
        }
    }


}
