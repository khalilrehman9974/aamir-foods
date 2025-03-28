<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\AccountLedger;
use App\Services\CommonService;
use App\Models\CoaDetailAccount;
use Illuminate\Support\Facades\DB;
use App\Models\JournalVoucherDetail;
use App\Models\JournalVoucherMaster;
use App\Http\Requests\VoucherRequest;
use App\Services\JournalVoucherService;


class JournalVoucherController extends Controller
{
    protected $commonService;
    protected $journalVoucherService;

    public function __construct(CommonService $commonService, JournalVoucherService $journalVoucherService)
    {
        $this->commonService = $commonService;
        $this->journalVoucherService = $journalVoucherService;

    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pageTitle = 'List Of JVouchers';
        $request = request()->all();
        $vouchers = $this->journalVoucherService->searchVoucher($request);
        $param = request()->param;

        return view('vouchers.jv.index', compact('vouchers','param', 'pageTitle'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $pageTitle = 'Create Voucher';
        $maxid = JournalVoucherMaster::max('id') + 1;
        $dropDownData = $this->journalVoucherService->DropDownData();
        return view('vouchers.jv.create', compact('dropDownData','maxid','pageTitle'));
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
            //Insert data into Voucher tables.

            $voucherMasterData = $this->journalVoucherService->prepareVoucherMasterData($request);
            $voucherMasterInsert = $this->commonService->findUpdateOrCreate(JournalVoucherMaster::class, ['id' => ''], $voucherMasterData);
            $voucherDetailData = $this->journalVoucherService->prepareVoucherDetailData($request, $voucherMasterInsert->id);
            $this->journalVoucherService->saveVoucher($voucherDetailData);


            // $AccountData = $this->journalVoucherService->prepareAccountData($request, $voucherDetailData, config('contants.JV'),config('contants.Jv_transaction') );
            // AccountLedger::insert($AccountData);

        //     DB::commit();
        // } catch (\Exception $e) {
        //     DB::rollback();
        //     return redirect('jv/create')->with('error', $e->getMessage());
        // }
        return redirect('jv/list')->with('message', config('constants.add'));
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\VoucherMaster  $voucherMaster
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $pageTitle = 'Update JV';
        $currentid= $id;
        $jv = JournalVoucherMaster::find($id);
        $date = Carbon::parse($jv->date)->format('d-m-Y');
        $jvDetails = JournalVoucherDetail::where('voucher_master_id', $id)->get();
        $dropDownData = $this->journalVoucherService->DropDownData();
        if (empty($jv)) {
            $message = config('constants.wrong');
        }


        return view('vouchers.jv.edit', compact('jv','date','pageTitle', 'jvDetails','dropDownData','currentid'));
    }

    public function update(Request $request)
    {

        // DB::beginTransaction();
        // try {
            //Insert data into Voucher tables.
            JournalVoucherDetail::where('voucher_master_id', $request['id'])->delete();
            $voucherMasterData = $this->journalVoucherService->prepareVoucherMasterData($request);
            $voucherMasterInsert = $this->commonService->findUpdateOrCreate(JournalVoucherMaster::class, ['id' => request('id')], $voucherMasterData);
            $voucherDetailData = $this->journalVoucherService->prepareVoucherDetailData($request, $voucherMasterInsert->id);
            $this->journalVoucherService->saveVoucher($voucherDetailData);


            // $AccountData = $this->journalVoucherService->prepareAccountData($request, $voucherDetailData, config('contants.JV'),config('contants.Jv_transaction') );
            // AccountLedger::insert($AccountData);

        //     DB::commit();
        // } catch (\Exception $e) {
        //     DB::rollback();
        //     return redirect('jv/create')->with('error', $e->getMessage());
        // }
        return redirect('jv/list')->with('message', config('constants.update'));
    }

    public function print($id)
    {
        $title = 'Journal Voucher';
        $jvMaster = JournalVoucherMaster::find($id);
        $date = Carbon::parse($jvMaster->date)->format('d-m-Y');
        $jvDetails = JournalVoucherDetail::where('voucher_master_id', $jvMaster->id)->get();
        // dd($jvDetails);
        $debitAccountArray = $jvDetails->pluck('debit_account');
        $creditAccountArray = $jvDetails->pluck('credit_account');
        $debitParty = CoaDetailAccount::whereIn('id', $debitAccountArray)->pluck('account_name', 'id');
        $creditParty = CoaDetailAccount::whereIn('id', $creditAccountArray)->pluck('account_name', 'id');

        $user = User::where('id', $jvMaster->created_by)->value('name');

        return view('vouchers.jv.print', compact('title','user', 'jvMaster','jvDetails','creditParty', 'date', 'debitParty'));
    }

    public function view($id)
    {
        $voucherMaster = $this->journalVoucherService->getVoucherMasterById($id);
        $voucherDetail = $this->journalVoucherService->getVoucherDetailById($id);
        if (empty($voucherMaster)) {
            $message = config('constants.wrong');
        }

        return view('vouchers.jv.view', compact('voucherMaster', 'voucherDetail'));
    }

}

