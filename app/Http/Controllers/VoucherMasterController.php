<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\VoucherDetail;
use App\Models\VoucherMaster;
use App\Services\CommonService;
use App\Services\VoucherService;
use Illuminate\Support\Facades\DB;

class VoucherMasterController extends Controller
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
        $pageTitle = 'List Of Vouchers';
        $request = request()->all();
        $purchases = $this->voucherService->searchVoucher($request);

        return view('vouchers.bpv.index', compact('purchases', 'request','pageTitle'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $pageTitle = 'Create Purchase';
        return view('vouchers.bpv.create', compact( 'type','pageTitle'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request = $request->except('_token', 'purchaseId');
        try {
            DB::beginTransaction();
            //Insert data into purchase tables.
            $saleMasterData = $this->voucherService->prepareVoucherMasterData($request);
            $saleMasterInsert = $this->commonService->findUpdateOrCreate(VoucherMaster::class, ['id' => ''], $saleMasterData);
            $saleDetailData = $this->voucherService->prepareVoucherDetailData($request, $saleMasterInsert->id);
            $this->voucherService->saveVoucher($saleDetailData);

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
        $purchase = VoucherMaster::find($id);
        $purchaseDetails = VoucherDetail::where('purchase_master_id', $id)->get();
        if (empty($purchase)) {
            $message = config('constants.wrong');
        }

        return view('vouchers.bpv.create', compact('purchase','type', 'purchaseDetails'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\VoucherMaster  $voucherMaster
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, VoucherMaster $voucherMaster)
    {
        try {
            DB::beginTransaction();
            $request = request()->all();
            VoucherMaster::where('id', $request['saleId'])->delete();
            VoucherDetail::where('purchase_master_id', $request['purchaseId'])->delete();
            // Stock::where('invoice_id', $request['saleId'])->delete();
            // AccountLedger::where('invoice_id', $request['saleId'])->delete();

            //Save data into relevant tables.
            $purchaseMasterData = $this->voucherService->prepareVoucherMasterData($request);
            $purchaseMasterInsert = $this->commonService->findUpdateOrCreate(VoucherMaster::class, ['id' => request('productId')], $purchaseMasterData);
            $purchaseDetailData = $this->voucherService->prepareVoucherDetailData($request, $purchaseMasterInsert->id);
            $this->voucherService->saveVoucher($purchaseDetailData);

            //Save data into stock table.
            // $this->stockService->prepareAndSaveData($request, $saleMasterInsert->id, voucherService::SALE_TRANSACTION_TYPE);
            // $debitAccountData = $this->voucherService->prepareAccountDebitData($request, $saleMasterInsert->id, voucherService::SALE_TRANSACTION_TYPE, voucherService::SALE_DESCRIPTION);
            // $creditAccountData = $this->voucherService->prepareAccountCreditData($request, $saleMasterInsert->id, voucherService::SALE_TRANSACTION_TYPE, voucherService::SALE_DESCRIPTION);
            // AccountLedger::insert($debitAccountData);
            // AccountLedger::insert($creditAccountData);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            return redirect('bpv/create')->with('error', $e->getMessage());
        }

        return redirect('bpv/list')->with('message', config('constants.update'));    }

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
            $deleteDetail = VoucherDetail::where('purchase_master_id', request()->id)->delete();
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
