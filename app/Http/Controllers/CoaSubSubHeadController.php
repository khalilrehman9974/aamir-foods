<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSubHeadRequest;
use App\Models\CoaSubHead;
use App\Models\CoaSubSubHead;
use App\Services\ChartOfAccountService;
use App\Services\CommonService;
use App\Services\PermissionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CoaSubSubHeadController extends Controller
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
        $subSubHeads = $this->chartOfAccountService->getListOfSubSubHeads($request->search);
        $permission = $this->permissionService->getUserPermission(Auth::user()->id, '24');
        $pageTitle = 'List of Sub-Sub Heads';
        return view('chart-of-accounts.sub-sub-head.index', compact('subSubHeads', 'permission', 'pageTitle'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $pageTitle = 'Create Sub-Sub Head Account';
        $mainHeads = $this->chartOfAccountService->getMainHeads();
        $controlHead = $this->chartOfAccountService->getControlHeads();
        $permission = $this->permissionService->getUserPermission(Auth::user()->id, '24');
        $title = 'Sub-Sub Head';

        return view('chart-of-accounts.sub-sub-head.create', compact('permission', 'controlHead', 'pageTitle', 'title', 'mainHeads'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreSubHeadRequest $request)
    {
        $data = $request->except('_token', 'id');
        $data['created_by'] = Auth::user()->id;
        $data['updated_by'] = Auth::user()->id;
        $this->chartOfAccountService->findUpdateOrCreate(CoaSubSubHead::class, ['id' => !empty(request('id')) ? request('id') : null], $data);
        $message = !empty(request('id')) ? config('constants.update') : config('constants.add');
        session()->flash('message', $message);

        return redirect('sub-sub-head/list');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $pageTitle = 'Update Sub-Sub Head';
        $subSubHead = CoaSubSubHead::find($id);
        if (!$subSubHead) {
            return abort(404);
        }
        $mainHeads = $this->chartOfAccountService->getMainHeads();
        $controlHead = $this->chartOfAccountService->getControlHeadsForMainHead($subSubHead->main_head);
        $subHeads = $this->chartOfAccountService->getSubHeadsForControlHead($subSubHead->control_head);
        $permission = $this->permissionService->getUserPermission(Auth::user()->id, '13');

        return view('chart-of-accounts.sub-sub-head.create', compact('subSubHead','subHeads', 'controlHead', 'mainHeads', 'controlHead', 'permission', 'pageTitle'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\coa_sub_head $coa_control_head
     * @return \Illuminate\Http\Response
     */
    public function destroy()
    {
        return $this->commonService->deleteResource(CoaSubSubHead::class);
    }

    /**
     * Get maximum account code
     *
     * @param  $mainHead
     * @return \Illuminate\Http\Response
     */
    public function getMaxSubSubHeadCode($subHead)
    {
        $subSubHeadAccount = $this->chartOfAccountService->generateSubSubHeadAccountCode($subHead);
        return response()->json(['status' => 'success', 'account_code' => $subSubHeadAccount]);
    }

    public function getSubAccountForControlHead($subHead)
    {
        $subAccounts = $this->chartOfAccountService->getSubHeadsForControlHead($subHead);
        if ($subAccounts) {
            return response()->json(['status' => 'success', 'data' => $subAccounts]);
        }
        return response()->json(['status' => 'fail', 'data' => []]);
    }
}
