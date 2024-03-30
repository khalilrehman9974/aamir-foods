<?php

namespace App\Http\Controllers;

use App\Models\tblcpv;
use App\Models\tblcpvTemp;
use App\Models\tblacc_code;
use App\Models\tblcust_sup;
use Illuminate\Http\Request;
use App\Models\tblcpv_details;
use App\Services\CommonService;
use App\Models\tblcpvDetailsTemp;

use Illuminate\Support\Facades\DB;
use App\Services\tblacc_codeService;
use App\Services\tblcpv_detailService;
use App\Services\CashPaymentVoucherService;
use App\Http\Requests\CashPaymentVoucherRequest;

class CashPaymentVouchersController extends Controller
{
    protected $commonService;
    protected $CashPaymentVoucherService;
    protected $tblcpv_detailService;
    protected $tblacc_codeService;
    public function __construct(tblacc_codeService $tblacc_codeService, tblcpv_detailService $tblcpv_detailService, CommonService $commonService, CashPaymentVoucherService $CashPaymentVoucherService)
    {
        $this->commonService = $commonService;
        $this->tblcpv_detailService = $tblcpv_detailService;
        $this->CashPaymentVoucherService = $CashPaymentVoucherService;
        $this->tblacc_codeService = $tblacc_codeService;
    }

    public function index()
    {


        $request = request()->all();
        $cpvs = $this->CashPaymentVoucherService->searchcpv($request);

        return view('vouchers.cpv.index', compact('cpvs', 'request'));
    }

    public function create()
    {
        $cpvTemp = tblcpvTemp::first();

        $cpvDetailsTemp = tblcpvDetailsTemp::get();
        $cpv_details = tblcpv_details::where('cp_id')->get();

        $acc_codes = tblacc_code::pluck('account_name', 'id');
        $maxid = tblcpv::max('id') + 1;
        $tblcust_sups = tblcust_sup::where('type', 'supplier')->pluck('cust_sup_name','cust_sup_id');
        // $dropDownData = $this->commonService->vouchersDropDownData();

        return view('vouchers.cpv.create', compact( 'cpvTemp', 'cpv_details', 'tblcust_sups', 'cpvDetailsTemp', 'acc_codes','maxid'));
    }

    public function store(CashPaymentVoucherRequest $request)
    {

        $request = $request->except('_token', 'id');
        DB::beginTransaction();
        try
        {
            if ($request["save_type"] == config('constants.in_active')) {

                $tblcpv = $this->CashPaymentVoucherService->preparetblcpvData($request);
                $tblcpvInsert = $this->commonService->findUpdateOrCreate(tblcpvTemp::class, ['id' => ''], $tblcpv);
                $cpvDetail = $this->CashPaymentVoucherService->preparetblcpv_detailData($request, $tblcpvInsert->id);
                $this->CashPaymentVoucherService->savecpv_temp($cpvDetail);
            } else  {
                $tblcpv = $this->CashPaymentVoucherService->preparetblcpvData($request);
                $tblcpvInsert = $this->commonService->findUpdateOrCreate(tblcpv::class, ['id' => ''], $tblcpv);
                $cpvDetails = $this->CashPaymentVoucherService->preparetblcpv_detailData($request, $tblcpvInsert->id);
                $this->CashPaymentVoucherService->savecpv($cpvDetails);
                tblcpvTemp::truncate();
                tblcpvDetailsTemp::truncate();

            }
            DB::commit();
            //
            return redirect('cpv/list')->with('message', CashPaymentVoucherService::CPV_SAVED);
        } catch (\Exception $e) {
            DB::rollback();
            return redirect('cpv/create')->with('message', $e->getMessage());
        }
    }

    public function edit($id)
    {
        $currid= $id;
        $cpv = tblcpv::find($id);
        $cpv_details = tblcpv_details::where('cp_id', $id)->get();

        $acc_codes = tblacc_code::pluck('account_name', 'id');
        $tblcust_sups = tblcust_sup::where('type', 'supplier')->pluck('cust_sup_name','cust_sup_id');
        // $dropDownData = $this->commonService->vouchersDropDownData();

        if (empty($cpv)) {
            abort(404);
        }

        return view('vouchers.cpv.create', compact('cpv','currid', 'acc_codes',  'tblcust_sups', 'cpv_details'));
    }
    public function update(CashPaymentVoucherRequest $request)
    {
        DB::beginTransaction();
        try {

            $request = request()->all();


            //Save data into relevant tables.
            $tblcpv = $this->CashPaymentVoucherService->preparetblcpvData($request);
            $tblcpvInsert = $this->commonService->findUpdateOrCreate(tblcpv::class, ['id' => request('id')], $tblcpv);
            $tblcpv_details = $this->CashPaymentVoucherService->preparetblcpv_detailData($request, $tblcpvInsert->id);
            $this->CashPaymentVoucherService->savecpv($tblcpv_details);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            return redirect('cpv/create')->with('error', $e->getMessage());
        }

        return redirect('cpv/list')->with('message', CashPaymentVoucherService::CPV_UPDATED);
    }


    public function delete()
    {
        try {
            DB::beginTransaction();
            $deletecpv = tblcpv::where('id', request()->id)->delete();
            $deletecpv_details = tblcpv_details::where('id', request()->id)->delete();
            DB::commit();
            if ($deletecpv && $deletecpv_details) {
                return response()->json(['status' => 'success', 'message' => CashPaymentVoucherService::CPV_DELETED]);
            } else {
                return response()->json(['status' => 'fail', 'message' => CashPaymentVoucherService::SOME_THING_WENT_WRONG]);
            }
        } catch (\Exception $e) {
            DB::rollback();
            return redirect('cpv/list')->with('error', $e->getMessage());
        }
    }
    public function gettblacc_code()
    {
        $acc_code = $this->tblacc_codeService->getById(request('account_name'));
        if ($acc_code) {
            return json_encode(['status' => 'success', 'data' => $acc_code]);
        } else {
            return response()->json(['status' => 'fail', 'message' => CashPaymentVoucherService::SOME_THING_WENT_WRONG]);
        }
    }

}
