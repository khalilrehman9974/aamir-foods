<?php

namespace App\Services;

use App\Models\BankReceiptVoucher;
use App\Models\BRVDetails;
use Carbon\Carbon;
use App\Models\VoucherDetail;
use App\Models\VoucherMaster;
use App\Models\CoaDetailAccount;
use Illuminate\Support\Facades\Auth;

class BankReceiptVoucherService
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
            'bankAccounts' => CoaDetailAccount::where('main_head', 1)->where('control_head', 1)->where('sub_head',2)->where('sub_sub_head', 1)->pluck('account_name', 'id'),
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

        $q = BankReceiptVoucher::query();
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
            'bank_id' => $request['bank_id'],
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
                $rec['bank_id'] = $data['bank_id'][$key];
                $rec['description'] = $data['description'][$key];
                $rec['amount'] = $data['amount'][$key];
                $rec['voucher_master_id'] = $data['voucher_master_id'];
                BRVDetails::create($rec);
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
            'account_id' => $request['account_id'],
            'description' => $request['description'],
            'debit' => 0,
            'credit' => $request['amount'],
            'created_by' => Auth::user()->id,
            'updated_by' => Auth::user()->id,
            'voucher_master_id' => $voucherParentId,
        ];
    }

    public function prepareAccountDebitData($request, $voucherParentId, $dataType, $description)
    {
        return [
            'account_id' => $request['bank_id'],
            'description' => $description . ' '. $voucherParentId, $dataType,
            'debit' => $request['amount'],
            'credit' => config('constants.ZERO'),
        ];
    }

    public function prepareAccountCreditData($request, $voucherParentId, $dataType, $description)
    {
        return [
            'account_id' => $request['account_id'],
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
