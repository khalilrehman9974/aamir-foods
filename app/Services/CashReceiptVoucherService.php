<?php

namespace App\Services;

use App\Models\VoucherDetail;
use App\Models\VoucherMaster;
use App\Models\CoaDetailAccount;
use Illuminate\Support\Facades\Auth;

class CashReceiptVoucherService
{
    protected $commonService;

    public function __construct(CommonService $commonService)
    {
        $this->commonService = $commonService;
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
    public function searchVoucher($request)
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



    /*
     * Prepare Voucher master data.
     * @param: $request
     * @return Array
     * */
    public function prepareVoucherMasterData($request)
    {
        return [
            'date' => $request['date'],
            'vr_type' => config('constants.vouchers.CRV'),
            'total_amount' => $request['total_amount'],
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
            'code' => $request['code'],
            'description' => $request['description'],
            'debit' => $request['amount'],
            'credit' => 0,
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
            'code' => config('constants.account_codes.CASH_IN_HAND'),
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
    public function saveVoucher($data)
    {
        foreach ($data['code'] as $key => $value) {
            if (!empty($data['code'][$key])) {
                $rec['code'] = $data['code'][$key];
                $rec['description'] = $data['description'][$key];
                $rec['debit'] = config('constants.ZERO');
                $rec['credit'] = $data['amount'][$key];
                $rec['created_by'] = Auth::user()->id;
                $rec['updated_by'] = Auth::user()->id;
                $rec['voucher_master_id'] = $data['voucher_master_id'];
                VoucherDetail::create($rec);
            }
        }
    }
}
