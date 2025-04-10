<?php

namespace App\Http\Controllers;

use App\Models\GeneralJournal;
use Illuminate\Http\Request;

class GeneralJournalController extends Controller
{

    // protected $generalJournalService;
    // public function __construct(GeneralJournalService $generalJournalService)
    // {
    //     $this->generalJournalService = $generalJournalService;
    // }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pageTitle = 'General Journal';
        return view('reports.general-journal.view', compact('pageTitle'));
    }

    public function print(Request $request)
    {
        $title = 'General Journal';
        $param = request()->param;

        $dateFrom = $request['from_date'];
        $dateTo = $request['to_date'];

        $fromDate = date('Y-m-d', strtotime($request['from_date']));
        $toDate = date('Y-m-d', strtotime($request['to_date']));
        $generalJournals = GeneralJournal::orwhereBetween('date', [$fromDate, $toDate])
            ->orderBy('date', 'asc')
            ->get();
        return view('reports.general-journal.general-journal-report', compact('param','generalJournals','dateFrom','dateTo', 'title'));
    }
}
