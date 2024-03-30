<?php

namespace App\Http\Controllers;

use App\Models\coa_control_head;
use App\Models\CoaControlHead;
use App\Services\ChartOfAccountService;
use App\Services\CommonService;
use App\Services\PermissionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CoaControlHeadController extends Controller
{
    private $chartOfAccountService;
    private $permissionService;
    private $commonService;

    public function __construct(ChartOfAccountService $chartOfAccountService, PermissionService $permissionService, CommonService $commonService)
    {
        $this->chartOfAccountService = $chartOfAccountService;
        $this->permissionService = $permissionService;
        $this->commonService = $commonService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $controlHeads = $this->chartOfAccountService->getListOfControlHeads($request->search);
        $permission = $this->permissionService->getUserPermission(Auth::user()->id, '24');
        $pageTitle = 'List of Control Account Heads';
        return view('chart-of-accounts.control-head.index', compact('controlHeads', 'permission', 'pageTitle'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $pageTitle = 'Create Control Head';
        $mainHeads = $this->chartOfAccountService->getMainHeads();
        $permission = $this->permissionService->getUserPermission(Auth::user()->id, '24');
        $title = 'Control Head';

        return view('chart-of-accounts.control-head.create', compact('permission','pageTitle', 'title', 'mainHeads'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->except('_token','id');
        $data['created_by'] = Auth::user()->id;
        $data['updated_by'] = Auth::user()->id;
        $this->chartOfAccountService->findUpdateOrCreate(CoaControlHead::class, ['id'=>!empty(request('id')) ? request('id') : null], $data);
        $message =  !empty(request('id')) ? config('constants.update') : config('constants.add');
        session()->flash('message', $message);

        return redirect('control-head/list');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\coa_control_head  $coa_control_head
     * @return \Illuminate\Http\Response
     */
    public function show(coa_control_head $coa_control_head)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\coa_control_head  $coa_control_head
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $pageTitle = 'Update Control Head';
        $mainHeads = $this->chartOfAccountService->getMainHeads();
        $controlHead = CoaControlHead::find($id);
        if (!$controlHead) {
            return abort(404);
        }
        $permission = $this->permissionService->getUserPermission(Auth::user()->id, '13');

        return view('chart-of-accounts.control-head.create', compact('controlHead','mainHeads', 'permission', 'pageTitle'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\coa_control_head  $coa_control_head
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, coa_control_head $coa_control_head)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\coa_control_head  $coa_control_head
     * @return \Illuminate\Http\Response
     */
    public function destroy()
    {
        return $this->commonService->deleteResource(CoaControlHead::class);
    }

    /**
     * Get maximum account code
     *
     * @param  $mainHead
     * @return \Illuminate\Http\Response
     */
    public function getMaxControlHeadCode($mainHead)
    {
        $controlHeadAccount = $this->chartOfAccountService->generateControlAccountCode($mainHead);
        return response()->json(['status' => 'success',  'account_code' => $controlHeadAccount]);
    }
}
