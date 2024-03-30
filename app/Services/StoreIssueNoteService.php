<?php

namespace App\Services;


use App\Models\Product;


/*
     * Class StoreIssueNoteService
     * @package App\Services
     * */


use App\Models\StoreIssueNote;
use App\Models\StoreIssueNoteDetail;
use Illuminate\Support\Facades\Auth;

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
            'products' => Product::pluck('name', 'id'),
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

    /*
     * Search IssueNote record.
     * @queries: $queries
     * @return: object
     * */
    public function searchIssueNote($request)
    {
        $query = StoreIssueNote::groupBy(
            'issue_note.id as id',
            'issue_note.issued_to',
            'issue_note.issued_by',
            'issue_note.remarks',
            'products.name as productName',
        );
        if (!empty($request['param'])) {
            $query = $query->where('issue_note.id', "=", $request['param']);
        }
        $purchases = $query->orderBy('id', 'DESC')->get();

        return $this->commonService->paginate($purchases, Self::PER_PAGE);
    }


    /*
     * Prepare IssueNote master data.
     * @param: $request
     * @return Array
     * */
    public function prepareIssueNoteMasterData($request)
    {
        return [
            'issued_to' => $request['issued_to'],
            'issued_by' => $request['issued_by'],
            'product_id' => $request['product_id'],
            'remarks' => $request['remarks'],
            $data['created_by'] = Auth::user()->id,
            $data['updated_by'] = Auth::user()->id
        ];
    }

    /*
     * Prepare dispatch detail data.
     * @param: $request
     * @return Array
     * */
    public function prepareIssueNoteDetailData($request, $store_issue_notesParentId)
    {
        return [
            'date' => $request['date'],
            'description' => $request['description'],
            'quantity' => $request['quantity'],
            'store_issue_notes_id' => $store_issue_notesParentId,
        ];
    }

    /*
     * Save dispatch data.
     * @param: $data
     * */
    public function saveIssueNote($data)
    {
        foreach ($data['date'] as $key => $value) {
            if (!empty($data['date'][$key])) {
                $rec['date'] = $data['date'][$key];
                $rec['description'] = $data['description'][$key];
                $rec['quantity'] = $data['quantity'][$key];
                $rec['created_by'] = Auth::user()->id;
                $rec['updated_by'] = Auth::user()->id;
                $rec['store_issue_notes_id'] = $data['store_issue_notes_id'];
                StoreIssueNoteDetail::create($rec);
            }
        }
    }
}
