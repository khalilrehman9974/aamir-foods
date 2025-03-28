<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\BRVDetails;
use Illuminate\Http\Request;
use App\Models\AccountLedger;
use App\Services\CommonService;
use App\Models\CoaDetailAccount;
use App\Models\BankReceiptVoucher;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\VoucherRequest;
use App\Services\BankReceiptVoucherService;

class BRVoucherController extends Controller
{
    protected $commonService;
    protected $bankReceiptVoucherService;

    public function __construct(CommonService $commonService, BankReceiptVoucherService $bankReceiptVoucherService)
    {
        $this->commonService = $commonService;
        $this->bankReceiptVoucherService = $bankReceiptVoucherService;

    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pageTitle = 'List Of BR Vouchers';
        $request = request()->all();
        $vouchers = $this->bankReceiptVoucherService->searchVoucher($request);
        $param = request()->param;

        return view('vouchers.brv.index', compact('vouchers','param', 'pageTitle'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $pageTitle = 'Create BRV';
        $maxid = BankReceiptVoucher::max('id') + 1;
        $dropDownData = $this->bankReceiptVoucherService->DropDownData();
        return view('vouchers.brv.create', compact( 'pageTitle','maxid' ,'dropDownData'));
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


            $voucherMasterData = $this->bankReceiptVoucherService->prepareVoucherMasterData($request);
            $voucherMasterInsert = $this->bankReceiptVoucherService->findUpdateOrCreate(BankReceiptVoucher::class, ['id' => ''], $voucherMasterData);

            $voucherDetailData = $this->bankReceiptVoucherService->prepareVoucherDetailData($request, $voucherMasterInsert->id);
            $this->bankReceiptVoucherService->saveVoucherDetailData($voucherDetailData);


            // $voucherMasterData = $this->bankReceiptVoucherService->prepareVoucherMasterData($request);
            // $voucherMasterInsert = $this->commonService->findUpdateOrCreate(VoucherMaster::class, ['id' => ''], $voucherMasterData);
            // $voucherDetailCreditData = $this->bankReceiptVoucherService->prepareVoucherDetailCreditData($request, $voucherMasterInsert->id);
            // $voucherDetailDebitData = $this->bankReceiptVoucherService->prepareVoucherDetailDebitData($request, $voucherMasterInsert->id);
            // $this->bankReceiptVoucherService->saveVoucherCreditData($voucherDetailCreditData);
            // $this->bankReceiptVoucherService->saveVoucherDebitData($voucherDetailDebitData);


            // $debitAccountData = $this->bankReceiptVoucherService->prepareAccountDebitData($request, $voucherDetailDebitData, config('contants.BRV'), config('contants.Brv_bank_transaction'));
            // $creditAccountData = $this->bankReceiptVoucherService->prepareAccountCreditData($request, $voucherDetailCreditData, config('contants.BRV'), config('contants.Brv_party_transaction'));
            // AccountLedger::insert($debitAccountData);
            // AccountLedger::insert($creditAccountData);

        //     DB::commit();
        // } catch (\Exception $e) {
        //     DB::rollback();
        //     return redirect('brv/create')->with('error', $e->getMessage());
        // }
        return redirect('brv/list')->with('message', config('constants.add'));
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
        $pageTitle = 'Update BRV';
        $currentid= $id;
        $brv = BankReceiptVoucher::find($id);
        $date = Carbon::parse($brv->date)->format('d-m-Y');
        $brvDetails = BRVDetails::where('voucher_master_id', $id)->get();
        $dropDownData = $this->bankReceiptVoucherService->DropDownData();
        if (empty($voucher)) {
            $message = config('constants.wrong');
        }

        return view('vouchers.brv.edit', compact('brv','dropDownData','date','currentid','pageTitle','brvDetails'));
    }


    public function update(Request $request)
    {

        // DB::beginTransaction();
        // try {
            //Insert data into Vouchers tables.

            BRVDetails::where('voucher_master_id', $request['id'])->delete();
            $voucherMasterData = $this->bankReceiptVoucherService->prepareVoucherMasterData($request);
            $voucherMasterInsert = $this->bankReceiptVoucherService->findUpdateOrCreate(BankReceiptVoucher::class, ['id' => request('id')], $voucherMasterData);

            $voucherDetailData = $this->bankReceiptVoucherService->prepareVoucherDetailData($request, $voucherMasterInsert->id);
            $this->bankReceiptVoucherService->saveVoucherDetailData($voucherDetailData);

            // $debitAccountData = $this->bankReceiptVoucherService->prepareAccountDebitData($request, $voucherDetailDebitData, config('contants.BRV'), config('contants.Brv_bank_transaction'));
            // $creditAccountData = $this->bankReceiptVoucherService->prepareAccountCreditData($request, $voucherDetailCreditData, config('contants.BRV'), config('contants.Brv_party_transaction'));
            // AccountLedger::insert($debitAccountData);
            // AccountLedger::insert($creditAccountData);

        //     DB::commit();
        // } catch (\Exception $e) {
        //     DB::rollback();
        //     return redirect('brv/create')->with('error', $e->getMessage());
        // }
        return redirect('brv/list')->with('message', config('constants.update'));
    }

    public function print($id)
    {
        $title = 'Bank Receipt Voucher';
        $brvMaster = BankReceiptVoucher::find($id);
        $date = Carbon::parse($brvMaster->date)->format('d-m-Y');
        $brvDetails = BRVDetails::where('voucher_master_id', $brvMaster->id)->get();
        $partyArray = $brvDetails->pluck('account_id');
        $bankArray = $brvDetails->pluck('bank_id');
        $party = CoaDetailAccount::whereIn('id', $partyArray)->pluck('account_name', 'id');
        $banks = CoaDetailAccount::whereIn('id', $bankArray)->pluck('account_name', 'id');

        $user = User::where('id', $brvMaster->created_by)->value('name');

        return view('vouchers.brv.print', compact('title','user', 'brvMaster','brvDetails','banks', 'date', 'party'));
    }

    public function view($id)
    {
        $voucherMaster = $this->bankReceiptVoucherService->getVoucherMasterById($id);
        $voucherDetail = $this->bankReceiptVoucherService->getVoucherDetailById($id);
        if (empty($voucherMaster)) {
            $message = config('constants.wrong');
        }

        return view('vouchers.brv.view', compact('voucherMaster', 'voucherDetail'));
    }

}
