<?php

namespace App\Services;



use App\Issuance;
use App\Models\tblacc_code;
use App\Models\tblcpv;
use App\Models\tblcpv_details;
use App\Models\tblcpvDetailsTemp;
use Illuminate\Support\Facades\Auth;

class CashPaymentVoucherService
{
    const PER_PAGE = 10;
    const CPV_SAVED = 'CPV saved successfully';
    const CPVTEMP_SAVED = 'CPV SAVED AS DRAFT SUCCESSFULLY';
    const CPV_UPDATED = 'CPV updated successfully';
    const CPV_DELETED = 'CPV is deleted successfully';
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

    public function gettblcpvByid($id)
    {
        return tblcpv::leftjoin('tblcust_sup', 'tblcust_sup.cust_sup_id', '=','tblcpv.sup_id' )
            ->select('tblcpv.id as id', 'tblcpv.date', 'tblcust_sup.id as tblcust_supid')
            ->where('tblcpv.id', $id)
            ->first();
    }


    public function searchcpv($request)
    {

        $query = tblcpv::select('tblcpv.id', 'date',  'sup_id', 'tblcust_sup.cust_sup_name'
            , 'tblcpv.created_at','tblcpv.status','tblcpv.updated_at')
            ->leftjoin('tblcust_sup', 'tblcust_sup.cust_sup_id', '=' ,'tblcpv.sup_id');
        if (!empty($request['param'])) {
            $query = $query->where('cust_sup_name', "=", $request['param']);

        }

        $brv = $query->orderBy('id', 'DESC')->get();

        return $this->commonService->paginate($brv, Self::PER_PAGE);
    }


    public function preparetblcpvData($request)
    {
        return [
            'date' => $request['date'],
            'sup_id' => $request['sup_id'],
            'status' => config('constants.active')
        ];
    }

    public function preparetblcpv_detailData($request, $tblcpvid)
    {
        return [
            'account_code' => $request['account_code'],
            'remarks' => $request['remarks'],
            'credit_amount' =>  array_sum($request['credit_amount']),
            'status' => config('constants.active'),
            'cp_id' => $tblcpvid,
        ];

    }
    public function savecpv($data)
    {
        tblcpv_details::where('cp_id', $data['cp_id'])->delete();
        foreach ($data['account_code'] as $key => $value) {
            if (!empty($data['account_code'][$key])) {
                $request['account_code'] = $data['account_code'][$key];
                $request['remarks'] = $data['remarks'][$key];
                $request['credit_amount'] = $data['credit_amount'];
                $request['status'] = config('constants.active');
                $request['cp_id'] = $data['cp_id'];
                tblcpv_details::create($request);
            }
        }
    }

    public function savecpv_temp($data)
    {
        foreach ($data['account_code'] as $key => $value) {
            if (!empty($data['account_code'][$key])) {
                $request['account_code'] = $data['account_code'][$key];
                $request['remarks'] = $data['remarks'][$key];
                $request['credit_amount'] = $data['credit_amount'];
                $request['status'] = config('constants.active');
                $request['cp_id'] = $data['cp_id'];
                tblcpvDetailsTemp::create($request);
            }
        }
    }


}
