<?php

namespace App\Http\Controllers;

use App\Models\CoaInventorySubHead;
use App\Services\CoaInventorySubHeadService;
use App\Services\CommonService;
use App\Services\PermissionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChartOfInvSubHeadController extends Controller
{
    protected $coInvSubHeadService;
    protected $permissionService;
    protected $commonService;

    public function __construct(CoaInventorySubHeadService $coInvSubHeadService, PermissionService $permissionService, CommonService $commonService)
    {
        $this->coInvSubHeadService = $coInvSubHeadService;
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
        $pageTitle = 'List of Inventory Sub Heads';
        $subHeads = $this->coInvSubHeadService->getListOfSubHeads($request->search);
        $permission = $this->permissionService->getUserPermission(Auth::user()->id, '24');

        return view('chart-of-inventory.sub-head.index', compact('subHeads', 'permission', 'pageTitle'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $pageTitle = 'Create Inventory Sub Head';
        $accountCode = $this->coInvSubHeadService->getMaxAccountCode();
        $mainHeads = $this->coInvSubHeadService->getMainHeads();
        $permission = $this->permissionService->getUserPermission(Auth::user()->id, '24');

        return view('chart-of-inventory.sub-head.create', compact('permission','mainHeads','pageTitle', 'accountCode'));
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
        $saved = $this->coInvSubHeadService->findUpdateOrCreate(CoaInventorySubHead::class, ['id'=>!empty(request('id')) ? request('id') : null], $data);
        if ($saved) {
            $message = request('id') ? config('constants.update') : config('constants.add');
        }
        session()->flash('message', $message);
        return redirect('co-inv-sub-head/list');
    }

    public function edit($id)
    {
        $pageTitle = 'Update Inventory Sub Head';
        $subHead = CoaInventorySubHead::find($id);
        $mainHeads = $this->coInvSubHeadService->getMainHeads();
        if (!$subHead) {
            return abort(404);
        }
        $permission = $this->permissionService->getUserPermission(Auth::user()->id, '13');

        return view('chart-of-inventory.sub-head.create', compact('subHead', 'mainHeads','permission', 'pageTitle'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CoaInventoryMainHead  $coaInventoryMainHead
     * @return \Illuminate\Http\Response
     */
    public function destroy(CoaInventorySubHead $coaInventoryMainHead)
    {
        return $this->commonService->deleteResource(CoaInventorySubHead::class);
    }

    public function getMaxSubSubHeadCode($mainHead)
    {
        $subHeadAccount = $this->coInvSubHeadService->getMaxAccountCode($mainHead);
        if ($subHeadAccount ) {
            return response()->json(['status' => 'success', 'account_code' => $subHeadAccount]);
        }
        return response()->json(['status' => 'fail', 'data' => []]);
    }
}
