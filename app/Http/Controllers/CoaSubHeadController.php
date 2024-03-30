<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSubHeadRequest;
use App\Models\CoaSubHead;
use App\Services\ChartOfAccountService;
use App\Services\CommonService;
use App\Services\PermissionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CoaSubHeadController extends Controller
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
        $subHeads = $this->chartOfAccountService->getListOfSubHeads($request->search);
        $permission = $this->permissionService->getUserPermission(Auth::user()->id, '24');
        $pageTitle = 'List of Sub Heads';
        return view('chart-of-accounts.sub-head.index', compact('subHeads', 'permission', 'pageTitle'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $pageTitle = 'Create Sub Head Account';
        $mainHeads = $this->chartOfAccountService->getMainHeads();
        $controlHead = $this->chartOfAccountService->getControlHeads();
        $permission = $this->permissionService->getUserPermission(Auth::user()->id, '24');
        $title = 'Sub Head';

        return view('chart-of-accounts.sub-head.create', compact('permission', 'controlHead', 'pageTitle', 'title', 'mainHeads'));
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
        $this->chartOfAccountService->findUpdateOrCreate(CoaSubHead::class, ['id' => !empty(request('id')) ? request('id') : null], $data);
        $message = !empty(request('id')) ? config('constants.update') : config('constants.add');
        session()->flash('message', $message);

        return redirect('sub-head/list');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $pageTitle = 'Update Sub Heads';
        $subHead = CoaSubHead::find($id);
        if (!$subHead) {
           return abort(404);
        }
        $mainHeads = $this->chartOfAccountService->getMainHeads();
        $controlHead = $this->chartOfAccountService->getControlHeadsForMainHead($subHead->main_head);
        $permission = $this->permissionService->getUserPermission(Auth::user()->id, '13');

        return view('chart-of-accounts.sub-head.create', compact('subHead', 'mainHeads', 'controlHead', 'permission', 'pageTitle'));
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
        return $this->commonService->deleteResource(CoaSubHead::class);
    }

    /**
     * Get maximum account code
     *
     * @param  $mainHead
     * @return \Illuminate\Http\Response
     */
    public function getMaxSubHeadCode($controlHead)
    {
        $subHeadAccount = $this->chartOfAccountService->generateSubHeadAccountCode($controlHead);
        if ($subHeadAccount ) {
            return response()->json(['status' => 'success', 'account_code' => $subHeadAccount]);
        }
        return response()->json(['status' => 'fail', 'data' => []]);
    }

    public function getControlAccountForMainHead($mainHead)
    {
        $controlAccounts = $this->chartOfAccountService->getControlHeadsForMainHead($mainHead);
        if ($controlAccounts) {
            return response()->json(['status' => 'success', 'data' => $controlAccounts]);
        }
        return response()->json(['status' => 'fail', 'data' => []]);
    }
}
