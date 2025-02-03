<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\CommonService;
use Illuminate\Support\Facades\DB;
use App\Services\PermissionService;
use Illuminate\Support\Facades\Auth;
use App\Models\CoaInventorySubSubHead;
use App\Models\InventorySubSubHeadPriceTagModel;
use App\Models\PriceTag;
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
        $dropDownData = $this->coInvSubSubHeadService->DropDownData();
        $permission = $this->permissionService->getUserPermission(Auth::user()->id, '24');

        return view('chart-of-inventory.sub-sub-head.create', compact('permission','dropDownData', 'mainHeads','subHeads','pageTitle', 'accountCode'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request = $request->except('_token', 'id');
        DB::beginTransaction();
        try {

            $accountMasterData = $this->coInvSubSubHeadService->prepareAccountMasterData($request);
            $accountMasterInsert = $this->coInvSubSubHeadService->findUpdateOrCreate(CoaInventorySubSubHead::class, ['id' => !empty(request('id')) ? request('id') : null], $accountMasterData);

            $detailAccountData = $this->coInvSubSubHeadService->prepareAccountDetailData($request, $accountMasterInsert->id);
            $this->coInvSubSubHeadService->savePriceTags($detailAccountData);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            return redirect('co-inv-sub-sub-head/create')->with('error', $e->getMessage());
        }
        $message = config('constants.add');
        return redirect('co-inv-sub-sub-head/list')->with('message', $message);
    }

    public function edit($id)
    {
        $pageTitle = 'Update Inventory Sub Sub Head';
        $subSubHead = CoaInventorySubSubHead::find($id);
        $priceTags = InventorySubSubHeadPriceTagModel::where('sub_sub_head_id', $id)->get();
        $mainHeads = $this->coInvSubSubHeadService->getMainHeads();
        $subHeads = $this->coInvSubSubHeadService->getSubHeads();
        $dropDownData = $this->coInvSubSubHeadService->DropDownData();
        $options = PriceTag::all();
        if (!$subSubHead) {
            return abort(404);
        }
        $permission = $this->permissionService->getUserPermission(Auth::user()->id, '13');

        return view('chart-of-inventory.sub-sub-head.create', compact('subHeads','options','priceTags','dropDownData','subSubHead', 'mainHeads','permission', 'pageTitle'));
    }


    public function update(Request $request)
    {

        DB::beginTransaction();
        try {
            InventorySubSubHeadPriceTagModel::where('sub_sub_head_id', $request['id'])->delete();
            $accountMasterData = $this->coInvSubSubHeadService->prepareAccountMasterData($request);
            $accountMasterInsert = $this->coInvSubSubHeadService->findUpdateOrCreate(CoaInventorySubSubHead::class, ['id' => !empty(request('id')) ? request('id') : null], $accountMasterData);

            $detailAccountData = $this->coInvSubSubHeadService->prepareAccountDetailData($request, $accountMasterInsert->id);
            $this->coInvSubSubHeadService->savePriceTags($detailAccountData);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            // return redirect('co-inv-sub-sub-head/edit/{$request}')->with('error', $e->getMessage());
            return redirect()->route('co-inv-sub-sub-head.edit', ['request' => $request->id])->with('error', $e->getMessage());
        }
        $message = config('constants.update');
        return redirect('co-inv-sub-sub-head/list')->with('message', $message);
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
