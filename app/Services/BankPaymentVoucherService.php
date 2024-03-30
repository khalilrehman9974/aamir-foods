<?php

namespace App\Services;

/*
 * Class BankReceiptService
 * @package App\Services
 * */


use App\Issuance;
use App\Models\tblacc_code;
use App\Models\tblbpv;
use App\Models\tblbpv_details;
use App\Models\tblbpvDetailsTemp;
use App\Models\VoucherMaster;
use App\Models\VoucherType;
use Illuminate\Support\Facades\Auth;

class BankPaymentVoucherService
{
    const PER_PAGE = 10;
    const BPV_SAVED = 'BPV saved successfully';
    const BPVTEMP_SAVED = 'BPV SAVED AS DRAFT SUCCESSFULLY';
    const BPV_UPDATED = 'BPV updated successfully';
    const BPV_DELETED = 'BPV is deleted successfully';
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


    public function searchbpv($request)
    {

        $query = VoucherMaster::select('tblbpv.id', 'date', 'bank_id', 'sup_id','tblbanks.name', 'cust_sup_name'
            , 'tblbpv.created_at','tblbpv.status','tblbpv.updated_at')
            ->leftjoin('tblcust_sup', 'tblcust_sup.cust_sup_id', '=' ,'tblbpv.sup_id')
            ->leftjoin('tblbanks', 'tblbanks.id', '=' ,'tblbpv.bank_id');
        if (!empty($request['param'])) {
            $query = $query->where('cust_sup_name', "=", $request['param']);

        }
        if (!empty($request['param'])) {
            $query = $query->where('name', "=", $request['param']);

        }


        $brv = $query->orderBy('id', 'DESC')->get();

        return $this->commonService->paginate($brv, Self::PER_PAGE);
    }


    public function preparebpvData($request)
    {
        return [
            'date' => $request['date'],
            'vr_type_id' => VoucherType::where('name', 'BPV')->pluck('name','id'),
            'business_id' => $request->session()->get('business_id'),
            // 'f_year_id' => $request['f_year_id'],
            'f_year_id' => $request->session()->get('financial_year_id'),
            'total_amount' => $request['total_amount'],
            'created_by' => Auth::user()->id,
            'updated_by' => Auth::user()->id
        ];
    }

    public function preparetblbpv_detailData($request, $tblbpvid)
    {
        return [
            'account_code' => $request['account_code'],
            'remarks' => $request['remarks'],
            'credit_amount' =>  array_sum($request['credit_amount']),
            'status' => config('constants.active'),
            'bp_id' => $tblbpvid,
        ];

    }
    public function saveBpv($data)
    {
        tblbpv_details::where('bp_id', $data['bp_id'])->delete();
        foreach ($data['account_code'] as $key => $value) {
            if (!empty($data['account_code'][$key])) {
                $request['account_code'] = $data['account_code'][$key];
                $request['remarks'] = $data['remarks'][$key];
                $request['credit_amount'] = $data['credit_amount'];
                $request['status'] = config('constants.active');
                $request['bp_id'] = $data['bp_id'];
                tblbpv_details::create($request);
            }
        }
    }

    public function saveBpv_temp($data)
    {
        foreach ($data['account_code'] as $key => $value) {
            if (!empty($data['account_code'][$key])) {
                $request['account_code'] = $data['account_code'][$key];
                $request['remarks'] = $data['remarks'][$key];
                $request['credit_amount'] = $data['credit_amount'];
                $request['status'] = config('constants.active');
                $request['bp_id'] = $data['bp_id'];
                tblbpvDetailsTemp::create($request);
            }
        }
    }


}
