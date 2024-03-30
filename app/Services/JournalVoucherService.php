<?php

namespace App\Services;



use App\Issuance;
use App\Models\tblacc_code;
use App\Models\tbljv;
use App\Models\tbljv_details;
use App\Models\tbljvDetailsTemp;
use Illuminate\Support\Facades\Auth;

class JVService
{
    const PER_PAGE = 10;
    const JV_SAVED = 'JV saved successfully';
    const JVTEMP_SAVED = 'JV SAVED AS DRAFT SUCCESSFULLY';
    const JV_UPDATED = 'JV updated successfully';
    const JV_DELETED = 'JV is deleted successfully';
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

    public function gettbljvByid($id)
    {
        return tbljv::leftjoin('tblcust_sup', 'tblcust_sup.cust_sup_id', '=','tbljv.cust_sup_id' )
            ->select('tbljv.id as id', 'tbljv.date', 'tblcust_sup.id as tblcust_supid')
            ->where('tbljv.id', $id)
            ->first();
    }


    public function searchjv($request)
    {

        $query = tbljv::select('tbljv.id', 'date',  'tbljv.cust_sup_id', 'tblcust_sup.cust_sup_name'
            , 'tbljv.created_at','tbljv.status','tbljv.updated_at')
            ->leftjoin('tblcust_sup ', 'tblcust_sup.cust_sup_id', '=' ,'tbljv.cust_sup_id');
        if (!empty($request['param'])) {
            $query = $query->where('cust_sup_name', "=", $request['param']);

        }

        $jv = $query->orderBy('id', 'DESC')->get();

        return $this->commonService->paginate($jv, Self::PER_PAGE);
    }


    public function preparetbljvData($request)
    {
        return [
            'date' => $request['date'],
            'cust_sup_id' => $request['cust_sup_id'],
            'status' => config('constants.active')
        ];
    }

    public function preparetbljv_detailData($request, $tbljvid)
    {
        return [
            'account_code' => $request['account_code'],
            'remarks' => $request['remarks'],
            'debit_amount' =>  array_sum($request['debit_amount']),
            'credit_amount' =>  array_sum($request['credit_amount']),
            'status' => config('constants.active'),
            'jv_id' => $tbljvid,
        ];

    }
    public function savejv($data)
    {
        tbljv_details::where('jv_id', $data['jv_id'])->delete();
        foreach ($data['account_code'] as $key => $value) {
            if (!empty($data['account_code'][$key])) {
                $request['account_code'] = $data['account_code'][$key];
                $request['remarks'] = $data['remarks'][$key];
                $request['debit_amount'] = $data['debit_amount'];
                $request['credit_amount'] = $data['credit_amount'];
                $request['status'] = config('constants.active');
                $request['jv_id'] = $data['jv_id'];
                tbljv_details::create($request);
            }
        }
    }

    public function savejv_temp($data)
    {
        foreach ($data['account_code'] as $key => $value) {
            if (!empty($data['account_code'][$key])) {
                $request['account_code'] = $data['account_code'][$key];
                $request['remarks'] = $data['remarks'][$key];
                $request['debit_amount'] = $data['debit_amount'];
                $request['credit_amount'] = $data['credit_amount'];
                $request['status'] = config('constants.active');
                $request['jv_id'] = $data['jv_id'];
                tbljvDetailsTemp::create($request);
            }
        }
    }


}
