<?php

namespace App\Http\Controllers;

use App\Models\CoaInventoryMainHead;
use App\Services\CoaInventoryMainHeadService;
use App\Services\CommonService;
use App\Services\PermissionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChartOfInvMainHeadController extends Controller
{
    protected $coaInvMainHeadService;
    protected $permissionService;
    protected $commonService;

    public function __construct(CoaInventoryMainHeadService $coaInvMainHeadService, PermissionService $permissionService, CommonService $commonService)
    {
        $this->coaInvMainHeadService = $coaInvMainHeadService;
        $this->permissionService = $permissionService;
        $this->commonService = $commonService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pageTitle = 'List of Inventory Main Heads';
        $mainHeads = $this->coaInvMainHeadService->getListOfMainHeads();
        $permission = $this->permissionService->getUserPermission(Auth::user()->id, '24');

        return view('chart-of-inventory.main-head.index', compact('mainHeads', 'permission', 'pageTitle'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $pageTitle = 'Create Inventory Main Head';
        $accountCode = $this->coaInvMainHeadService->getMaxAccountCode();
        $permission = $this->permissionService->getUserPermission(Auth::user()->id, '24');

        return view('chart-of-inventory.main-head.create', compact('permission', 'pageTitle', 'accountCode'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->except('_token', 'id');
        $data['created_by'] = Auth::user()->id;
        $data['updated_by'] = Auth::user()->id;
        $saved = $this->coaInvMainHeadService->findUpdateOrCreate(CoaInventoryMainHead::class, ['id' => !empty(request('id')) ? request('id') : null], $data);
        if ($saved) {
            $message = request('id') ? config('constants.update') : config('constants.add');
        }
        session()->flash('message', $message);
        return redirect('co-inv-main-head/list');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\CoaInventoryMainHead $coaInventoryMainHead
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $pageTitle = 'Update Inventory Sub Head';
        $mainHead = CoaInventoryMainHead::find($id);
        if (!$mainHead) {
            return abort(404);
        }
        $permission = $this->permissionService->getUserPermission(Auth::user()->id, '13');

        return view('chart-of-inventory.main-head.create', compact('mainHead', 'permission', 'pageTitle'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\CoaInventoryMainHead $coaInventoryMainHead
     * @return \Illuminate\Http\Response
     */
    public function destroy(CoaInventoryMainHead $coaInventoryMainHead)
    {
        return $this->commonService->deleteResource(CoaInventoryMainHead::class);
    }
}
