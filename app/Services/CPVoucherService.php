<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\VoucherDetail;
use App\Models\VoucherMaster;
use App\Models\CoaDetailAccount;
use App\Models\CashPaymentVoucher;
use App\Models\CPVDetails;
use Illuminate\Support\Facades\Auth;

class CPVoucherService
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


    public function findUpdateOrCreate($model, array $where, array $data)
    {
        $object = $model::firstOrNew($where);

        foreach ($data as $property => $value){
            $object->{$property} = $value;
        }
        $object->save();

        return $object;
    }

    public function DropDownData()
    {
        $result = [
            'accounts' => CoaDetailAccount::pluck('account_name', 'id'),
            'cashAccount' => CoaDetailAccount::where('main_head', 1)->where('control_head', 1)->where('sub_head',2)->where('sub_sub_head', 2)->pluck('account_name', 'id'),
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
        $q = CashPaymentVoucher::query();
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
    public function prepareVoucherDetailData($request, $voucherParentId)
    {
        return [
            'account_id' => $request['account_id'],
            'cash_account_id' => $request['cash_account_id'],
            'description' => $request['description'],
            'amount' => $request['amount'],
            'voucher_master_id' => $voucherParentId,
        ];
    }

    public function saveVoucherDetailData($data)
    {
        foreach ($data['account_id'] as $key => $value) {
            if (!empty($data['account_id'][$key])) {
                $rec['account_id'] = $data['account_id'][$key];
                $rec['cash_account_id'] = $data['cash_account_id'][$key];
                $rec['description'] = $data['description'][$key];
                $rec['amount'] = $data['amount'][$key];
                $rec['voucher_master_id'] = $data['voucher_master_id'];
                CPVDetails::create($rec);
            }
        }
    }


    /*
     * Prepare Purchase detail data.
     * @param: $request
     * @return Array
     * */
    public function prepareVoucherDetailCreditData($request, $voucherParentId)
    {
        return [
            'account_id' => config('constants.account_codes.CASH_IN_HAND'),
            'description' => $request['description'],
            'debit' => config('constants.ZERO'),
            'credit' => $request['amount'],
            'created_by' => Auth::user()->id,
            'updated_by' => Auth::user()->id,
            'voucher_master_id' => $voucherParentId,
        ];
    }


    public function prepareAccountDebitData($request, $voucherParentId, $dataType, $description)
    {
        return [
            'account_id' => $request['account_id'],
            'description' => $description . ' '. $voucherParentId, $dataType,
            'debit' => $request['amount'],
            'credit' => config('constants.ZERO'),
        ];
    }

    public function prepareAccountCreditData($request, $voucherParentId, $dataType, $description)
    {
        return [
            'account_id' => config('constants.account_codes.CASH_IN_HAND'),
            'description' => $description . ' '. $voucherParentId. $dataType,
            'debit' => config('constants.ZERO'),
            'credit' => $request['amount'],
        ];
    }

    /*
     * Save Voucher data.
     * @param: $data
     * */
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
}
