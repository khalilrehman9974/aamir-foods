<?php

namespace App\Http\Controllers;

use App\Http\Requests\FinancialYearStoreRequest;
use App\Models\FinancialYear;
use App\Services\FinancialYearService;
use App\Services\PermissionService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class FinancialYearsController extends Controller
{
    private $financialYearService;
    private $permissionService;

    public function __construct(FinancialYearService $financialYearService, PermissionService $permissionService)
    {
        $this->financialYearService = $financialYearService;
        $this->permissionService = $permissionService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $fYears = FinancialYear::paginate(FinancialYear::PER_PAGE);
        $permission = $this->permissionService->getUserPermission(Auth::user()->id, '12');

        return view('financial_year.index', compact('fYears', 'permission'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $permission = $this->permissionService->getUserPermission(Auth::user()->id, '12');
        return view('financial_year.create', compact('permission'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  FinancialYearStoreRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(FinancialYearStoreRequest $request)
    {
        $data = $this->financialYearService->dataArray($request);
        $this->financialYearService->findUpdateOrCreate(FinancialYear::class, ['id'=>$data['id']], $data);
        $message = 'Financial Year has been added';
        if($data['id']){
            $message = 'Financial Year has been updated';
        }
        Session::flash('message', $message);
        return redirect('financial_year/list');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $fYear = FinancialYear::find($id);
        $permission = $this->permissionService->getUserPermission(Auth::user()->id, '12');

        return view('financial_year.create', compact('fYear', 'permission'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $deleted = FinancialYear::destroy($id);
        if($deleted){
            return response()->json(['success'=>'200', 'message'=>'Record is deleted']);
        }else{
            return response()->json(['error'=>'', 'message'=>'Record not deleted']);
        }
    }
}
