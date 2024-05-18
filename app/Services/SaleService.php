<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\SaleDetail;
use App\Models\SaleMaster;
use Illuminate\Support\Facades\Auth;

class SaleService
{
    const PER_PAGE = 10;

    protected $commonService;

    public function __construct(CommonService $commonService)
    {
        $this->commonService = $commonService;
    }

    /*
     * Get contract by id.
     * @param $id
     * */
    public function getSaleMasterById($id)
    {
        return SaleMaster::leftjoin('parties', 'parties.id', '=', 'sale_masters.party_id')
            ->leftjoin('salemans', 'salemans.id', '=', 'sale_masters.saleman_id')
            ->select(
                'sale_masters.id as id',
                'sale_masters.date',
                'sale_masters.dispatch_note',
                'sale_masters.type_id',
                'sale_masters.bilty_no',
                'sale_masters.remarks',
                'sale_masters.created_at',
                'sale_masters.deliverd_to',
                'sale_masters.updated_at',
                'sale_masters.transporter_id',
                'sale_masters.total_amount',
                'sale_masters.freight',
                'sale_masters.scheme',
                'sale_masters.commission',
                'parties.name as partyName',
                'salemans.id as salemanId'
            )
            ->where('sale_masters.id', $id)
            ->first();
    }

    /*
    * Get contract by id.
    * @param $id
    * */
    public function getSaleDetailById($id)
    {
        return SaleDetail::leftjoin('salemans', 'sale_details.product_id', '=', 'salemans.id')
            ->select('salemans.name as salemanName', 'sale_details.unit', 'sale_details.quantity', 'sale_details.amount', 'sale_details.rate', 'sale_details.total_unit')
            ->where('sale_details.sale_master_id', $id)
            ->get();
    }

    /*
     * Search sale record.
     * @queries: $queries
     * @return: object
     * */
    public function searchSale($request)
    {
        $query = SaleMaster::groupBy(
            'sale_masters.id',
            'sale_masters.date',
            'sale_masters.dispatch_note',
            'sale_masters.type_id',
            'sale_masters.party_id',
            'sale_masters.bilty_no',
            'sale_masters.remarks',
            'sale_masters.created_at',
            'sale_masters.deliverd_to',
            'sale_masters.updated_at',
            'sale_masters.saleman_id',
            'sale_masters.transporter_id',
            'sale_masters.total_amount',
            'sale_masters.freight',
            'sale_masters.scheme',
            'sale_masters.commission',
        );
        if (!empty($request['param'])) {
            $query = $query->where('sale_masters.id', "=", $request['param']);
            //            $query = $query->orwhere('parties.name', "% like %", $request['param']);
        }
        //        $query->select('sale_masters.id','sale_masters.date','sale_masters.amount','sale_masters.quantity');
        $sales = $query->orderBy('id', 'DESC')->get();

        return $this->commonService->paginate($sales, Self::PER_PAGE);
    }

    // /*
    //  * Get list of products for selected category and brand.
    //  * @param: $request
    //  * @return Array
    //  * */
    // public function getProductsByCategoryBrand($request)
    // {
    //     return Product::where('brand_id', $request['brandCode'])->get();
    // }

    /*
     * Prepare sale master data.
     * @param: $request
     * @return Array
     * */
    public function prepareSaleMasterData($request)
    {
        return [
            'dispatch_note' => $request['dispatch_note'],
            'date' => $request['dispatch_note'],
            'type_id' => $request['type_id'],
            'party_id' => $request['party_id'],
            'bilty_no' => $request['bilty_no'],
            'deliverd_to' => $request['deliverd_to'],
            'saleman_id' => $request['saleman_id'],
            'transporter_id' => $request['transporter_id'],
            'business_id' => $request['business_id'],
            'f_year_id' => $request['f_year_id'],
            'remarks' => $request['remarks'],
            'total_amount' => array_sum($request['total_amount']),
            'freight' => $request['freight'],
            'scheme' => $request['scheme'],
            'commission' => $request['commission'],
            $data['created_by'] = Auth::user()->id,
            $data['updated_by'] = Auth::user()->id
        ];
    }

    /*
     * Prepare sale detail data.
     * @param: $request
     * @return Array
     * */
    public function prepareSaleDetailData($request, $saleParentId)
    {
        return [
            'product_id' => $request['product_id'],
            'quantity' => $request['quantity'],
            'unit' => $request['unit'],
            'total_unit' => $request['total_unit'],
            'rate' => $request['rate'],
            'amount' => $request['amount'],
            'sale_master_id' => $saleParentId,
        ];
    }

    /*
     * Save sale data.
     * @param: $data
     * */
    public function saveSale($data)
    {
        foreach ($data['product_id'] as $key => $value) {
            if (!empty($data['product_id'][$key])) {
                $rec['product_id'] = $data['product_id'][$key];
                $rec['unit'] = $data['unit'][$key];
                $rec['quantity'] = $data['quantity'][$key];
                $rec['rate'] = $data['rate'][$key];
                $rec['amount'] = $data['amount'][$key];
                $rec['total_unit'] = $data['total_unit'][$key];
                $rec['sale_master_id'] = $data['sale_master_id'];
                SaleDetail::create($rec);
            }
        }
    }

    public function prepareAccountCreditData($request, $saleParentId, $dataType, $description)
    {
        return [
            'date' => Carbon::parse($request['date'])->format('Y-m-d'),
            'invoice_id' => $saleParentId,
            'account_id' => 'S-00000001',
            'description' => $description . ' '. $saleParentId. $dataType,
            'debit' => 0,
            'credit' => $request['totalAmount'],
        ];
    }

    public function prepareAccountDebitData($request, $saleParentId, $dataType, $description)
    {
        return [
            'invoice_id' => $saleParentId,
            'account_id' => $request['customer_id'],
            'description' => $description . ' '. $saleParentId, $dataType,
            'debit' => $request['totalAmount'],
            'credit' => 0,
        ];
    }
}
