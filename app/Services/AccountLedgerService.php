<?php


namespace App\Services;


use App\Models\Account;
use App\Models\AccountLedger;
use App\Models\Customer;
use Carbon\Carbon;

class AccountLedgerService
{

    public function viewLedger($request)
    {
        $accountId = $request['account_id'];
        if($request['mobile_number'] != ""){
            $accountId = Account::where('mobile_no', $request['mobile_number'])->first();
            $accountId = $accountId->id;
        }
        $data = AccountLedger::whereBetween('date', [Carbon::parse($request['from_date'])->format('Y-m-d') . " 00:00:00", Carbon::parse($request['to_date'])->format('Y-m-d') . " 23:59:59"])
        ->where('account_id', $accountId)
            ->get();

        return $data;
    }

    public function prepareCreditData($request, $purchaseParentId, $dataType, $description)
    {
        return [
            'date' => Carbon::parse($request['date'])->format('Y-m-d'),
            'invoice_id' => $purchaseParentId,
            'account_id' => $request['party_id'],

            'description' => $description . ' '. $purchaseParentId,
            'transaction_type' => $dataType,
            'debit' => 0,
            'credit' => $request['totalAmount'],

        ];
    }

    public function prepareDebitData($request, $purchaseParentId, $dataType, $description)
    {
        return [
            'date' => Carbon::parse($request['date'])->format('Y-m-d'),
            'invoice_id' => $purchaseParentId,
            'account_id' => 'PR-00000001',
            'description' => $description . ' '. $purchaseParentId,
            'transaction_type' => $dataType,
            'debit' => $request['totalAmount'],
            'credit' => 0,

        ];
    }
}
