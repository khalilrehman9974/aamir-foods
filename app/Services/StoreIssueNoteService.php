<?php

namespace App\Services;


/*
     * Class StoreIssueNoteService
     * @package App\Services
     * */


use Carbon\Carbon;
use App\Models\StoreIssueNote;
use App\Models\StoreIssueNoteDetail;
use Illuminate\Support\Facades\Auth;
use App\Models\CoaInventoryDetailAccount;
use App\Models\Department;

class StoreIssueNoteService
{
    const PER_PAGE = 10;
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
            'departments' => Department::pluck('name', 'id'),
        ];

        return $result;
    }

    /*
     * Get contract by id.
     * @param $id
     * */
    public function getIssueNoteMasterById($id)
    {
        return StoreIssueNote::leftjoin('products', 'product.id', '=', 'issue_note.product_id')
            ->select(
                'issue_note.id as id',
                'issue_note.issued_to',
                'issue_note.issued_by',
                'issue_note.remarks',
                'products.name as productName',
            )
            ->where('issue_note.id', $id)
            ->first();
    }

    public function search($request)
    {

        $q = StoreIssueNote::query();

        if (!empty($request['date'])) {
            $formattedDate = date('Y-m-d', strtotime($request['date']));
            $q->where('date', $formattedDate);
        } elseif (!empty($request['to_department'])) {
            $q->where('to_department', $request['to_department']);
        }

        $storeIssueNotes = $q->with('fromDepartment','toDepartment')->orderBy('id', 'DESC')->paginate(config('constants.PER_PAGE'));
        return $storeIssueNotes;



        // leftjoin('products', 'product.id', '=', 'issue_note.product_id')->
        // $q = StoreIssueNote::query();
        // if (!empty($request['param'])) {
        //     $q = StoreIssueNote::with('product')
        //     ->where('issued_to', 'like', '%' . $request['param'] . '%')
        //     ->orWhere('issued_by', 'like', '%' . $request['param'] . '%');
        // }
        // $storeIssueNotes = $q->orderBy('id', 'DESC')->paginate(config('constants.PER_PAGE'));

        // return $storeIssueNotes;
    }

    /*
     * Prepare IssueNote master data.
     * @param: $request
     * @return Array
     * */
    public function prepareIssueNoteMasterData($request)
    {
        return [
            'date' => Carbon::parse($request['date'])->format('Y-m-d'),
            'receiver_name' => $request['receiver_name'],
            'from_department' => $request['from_department'],
            'to_department' => $request['to_department'],
            'created_by' => Auth::user()->id,
            'updated_by' => Auth::user()->id
        ];
    }


    public function prepareIssueNoteDetailData($request, $issueNoteParentId)
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
            'store_issue_notes_id' => $issueNoteParentId,
        ];
    }

    public function saveIssueNote($data)
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
                $rec['store_issue_notes_id'] = $data['store_issue_notes_id'];
                StoreIssueNoteDetail::create($rec);
            }
        }
    }
}
