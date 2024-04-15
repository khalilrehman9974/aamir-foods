<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\VoucherDetail;
use App\Models\VoucherMaster;
use App\Services\CommonService;
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

        return view('vouchers.brv.index', compact('vouchers', 'pageTitle'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $pageTitle = 'Create BRV';
        $maxid = VoucherMaster::where('vr_type_id', 'BRV')->max('id') + 1;
        $dropDownData = $this->bankReceiptVoucherService->DropDownData();
        return view('vouchers.brv.create', compact( 'pageTitle','maxid' ,'dropDownData'));
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
            //Insert data into Vouchers tables.
            $request['business_id'] = $session->business_id;
            $request['f_year_id'] = $session->financial_year;
            $voucherMasterData = $this->bankReceiptVoucherService->prepareVoucherMasterData($request);
            $voucherMasterInsert = $this->commonService->findUpdateOrCreate(VoucherMaster::class, ['id' => ''], $voucherMasterData);
            $voucherDetailCreditData = $this->bankReceiptVoucherService->prepareVoucherDetailCreditData($request, $voucherMasterInsert->id);
            $voucherDetailDebitData = $this->bankReceiptVoucherService->prepareVoucherDetailDebitData($request, $voucherMasterInsert->id);
            $this->bankReceiptVoucherService->saveVoucher($voucherDetailCreditData);
            $this->bankReceiptVoucherService->saveVoucher($voucherDetailDebitData);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            return redirect('brv/create')->with('error', $e->getMessage());
        }
        return redirect('brv/list')->with('message', config('constants.add'));
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

        return view('vouchers.brv.create', compact('voucher','currentid', 'voucherDetails'));
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
            return redirect('brv/list')->with('error', $e->getMessage());
        }

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
