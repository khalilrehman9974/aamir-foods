<?php

namespace App\Services;

use Illuminate\Support\Carbon;
use App\Models\CoaDetailAccount;
use App\Models\ClaimRateAdjustment;
use App\Models\ClaimRateAdjustmentDetail;
use Illuminate\Support\Facades\Auth;
use App\Models\CoaInventoryDetailAccount;
use App\Models\Transporter;

class ClaimRateAdjustmentService
{
    protected $commonService;

    public function __construct(CommonService $commonService)
    {
        $this->commonService = $commonService;
    }

    public function findUpdateOrCreate($model, array $where, array $data)
    {
        $object = $model::firstOrNew($where);

        foreach ($data as $property => $value) {
            $object->{$property} = $value;
        }
        $object->save();

        return $object;
    }

    /*
     * Search sale record.
     * @queries: $queries
     * @return: object
     * */

    // public function searchSale($request)
    // {
    public function searchClaim($request)
    {
        $q = ClaimRateAdjustment::query();
        if (!empty($request['date'])) {
            $q->where('date', $request['date']);
        } elseif (!empty($request['party_id'])) {
            $q->where('party_id', $request['party_id']);
        }

        $saleOrders = $q->with(['party','SaleMan'])->orderBy('updated_at', 'DESC')->paginate(config('constants.PER_PAGE'));
        return $saleOrders;
    }


    /*
     * Prepare sale master data.
     * @param: $request
     * @return Array
     * */
    public function prepareClaimMasterData($request)
    {
        $session = $this->commonService->getSession();
        return [
            'date' => Carbon::parse($request['date'])->format('Y-m-d'),
            'party_id' => $request['party_id'],
            'business_id' => $session->business_id,
            'f_year_id' => $session->financial_year,
            'remarks' => $request['remarks'],
            'saleman' => $request['saleman'],
            'sector' => $request['sector'],
            'area' => $request['area'],
            'delivered_to' => $request['delivered_to'] ?? null,
            'driver_name' => $request['driver_name'],
            'transporter' => $request['transporter'],
            'bilty_no' => $request['bilty_no'],
            'discount' => $request['discount'],
            'commission' => $request['commission'],
            'total_boray' => $request['total_boray'],
            'total_carton' => $request['total_carton'],
            'gross_amount' => $request['gross_amount'],
            'created_by' => Auth::user()->id,
            'updated_by' => Auth::user()->id
        ];
    }

    public function DropDownData()
    {
        $result = [
            'parties' => CoaDetailAccount::pluck('account_name', 'id'),
            'products' => CoaInventoryDetailAccount::pluck('name', 'id'),
            'transporters' => Transporter::pluck('name', 'id')

        ];

        return $result;
    }

    /*
     * Prepare sale detail data.
     * @param: $request
     * @return Array
     * */
    public function prepareClaimDetailData($request, $claimParentId)
    {
        return [
            'product_id' => $request['product_id'],
            'packing_type' => $request['packing_type'],
            'measurement_type' => $request['measurement_type'],
            'quantity' => $request['quantity'],
            'dzn' => $request['dzn'],
            'total_dzn' => $request['total_dzn'],
            'rate' => $request['rate'],
            'amount' => $request['amount'],
            'created_by' => Auth::user()->id,
            'updated_by' => Auth::user()->id,
            'master_id' => $claimParentId,
        ];
    }

    /*
     * Save sale data.
     * @param: $data
     * */
    public function saveClaim($data)
    {
        foreach ($data['product_id'] as $key => $value) {
            if (!empty($data['product_id'][$key])) {
                $rec['product_id'] = $data['product_id'][$key];
                $rec['packing_type'] = $data['packing_type'][$key];
                $rec['measurement_type'] = $data['measurement_type'][$key];
                $rec['quantity'] = $data['quantity'][$key];
                $rec['dzn'] = $data['dzn'][$key];
                $rec['total_dzn'] = $data['total_dzn'][$key];
                $rec['rate'] = $data['rate'][$key];
                $rec['amount'] = $data['amount'][$key];
                $rec['master_id'] = $data['master_id'];
                ClaimRateAdjustmentDetail::create($rec);
            }
        }
    }
}
