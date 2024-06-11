<?php

namespace App\Services;

use App\Models\AccountLedger;
use App\Models\VoucherDetail;
use App\Models\VoucherMaster;
use Illuminate\Support\Carbon;
use App\Models\CoaDetailAccount;
use App\Models\VoucherDetailTemp;
use Illuminate\Support\Facades\Auth;

class BankPaymentVoucherService
{
    protected $commonService;

    public function __construct(CommonService $commonService)
    {
        $this->commonService = $commonService;
    }


    public function findUpdateOrCreate($model, array $where, array $data)
    {
        $object = $model::firstOrNew($where);

        foreach ($data as $property => $value){
            $object->{$property} = $value;
        }
        $object->save();

        return $object;
    }


    /*
     * Get contract by id.
     * @param $id
     * */
    public function getVoucherMasterById($id)
    {
        return VoucherMaster::select(
            'voucher_masters.id as id',
            'voucher_masters.date',
            'voucher_masters.vr_type',
            'voucher_masters.total_amount',
            'voucher_masters.created_at',
            'voucher_masters.updated_at',
        )
            ->where('voucher_masters.id', $id)
            ->first();
    }

    public function DropDownData()
    {
        $result = [
            'accounts' => CoaDetailAccount::pluck('account_name', 'id'),
        ];

        return $result;
    }

    /*
    * Get contract by id.
    * @param $id
    * */
    public function getVoucherDetailById($id)
    {
        return VoucherDetail::select(
            'voucher_details.code',
            'voucher_details.description',
            'voucher_details.debit',
            'voucher_details.credit'
        )
            ->where('voucher_details.voucher_master_id', $id)
            ->get();
    }

    /*
     * Search Voucher record.
     * @queries: $queries
     * @return: object
     * */
    public function searchVoucher2($request)
    {
        $query = VoucherMaster::groupBy(
            'voucher_masters.id',
            'voucher_masters.date',
            'voucher_masters.total_amount',
            'voucher_masters.created_at',
            'voucher_masters.updated_at',
        );
        if (!empty($request['param'])) {
            $query = $query->where('voucher_masters.id', "=", $request['param']);
        }
        $vouchers = $query->orderBy('id', 'DESC')->get();

        return $this->commonService->paginate($vouchers, config('constants.PER_PAGE'));
    }

    public function searchVoucher($request)
    {
        $q = VoucherMaster::query();
        if (!empty($request['param'])) {
            $q = VoucherMaster::with('vouchers')->where('account_id', 'like', '%' . $request['param'] . '%');
        }
        $vouchers = $q->orderBy('id', 'ASC')->paginate(config('constants.PER_PAGE'));

        return $vouchers;
    }

    /*
     * Prepare Voucher master data.
     * @param: $request
     * @return Array
     * */
    public function prepareVoucherMasterData($request)
    {
        $session = $this->commonService->getSession();
        return [
            'date' => Carbon::parse($request['date'])->format('Y-m-d'),
            'vr_type' => config('constants.BPV'),
            'total_amount' => $request['total_amount'],
            'business_id' => $session->business_id,
            'f_year_id' => $session->financial_year,
            'created_by' => Auth::user()->id,
            'updated_by' => Auth::user()->id
        ];
    }


    /*
     * Prepare Purchase detail data.
     * @param: $request
     * @return Array
     * */
    public function prepareVoucherDetailDebitData($request, $voucherParentId)
    {
        return [
            'account_id' => $request['account_id'],
            'description' => $request['description'],
            'debit' => $request['amount'],
            'credit' => 0,
            'created_by' => Auth::user()->id,
            'updated_by' => Auth::user()->id,
            'voucher_master_id' => $voucherParentId,
        ];
    }

    public function prepareVoucherDetailTempData($request, $voucherParentId)
    {
        // dd($request);
        return [
            'account_id' => $request['account_id'],
            'bank_id' => $request['bank_id'],
            'description' => $request['description'],
            'amount' => $request['amount'],
            'created_by' => Auth::user()->id,
            'updated_by' => Auth::user()->id,
            'voucher_master_id' => $voucherParentId,
        ];
    }

