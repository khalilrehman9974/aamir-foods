<?php

namespace App\Services;

/*
     * Class StoreIssueNoteService
     * @package App\Services
     * */

use Carbon\Carbon;
use App\Models\Department;
use App\Models\StockLedger;
use App\Models\StoreReturnDetail;
use App\Models\StoreReturnMaster;
use Illuminate\Support\Facades\Auth;
use App\Models\CoaInventoryDetailAccount;

class StoreReturnService
{
    protected $commonService;

    public function __construct(CommonService $commonService)
    {
        $this->commonService = $commonService;
    }
    /*
    * Store company data.
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
            'products' => CoaInventoryDetailAccount::pluck('name', 'id'),
            'departments' => Department::pluck('name', 'id')
        ];

        return $result;
    }

    /*
     * Get contract by id.
     * @param $id
     * */
    public function getIssueNoteMasterById($id)
    {
        return StoreReturnMaster::leftjoin('products', 'product.id', '=', 'store_return_masters.product_id')
            ->select(
                'store_return_masters.id as id',
                'store_return_masters.return_to',
                'store_return_masters.return_by',
                'store_return_masters.remarks',
                'products.name as productName',
            )
            ->where('store_return_masters.id', $id)
            ->first();
    }

    /*
     * Search storeReturns record.
     * @queries: $queries
     * @return: object
     * */
    public function search($request)
    {
        $q = StoreReturnMaster::query();

        if (!empty($request['date'])) {
            $formattedDate = date('Y-m-d', strtotime($request['date']));
            $q->where('date', $formattedDate);
        } elseif (!empty($request['to_department'])) {
            $q->where('to_department', $request['to_department']);
        }
        $storeReturns = $q->with('fromDepartment', 'toDepartment')->orderBy('id', 'DESC')->paginate(config('constants.PER_PAGE'));




        return $storeReturns;
    }


    /*
     * Prepare IssueNote master data.
     * @param: $request
     * @return Array
     * */
    public function prepareStoreReturnMasterData($request)
    {
        $session = $this->commonService->getSession();
        return [
            'date' => Carbon::parse($request['date'])->format('Y-m-d'),
            'receiver_name' => $request['receiver_name'],
            'from_department' => $request['from_department'],
            'business_id' => $session->business_id,
            'f_year_id' => $session->financial_year,
            'to_department' => $request['to_department'],
            'created_by' => Auth::user()->id,
            'updated_by' => Auth::user()->id
        ];
    }

    public function preparestoreReturnDetailData($request, $returnNoteParentId)
    {
        return [
            'product_id' => $request['product_id'],
            'packing_type' => $request['packing_type'],
            'measurement_type' => $request['measurement_type'],
            'size' => $request['size'],
            'bags' => $request['bags'],
            'avg_weight' => $request['avg_weight'],
            'total_qty' => $request['total_qty'],
            'remarks' => $request['remarks'],
            'created_by' => Auth::user()->id,
            'updated_by' => Auth::user()->id,
            'store_return_master_id' => $returnNoteParentId,
        ];
    }


    /*
     * Prepare dispatch detail data.
     * @param: $request
     * @return Array
     * */
    public function saveStoreReturn($data)
    {
        foreach ($data['product_id'] as $key => $value) {
            if (!empty($data['product_id'][$key])) {
                $rec['product_id'] = $data['product_id'][$key];
                $rec['packing_type'] = $data['packing_type'][$key];
                $rec['measurement_type'] = $data['measurement_type'][$key];
                $rec['size'] = $data['size'][$key];
                $rec['bags'] = $data['bags'][$key];
                $rec['avg_weight'] = $data['avg_weight'][$key];
                $rec['total_qty'] = $data['total_qty'][$key];
                $rec['remarks'] = $data['remarks'][$key];
                $rec['created_by'] = Auth::user()->id;
                $rec['updated_by'] = Auth::user()->id;
                $rec['store_return_master_id'] = $data['store_return_master_id'];
                StoreReturnDetail::create($rec);
            }
        }
    }

    public function prepareStockLedgerData($request, $storeReturnInvoiceId)
    {
        $department = Department::where('id', $request['from_department'])->first("name");

        return [
            'product_id' => $request['product_id'],
            'party_title' =>  $department->name,
            'date' => Carbon::parse($request['date'])->format('Y-m-d'),
            'document_no' => 'S/R/N' . '-' . $storeReturnInvoiceId,
            'stock_in_bags' => !empty($request['bags']) ? $request['bags'] : 0,
            'stock_in_weight' => !empty($request['avg_weight']) ? $request['avg_weight'] : 0,
            'stock_in_quantity' =>  $request['total_qty'],
            'stock_out_bags' =>  config('constants.ZERO'),
            'stock_out_weight' =>  config('constants.ZERO'),
            'stock_out_quantity' => config('constants.ZERO'),
            'invoice_id' => $storeReturnInvoiceId,
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
                $rec['party_title'] = $data['party_title'];
                $rec['stock_in_bags'] = $data['stock_in_bags'][$key] ?? config('constants.ZERO');
                $rec['stock_in_weight'] = $data['stock_in_weight'][$key] ?? config('constants.ZERO');
                $rec['stock_in_quantity'] = $data['stock_in_quantity'][$key];
                $rec['stock_out_bags'] = $data['stock_out_bags'];
                $rec['stock_out_weight'] = $data['stock_out_weight'];
                $rec['stock_out_quantity'] = $data['stock_out_quantity'];
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
