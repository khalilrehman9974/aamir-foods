<?php

namespace App\Http\Controllers;

use App\Http\Requests\CoaMainHeadRequest;
use App\Models\CoaMainHead;
use App\Services\CommonService;
use App\Services\PermissionService;
use App\Services\CoaMainHeadService;
use Illuminate\Support\Facades\Auth;

class CoaMainHeadController extends Controller
{
    private $coaMainHeadService;
    private $permissionService;
    private $commonService;
    const PER_PAGE = 10;

    public function __construct(CoaMainHeadService $coaMainHeadService, PermissionService $permissionService, CommonService $commonService)
    {
        $this->coaMainHeadService = $coaMainHeadService;
        $this->permissionService = $permissionService;
        $this->commonService = $commonService;
    }
    /*
    * list of main heads
    */
    public function index()
    {
        $pageTitle = 'List of Main Heads';
        $mainHeads = $this->coaMainHeadService->getListOfMainHeads();
        $permission = $this->permissionService->getUserPermission(Auth::user()->id, '24');

        return view('chart-of-accounts.main-head.index', compact('mainHeads', 'permission', 'pageTitle'));
    }

    /*
     * Create record
     */
    public function create()
    {
        $pageTitle = 'Create Main Head';
        $accountCode = $this->coaMainHeadService->getMaxAccountCode();
        $permission = $this->permissionService->getUserPermission(Auth::user()->id, '24');

        return view('chart-of-accounts.main-head.create', compact('permission','pageTitle', 'accountCode'));
    }

    /*
     * Store resource
     * @param $request
     * @param $id
     */
    public function store(CoaMainHeadRequest $request)
    {
        $data = $request->except('_token','id');
        $data['created_by'] = Auth::user()->id;
        $data['updated_by'] = Auth::user()->id;
        $saved = $this->coaMainHeadService->findUpdateOrCreate(CoaMainHead::class, ['id'=>!empty(request('id')) ? request('id') : null], $data);
        if ($saved) {
            $message = request('id') ? config('constants.update') : config('constants.add');
        }
        session()->flash('message', $message);
        return redirect('main-head/list');
    }

    /*
     * Edit resource
     * @param $id
     */
    public function edit($id)
    {
        $pageTitle = 'Edit Main Head';
        $mainHead = CoaMainHead::find($id);
        $permission = $this->permissionService->getUserPermission(Auth::user()->id, '13');

        return view('chart-of-accounts.main-head.create', compact('mainHead', 'permission', 'pageTitle'));
    }

    /*
     * Delete resource
     * @param $id
     */
    public function destroy()
    {
        return $this->commonService->deleteResource(CoaMainHead::class);
    }

}
