<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\Area;
use App\Models\SaleMan;
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

        foreach ($data as $property => $value){
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
                'dispatch_note_masters.id as id',
                'dispatch_note_masters.date',
                'dispatch_note_masters.po_no',
                'dispatch_note_masters.party_id',
                'dispatch_note_masters.transporter_id',
                'dispatch_note_masters.bilty_no',
                'dispatch_note_masters.fare',
                'dispatch_note_masters.contact_no',
                'saleMans.name as saleManName',
            )
            ->where('dispatch_note_masters.id', $id)
            ->first();
    }



    public function searchDispatch($request)
    {
        $q = DispatchNoteMaster::query();
        if (!empty($request['param'])) {
            $q = DispatchNoteMaster::with('party','transporter','saleMan')
            ->where('po_no', 'like', '%' . $request['param'] . '%')
            ->orWhere('fare', 'like', '%' . $request['param'] . '%')
            ->orWhere('bilty_no', 'like', '%' . $request['param'] . '%')
            ->orWhere('date', 'like', '%' . $request['param'] . '%');
        }
        $dispatchNotes = $q->orderBy('id', 'ASC')->paginate(config('constants.PER_PAGE'));

        return $dispatchNotes;
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
            'date' => Carbon::parse($request['date'])->format('Y-m-d'),
            'sale_man_id' => $request['sale_man_id'],
            'party_id' => $request['party_id'],
            'transporter_id' => $request['transporter_id'],
            'bilty_no' => $request['bilty_no'],
            'contact_no' => $request['contact_no'],
            'fare' => $request['fare'],
            'created_by' => Auth::user()->id,
            'updated_by' => Auth::user()->id
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
            'dispatch_note_master_id' => $dispatchParentId,
        ];
    }

    /*
     * Save dispatch data.
     * @param: $data
     * */
    public function saveDispatch($data)
    {
        DispatchNoteDetail::where('dispatch_note_master_id', $data['dispatch_note_master_id'])->delete();
        foreach ($data['product_id'] as $key => $value) {
            if (!empty($data['product_id'][$key])) {
                $rec['product_id'] = $data['product_id'][$key];
                $rec['unit'] = $data['unit'][$key];
                $rec['quantity'] = $data['quantity'][$key];
                $rec['remarks'] = $data['remarks'][$key];
                $rec['created_by'] = Auth::user()->id;
                $rec['updated_by'] = Auth::user()->id;
                $rec['dispatch_note_master_id'] = $data['dispatch_note_master_id'];
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
