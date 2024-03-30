<?php

namespace App\Http\Controllers;

use App\Models\VoucherDetail;
use App\Models\VoucherMaster;
use App\Services\CommonService;
use App\Services\VoucherService;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\VoucherRequest;

class JournalVoucherController extends Controller
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
        $pageTitle = 'List Of JVouchers';
        $request = request()->all();
        $vouchers = $this->voucherService->searchVoucher($request);

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
        $maxid = VoucherMaster::max('id') + 1;
        $dropDownData = $this->voucherService->DropDownData();
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
        $data['vr_type'] = config('constants.JV');
        $request = $request->except('_token', 'id');
        try {
            DB::beginTransaction();
            //Insert data into Voucher tables.
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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\VoucherMaster  $voucherMaster
     * @return \Illuminate\Http\Response
     */
    public function update(VoucherRequest $request)
    {
        try {
            DB::beginTransaction();
            $request = request()->all();
            VoucherMaster::where('id', $request['id'])->delete();
            VoucherDetail::where('voucher_master_id', $request['id'])->delete();
            // Stock::where('invoice_id', $request['saleId'])->delete();
            // AccountLedger::where('invoice_id', $request['saleId'])->delete();

            //Save data into relevant tables.
            $voucherMasterData = $this->voucherService->prepareVoucherMasterData($request);
            $voucherMasterInsert = $this->commonService->findUpdateOrCreate(VoucherMaster::class, ['id' => request('id')], $voucherMasterData);
            $voucherDetailData = $this->voucherService->prepareVoucherDetailData($request, $voucherMasterInsert->id);
            $this->voucherService->saveVoucher($voucherDetailData);

            //Save data into stock table.
            // $this->stockService->prepareAndSaveData($request, $saleMasterInsert->id, voucherService::SALE_TRANSACTION_TYPE);
            // $debitAccountData = $this->voucherService->prepareAccountDebitData($request, $saleMasterInsert->id, voucherService::SALE_TRANSACTION_TYPE, voucherService::SALE_DESCRIPTION);
            // $creditAccountData = $this->voucherService->prepareAccountCreditData($request, $saleMasterInsert->id, voucherService::SALE_TRANSACTION_TYPE, voucherService::SALE_DESCRIPTION);
            // AccountLedger::insert($debitAccountData);
            // AccountLedger::insert($creditAccountData);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            return redirect('jv/create')->with('error', $e->getMessage());
        }

        return redirect('jv/list')->with('message', config('constants.update'));    }

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
            return redirect('jv/list')->with('error', $e->getMessage());
        }

    }

    public function view($id)
    {
        $voucherMaster = $this->voucherService->getVoucherMasterById($id);
        $voucherDetail = $this->voucherService->getVoucherDetailById($id);
        if (empty($voucherMaster)) {
            $message = config('constants.wrong');
        }

        return view('vouchers.jv.view', compact('voucherMaster', 'voucherDetail'));
    }

}

