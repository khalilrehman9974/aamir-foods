<?php

namespace App\Services;

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
                'transporters.name as transporterName',
            )
            ->where('goods_received_note_masters.id', $id)
            ->first();
    }

    public function DropDownData()
    {
        $result = [
            'transporters' => Transporter::pluck('name','id'),
            'products' => CoaInventoryDetailAccount::pluck('name','id'),

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
            $q = GoodsReceivedNote::with('transporter')
            ->where('supplier_name', 'like', '%' . $request['param'] . '%')
            ->orwhere('date', 'like', '%' . $request['param'] . '%')
            ->orwhere('purchase_order_no', 'like', '%' . $request['param'] . '%');
        }
        $goodsReceivedNotes = $q->orderBy('supplier_name', 'ASC')->paginate(config('constants.PER_PAGE'));

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
            'supplier_name' => $request['supplier_name'],
            'fare' => $request['fare'],
            'supplier_bill_no' => $request['supplier_bill_no'],
            'transporter_id' => $request['transporter_id'],
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
            'quantity' => $request['quantity'],
            'remarks' => $request['remarks'],
            'goods_received_note_master_id' => $grnParentId,
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
                $rec['quantity'] = $data['quantity'][$key];
                $rec['remarks'] = $data['remarks'][$key];
                $rec['goods_received_note_master_id'] = $data['goods_received_note_master_id'];
                GRNotesDetail::create($rec);
            }
        }
    }

}
