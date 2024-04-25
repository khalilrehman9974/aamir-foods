<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\CommonService;
use App\Services\PermissionService;
use Illuminate\Support\Facades\Auth;
use App\Models\CoaInventorySubSubHead;
use App\Services\ChartInventorySubSubHeadService;

class ChartOfInvSubSubHeadController extends Controller
{
    protected $coInvSubSubHeadService;
    protected $permissionService;
    protected $commonService;

    public function __construct(ChartInventorySubSubHeadService $coInvSubSubHeadService, PermissionService $permissionService, CommonService $commonService)
    {
        $this->coInvSubSubHeadService = $coInvSubSubHeadService;
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
        $pageTitle = 'List of Inventory Sub Sub Heads';
        $subSubHeads = $this->coInvSubSubHeadService->getListOfSubSubHeads($request->search);
        $permission = $this->permissionService->getUserPermission(Auth::user()->id, '24');

        return view('chart-of-inventory.sub-sub-head.index', compact('subSubHeads', 'permission', 'pageTitle'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $pageTitle = 'Create Inventory Sub Sub Head';
        $accountCode = $this->coInvSubSubHeadService->getMaxSubSubHeadCode();
        $mainHeads = $this->coInvSubSubHeadService->getMainHeads();
        $subHeads = $this->coInvSubSubHeadService->getSubHeads();
        $permission = $this->permissionService->getUserPermission(Auth::user()->id, '24');

        return view('chart-of-inventory.sub-sub-head.create', compact('permission','mainHeads','subHeads','pageTitle', 'accountCode'));
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
        $saved = $this->coInvSubSubHeadService->findUpdateOrCreate(CoaInventorySubSubHead::class, ['id'=>!empty(request('id')) ? request('id') : null], $data);
        if ($saved) {
            $message = request('id') ? config('constants.update') : config('constants.add');
        }
        session()->flash('message', $message);
        return redirect('co-inv-sub-sub-head/list');
    }

    public function edit($id)
    {
        $pageTitle = 'Update Inventory Sub Sub Head';
        $subSubHead = CoaInventorySubSubHead::find($id);
        $mainHeads = $this->coInvSubSubHeadService->getMainHeads();
        $subHeads = $this->coInvSubSubHeadService->getSubHeads();
        if (!$subSubHead) {
            return abort(404);
        }
        $permission = $this->permissionService->getUserPermission(Auth::user()->id, '13');

        return view('chart-of-inventory.sub-sub-head.create', compact('subHeads','subSubHead', 'mainHeads','permission', 'pageTitle'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CoaInventoryMainHead  $coaInventoryMainHead
     * @return \Illuminate\Http\Response
     */
    public function destroy(CoaInventorySubSubHead $coaInventoryMainHead)
    {
        return $this->commonService->deleteResource(CoaInventorySubSubHead::class);
    }

    public function getSubHeadAccountsByMainHead($mainHead)
    {
        $subSubHeads = $this->coInvSubSubHeadService->getSubHeadsByMainHead($mainHead);
        if ($subSubHeads) {
            return response()->json(['status' => 'success', 'data' => $subSubHeads]);
        }
        return response()->json(['status' => 'fail', 'data' => []]);
    }

    public function getMaxSubSubHeadCode($subHead)
    {
        $subHeadAccount = $this->coInvSubSubHeadService->getMaxSubSubHeadCode($subHead);
        if ($subHeadAccount ) {
            return response()->json(['status' => 'success', 'account_code' => $subHeadAccount]);
        }
        return response()->json(['status' => 'fail', 'data' => []]);
    }



}
