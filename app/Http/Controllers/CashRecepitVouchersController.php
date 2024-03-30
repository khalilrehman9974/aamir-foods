<?php

namespace App\Http\Controllers;
use App\Models\tblcrv;
use App\Models\tblcrvTemp;
use App\Models\tblacc_code;
use App\Models\tblcust_sup;

use App\Models\tblcrv_details;
use App\Services\CommonService;
use App\Models\tblcrvDetailsTemp;

use Illuminate\Support\Facades\DB;
use App\Services\tblacc_codeService;
use App\Services\tblcrv_detailService;
use App\Services\CashReceiptVoucherService;
use App\Http\Requests\CashReceiptVoucherRequest;


use Illuminate\Http\Request;

class CashRecepitVouchersController extends Controller
{
    protected $commonService;
    protected $CashReceiptVoucherService;
    protected $tblcrv_detailService;
    protected $tblacc_codeService;
    public function __construct(tblacc_codeService $tblacc_codeService, tblcrv_detailService $tblcrv_detailService, CommonService $commonService, CashReceiptVoucherService $CashReceiptVoucherService)
    {
        $this->commonService = $commonService;
        $this->tblcrv_detailService = $tblcrv_detailService;
        $this->CashReceiptVoucherService = $CashReceiptVoucherService;
        $this->tblacc_codeService = $tblacc_codeService;
    }

    public function index()
    {


        $request = request()->all();
        $crvs = $this->CashReceiptVoucherService->searchcrv($request);

        return view('vouchers.crv.index', compact('crvs', 'request'));
    }

    public function create()
    {
        $crvTemp = tblcrvTemp::first();

        $crvDetailsTemp = tblcrvDetailsTemp::get();
        $crv_details = tblcrv_details::where('cr_id')->get();

        $acc_codes = tblacc_code::pluck('account_name', 'id');
        $maxid = tblcrv::max('id') + 1;
        $tblcust_sups = tblcust_sup::where('type', 'customer')->pluck('cust_sup_name','cust_sup_id');
        // $dropDownData = $this->commonService->vouchersDropDownData();

        return view('vouchers.crv.create', compact( 'crvTemp', 'crv_details', 'tblcust_sups', 'crvDetailsTemp', 'acc_codes','maxid'));
    }

    public function store(CashReceiptVoucherRequest $request)
    {

        $request = $request->except('_token', 'id');
        DB::beginTransaction();
        try
        {
            if ($request["save_type"] == config('constants.in_active')) {

                $tblcrv = $this->CashReceiptVoucherService->preparetblcrvData($request);
                $tblcrvInsert = $this->commonService->findUpdateOrCreate(tblcrvTemp::class, ['id' => ''], $tblcrv);
                $crvDetail = $this->CashReceiptVoucherService->preparetblcrv_detailData($request, $tblcrvInsert->id);
                $this->CashReceiptVoucherService->savecrv_temp($crvDetail);
            } else  {
                $tblcrv = $this->CashReceiptVoucherService->preparetblcrvData($request);
                $tblcrvInsert = $this->commonService->findUpdateOrCreate(tblcrv::class, ['id' => ''], $tblcrv);
                $crvDetails = $this->CashReceiptVoucherService->preparetblcrv_detailData($request, $tblcrvInsert->id);
                $this->CashReceiptVoucherService->savecrv($crvDetails);
                tblcrvTemp::truncate();
                tblcrvDetailsTemp::truncate();

            }
            DB::commit();
            //
            return redirect('crv/list')->with('message', CashReceiptVoucherService::CRV_SAVED);
        } catch (\Exception $e) {
            DB::rollback();
            return redirect('crv/create')->with('message', $e->getMessage());
        }
    }

    public function edit($id)
    {
        $currid= $id;
        $crv = tblcrv::find($id);
        $crv_details = tblcrv_details::where('cr_id', $id)->get();

        $acc_codes = tblacc_code::pluck('account_name', 'id');
        $tblcust_sups = tblcust_sup::where('type', 'customer')->pluck('cust_sup_name','cust_sup_id');
        // $dropDownData = $this->commonService->vouchersDropDownData();

        if (empty($crv)) {
            abort(404);
        }

        return view('vouchers.crv.create', compact('crv', 'currid', 'acc_codes',  'tblcust_sups', 'crv_details'));
    }
    public function update(CashReceiptVoucherRequest $request)
    {
        DB::beginTransaction();
        try {

            $request = request()->all();


            //Save data into relevant tables.
            $tblcrv = $this->CashReceiptVoucherService->preparetblcrvData($request);
            $tblcrvInsert = $this->commonService->findUpdateOrCreate(tblcrv::class, ['id' => request('id')], $tblcrv);
            $tblcrv_details = $this->CashReceiptVoucherService->preparetblcrv_detailData($request, $tblcrvInsert->id);
            $this->CashReceiptVoucherService->savecrv($tblcrv_details);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            return redirect('crv/create')->with('error', $e->getMessage());
        }

        return redirect('crv/list')->with('message', CashReceiptVoucherService::CRV_UPDATED);
    }


    public function delete()
    {
        try {
            DB::beginTransaction();
            $deletecrv = tblcrv::where('id', request()->id)->delete();
            $deletecrv_details = tblcrv_details::where('id', request()->id)->delete();
            DB::commit();
            if ($deletecrv && $deletecrv_details) {
                return response()->json(['status' => 'success', 'message' => CashReceiptVoucherService::CRV_DELETED]);
            } else {
                return response()->json(['status' => 'fail', 'message' => CashReceiptVoucherService::SOME_THING_WENT_WRONG]);
            }
        } catch (\Exception $e) {
            DB::rollback();
            return redirect('crv/list')->with('error', $e->getMessage());
        }
    }
    public function gettblacc_code()
    {
        $acc_code = $this->tblacc_codeService->getById(request('account_name'));
        if ($acc_code) {
            return json_encode(['status' => 'success', 'data' => $acc_code]);
        } else {
            return response()->json(['status' => 'fail', 'message' => CashReceiptVoucherService::SOME_THING_WENT_WRONG]);
        }
    }
}
