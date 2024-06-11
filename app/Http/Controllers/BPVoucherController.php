<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AccountLedger;
use App\Models\VoucherDetail;
use App\Models\VoucherMaster;
use App\Services\CommonService;
use App\Models\CoaDetailAccount;
use App\Models\VoucherDetailTemp;
use App\Models\VoucherMasterTemp;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\VoucherRequest;
use App\Services\AccountLedgerService;
use App\Services\BankPaymentVoucherService;


class BPVoucherController extends Controller
{
    protected $commonService;
    protected $bankPaymentVoucherService;
    protected $accountLedgerService;

    public function __construct(
        CommonService $commonService,
        BankPaymentVoucherService $bankPaymentVoucherService,
        AccountLedgerService $accountLedgerService
    ) {
        $this->commonService = $commonService;
        $this->bankPaymentVoucherService = $bankPaymentVoucherService;
        $this->accountLedgerService = $accountLedgerService;
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

        return view('vouchers.bpv.index', compact('vouchers', 'param', 'pageTitle'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $bpvTemp = VoucherMasterTemp::first();
        $bpvDetailsTemp = VoucherDetailTemp::get();
        $pageTitle = 'Create BPV';
        $maxid = VoucherMaster::where('vr_type', 'BPV')->max('id') + 1;
        $dropDownData = $this->bankPaymentVoucherService->DropDownData();
        return view('vouchers.bpv.create', compact('pageTitle','bpvTemp','bpvDetailsTemp', 'dropDownData', 'maxid'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request);
        $session = $this->commonService->getSession();
        $request = $request->except('_token', 'id');
        // DB::beginTransaction();
        // try {
        if ($request["save_type"] == config('constants.in_active')) {
            $bpvTemp = $this->bankPaymentVoucherService->prepareVoucherMasterData($request);
            $tblbpvInsert = $this->bankPaymentVoucherService->findUpdateOrCreate(VoucherMasterTemp::class, ['id' => ''], $bpvTemp);
            $bpvTempDetailDebitData = $this->bankPaymentVoucherService->prepareVoucherDetailTempData($request, $tblbpvInsert->id);
            $this->bankPaymentVoucherService->saveVoucherTempData($bpvTempDetailDebitData);

        }
        else {

            //Insert data into Vouchers tables.
            $request['business_id'] = $session->business_id;
            $request['f_year_id'] = $session->financial_year;
            $voucherMasterData = $this->bankPaymentVoucherService->prepareVoucherMasterData($request);
            $voucherMasterInsert = $this->bankPaymentVoucherService->findUpdateOrCreate(VoucherMaster::class, ['id' => ''], $voucherMasterData);
            // $voucherCreditData = $this->bankPaymentVoucherService->prepareVoucherCreditData($request);
            $voucherDetailCreditData = $this->bankPaymentVoucherService->prepareVoucherDetailCreditData($request, $voucherMasterInsert->id);
            $voucherDetailDebitData = $this->bankPaymentVoucherService->prepareVoucherDetailDebitData($request, $voucherMasterInsert->id);
            $this->bankPaymentVoucherService->saveVoucherCreditData($voucherDetailCreditData);
            $this->bankPaymentVoucherService->saveVoucherDebitData($voucherDetailDebitData);

            //Insert data into accounts ledger table.
            $debitAccountData = $this->bankPaymentVoucherService->prepareAccountDebitData($request, $voucherDetailDebitData);
            $creditAccountData = $this->bankPaymentVoucherService->prepareAccountCreditData($request, $voucherDetailCreditData);
            $this->bankPaymentVoucherService->saveCreditData($creditAccountData);
            $this->bankPaymentVoucherService->saveDebitData($debitAccountData);

            // AccountLedger::insert($debitAccountData);
            // AccountLedger::insert($creditAccountData);

            VoucherMasterTemp::where('id', request()->id)->delete();
            VoucherDetailTemp::where('voucher_master_id', request()->id)->delete();
        }

        // DB::commit();
        // }
        // catch (\Exception $e) {
        //     DB::rollback();
        //     return redirect('bpv/create')->with('error', $e->getMessage());
        // }
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
    public function edit($id)
    {
        $pageTitle = 'Edit BPV';
        $currentid = $id;
        $bpv = VoucherMaster::find($id);
        $voucherDetails = VoucherDetail::where('voucher_master_id', $id )->where('debit', '>', 0)->get();
        // dd($voucherDetails);
        $bankId = VoucherDetail::where('voucher_master_id', $id )->where('credit', '>', 0)->first('account_id');
        $dropDownData = $this->bankPaymentVoucherService->DropDownData();
        if (empty($voucher)) {
            $message = config('constants.wrong');
        }

        return view('vouchers.bpv.create', compact('bpv','bankId','dropDownData' ,'pageTitle','voucherDetails', 'currentid'));
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
            if ($deleteMaster && $deleteDetail) {
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

    public function getPartyCode($name)
    {
        $detailAccount = CoaDetailAccount::where('account_name', trim($name))->first('account_code');
        // $detailAccount = CoaDetailAccount::where('account_name', $code)->pluck('account_code');
        if ($detailAccount) {
            return response()->json(['status' => 'success', 'account_code' => $detailAccount->account_code]);
        }
        return response()->json(['status' => 'fail', 'data' => []]);
    }

    public function getParty($code)
    {
        $detailAccount = CoaDetailAccount::where('account_code', trim($code))->first('account_name');
        if ($detailAccount) {
            return response()->json(['status' => 'success', 'account_name' => $detailAccount->account_name]);
        }
        return response()->json(['status' => 'fail', 'data' => []]);
    }

    public function getDetailData($id)
    {
        dd($id);
        $detailAccount = CoaDetailAccount::where('voucher_master_id', trim($id))->get();
        // dd($detailAccount);
        if ($detailAccount) {
            return response()->json(['status' => 'success', 'data' => []]);
        }
        return response()->json(['status' => 'fail', 'data' => []]);
    }
}
