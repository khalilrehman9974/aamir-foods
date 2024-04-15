<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\VoucherDetail;
use App\Models\VoucherMaster;
use App\Services\CommonService;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\VoucherRequest;
use App\Services\CashReceiptVoucherService;

class CRVoucherController extends Controller
{
    protected $commonService;
    protected $cashReceiptVoucherService;

    public function __construct(CommonService $commonService, CashReceiptVoucherService $cashReceiptVoucherService)
    {
        $this->commonService = $commonService;
        $this->cashReceiptVoucherService = $cashReceiptVoucherService;

    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pageTitle = 'List Of CR Vouchers';
        $request = request()->all();
        $vouchers = $this->cashReceiptVoucherService->searchVoucher($request);

        return view('vouchers.crv.index', compact('vouchers', 'pageTitle'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $pageTitle = 'Create CRVoucher';
        $maxid = VoucherMaster::where('vr_type_id', 'CRV')->max('id') + 1;
        $dropDownData = $this->cashReceiptVoucherService->DropDownData();
        return view('vouchers.crv.create', compact( 'pageTitle','maxid','dropDownData'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(VoucherRequest $request)
    {
        $request = $request->except('_token', 'voucherId');
        $session = $this->commonService->getSession();
        DB::beginTransaction();
        try {
            //Insert data into purchase tables.
            $request['business_id'] = $session->business_id;
            $request['f_year_id'] = $session->financial_year;
            $voucherMasterData = $this->cashReceiptVoucherService->prepareVoucherMasterData($request);
            $voucherMasterInsert = $this->commonService->findUpdateOrCreate(VoucherMaster::class, ['id' => ''], $voucherMasterData);
            $voucherDetailCreditData = $this->cashReceiptVoucherService->prepareVoucherDetailCreditData($request, $voucherMasterInsert->id);
            $voucherDetailDebitData = $this->cashReceiptVoucherService->prepareVoucherDetailDebitData($request, $voucherMasterInsert->id);
            $this->cashReceiptVoucherService->saveVoucher($voucherDetailCreditData);
            $this->cashReceiptVoucherService->saveVoucher($voucherDetailDebitData);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            return redirect('crv/create')->with('error', $e->getMessage());
        }
        return redirect('crv/list')->with('message', config('constants.add'));
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
        $voucher = VoucherMaster::find($id);
        $voucherDetails = VoucherDetail::where('voucher_master_id', $id)->get();
        if (empty($voucher)) {
            $message = config('constants.wrong');
        }

        return view('vouchers.crv.create', compact('voucher', 'voucherDetails'));
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\VoucherMaster  $voucherMaster
     * @return \Illuminate\Http\Response
     */
    public function destroy()
    {
        try {
            DB::beginTransaction();
            $deleteMaster = VoucherMaster::where('id', request()->id)->delete();
            $deleteDetail = VoucherDetail::where('voucher_master_id', request()->id)->delete();
            // $deleteStock = Stock::where('invoice_id', request()->id)->delete();
            // $accountEntryDetail = AccountLedger::where('invoice_id', request()->id)->delete();
            DB::commit();
            // && $deleteStock && $accountEntryDetail
            if ($deleteMaster && $deleteDetail ) {
                return $this->commonService->deleteResource(VoucherMaster::class, VoucherDetail::class);
            }

        } catch (\Exception $e) {
            DB::rollback();
            return redirect('crv/list')->with('error', $e->getMessage());
        }

    }

    public function view($id)
    {
        $voucherMaster = $this->cashReceiptVoucherService->getVoucherMasterById($id);
        $voucherDetail = $this->cashReceiptVoucherService->getVoucherDetailById($id);
        if (empty($voucherMaster)) {
            $message = config('constants.wrong');
        }

        return view('vouchers.crv.view', compact('voucherMaster', 'voucherDetail'));
    }

}
