<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\CRVDetails;
use Illuminate\Http\Request;
use App\Models\AccountLedger;
use App\Services\CommonService;
use App\Models\CashReceiptVoucher;
use Illuminate\Support\Facades\DB;
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
        $param = request()->param;

        return view('vouchers.crv.index', compact('vouchers', 'param', 'pageTitle'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $pageTitle = 'Create CRVoucher';
        $maxid = CashReceiptVoucher::max('id') + 1;
        $dropDownData = $this->cashReceiptVoucherService->DropDownData();
        return view('vouchers.crv.create', compact('pageTitle', 'maxid', 'dropDownData'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request);
        $request = $request->except('_token', 'id');
        // DB::beginTransaction();
        // try {
        //Insert data into purchase tables.
        $voucherMasterData = $this->cashReceiptVoucherService->prepareVoucherMasterData($request);
        $voucherMasterInsert = $this->cashReceiptVoucherService->findUpdateOrCreate(CashReceiptVoucher::class, ['id' => ''], $voucherMasterData);

        $voucherDetailData = $this->cashReceiptVoucherService->prepareVoucherDetailData($request, $voucherMasterInsert->id);
        $this->cashReceiptVoucherService->saveVoucherDetailData($voucherDetailData);


        // $debitAccountData = $this->cashReceiptVoucherService->prepareAccountDebitData($request, $voucherDetailDebitData, config('contants.CRV'),config('contants.Crv_cash_in_hand') );
        // $creditAccountData = $this->cashReceiptVoucherService->prepareAccountCreditData($request, $voucherDetailCreditData, config('contants.CRV'), config('contants.Crv_party_transaction'));
        // AccountLedger::insert($debitAccountData);
        // AccountLedger::insert($creditAccountData);

        //     DB::commit();
        // } catch (\Exception $e) {
        //     DB::rollback();
        //     return redirect('crv/create')->with('error', $e->getMessage());
        // }
        return redirect('crv/list')->with('message', config('constants.add'));
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
        $pageTitle = 'Update CRVoucher';
        $currentid = $id;
        $crv = CashReceiptVoucher::find($id);
        $date = Carbon::parse($crv->date)->format('d-m-Y');
        $dropDownData = $this->cashReceiptVoucherService->DropDownData();
        $crvDetails = CRVDetails::where('voucher_master_id', $id)->get();
        if (empty($crv)) {
            $message = config('constants.wrong');
        }

        return view('vouchers.crv.edit', compact('crv', 'currentid', 'dropDownData', 'date', 'pageTitle', 'crvDetails'));
    }

    public function update(Request $request)
    {


        // DB::beginTransaction();
        // try {

        CRVDetails::where('voucher_master_id', $request['id'])->delete();
        //Insert data into purchase tables.
        $voucherMasterData = $this->cashReceiptVoucherService->prepareVoucherMasterData($request);
        $voucherMasterInsert = $this->cashReceiptVoucherService->findUpdateOrCreate(CashReceiptVoucher::class, ['id' => request('id')], $voucherMasterData);

        $voucherDetailData = $this->cashReceiptVoucherService->prepareVoucherDetailData($request, $voucherMasterInsert->id);
        $this->cashReceiptVoucherService->saveVoucherDetailData($voucherDetailData);


        // $debitAccountData = $this->cashReceiptVoucherService->prepareAccountDebitData($request, $voucherDetailDebitData, config('contants.CRV'),config('contants.Crv_cash_in_hand') );
        // $creditAccountData = $this->cashReceiptVoucherService->prepareAccountCreditData($request, $voucherDetailCreditData, config('contants.CRV'), config('contants.Crv_party_transaction'));
        // AccountLedger::insert($debitAccountData);
        // AccountLedger::insert($creditAccountData);

        //     DB::commit();
        // } catch (\Exception $e) {
        //     DB::rollback();
        //     return redirect('crv/create')->with('error', $e->getMessage());
        // }
        return redirect('crv/list')->with('message', config('constants.update'));
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
