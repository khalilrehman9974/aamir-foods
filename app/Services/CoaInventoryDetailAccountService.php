<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\CoaSubHead;
use App\Models\CoaMainHead;
use App\Models\PackingType;
use App\Models\CoaSubSubHead;
use App\Models\CoaControlHead;
use App\Models\MeasurementType;
use App\Models\CoaDetailAccount;
use App\Models\CoaInventorySubHead;
use Illuminate\Support\Facades\Auth;
use App\Models\DetailAccountProducts;
use App\Models\CoaInventorySubSubHead;
use App\Models\CoaInventoryDetailAccount;

/*
 * Class BankService
 * @package App\Services
 * */


// use Illuminate\Support\Facades\Input;

class CoaInventoryDetailAccountService
{
    protected $commonService;

    public function __construct(CommonService $commonService)
    {
        $this->commonService = $commonService;
    }

    const PER_PAGE = 10;

    /*
    * Store company data.
    * @param $model
    * @param $where
    * @param $data
    *
    * @return object $object.
    * */
    public function findUpdateOrCreate($model, array $where, array $data)
    {
        $object = $model::firstOrNew($where);

        foreach ($data as $property => $value) {
            $object->{$property} = $value;
        }
        $object->save();

        return $object;
    }

    public function DropDownData()
    {
        $result = [
            'MeasurementTypes' => MeasurementType::pluck('name', 'id'),
            'PackingType' => PackingType::pluck('name', 'id'),
        ];

        return $result;
    }

    public function getListOfMainHeads()
    {
        return CoaMainHead::orderBy('id', 'ASC')->paginate(config('constants.PER_PAGE'));
    }

    public function getMaxAccountCode()
    {
        return CoaInventoryDetailAccount::max('code') ? CoaInventoryDetailAccount::max('code') + 1 : 1;
    }

    public function getListOfDetailAccounts($param = null)
    {
        $q = CoaInventoryDetailAccount::with('getMainHead', 'getControlHead', 'getSubHead', 'getSubSubHead', 'priceTag');
        if (!empty($param)) {
            $q->where('name', 'LIKE', '%' . $param . '%');
        }
        $detailAccounts = $q->orderBy('id', 'DESC')->paginate(config('constants.PER_PAGE'));

        return $detailAccounts;
    }

    public function getSubHeadsByMainHead($mainHead)
    {
        return CoaInventorySubHead::where('main_head', $mainHead)->pluck('name', 'id');
    }

    // public function getSubSubHeadsBySubHead($subHead)
    // {
    //     return CoaInventorySubSubHead::where('sub_head_id', $subHead)->pluck('name', 'id');
    // }


    public function prepareCoaDetailAccountData($request)
    {
        $session = $this->commonService->getSession();

        return [
            'main_head' => $request['coa_main_head'],
            'control_head' => $request['control_head'],
            'sub_head' => $request['sub_head'],
            'sub_sub_head' => $request['sub_sub_head'],
            'account_code' => $request['code'],
            'account_name' => $request['name'],
            'business_id' => $session->business_id,
            'f_year_id' => $session->financial_year,
            'created_by' => Auth::user()->id,
            'updated_by' => Auth::user()->id,
            'created_at' => now(),
            'updated_at' => now()
        ];
    }

