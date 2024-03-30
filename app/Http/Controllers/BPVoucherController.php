<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\VoucherDetail;
use App\Models\VoucherMaster;
use App\Services\CommonService;
use App\Services\VoucherService;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\VoucherRequest;

class BPVoucherController extends Controller
{
    protected $commonService;
    protected $voucherService;

    public function __construct(CommonService $commonService, VoucherService $voucherService)
    {
        $this->commonService = $commonService;
        $this->voucherService = $voucherService;

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
        $vouchers = $this->voucherService->searchVoucher($request);

        return view('vouchers.bpv.index', compact('vouchers', 'pageTitle'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $pageTitle = 'Create BPV';
        $maxid = VoucherMaster::max('id') + 1;
        $dropDownData = $this->voucherService->DropDownData();
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
        $data['vr_type'] = config('constants.BPV');
        $request = $request->except('_token', 'id');
        try {
            DB::beginTransaction();
            //Insert data into purchase tables.
            $voucherMasterData = $this->voucherService->prepareVoucherMasterData($request);
            $voucherMasterInsert = $this->commonService->findUpdateOrCreate(VoucherMaster::class, ['id' => ''], $voucherMasterData);
            $voucherDetailData = $this->voucherService->prepareVoucherDetailData($request, $voucherMasterInsert->id);
            $this->voucherService->saveVoucher($voucherDetailData);

            //Insert data into stock table.
            // $this->stockService->prepareAndSaveData($request, $saleMasterInsert->id, voucherService::SALE_TRANSACTION_TYPE);

            //Insert data into accounts ledger table.
            // $debitAccountData = $this->voucherService->prepareAccountDebitData($request, $saleMasterInsert->id, voucherService::SALE_TRANSACTION_TYPE, voucherService::SALE_DESCRIPTION);
            // $creditAccountData = $this->voucherService->prepareAccountCreditData($request, $saleMasterInsert->id, voucherService::SALE_TRANSACTION_TYPE, voucherService::SALE_DESCRIPTION);
            // AccountLedger::insert($debitAccountData);
            // AccountLedger::insert($creditAccountData);
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
            return redirect('bpv/list')->with('error', $e->getMessage());
        }

    }

    public function view($id)
    {
        $voucherMaster = $this->voucherService->getVoucherMasterById($id);
        $voucherDetail = $this->voucherService->getVoucherDetailById($id);
        if (empty($voucherMaster)) {
            $message = config('constants.wrong');
        }

        return view('vouchers.bpv.view', compact('voucherMaster', 'voucherDetail'));
    }

}
