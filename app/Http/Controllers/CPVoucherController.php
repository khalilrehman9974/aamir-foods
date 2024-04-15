<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\VoucherDetail;
use App\Models\VoucherMaster;
use App\Services\CommonService;
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

        return view('vouchers.cpv.index', compact('vouchers', 'pageTitle'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $pageTitle = 'Create CPV';
        $maxid = VoucherMaster::where('vr_type_id', 'CPV')->max('id') + 1;
        $dropDownData = $this->cpVoucherService->DropDownData();
        return view('vouchers.cpv.create', compact( 'pageTitle','maxid', 'dropDownData'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(VoucherRequest $request)
    {
        $data['vr_type_id'] = config('constants.CPV');
        $session = $this->commonService->getSession();
        $request = $request->except('_token', 'id');
        DB::beginTransaction();
        try {
            //Insert data into purchase tables.
            $request['business_id'] = $session->business_id;
            $request['f_year_id'] = $session->financial_year;
            $voucherMasterData = $this->cpVoucherService->prepareVoucherMasterData($request);
            $voucherMasterInsert = $this->commonService->findUpdateOrCreate(VoucherMaster::class, ['id' => ''], $voucherMasterData);
            $voucherDetailCreditData = $this->cpVoucherService->prepareVoucherDetailCreditData($request, $voucherMasterInsert->id);
            $voucherDetailDebitData = $this->cpVoucherService->prepareVoucherDetailDebitData($request, $voucherMasterInsert->id);
            $this->cpVoucherService->saveVoucher($voucherDetailCreditData);
            $this->cpVoucherService->saveVoucher($voucherDetailDebitData);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            return redirect('cpv/create')->with('error', $e->getMessage());
        }
        return redirect('cpv/list')->with('message', config('constants.add'));
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
        $currentid= $id;
        $voucher = VoucherMaster::find($id);
        $voucherDetails = VoucherDetail::where('voucher_master_id', $id)->get();
        if (empty($voucher)) {
            $message = config('constants.wrong');
        }

        return view('vouchers.cpv.create', compact('voucher', 'voucherDetails','currentid'));
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
            return redirect('cpv/list')->with('error', $e->getMessage());
        }

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
