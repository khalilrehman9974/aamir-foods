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
use App\Models\DispatchNoteImages;

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
                'saleMans.name as saleManName'
            )->where('dispatch_note_masters.id', $id)->first();
    }



    public function searchDispatch($request)
    {
        $q = DispatchNoteMaster::query();
        if (!empty($request['param'])) {
            $q = DispatchNoteMaster::with('party','saleMan','Belt','Area','DeliveredToParty')->where('date', 'like', '%' . $request['param'] . '%')
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
        $dispatchNotes = $q->orderBy('id', 'ASC')->paginate(config('constants.PER_PAGE'));

        return $dispatchNotes;
    }

    public function prepareSOMasterData($saleOrder)
    {
        $status= 'Dispatched';
        return [
            'date' => Carbon::parse($saleOrder['date'])->format('Y-m-d'),
            'party_id' => $saleOrder['party_id'],
            'business_id' => $saleOrder['business_id'],
            'f_year_id' => $saleOrder['f_year_id'],
            'saleman' => $saleOrder['saleman'],
            'belt' => $saleOrder['belt'],
            'area' => $saleOrder['area'],
            'delivered_to' => $saleOrder['delivered_to'],
            'status' => $status,
            'total_boray' => $saleOrder['total_boray'],
            'total_carton' => $saleOrder['total_carton'],
            'remarks' => $saleOrder['remarks'],
            'total_amount' => $saleOrder['total_amount'],
            'created_at' => $saleOrder['created_at'],
            'updated_at' => $saleOrder['updated_at'],
            'created_by' => $saleOrder['created_by'],
            'updated_by' => $saleOrder['updated_by']
        ];
    }

    /*
     * Prepare dispatch master data.
     * @param: $request
     * @return Array
     * */
    public function prepareDispatchMasterData($request)
    {
        return [
            'sale_order_number' => $request['sale_order_number'],
            'date' => Carbon::parse($request['date'])->format('Y-m-d'),
            'party_id' => $request['party_id'],
            'saleman' => $request['saleman'],
            'sector' => $request['sector'],
            'area' => $request['area'],
            'delivered_to' => $request['delivered_to'] ?? null,
            'transporter_id' => $request['transporter_id'],
            'vehicle_no' => $request['vehicle_no'],
            'bility_no' => $request['bility_no'],
            'driver_name' => $request['driver_name'],
            'carriage' => $request['carriage'],
            'total_boray' => $request['total_boray'],
            'total_carton' => $request['total_carton'],
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
            'packing_type' => $request['packing_type'],
            'measurement_type' => $request['measurement_type'],
            'quantity' => $request['quantity'],
            'dzn' => $request['dzn'],
            'total_dzn' => $request['total_dzn'],
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
                $rec['packing_type'] = $data['packing_type'][$key];
                $rec['measurement_type'] = $data['measurement_type'][$key];
                $rec['quantity'] = $data['quantity'][$key];
                $rec['dzn'] = $data['dzn'][$key];
                $rec['total_dzn'] = $data['total_dzn'][$key];
                $rec['remarks'] = $data['remarks'][$key];
                $rec['created_by'] = Auth::user()->id;
                $rec['updated_by'] = Auth::user()->id;
                $rec['dispatch_note_master_id'] = $data['dispatch_note_master_id'];
                DispatchNoteDetail::create($rec);
            }
        }
    }


    public function prepareDispatchNoteImagesData($request, $dispatchNoteParentId)
    {

        $imagePaths = [];

        if (isset($request['images'])) {
            foreach ($request['images'] as $file) {
                $destinationPath = public_path('images/dispatchNote');

                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 0777, true);
                }

                $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $file->move($destinationPath, $filename);

                $imagePaths[] = $filename;
            }

            return [
                'images' => $imagePaths,
                'dispatch_note_id' => $dispatchNoteParentId,
            ];
        } else {

            return [
                'dispatch_note_id' => $dispatchNoteParentId,
            ];
        }
    }

    public function saveDispatchNoteImages($data)
    {
        if (empty($data['images'])) {
            $rec['images'] = null;
            $rec['dispatch_note_id'] = $data['dispatch_note_id'];
            DispatchNoteImages::create($rec);

        } else {
            foreach ($data['images'] as $key => $value) {
                if (!empty($value)) {
                    $rec['images'] = $value;
                    $rec['dispatch_note_id'] = $data['dispatch_note_id'];
                    DispatchNoteImages::create($rec);
                }
            }
        }

    }


    public function prepareLedgerData($request, $dispatchParentId)
    {
        return [
            'product_id' => $request['product_id'],
            'packing_type' => $request['packing_type'],
            'measurement_type' => $request['measurement_type'],
            'quantity' => $request['quantity'],
            'dzn' => $request['dzn'],
            'total_dzn' => $request['total_dzn'],
            'remarks' => $request['remarks'],
            'dispatch_note_master_id' => $dispatchParentId,
        ];
    }

    /*
     * Save dispatch data.
     * @param: $data
     * */
    public function saveLedger($data)
    {
        DispatchNoteDetail::where('dispatch_note_master_id', $data['dispatch_note_master_id'])->delete();
        foreach ($data['product_id'] as $key => $value) {
            if (!empty($data['product_id'][$key])) {
                $rec['product_id'] = $data['product_id'][$key];
                $rec['packing_type'] = $data['packing_type'][$key];
                $rec['measurement_type'] = $data['measurement_type'][$key];
                $rec['quantity'] = $data['quantity'][$key];
                $rec['dzn'] = $data['dzn'][$key];
                $rec['total_dzn'] = $data['total_dzn'][$key];
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
