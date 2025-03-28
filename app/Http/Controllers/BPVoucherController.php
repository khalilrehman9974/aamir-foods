<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\BPVDetails;
use Illuminate\Http\Request;
use App\Models\AccountLedger;
use App\Services\CommonService;
use App\Models\CoaDetailAccount;
use App\Models\BankPaymentVoucher;
use Illuminate\Support\Facades\DB;
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
        $pageTitle = 'Create BPV';
        $maxid = BankPaymentVoucher::max('id') + 1;
        $dropDownData = $this->bankPaymentVoucherService->DropDownData();

        return view('vouchers.bpv.create', compact('pageTitle', 'dropDownData', 'maxid'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
       
        $request = $request->except('_token', 'id');
        // DB::beginTransaction();
        // try {

            //Insert data into Vouchers tables.
            $voucherMasterData = $this->bankPaymentVoucherService->prepareVoucherMasterData($request);
            $voucherMasterInsert = $this->bankPaymentVoucherService->findUpdateOrCreate(BankPaymentVoucher::class, ['id' => ''], $voucherMasterData);

            $voucherDetailData = $this->bankPaymentVoucherService->prepareVoucherDetailData($request, $voucherMasterInsert->id);
            $this->bankPaymentVoucherService->saveVoucherDetailData($voucherDetailData);

            //Insert data into accounts ledger table.
            // $debitAccountData = $this->bankPaymentVoucherService->prepareAccountDebitData($request, $voucherDetailDebitData);
            // $creditAccountData = $this->bankPaymentVoucherService->prepareAccountCreditData($request, $voucherDetailCreditData);
            // $this->bankPaymentVoucherService->saveCreditData($creditAccountData);
            // $this->bankPaymentVoucherService->saveDebitData($debitAccountData);

            // AccountLedger::insert($debitAccountData);
            // AccountLedger::insert($creditAccountData);


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
    public function show(Request $voucherMaster)
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
        $bpv = BankPaymentVoucher::find($id);
        $date = Carbon::parse($bpv->date)->format('d-m-Y');
        $bpvDetails = BPVDetails::where('voucher_master_id', $id )->get();
        $dropDownData = $this->bankPaymentVoucherService->DropDownData();
        if (empty($voucher)) {
            $message = config('constants.wrong');
        }

        return view('vouchers.bpv.edit', compact('bpv','date','dropDownData' ,'pageTitle','bpvDetails', 'currentid'));
    }

    public function update(Request $request)
    {
        // dd($request);
        // DB::beginTransaction();
        // try {

            //Insert data into Vouchers tables.
            BPVDetails::where('voucher_master_id', $request['id'])->delete();
            $voucherMasterData = $this->bankPaymentVoucherService->prepareVoucherMasterData($request);
            $voucherMasterInsert = $this->bankPaymentVoucherService->findUpdateOrCreate(BankPaymentVoucher::class, ['id' => request('id')], $voucherMasterData);

            $voucherDetailData = $this->bankPaymentVoucherService->prepareVoucherDetailData($request, $voucherMasterInsert->id);
            $this->bankPaymentVoucherService->saveVoucherDetailData($voucherDetailData);

            //Insert data into accounts ledger table.
            // $debitAccountData = $this->bankPaymentVoucherService->prepareAccountDebitData($request, $voucherDetailDebitData);
            // $creditAccountData = $this->bankPaymentVoucherService->prepareAccountCreditData($request, $voucherDetailCreditData);
            // $this->bankPaymentVoucherService->saveCreditData($creditAccountData);
            // $this->bankPaymentVoucherService->saveDebitData($debitAccountData);

            // AccountLedger::insert($debitAccountData);
            // AccountLedger::insert($creditAccountData);


        // DB::commit();
        // }
        // catch (\Exception $e) {
        //     DB::rollback();
        //     return redirect('bpv/create')->with('error', $e->getMessage());
        // }
        return redirect('bpv/list')->with('message', config('constants.update'));
    }

    public function print($id)
    {
        $title = 'Bank Payment Voucher';
        $bpvMaster = BankPaymentVoucher::find($id);
        $date = Carbon::parse($bpvMaster->date)->format('d-m-Y');
        $bpvDetails = BPVDetails::where('voucher_master_id', $bpvMaster->id)->get();
        $partyArray = $bpvDetails->pluck('account_id');
        $bankArray = $bpvDetails->pluck('bank_id');
        $party = CoaDetailAccount::whereIn('id', $partyArray)->pluck('account_name', 'id');
        $banks = CoaDetailAccount::whereIn('id', $bankArray)->pluck('account_name', 'id');

        $user = User::where('id', $bpvMaster->created_by)->value('name');

        return view('vouchers.bpv.print', compact('title','user', 'bpvMaster','bpvDetails','banks', 'date', 'party'));
    }

    public function view($id)
    {
        $voucherMaster = $this->bankPaymentVoucherService->getVoucherMasterById($id);
        // $voucherDetail = $this->bankPaymentVoucherService->getVoucherDetailById($id);
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
        $detailAccount = CoaDetailAccount::where('voucher_master_id', trim($id))->get();
        // dd($detailAccount);
        if ($detailAccount) {
            return response()->json(['status' => 'success', 'data' => []]);
        }
        return response()->json(['status' => 'fail', 'data' => []]);
    }
}
