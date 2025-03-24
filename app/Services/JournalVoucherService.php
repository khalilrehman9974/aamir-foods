<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\CoaDetailAccount;
use App\Models\JournalVoucherDetail;
use App\Models\JournalVoucherMaster;
use Illuminate\Support\Facades\Auth;

class JournalVoucherService
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
        return JournalVoucherMaster::select(
            'voucher_masters.id',
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
        return JournalVoucherMaster::select(
            'voucher_details.account_id',
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
        $q = JournalVoucherMaster::query();
        if (!empty($request['date'])) {
            $formattedDate = date('Y-m-d', strtotime($request['date']));
            $q->where('date', $formattedDate);
        }

        $vouchers = $q->orderBy('id', 'DESC')->paginate(config('constants.PER_PAGE'));

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
            'business_id' => $session->business_id,
            'f_year_id' => $session->financial_year,
            'debit_amount' => $request['debit_amount'],
            'credit_amount' => $request['credit_amount'],
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
            'debit' => $request['debit'],
            'credit' => $request['credit'],
            'created_by' => Auth::user()->id,
            'updated_by' => Auth::user()->id,
            'voucher_master_id' => $voucherParentId,
        ];
    }

    public function prepareAccountData($request, $voucherParentId, $dataType, $description)
    {
        return [
            'account_id' => $request['account_id'],
            'description' => $description . ' '. $voucherParentId, $dataType,
            'debit' => $request['debit'],
            'credit' => $request['credit'],
        ];
    }

    /*
     * Prepare Purchase detail data.
     * @param: $request
     * @return Array
     * */
    public function prepareVoucherDetailData($request, $voucherParentId)
    {
        return [
            'debit_account' => $request['debit_account'],
            'credit_account' => $request['credit_account'],
            'description' => $request['description'],
            'debit' => $request['debit'],
            'credit' => $request['credit'],
            'voucher_master_id' => $voucherParentId,
        ];
    }

    /*
     * Save Voucher data.
     * @param: $data
     * */
    public function saveVoucher($data)
    {
        foreach ($data['debit_account'] as $key => $value) {
            if (!empty($data['debit_account'][$key])) {
                $rec['debit_account'] = $data['debit_account'][$key];
                $rec['credit_account'] = $data['credit_account'][$key];
                $rec['description'] = $data['description'][$key];
                $rec['debit'] = $data['debit'][$key];
                $rec['credit'] = $data['credit'][$key];
                $rec['voucher_master_id'] = $data['voucher_master_id'];
                JournalVoucherDetail::create($rec);
            }
        }
    }
}
