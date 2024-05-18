<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\Transporter;
use App\Models\PurchaseDetail;
use App\Models\PurchaseMaster;
use App\Models\CoaDetailAccount;
use Illuminate\Support\Facades\Auth;
use App\Models\CoaInventoryDetailAccount;

class PurchaseService
{
    const PER_PAGE = 10;
    const PURCHASE_TRANSACTION_TYPE = 'purchase';
    const PURCHASE_DESCRIPTION = 'Purchased products';
    
    protected $commonService;

    public function __construct(CommonService $commonService)
    {
        $this->commonService = $commonService;
    }

    /*
     * Get contract by id.
     * @param $id
     * */
    public function getpurchaseMasterById($id)
    {
        return PurchaseMaster::leftjoin('parties', 'parties.id', '=', 'purchase_masters.party_id')
            ->select(
                'purchase_masters.id as id',
                'purchase_masters.date',
                'purchase_masters.grn_no',
                'purchase_masters.type',
                'purchase_masters.bill_no',
                'purchase_masters.transporter_id',
                'purchase_masters.total_amount',
                'purchase_masters.fare',
                'purchase_masters.carriage_inward',
                'purchase_masters.remarks',
                'parties.name as partyName',
            )
            ->where('purchase_masters.id', $id)
            ->first();
    }

    public function DropDownData()
    {
        $result = [
            'parties' => CoaDetailAccount::pluck('account_name','id'),
            'transporters' => Transporter::pluck('name','id'),
            'products' => CoaInventoryDetailAccount::pluck('name','id'),
        ];

        return $result;
    }

    /*
    * Get contract by id.
    * @param $id
    * */
    public function getPurchaseDetailById($id)
    {
        return PurchaseDetail::leftjoin('purchases', 'purchase_details.product_id', '=', 'purchases.id')
            ->select('purchases.name as purchaseName', 'purchase_details.unit', 'purchase_details.quantity', 'purchase_details.amount', 'purchase_details.rate', 'purchase_details.total_unit')
            ->where('purchase_details.purchase_master_id', $id)
            ->get();
    }

    public function search($request)
    {
        $q = PurchaseMaster::query();
        if (!empty($request['param'])) {
            $q = PurchaseMaster::with('type','party','transporter')->where('grn_no', 'like', '%' . $request['param'] . '%');
        }
        $purchases = $q->orderBy('grn_no', 'ASC')->paginate(config('constants.PER_PAGE'));

        return $purchases;
    }

    /*
     * Prepare Purchase master data.
     * @param: $request
     * @return Array
     * */
    public function preparePurchaseMasterData($request)
    {
        $session = $this->commonService->getSession();
        return [
            'grn_no' => $request['grn_no'],
            'date' => Carbon::parse($request['date'])->format('Y-m-d'),
            'type' => config('constants.transaction.Purchase'),
            'party_id' => $request['party_id'],
            'transporter_id' => $request['transporter_id'],
            'bill_no' => $request['bill_no'],
            'business_id' => $session->business_id,
            'f_year_id' => $session->financial_year,
            'remarks' => $request['remarks'],
            'total_amount' =>config('constants.ZERO'),
            'fare' => $request['fare'],
            'carriage_inward' => $request['carriage_inward'],
            'created_by' => Auth::user()->id,
            'updated_by' => Auth::user()->id
        ];
    }

    /*
     * Prepare Purchase detail data.
     * @param: $request
     * @return Array
     * */
    public function preparePurchaseDetailData($request, $purchaseParentId)
    {
        return [
            'product_id' => $request['product_id'],
            'quantity' => $request['quantity'],
            'unit' => $request['unit'],
            'total_unit' => config('constants.ZERO'),
            'rate' => $request['rate'],
            'amount' => config('constants.ZERO'),
            'purchase_master_id' => $purchaseParentId,
        ];
    }

    /*
     * Save purchase data.
     * @param: $data
     * */
    public function savePurchase($data)
    {
        foreach ($data['product_id'] as $key => $value) {
            if (!empty($data['product_id'][$key])) {
                $rec['product_id'] = $data['product_id'][$key];
                $rec['unit'] = $data['unit'][$key];
                $rec['quantity'] = $data['quantity'][$key];
                $rec['rate'] = $data['rate'][$key];
                $rec['amount'] = config('constants.ZERO');
                $rec['total_unit'] = config('constants.ZERO');
                $rec['purchase_master_id'] = $data['purchase_master_id'];
                PurchaseDetail::create($rec);
            }
        }
    }

}
