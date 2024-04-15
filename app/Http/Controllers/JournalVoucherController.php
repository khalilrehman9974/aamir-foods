<?php

namespace App\Http\Controllers;

use App\Models\VoucherDetail;
use App\Models\VoucherMaster;
use App\Services\CommonService;
use Illuminate\Support\Facades\DB;
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

        return view('vouchers.jv.index', compact('vouchers', 'pageTitle'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $pageTitle = 'Create Voucher';
        $maxid = VoucherMaster::where('vr_type_id', 'JV')->max('id') + 1;
        $dropDownData = $this->journalVoucherService->DropDownData();
        return view('vouchers.jv.create', compact('dropDownData','maxid','pageTitle'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(VoucherRequest $request)
    {
        $request = $request->except('_token', 'id');
        $session = $this->commonService->getSession();
        DB::beginTransaction();
        try {
            //Insert data into Voucher tables.
            $request['business_id'] = $session->business_id;
            $request['f_year_id'] = $session->financial_year;
            $voucherMasterData = $this->journalVoucherService->prepareVoucherMasterData($request);
            $voucherMasterInsert = $this->commonService->findUpdateOrCreate(VoucherMaster::class, ['id' => ''], $voucherMasterData);
            $voucherDetailCreditData = $this->journalVoucherService->prepareVoucherDetailCreditData($request, $voucherMasterInsert->id);
            $voucherDetailDebitData = $this->journalVoucherService->prepareVoucherDetailDebitData($request, $voucherMasterInsert->id);
            $this->journalVoucherService->saveVoucher($voucherDetailCreditData);
            $this->journalVoucherService->saveVoucher($voucherDetailDebitData);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            return redirect('jv/create')->with('error', $e->getMessage());
        }
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
        $currentid= $id;
        $voucher = VoucherMaster::find($id);
        $voucherDetails = VoucherDetail::where('voucher_master_id', $id)->get();
        if (empty($voucher)) {
            $message = config('constants.wrong');
        }

        return view('vouchers.jv.create', compact('voucher', 'voucherDetails','currentid'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\VoucherMaster  $voucherMaster
     * @return \Illuminate\Http\Response
     */
    public function destroy()
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
            return redirect('jv/list')->with('error', $e->getMessage());
        }

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