    /*
     * Prepare Purchase detail data.
     * @param: $request
     * @return Array
     * */
    public function prepareVoucherDetailCreditData($request, $voucherParentId)
    {
        return [
            'account_id' => $request['bank_id'],
            'description' => $request['description'],
            'debit' => 0,
            'credit' => $request['amount'],
            'created_by' => Auth::user()->id,
            'updated_by' => Auth::user()->id,
            'voucher_master_id' => $voucherParentId,
        ];
    }

    /*
     * Save Voucher data.
     * @param: $data
     * */
    public function saveVoucherDebitData($data)
    {
        foreach ($data['account_id'] as $key => $value) {
            if (!empty($data['account_id'][$key])) {
                $rec['account_id'] = $data['account_id'][$key];
                $rec['description'] = $data['description'][$key];
                $rec['debit'] = $data['debit'][$key];
                $rec['credit'] = config('constants.ZERO');
                $rec['created_by'] = Auth::user()->id;
                $rec['updated_by'] = Auth::user()->id;
                $rec['voucher_master_id'] = $data['voucher_master_id'];
                VoucherDetail::create($rec);
            }
        }
    }


    public function saveVoucherTempData($data)
    {
        foreach ($data['account_id'] as $key => $value) {
            if (!empty($data['account_id'][$key])) {
                $rec['account_id'] = $data['account_id'][$key];
                $rec['description'] = $data['description'][$key];
                $rec['bank_id'] = $data['bank_id'][$key];
                $rec['amount'] = $data['amount'][$key];
                $rec['created_by'] = Auth::user()->id;
                $rec['updated_by'] = Auth::user()->id;
                $rec['voucher_master_id'] = $data['voucher_master_id'];
                VoucherDetailTemp::create($rec);
            }
        }
    }

    public function saveVoucherCreditData($data)
    {
        foreach ($data['account_id'] as $key => $value) {
            if (!empty($data['account_id'][$key])) {
                $rec['account_id'] = $data['account_id'][$key];
                $rec['description'] = $data['description'][$key];
                $rec['debit'] = config('constants.ZERO');
                $rec['credit'] = $data['credit'][$key];
                $rec['created_by'] = Auth::user()->id;
                $rec['updated_by'] = Auth::user()->id;
                $rec['voucher_master_id'] = $data['voucher_master_id'];
                VoucherDetail::create($rec);
            }
        }
    }


    // public function getPartyCode()
    // {
    //     // $voucher = VoucherMaster::find($id);; ? CoaDetailAccount::max('account_code') + 1 : 1
    //     return CoaDetailAccount::find($code);
    // }

    public function prepareAccountCreditData($request, $voucherParentId)
    {
        return [
            'account_id' => $request['account_id'],
            'description' => $request['description'],
            'debit' => config('constants.ZERO'),
            'credit' => $request['amount'],
        ];
    }

    public function saveCreditData($data)
    {
        // dd($data);
        foreach ($data['account_id'] as $key => $value) {
            // dd($key,$value);
            if (!empty($data['account_id'][$key])) {
                $rec['account_id'] = $data['account_id'][$key];
                $rec['description'] = $data['description'][$key];
                $rec['debit'] = config('constants.ZERO');
                $rec['credit'] = $data['credit'][$key];
                AccountLedger::create($rec);
            }
        }
    }

    public function prepareAccountDebitData($request, $voucherParentId)
    {
        return [
            'account_id' => $request['account_id'],
            'description' => $request['description'] ,
            'debit' => $request['amount'],
            'credit' => config('constants.ZERO'),
        ];
    }

    public function saveDebitData($data)
    {
        foreach ($data['account_id'] as $key => $value) {
            if (!empty($data['account_id'][$key])) {
                $rec['account_id'] = $data['account_id'][$key];
                $rec['description'] = $data['description'][$key];
                $rec['debit'] = $data['debit'][$key];
                $rec['credit'] = config('constants.ZERO');
                AccountLedger::create($rec);
            }
        }
    }


}
