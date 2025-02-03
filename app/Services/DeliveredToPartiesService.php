<?php

namespace App\Services;

/*
 * Class DeliveredToPartiesService
 * @package App\Services
 * */

use App\Models\Area;
use App\Models\CoaDetailAccount;
use App\Models\SaleMan;
use App\Models\DeliveredToParties;
use Illuminate\Support\Facades\Auth;
use App\Models\DeliveredToPartiesAreas;
use App\Models\DeliveredToPartiesSectors;

class DeliveredToPartiesService
{

    protected $commonService;

    public function __construct(CommonService $commonService)
    {
        $this->commonService = $commonService;
    }

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

    public function getListOfDeliveredToParties($param = null)
    {
        $q = DeliveredToParties::with('SaleMan','Party');
        if (!empty($param)) {
            $q->where('party_name', 'LIKE', '%' . $param . '%');
        }
        $deliveredToParties = $q->orderBy('id', 'ASC')->paginate(config('constants.PER_PAGE'));

        return $deliveredToParties;
    }


    public function DropDownData()
    {
        $result = [
            'saleMans' => SaleMan::pluck('name', 'id'),
            'parties' => CoaDetailAccount::pluck('account_name', 'id'),
        ];

        return $result;
    }


    public function prepareDetailAccountMasterData($request)
    {
        $session = $this->commonService->getSession();
        return [
            'detail_account_id' => $request['detail_account_id'],
            'party_name' => $request['party_name'],
            'saleMan_id' => $request['saleMan_id'],
            'commision' => $request['commision'],
            'mode' => $request['mode'],
            'business_id' => $session->business_id,
            'f_year_id' => $session->financial_year,
            'status' => $request['status'],
            'address' => $request['address'],
            'cnic' => $request['cnic'],
            'contact_no_1' => $request['contact_no_1'],
            'contact_no_2' => $request['contact_no_2'],
            'email' => $request['email'],
            'opening_balance' => $request['opening_balance'],
            'credit_limit' => $request['credit_limit'],
            'created_by' => Auth::user()->id,
            'updated_by' => Auth::user()->id
        ];
    }

    public function prepareDetailAccountSectorsData($request, $detailAccountMasterInsert)
    {

        return [
            'sector_id' => $request['sector_id'],
            'delivered_to_party_id' => $detailAccountMasterInsert,
        ];
    }

    /*
     * Save sale data.
     * @param: $data
     * */
    public function saveDeliveredToPartiesSectors($data)
    {
        foreach ($data['sector_id'] as $key => $value) {
            if (!empty($data['sector_id'][$key])) {
                $rec['sector_id'] = $data['sector_id'][$key];
                $rec['delivered_to_party_id'] = $data['delivered_to_party_id'];
                DeliveredToPartiesSectors::create($rec);
            }
        }
    }

    public function prepareDetailAccountAreasData($request, $detailAccountMasterInsert)
    {
        return [
            'area_id' => $request['area_id'],
            'delivered_to_party_id' => $detailAccountMasterInsert,
        ];
    }

    /*
     * Save sale data.
     * @param: $data
     * */
    public function saveDeliveredToPartiesAreas($data)
    {
        foreach ($data['area_id'] as $key => $value) {
            if (!empty($data['area_id'][$key])) {
                $rec['area_id'] = $data['area_id'][$key];
                $arrayId = $rec['area_id'];
                $rec['sector_id'] = Area::where("id", $arrayId)->value("sector_id");
                $rec['delivered_to_party_id'] = $data['delivered_to_party_id'];
                DeliveredToPartiesAreas::create($rec);
            }
        }
    }


}
