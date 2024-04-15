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
        // leftjoin('products', 'product.id', '=', 'issue_note.product_id')->
        $q = StoreIssueNote::query();
        if (!empty($request['param'])) {
            $q = StoreIssueNote::with('product')
            ->where('issued_to', 'like', '%' . $request['param'] . '%')
            ->orWhere('issued_by', 'like', '%' . $request['param'] . '%');
        }
        $storeIssueNotes = $q->orderBy('issued_to', 'ASC')->paginate(config('constants.PER_PAGE'));

        return $storeIssueNotes;
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
            'created_by' => Auth::user()->id,
            'updated_by' => Auth::user()->id
        ];
    }


    public function saveIssueNote($request, $store_issue_notesParentId)
    {
        $data = $request;
        foreach ($data["date"] as $key => $value) {
                $rec['date'] = Carbon::parse($data['date'][$key])->format("Y-m-d") ;
                $rec['description'] = $data['description'][$key];
                $rec['quantity'] = $data['quantity'][$key];
                $rec['created_by'] = Auth::Id();
                $rec['updated_by'] = Auth::Id();
                $rec['store_issue_notes_id'] = $store_issue_notesParentId;
                StoreIssueNoteDetail::create($rec);
        }
    }
}
