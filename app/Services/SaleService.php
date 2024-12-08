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
        $q = SaleMaster::query();
        if (!empty($request['param'])) {
            $q = SaleMaster::where('date', 'like', '%' . $request['param'] . '%')
            ->orWhere('sale_order_number', 'like', '%' . $request['param'] . '%')
            ->orWhere('party_id', 'like', '%' . $request['param'] . '%')
            ->orWhere('saleman', 'like', '%' . $request['param'] . '%')
            ->orWhere('area', 'like', '%' . $request['param'] . '%')
            ->orWhere('vehicle_no', 'like', '%' . $request['param'] . '%')
            ->orWhere('bility_no', 'like', '%' . $request['param'] . '%')
            ->orWhere('driver_name', 'like', '%' . $request['param'] . '%')
            ->orWhere('total_boray', 'like', '%' . $request['param'] . '%')
            ->orWhere('total_carton', 'like', '%' . $request['param'] . '%')
            ->orWhere('sector', 'like', '%' . $request['param'] . '%');
        }
        $saleInvoices = $q->orderBy('id', 'ASC')->paginate(config('constants.PER_PAGE'));

        return $saleInvoices;
    }


    /*
     * Prepare sale master data.
     * @param: $request
     * @return Array
     * */
    public function prepareSaleMasterData($request)
    {
        return [
            'dispatch_note_number' => $request['dispatch_note_number'],
            'sale_order_number' => $request['sale_order_number'],
            'date' => Carbon::parse($request['date'])->format('Y-m-d'),
            'party_id' => $request['party_id'],
            'saleman' => $request['saleman'],
            'sector' => $request['sector'],
            'area' => $request['area'],
            'deliverd_to' => $request['deliverd_to'],
            'vehicle_no' => $request['vehicle_no'],
            'driver_name' => $request['driver_name'],
            'bilty_no' => $request['bilty_no'],
            'business_id' => $request['business_id'],
            'f_year_id' => $request['f_year_id'],
            'remarks' => $request['remarks'],
            'total_boray' => $request['total_boray'],
            'total_carton' => $request['total_carton'],
            'gross_bill' => $request['gross_bill'],
            'carriage' => $request['carriage'],
            'discount' => $request['discount'],
            'commission' => $request['commission'],
            'net_amount' => $request['net_amount'],
            'created_by' => Auth::user()->id,
            'updated_by' => Auth::user()->id
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
            'packing_type' => $request['packing_type'],
            'measurement_type' => $request['measurement_type'],
            'quantity' => $request['quantity'],
            'dzns' => $request['dzns'],
            'total_dzns' => $request['total_dzns'],
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
                $rec['packing_type'] = $data['packing_type'][$key];
                $rec['measurement_type'] = $data['measurement_type'][$key];
                $rec['quantity'] = $data['quantity'][$key];
                $rec['dzns'] = $data['dzns'][$key];
                $rec['total_dzns'] = $data['total_dzns'][$key];
                $rec['rate'] = $data['rate'][$key];
                $rec['amount'] = $data['amount'][$key];
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
