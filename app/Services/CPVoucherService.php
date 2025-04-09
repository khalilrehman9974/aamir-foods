<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\CPVDetails;
use App\Models\AccountLedger;
use App\Models\VoucherDetail;
use App\Models\VoucherMaster;
use App\Models\CoaDetailAccount;
use App\Models\CashPaymentVoucher;
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

    public function prepareAccountCreditData($request, $voucherParentId)
    {
        return [
            'date' => Carbon::parse($request['date'])->format('Y-m-d'),
            'invoice_id' => $voucherParentId,
            'party_id' =>  $request['cash_account_id'],
            'document_number' => 'CPV' . '-' . $voucherParentId,
            'rate' => config('constants.ZERO'),
            'bilty_no' => null,
            'transporter_id' => null,
            'total_quantity' => config('constants.ZERO'),
            'measurementType' => config('constants.ZERO'),
            'bags' => config('constants.ZERO'),
            'description' => $request['description']  ,
            'debit' => config('constants.ZERO'),
            'credit' => $request['amount'],
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    public function saveCreditData($data)
    {

        foreach ($data['party_id'] as $key => $value) {
            if (!empty($data['party_id'][$key])) {
                $rec['party_id'] = $data['party_id'][$key];
                $rec['date'] = $data['date'];
                $rec['invoice_id'] = $data['invoice_id'];
                $rec['document_number'] = $data['document_number'];
                $rec['rate'] = $data['rate'];
                $rec['bilty_no'] = $data['bilty_no'];
                $rec['transporter_id'] = $data['transporter_id'];
                $rec['total_quantity'] = $data['total_quantity'];
                $rec['measurementType'] = $data['measurementType'];
                $rec['bags'] = $data['bags'];
                $rec['description'] = $data['description'][$key];
                $rec['debit'] = $data['debit'];
                $rec['credit'] = $data['credit'][$key];
                $rec['created_at'] = now();
                $rec['updated_at'] = now();
                AccountLedger::create($rec);
            }
        }
    }

    public function prepareAccountDebitData($request, $voucherParentId)
    {

        return [
            'date' => Carbon::parse($request['date'])->format('Y-m-d'),
            'invoice_id' => $voucherParentId,
            'party_id' =>  $request['account_id'],
            'document_number' => 'CPV' . '-' . $voucherParentId,
            'rate' => config('constants.ZERO'),
            'bilty_no' => null,
            'transporter_id' => null,
            'total_quantity' => config('constants.ZERO'),
            'measurementType' => config('constants.ZERO'),
            'bags' => config('constants.ZERO'),
            'description' => $request['description']  ,
            'debit' => $request['amount'],
            'credit' => config('constants.ZERO'),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    public function saveDebitData($data)
    {
        foreach ($data['party_id'] as $key => $value) {
            if (!empty($data['party_id'][$key])) {
                $rec['party_id'] = $data['party_id'][$key];
                $rec['date'] = $data['date'];
                $rec['invoice_id'] = $data['invoice_id'];
                $rec['document_number'] = $data['document_number'];
                $rec['rate'] = $data['rate'];
                $rec['bilty_no'] = $data['bilty_no'];
                $rec['transporter_id'] = $data['transporter_id'];
                $rec['total_quantity'] = $data['total_quantity'];
                $rec['measurementType'] = $data['measurementType'];
                $rec['bags'] = $data['bags'];
                $rec['description'] = $data['description'][$key];
                $rec['debit'] = $data['debit'][$key];
                $rec['credit'] = $data['credit'];
                $rec['created_at'] = now();
                $rec['updated_at'] = now();
                AccountLedger::create($rec);
            }
        }
    }


}
