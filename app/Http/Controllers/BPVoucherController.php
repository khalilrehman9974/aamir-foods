<?php

namespace App\Http\Controllers;

use App\Models\VoucherDetail;
use App\Models\VoucherMaster;
use App\Services\CommonService;
use App\Models\CoaDetailAccount;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\VoucherRequest;
use App\Services\BankPaymentVoucherService;

class BPVoucherController extends Controller
{
    protected $commonService;
    protected $bankPaymentVoucherService;

    public function __construct(CommonService $commonService, BankPaymentVoucherService $bankPaymentVoucherService)
    {
        $this->commonService = $commonService;
        $this->bankPaymentVoucherService = $bankPaymentVoucherService;

    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pageTitle = 'List Of BPVouchers';
        $request = request()->all();
        $vouchers = $this->bankPaymentVoucherService->searchVoucher($request);
        $param = request()->param;

        return view('vouchers.bpv.index', compact('vouchers','param', 'pageTitle'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $pageTitle = 'Create BPV';
        $maxid = VoucherMaster::where('vr_type_id', 'BPV')->max('id') + 1;
        $dropDownData = $this->bankPaymentVoucherService->DropDownData();
        return view('vouchers.bpv.create', compact( 'pageTitle','dropDownData','maxid'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(VoucherRequest $request)
    {
        $session = $this->commonService->getSession();
        $request = $request->except('_token', 'id');
        DB::beginTransaction();
        try {
            //Insert data into Vouchers tables.
            $request['business_id'] = $session->business_id;
            $request['f_year_id'] = $session->financial_year;
            $voucherMasterData = $this->bankPaymentVoucherService->prepareVoucherMasterData($request);
            $voucherMasterInsert = $this->commonService->findUpdateOrCreate(VoucherMaster::class, ['id' => ''], $voucherMasterData);
            $voucherDetailCreditData = $this->bankPaymentVoucherService->prepareVoucherDetailCreditData($request, $voucherMasterInsert->id);
            $voucherDetailDebitData = $this->bankPaymentVoucherService->prepareVoucherDetailDebitData($request, $voucherMasterInsert->id);
            $this->bankPaymentVoucherService->saveVoucher($voucherDetailCreditData);
            $this->bankPaymentVoucherService->saveVoucher($voucherDetailDebitData);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            return redirect('bpv/create')->with('error', $e->getMessage());
        }
        return redirect('bpv/list')->with('message', config('constants.add'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\VoucherMaster  $voucherMaster
     * @return \Illuminate\Http\Response
     */
    public function show(VoucherMaster $voucherMaster)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\VoucherMaster  $voucherMaster
     * @return \Illuminate\Http\Response
     */
    public function edit(VoucherMaster $voucherMaster, $id)
    {
        $currentid= $id;
        $voucher = VoucherMaster::find($id);
        $voucherDetails = VoucherDetail::where('voucher_master_id', $id)->get();
        if (empty($voucher)) {
            $message = config('constants.wrong');
        }

        return view('vouchers.bpv.create', compact('voucher', 'voucherDetails','currentid'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\VoucherMaster  $voucherMaster
     * @return \Illuminate\Http\Response
     */
    public function destroy(VoucherMaster $voucherMaster)
    {
        DB::beginTransaction();
        try {
            $deleteMaster = VoucherMaster::where('id', request()->id)->delete();
            $deleteDetail = VoucherDetail::where('voucher_master_id', request()->id)->delete();
            DB::commit();
            // && $deleteStock && $accountEntryDetail
            if ($deleteMaster && $deleteDetail ) {
                return $this->commonService->deleteResource(VoucherMaster::class, VoucherDetail::class);
            }

        } catch (\Exception $e) {
            DB::rollback();
            return redirect('bpv/list')->with('error', $e->getMessage());
        }

    }

    public function view($id)
    {
        $voucherMaster = $this->bankPaymentVoucherService->getVoucherMasterById($id);
        $voucherDetail = $this->bankPaymentVoucherService->getVoucherDetailById($id);
        if (empty($voucherMaster)) {
            $message = config('constants.wrong');
        }

        return view('vouchers.bpv.view', compact('voucherMaster', 'voucherDetail'));
    }

    public function getPartyCode($code)
    {
        // $detailAccount = CoaDetailAccount::find($code)->select('account_name')->where('account_name',$code)->pluck('account_code');
        $detailAccount = CoaDetailAccount::find($code)->pluck('account_code');
        // $detailAccount = $this->bankPaymentVoucherService->getPartyCode($party);
        if ($detailAccount) {
            return response()->json(['status' => 'success', 'code' => $detailAccount]);
        }
        return response()->json(['status' => 'fail', 'data' => []]);
    }

    public function getParty($code)
    {
        $detailAccount = CoaDetailAccount::select('account_name')->where('account_code',$code)->get();
        if ($detailAccount) {
            return response()->json(['status' => 'success', 'account_name' => $detailAccount]);
        }
        return response()->json(['status' => 'fail', 'data' => []]);
    }

}
