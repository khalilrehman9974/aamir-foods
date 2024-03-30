<?php

namespace App\Services;

use App\Models\Area;
use App\Models\SaleMan;
use App\Models\Distributer;
use App\Models\Transporter;
use App\Models\CoaDetailAccount;
use App\Models\DispatchNoteDetail;
use App\Models\DispatchNoteMaster;
use Illuminate\Support\Facades\Auth;
use App\Models\CoaInventoryDetailAccount;

class DispatchNoteService
{
    const PER_PAGE = 10;
    protected $commonService;

    public function __construct(CommonService $commonService)
    {
        $this->commonService = $commonService;
    }
    /*
    * Store dispatch note data.
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
            'products' => CoaInventoryDetailAccount::pluck('name','id'),
            'saleMans' => SaleMan::pluck('name','id'),
            'areas' => Area::pluck('name','id'),
            'parties' => CoaDetailAccount::pluck('account_name','id'),
            'transporters' => Transporter::pluck('name','id'),
            'distributers'=> Distributer::pluck('name','id')
        ];

        return $result;
    }

        /*
     * Get contract by id.
     * @param $id
     * */
    public function getdispatchMasterById($id)
    {
        return DispatchNoteMaster::leftjoin('saleMans', 'sale_man.id', '=', 'dispatch_note_masters.sale_man_id')
            ->select(
                'dispatch_masters.id as id',
                'dispatch_masters.date',
                'dispatch_masters.po_no',
                'dispatch_masters.civil_distributer_id',
                'dispatch_masters.party_id',
                'dispatch_masters.transporter_id',
                'dispatch_masters.bilty_no',
                'dispatch_masters.fare',
                'dispatch_masters.contact_no',
                'dispatch_masters.total_balance',
                'saleMans.name as saleManName',
            )
            ->where('dispatch_masters.id', $id)
            ->first();
    }

    /*
    * Get contract by id.
    * @param $id
    * */
    // public function getDispatchDetailById($id)
    // {
    //     return DispatchNoteDetail::leftjoin('purchases', 'purchase_details.product_id', '=', 'purchases.id')
    //         ->select('purchases.name as purchaseName', 'purchase_details.unit', 'purchase_details.quantity', 'purchase_details.amount', 'purchase_details.rate', 'purchase_details.total_unit')
    //         ->where('purchase_details.purchase_master_id', $id)
    //         ->get();
    // }

    /*
     * Search dispatch record.
     * @queries: $queries
     * @return: object
     * */
    public function searchDispatch($request)
    {
        $query = DispatchNoteMaster::groupBy(
            'dispatch_masters.id as id',
            'dispatch_masters.date',
            'dispatch_masters.po_no',
            'dispatch_masters.civil_distributer_id',
            'dispatch_masters.party_id',
            'dispatch_masters.transporter_id',
            'dispatch_masters.bilty_no',
            'dispatch_masters.fare',
            'dispatch_masters.contact_no',
            'dispatch_masters.total_balance',
            'saleMans.name as saleManName',
        );
        if (!empty($request['param'])) {
            $query = $query->where('dispatch_masters.id', "=", $request['param']);
        }
        $purchases = $query->orderBy('id', 'DESC')->get();

        return $this->commonService->paginate($purchases, Self::PER_PAGE);
    }


    /*
     * Prepare dispatch master data.
     * @param: $request
     * @return Array
     * */
    public function prepareDispatchMasterData($request)
    {
        return [
            'po_no' => $request['po_no'],
            'date' => $request['date'],
            'civil_distributer_id' => $request['civil_distributer_id'],
            'party_id' => $request['party_id'],
            'transporter_id' => $request['transporter_id'],
            'bilty_no' => $request['bilty_no'],
            'contact_no' => $request['contact_no'],
            'total_balance' => array_sum($request['total_balance']),
            'fare' => $request['fare'],
            $data['created_by'] = Auth::user()->id,
            $data['updated_by'] = Auth::user()->id
        ];
    }

    /*
     * Prepare dispatch detail data.
     * @param: $request
     * @return Array
     * */
    public function prepareDispatchDetailData($request, $dispatchParentId)
    {
        return [
            'product_id' => $request['product_id'],
            'quantity' => $request['quantity'],
            'unit' => $request['unit'],
            'remarks' => $request['remarks'],
            'dispatch_master_id' => $dispatchParentId,
        ];
    }

    /*
     * Save dispatch data.
     * @param: $data
     * */
    public function saveDispatch($data)
    {
        foreach ($data['product_id'] as $key => $value) {
            if (!empty($data['product_id'][$key])) {
                $rec['product_id'] = $data['product_id'][$key];
                $rec['unit'] = $data['unit'][$key];
                $rec['quantity'] = $data['quantity'][$key];
                $rec['remarks'] = $data['remarks'][$key];
                $rec['dispatch_master_id'] = $data['dispatch_master_id'];
                DispatchNoteDetail::create($rec);
            }
        }
    }

    // public function prepareAccountCreditData($request, $saleParentId, $dataType, $description)
    // {
    //     return [
    //         'date' => Carbon::parse($request['date'])->format('Y-m-d'),
    //         'invoice_id' => $saleParentId,
    //         'account_id' => 'S-00000001',
    //         'description' => $description . ' '. $saleParentId,
    //         'transaction_type' => $dataType,
    //         'debit' => 0,
    //         'credit' => $request['totalAmount'],
    //     ];
    // }

    // public function prepareAccountDebitData($request, $saleParentId, $dataType, $description)
    // {
    //     return [
    //         'date' => Carbon::parse($request['date'])->format('Y-m-d'),
    //         'invoice_id' => $saleParentId,
    //         'account_id' => $request['customer_id'],
    //         'description' => $description . ' '. $saleParentId,
    //         'transaction_type' => $dataType,
    //         'debit' => $request['totalAmount'],
    //         'credit' => 0,
    //     ];
    // }
}
