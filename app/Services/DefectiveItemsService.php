<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\StockLedger;
use App\Models\CoaDetailAccount;
use Illuminate\Support\Facades\Auth;
use App\Models\CoaInventoryDetailAccount;
use App\Models\DefectiveItemsDetails;
use App\Models\DefectiveItemsMaster;
use App\Models\Department;
use PragmaRX\Google2FA\Support\Constants;

class DefectiveItemsService
{

    protected $commonService;

    public function __construct(CommonService $commonService)
    {
        $this->commonService = $commonService;
    }



    public function DropDownData()
    {
        $result = [
            'departments' => Department::pluck('name', 'id'),
            'products' => CoaInventoryDetailAccount::pluck('name', 'id'),
        ];

        return $result;
    }


    public function search($request)
    {
        $q = DefectiveItemsMaster::query();

        if (!empty($request['date'])) {
            $formattedDate = date('Y-m-d', strtotime($request['date']));
            $q->where('date', $formattedDate);
        } elseif (!empty($request['entered_by'])) {
            $q->where('entered_by', $request['entered_by']);
        }

        $defectiveItems = $q->orderBy('id', 'DESC')->paginate(config('constants.PER_PAGE'));

        return $defectiveItems;
    }

    /*
     * Prepare Purchase master data.
     * @param: $request
     * @return Array
     * */
    public function prepareDefectiveItemsMasterData($request)
    {
        $session = $this->commonService->getSession();
        return [
            'date' => Carbon::parse($request['date'])->format('Y-m-d'),
            'entered_by' => $request['entered_by'],
            'business_id' => $session->business_id,
            'f_year_id' => $session->financial_year,
            'created_by' => Auth::user()->id,
            'updated_by' => Auth::user()->id
        ];
    }

    /*
     * Prepare Purchase detail data.
     * @param: $request
     * @return Array
     * */
    public function prepareDefectiveItemsDetailData($request, $defectiveItemsParentId)
    {
        return [
            'from_department' => $request['from_department'],
            'product_id' => $request['product_id'],
            'packing_type' => $request['packing_type'],
            'measurement_type' => $request['measurement_type'],
            'size' => $request['size'],
            'bags' => $request['bags'],
            'avg_weight' => $request['avg_weight'],
            'total_quantity' => $request['total_quantity'],
            'remarks' => $request['remarks'],
            'master_id' => $defectiveItemsParentId,

        ];
    }

    /*
     * Save purchase data.
     * @param: $data
     * */
    public function saveDefectiveItems($data)
    {
        foreach ($data['product_id'] as $key => $value) {
            if (!empty($data['product_id'][$key])) {
                $rec['product_id'] = $data['product_id'][$key];
                $rec['from_department'] = $data['from_department'][$key];
                $rec['packing_type'] = $data['packing_type'][$key];
                $rec['measurement_type'] = $data['measurement_type'][$key];
                $rec['size'] = $data['size'][$key];
                $rec['bags'] = $data['bags'][$key];
                $rec['avg_weight'] = $data['avg_weight'][$key];
                $rec['total_quantity'] = $data['total_quantity'][$key];
                $rec['remarks'] = $data['remarks'][$key];
                $rec['master_id'] = $data['master_id'];
                DefectiveItemsDetails::create($rec);
            }
        }
    }

    public function prepareStockLedgerData($request, $defectiveItemsParentId)
    {
        $department = Department::whereIn('id', $request['from_department'])->get();

        return [
            'product_id' => $request['product_id'],
            'party_title' => $request['from_department'],
            'date' => Carbon::parse($request['date'])->format('Y-m-d'),
            'document_no' => 'Defective' . '-' . $defectiveItemsParentId,
            'stock_out_bags' =>  !empty($request['bags']) ? $request['bags'] : 0,
            'stock_out_weight' =>  !empty($request['avg_weight']) ? $request['avg_weight'] : 0,
            'stock_out_quantity' => $request['total_quantity'],
            'stock_in_bags' =>  config('constants.ZERO'),
            'stock_in_weight' =>  config('constants.ZERO'),
            'stock_in_quantity' => config('constants.ZERO'),
            'invoice_id' => $defectiveItemsParentId,
            'rate' => config('constants.ZERO'),
            'created_by' => Auth::user()->id,
            'updated_by' => Auth::user()->id
        ];
    }

    /*
     * Save StockLedger data.
     * @param: $data
     * */
    public function saveStockLedger($data)
    {
        // dd($data);
        foreach ($data['product_id'] as $key => $value) {
            if (!empty($data['product_id'][$key])) {
                $rec['product_id'] = $data['product_id'][$key];
                $rec['date'] = $data['date'];
                $party_title = $data['party_title'][$key];
                $rec['party_title'] = Department::where('id', $party_title)->value("name");
                // $rec['party_title'] = $data['party_title'][$key];
                $rec['stock_out_bags'] = $data['stock_out_bags'][$key] ?? config('constants.ZERO');
                $rec['stock_out_weight'] = $data['stock_out_weight'][$key] ?? config('constants.ZERO');
                $rec['stock_out_quantity'] = $data['stock_out_quantity'][$key];
                $rec['stock_in_bags'] = $data['stock_in_bags'];
                $rec['stock_in_weight'] = $data['stock_in_weight'];
                $rec['stock_in_quantity'] = $data['stock_in_quantity'];
                $rec['document_no'] = $data['document_no'];
                $rec['rate'] = $data['rate'];
                $rec['invoice_id'] = $data['invoice_id'];
                $rec['created_by'] = $data['created_by'];
                $rec['updated_by'] = $data['updated_by'];
                StockLedger::create($rec);
            }
        }
    }
}
