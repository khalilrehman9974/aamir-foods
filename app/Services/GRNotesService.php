<?php

namespace App\Services;

use App\Models\CoaDetailAccount;
use Carbon\Carbon;
use App\Models\Transporter;
use App\Models\GRNotesDetail;
use App\Models\GoodsReceivedNote;
use Illuminate\Support\Facades\Auth;
use App\Models\CoaInventoryDetailAccount;

class GRNotesService
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
    public function getGRNMasterById($id)
    {
        return GoodsReceivedNote::leftjoin('transporters', 'transporters.id', '=', 'goods_received_note_masters.transporter_id')
            ->select(
                'goods_received_note_masters.id',
                'goods_received_note_masters.date',
                'goods_received_note_masters.purchase_order_no',
                'goods_received_note_masters.supplier_name',
                'goods_received_note_masters.fare',
                'goods_received_note_masters.supplier_bill_no',
                'goods_received_note_masters.transporter_id',
                'goods_received_note_masters.business_id',
                'goods_received_note_masters.f_year_id',
                'goods_received_note_masters.remarks',
                'transporters.name as transporterName'
            )
            ->where('goods_received_note_masters.id', $id)
            ->first();
    }

    public function DropDownData()
    {
        $result = [
            'transporters' => Transporter::pluck('name','id'),
            'products' => CoaInventoryDetailAccount::pluck('name','id'),
            'parties' => CoaDetailAccount::pluck('account_name','id'),

        ];

        return $result;
    }

    /*
     * Search GRN record.
     * @queries: $queries
     * @return: object
     * */

    public function searchGRN($request)
    {
        $q = GoodsReceivedNote::query();
        if (!empty($request['param'])) {
            $q = GoodsReceivedNote::with('transporter','party')
            ->where('party_id', 'like', '%' . $request['param'] . '%')
            ->orwhere('date', 'like', '%' . $request['param'] . '%')
            ->orwhere('purchase_order_no', 'like', '%' . $request['param'] . '%');
        }
        $goodsReceivedNotes = $q->orderBy('id', 'DESC')->paginate(config('constants.PER_PAGE'));

        return $goodsReceivedNotes;
    }

    /*
     * Prepare GRN master data.
     * @param: $request
     * @return Array
     * */
    public function prepareGRNMasterData($request)
    {
        $session = $this->commonService->getSession();
        return [
            'purchase_order_no' => $request['purchase_order_no'],
            'date' => Carbon::parse($request['date'])->format('Y-m-d'),
            'party_id' => $request['party_id'],
            'fare' => $request['fare'],
            'supplier_bill_no' => $request['supplier_bill_no'],
            'unloaded_by' => $request['unloaded_by'],
            'transporter_id' => $request['transporter_id'],
            'total_quantity' => $request['total_quantity'],
            'remarks' => $request['remarks'],
            'business_id' => $session->business_id,
            'f_year_id' => $session->financial_year,
            'created_by' => Auth::user()->id,
            'updated_by' => Auth::user()->id
        ];
    }

    /*
     * Prepare GRN detail data.
     * @param: $request
     * @return Array
     * */
    public function prepareGRNDetailData($request, $grnParentId)
    {
        return [
            'product_id' => $request['product_id'],
            'packing_type' => $request['packing_type'],
            'measurement_type' => $request['measurement_type'],
            'size' => $request['size'] ,
            'bags' => $request['bags'] ,
            'measurementType' => $request['measurementType'] ,
            'po_quantity' => $request['po_quantity'],
            'received_qty' => $request['received_qty'],
            'balance' => $request['balance'],
            'detail_remarks' => $request['detail_remarks'],
            'master_id' => $grnParentId,
        ];
    }

    /*
     * Save GRN data.
     * @param: $data
     * */
    public function saveGRN($data)
    {
        foreach ($data['product_id'] as $key => $value) {
            if (!empty($data['product_id'][$key])) {
                $rec['product_id'] = $data['product_id'][$key];
                $rec['packing_type'] = $data['packing_type'][$key];
                $rec['measurement_type'] = $data['measurement_type'][$key];
                $rec['size'] = $data['size'][$key];
                $rec['bags'] = $data['bags'][$key];
                $rec['measurementType'] = $data['measurementType'][$key];
                $rec['po_quantity'] = $data['po_quantity'][$key];
                $rec['received_qty'] = $data['received_qty'][$key];
                $rec['balance'] = $data['balance'][$key];
                $rec['detail_remarks'] = $data['detail_remarks'][$key];
                $rec['master_id'] = $data['master_id'];
                GRNotesDetail::create($rec);
            }
        }
    }

}