    public function prepareCoaDetailAccountDetailData($request)
    {

        $openingStock = $request['opening_stock'];
        $stockRate = $request['stock_rate'];
        $openingBalance = ($openingStock > 0 && $stockRate > 0) ? $openingStock * $stockRate : 0;

        $maxid = CoaDetailAccount::max('id');

        return [
            'det_account_code' => $maxid,
            'address' => null,
            'email' => null,
            'cnic' => null,
            'contact_no_1' => null,
            'remarks' => null,
            'opening_balance' => $openingBalance,
            'credit_limit' => null,
            'credit_days' => null,
            'contact_no_2' => null,
            'created_by' => Auth::user()->id,
            'updated_by' => Auth::user()->id,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    public function prepareUpdatedCoaDetailAccountDetailData($request, $party)
    {

        $openingStock = $request['opening_stock'];
        $stockRate = $request['stock_rate'];
        $openingBalance = ($openingStock > 0 && $stockRate > 0) ? $openingStock * $stockRate : 0;

        // $maxid = $party->id;
        $maxid = $party->value('id');

        return [
            'det_account_code' => $maxid,
            'address' => null,
            'email' => null,
            'cnic' => null,
            'contact_no_1' => null,
            'remarks' => null,
            'opening_balance' => $openingBalance,
            'credit_limit' => null,
            'credit_days' => null,
            'contact_no_2' => null,
            'created_by' => Auth::user()->id,
            'updated_by' => Auth::user()->id,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    public function getControlHeadsForMainHead($mainHead)
    {
        return CoaControlHead::where('main_head', $mainHead)->pluck('account_name', 'id'); //change here
    }

    public function getSubHeadsForControlHead($controlHead)
    {
        return CoaSubHead::where('control_head', $controlHead)->pluck('account_name', 'id'); //change here
    }

    public function getSubSubHeadsBySubHead($subHead)
    {
        return CoaSubSubHead::where('sub_head', $subHead)->pluck('account_name', 'id'); //change here
    }

    public function prepareProductAssingData($request, $masterId)
    {

        $partyIds = CoaDetailAccount::whereNull('deleted_at')->pluck('id')->toArray();

        return [
            'detail_account_id' => $partyIds,
            'master_price_tag' => $request['priceTag_id'],
            'master_third_level' => $request['sub_sub_head'],
            'product_id' => $masterId,
            'price' => $request['rate'],
            'discount' => $request['discount'] ?? 0,
            'scheme' => $request['scheme'] ?? 0,
            'created_at' => now(),
            'updated_at' => now()
        ];
    }

    public function prepareDetailAccountMasterData($request)
    {

        $session = $this->commonService->getSession();
        return [
            'coa_main_head' => $request['coa_main_head'],
            'business_id' => $session->business_id,
            'f_year_id' => $session->financial_year,
            'control_head' => $request['control_head'],
            'sub_head' => $request['sub_head'],
            'sub_sub_head' => $request['sub_sub_head'],
            'code' => $request['code'],
            'priceTag_id' => $request['priceTag_id'],
            'name' => $request['name'],
            'remarks' => $request['remarks'],
            'danger_level' => $request['danger_level'],
            'opening_stock' => $request['opening_stock'],
            'stock_rate' => $request['stock_rate'],
            'use_in' => $request['use_in'],
            'image' => $request['image'],
            'measurement_type_id' => $request['measurement_type_id'],
            'packing_type_id' => $request['packing_type_id'],
            'size' => $request['size'],
            'max_limit' => $request['max_limit'],
            'min_limit' => $request['min_limit'],
            'rate' => $request['rate'],
            'discount' => $request['discount'] ?? null,
            'scheme' => $request['scheme'] ?? null,
            'created_by' => Auth::user()->id,
            'updated_by' => Auth::user()->id,
            'created_at' => now(),
            'updated_at' => now()
        ];
    }

    public function ProductAssing($data)
    {
        foreach ($data['detail_account_id'] as $key => $value) {
            if (!empty($data['detail_account_id'][$key])) {
                $rec['detail_account_id'] = $data['detail_account_id'][$key];
                $rec['master_price_tag'] = $data['master_price_tag'];
                $rec['master_third_level'] = $data['master_third_level'];
                $rec['product_id'] = $data['product_id'];
                $rec['price'] = $data['price'];
                $rec['discount'] = $data['discount'];
                $rec['scheme'] = $data['scheme'];

                DetailAccountProducts::create($rec);
            }
        }
    }

    public function prepareDetailAccountCreditData($request, $detailAccountMasterId)
    {
        $openingStock = $request['opening_stock'];
        $stockRate = $request['stock_rate'];
        $creditValue = ($openingStock > 0 && $stockRate > 0) ? $openingStock * $stockRate : 0;

        return [
            'date' => Carbon::now()->format('Y-m-d'),
            'invoice_id' => $detailAccountMasterId,
            'party_id' =>  '108',
            'document_number' => 'OPENING BALANCE',
            'rate' => config('constants.ZERO'),
            'bilty_no' => null,
            'transporter_id' => null,
            'total_quantity' => config('constants.ZERO'),
            'measurementType' => config('constants.ZERO'),
            'bags' => config('constants.ZERO'),
            'description' => 'Opening Balance of ' . $request['name'],
            'debit' => config('constants.ZERO'),
            'credit' =>  $creditValue ?? 0,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    public function prepareDetailAccountDebitData($request, $detailAccountMasterId)
    {
        $openingStock = $request['opening_stock'];
        $stockRate = $request['stock_rate'];
        $debitValue = ($openingStock > 0 && $stockRate > 0) ? $openingStock * $stockRate : 0;
        $maxid = CoaDetailAccount::max('id');

        return [
            'date' => Carbon::now()->format('Y-m-d'),
            'invoice_id' => $detailAccountMasterId,
            'party_id' =>  $maxid,
            'document_number' => 'OPENING BALANCE',
            'rate' => config('constants.ZERO'),
            'bilty_no' => null,
            'transporter_id' => null,
            'total_quantity' => config('constants.ZERO'),
            'measurementType' => config('constants.ZERO'),
            'bags' => config('constants.ZERO'),
            'description' => 'OPENING BALANCE',
            'debit' => $debitValue ?? 0,
            'credit' =>  config('constants.ZERO'),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    public function updateDetailAccountCreditData($request)
    {
        $openingStock = $request['opening_stock'];
        $stockRate = $request['stock_rate'];
        $creditValue = ($openingStock > 0 && $stockRate > 0) ? $openingStock * $stockRate : 0;

        return [
            'date' => Carbon::now()->format('Y-m-d'),
            'invoice_id' => $request->id,
            'party_id' =>  '108',
            'document_number' => 'OPENING BALANCE',
            'rate' => config('constants.ZERO'),
            'bilty_no' => null,
            'transporter_id' => null,
            'total_quantity' => config('constants.ZERO'),
            'measurementType' => config('constants.ZERO'),
            'bags' => config('constants.ZERO'),
            'description' => 'Opening Balance of ' . $request['name'],
            'debit' => config('constants.ZERO'),
            'credit' =>  $creditValue ?? 0,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    public function updateDetailAccountDebitData($request, $partyId)
    {
        $openingStock = $request['opening_stock'];
        $stockRate = $request['stock_rate'];
        $debitValue = ($openingStock > 0 && $stockRate > 0) ? $openingStock * $stockRate : 0;
        $maxid = CoaDetailAccount::max('id');

        return [
            'date' => Carbon::now()->format('Y-m-d'),
            'invoice_id' => $request->id,
            'party_id' =>  $partyId ?? $maxid,
            'document_number' => 'OPENING BALANCE',
            'rate' => config('constants.ZERO'),
            'bilty_no' => null,
            'transporter_id' => null,
            'total_quantity' => config('constants.ZERO'),
            'measurementType' => config('constants.ZERO'),
            'bags' => config('constants.ZERO'),
            'description' => 'OPENING BALANCE',
            'debit' => $debitValue ?? 0,
            'credit' =>  config('constants.ZERO'),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    public function prepareGeneralJournalDetailAccountCreditData($request, $detailAccountId)
    {
        $partyName = CoaDetailAccount::find(108)->account_name ?? null;
        $openingStock = $request['opening_stock'];
        $stockRate = $request['stock_rate'];
        $creditValue = ($openingStock > 0 && $stockRate > 0) ? $openingStock * $stockRate : 0;

        $session = $this->commonService->getSession();
        return [
            'date' => Carbon::parse($request['date'])->format('Y-m-d'),
            'invoice_id' => $detailAccountId,
            'document_number' => 'Coi' . '-' . $detailAccountId,
            'business_id' => $session->business_id,
            'f_year_id' => $session->financial_year,
            'description' => $partyName,
            'narration' => 'Credit Opening Balance of' . ' ' . $partyName,
            'debit' =>  config('constants.ZERO'),
            'credit' => $creditValue,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    public function prepareGeneralJournalDetailAccountDebitData($request, $detailAccountId)
    {
        $openingStock = $request['opening_stock'];
        $stockRate = $request['stock_rate'];
        $debitValue = ($openingStock > 0 && $stockRate > 0) ? $openingStock * $stockRate : 0;

        $session = $this->commonService->getSession();
        return [
            'date' => Carbon::parse($request['date'])->format('Y-m-d'),
            'invoice_id' => $detailAccountId,
            'document_number' => 'Coi' . '-' . $detailAccountId,
            'business_id' => $session->business_id,
            'f_year_id' => $session->financial_year,
            'description' => $request['name'],
            'narration' => 'Debit Opening Balance of' . ' ' . $request['name'],
            'debit' =>  $debitValue,
            'credit' => config('constants.ZERO'),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }


    public function prepareUpdateGeneralJournalDetailAccountCreditData($request)
    {
        $partyName = CoaDetailAccount::find(108)->account_name ?? null;
        $openingStock = $request['opening_stock'];
        $stockRate = $request['stock_rate'];
        $creditValue = ($openingStock > 0 && $stockRate > 0) ? $openingStock * $stockRate : 0;

        $session = $this->commonService->getSession();
        return [
            'date' => Carbon::parse($request['date'])->format('Y-m-d'),
            'invoice_id' => $request->id,
            'document_number' => 'Coi' . '-' . $request->id,
            'business_id' => $session->business_id,
            'f_year_id' => $session->financial_year,
            'description' => $partyName,
            'narration' => 'Credit Opening Balance of' . ' ' . $partyName,
            'debit' =>  config('constants.ZERO'),
            'credit' => $creditValue,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    public function prepareUpdateGeneralJournalDetailAccountDebitData($request)
    {
        $openingStock = $request['opening_stock'];
        $stockRate = $request['stock_rate'];
        $debitValue = ($openingStock > 0 && $stockRate > 0) ? $openingStock * $stockRate : 0;

        $session = $this->commonService->getSession();
        return [
            'date' => Carbon::parse($request['date'])->format('Y-m-d'),
            'invoice_id' => $request->id,
            'document_number' => 'Coi' . '-' . $request->id,
            'business_id' => $session->business_id,
            'f_year_id' => $session->financial_year,
            'description' => $request['name'],
            'narration' => 'Debit Opening Balance of' . ' ' . $request['name'],
            'debit' =>  $debitValue,
            'credit' => config('constants.ZERO'),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
