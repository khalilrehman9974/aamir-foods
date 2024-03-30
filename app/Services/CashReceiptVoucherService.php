<?php

namespace App\Services;



use App\Issuance;
use App\Models\tblacc_code;
use App\Models\tblcrv;
use App\Models\tblcrv_details;
use App\Models\tblcrvDetailsTemp;
use Illuminate\Support\Facades\Auth;

class CashReceiptVoucherService
{
    const PER_PAGE = 10;
    const CRV_SAVED = 'CRV saved successfully';
    const CRVTEMP_SAVED = 'CRV SAVED AS DRAFT SUCCESSFULLY';
    const CRV_UPDATED = 'CRV updated successfully';
    const CRV_DELETED = 'CRV is deleted successfully';
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

    public function gettblcrvByid($id)
    {
        return tblcrv::leftjoin('tblcust_sup', 'tblcust_sup.cust_sup_id', '=','tblcrv.cust_id' )
            ->select('tblcrv.id as id', 'tblcrv.date', 'tblcust_sup.id as tblcust_supid')
            ->where('tblcrv.id', $id)
            ->first();
    }


    public function searchcrv($request)
    {

        $query = tblcrv::select('tblcrv.id', 'date',  'cust_id', 'tblcust_sup.cust_sup_name'
            , 'tblcrv.created_at','tblcrv.status','tblcrv.updated_at')
            ->leftjoin('tblcust_sup', 'tblcust_sup.cust_sup_id', '=' ,'tblcrv.cust_id');
        if (!empty($request['param'])) {
            $query = $query->where('cust_sup_name', "=", $request['param']);

        }

        $brv = $query->orderBy('id', 'DESC')->get();

        return $this->commonService->paginate($brv, Self::PER_PAGE);
    }


    public function preparetblcrvData($request)
    {
        return [
            'date' => $request['date'],
            'cust_id' => $request['cust_id'],
            'status' => config('constants.active')
        ];
    }

    public function preparetblcrv_detailData($request, $tblcrvid)
    {
        return [
            'account_code' => $request['account_code'],
            'remarks' => $request['remarks'],
            'debit_amount' =>  array_sum($request['debit_amount']),
            'status' => config('constants.active'),
            'cr_id' => $tblcrvid,
        ];

    }
    public function savecrv($data)
    {
        tblcrv_details::where('cr_id', $data['cr_id'])->delete();
        foreach ($data['account_code'] as $key => $value) {
            if (!empty($data['account_code'][$key])) {
                $request['account_code'] = $data['account_code'][$key];
                $request['remarks'] = $data['remarks'][$key];
                $request['debit_amount'] = $data['debit_amount'];
                $request['status'] = config('constants.active');
                $request['cr_id'] = $data['cr_id'];
                tblcrv_details::create($request);
            }
        }
    }

    public function savecrv_temp($data)
    {
        foreach ($data['account_code'] as $key => $value) {
            if (!empty($data['account_code'][$key])) {
                $request['account_code'] = $data['account_code'][$key];
                $request['remarks'] = $data['remarks'][$key];
                $request['debit_amount'] = $data['debit_amount'];
                $request['status'] = config('constants.active');
                $request['cr_id'] = $data['cr_id'];
                tblcrvDetailsTemp::create($request);
            }
        }
    }


}
