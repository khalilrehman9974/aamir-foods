<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\CPVDetails;
use Illuminate\Http\Request;
use App\Models\AccountLedger;
use App\Services\CommonService;
use App\Models\CashPaymentVoucher;
use App\Services\CPVoucherService;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\VoucherRequest;

class CPVoucherController extends Controller
{
    protected $commonService;
    protected $cpVoucherService;

    public function __construct(CommonService $commonService, CPVoucherService $cpVoucherService)
    {
        $this->commonService = $commonService;
        $this->cpVoucherService = $cpVoucherService;

    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pageTitle = 'List Of CP Vouchers';
        $request = request()->all();
        $vouchers = $this->cpVoucherService->searchVoucher($request);
        $param = request()->param;

        return view('vouchers.cpv.index', compact('vouchers','param', 'pageTitle'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $pageTitle = 'Create CPV';
        $maxid = CashPaymentVoucher::max('id') + 1;
        $dropDownData = $this->cpVoucherService->DropDownData();
        return view('vouchers.cpv.create', compact( 'pageTitle','maxid', 'dropDownData'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        // $request = $request->except('_token', 'id');
        // DB::beginTransaction();
        // try {
            //Insert data into purchase tables.

            // CRVDetails::where('voucher_master_id', $request['id'])->delete();
            //Insert data into purchase tables.
            $voucherMasterData = $this->cpVoucherService->prepareVoucherMasterData($request);
            $voucherMasterInsert = $this->cpVoucherService->findUpdateOrCreate(CashPaymentVoucher::class, ['id' => ''], $voucherMasterData);

            $voucherDetailData = $this->cpVoucherService->prepareVoucherDetailData($request, $voucherMasterInsert->id);
            $this->cpVoucherService->saveVoucherDetailData($voucherDetailData);

            //Insert data into accounts ledger table.
            // $debitAccountData = $this->cpVoucherService->prepareAccountDebitData($request, $voucherDetailDebitData, config('contants.CPV'), config('contants.Cpv_party_transaction'));
            // $creditAccountData = $this->cpVoucherService->prepareAccountCreditData($request, $voucherDetailCreditData, config('contants.CPV'), config('contants.Cpv_cash_in_hand'));
            // AccountLedger::insert($debitAccountData);
            // AccountLedger::insert($creditAccountData);

        //     DB::commit();
        // } catch (\Exception $e) {
        //     DB::rollback();
        //     return redirect('cpv/create')->with('error', $e->getMessage());
        // }
        return redirect('cpv/list')->with('message', config('constants.add'));
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
        $pageTitle = 'Update CPV';
        $currentid= $id;
        $cpv = CashPaymentVoucher::find($id);
        $date = Carbon::parse($cpv->date)->format('d-m-Y');
        $cpvDetails = CPVDetails::where('voucher_master_id', $id)->get();
        $dropDownData = $this->cpVoucherService->DropDownData();
        if (empty($cpv)) {
            $message = config('constants.wrong');
        }

        return view('vouchers.cpv.edit', compact('pageTitle','dropDownData','date','cpv', 'cpvDetails','currentid'));
    }

    public function update(Request $request)
    {

        // $request = $request->except('_token', 'id');
        // DB::beginTransaction();
        // try {
            //Insert data into purchase tables.

            CPVDetails::where('voucher_master_id', $request['id'])->delete();
            //Insert data into purchase tables.
            $voucherMasterData = $this->cpVoucherService->prepareVoucherMasterData($request);
            $voucherMasterInsert = $this->cpVoucherService->findUpdateOrCreate(CashPaymentVoucher::class, ['id' => request('id')], $voucherMasterData);

            $voucherDetailData = $this->cpVoucherService->prepareVoucherDetailData($request, $voucherMasterInsert->id);
            $this->cpVoucherService->saveVoucherDetailData($voucherDetailData);

            //Insert data into accounts ledger table.
            // $debitAccountData = $this->cpVoucherService->prepareAccountDebitData($request, $voucherDetailDebitData, config('contants.CPV'), config('contants.Cpv_party_transaction'));
            // $creditAccountData = $this->cpVoucherService->prepareAccountCreditData($request, $voucherDetailCreditData, config('contants.CPV'), config('contants.Cpv_cash_in_hand'));
            // AccountLedger::insert($debitAccountData);
            // AccountLedger::insert($creditAccountData);

        //     DB::commit();
        // } catch (\Exception $e) {
        //     DB::rollback();
        //     return redirect('cpv/create')->with('error', $e->getMessage());
        // }
        return redirect('cpv/list')->with('message', config('constants.update'));
    }

    public function view($id)
    {
        $voucherMaster = $this->cpVoucherService->getVoucherMasterById($id);
        $voucherDetail = $this->cpVoucherService->getVoucherDetailById($id);
        if (empty($voucherMaster)) {
            $message = config('constants.wrong');
        }

        return view('vouchers.cpv.view', compact('voucherMaster', 'voucherDetail'));
    }

}
