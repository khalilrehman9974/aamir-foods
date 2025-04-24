<?php

namespace App\Http\Controllers;

use App\Models\CoaSubHead;
use Illuminate\Http\Request;
use App\Models\CoaSubSubHead;
use App\Services\CommonService;
use Illuminate\Support\Facades\DB;
use App\Services\PermissionService;
use Illuminate\Support\Facades\Auth;
use App\Services\ChartOfAccountService;
use App\Http\Requests\StoreSubHeadRequest;
use App\Models\InventorySubSubHeadPriceTagModel;

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
    public function index()
    {
        $request = request()->all();
        $dropDownData = $this->chartOfAccountService->DropDownData();
        $subSubHeads = $this->chartOfAccountService->getListOfSubSubHeads($request);
        $permission = $this->permissionService->getUserPermission(Auth::user()->id, '24');
        $pageTitle = 'List of Sub-Sub Heads';
        return view('chart-of-accounts.sub-sub-head.index', compact('subSubHeads', 'dropDownData', 'permission', 'pageTitle'));
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
        $dropDownData = $this->chartOfAccountService->DropDownData();
        $permission = $this->permissionService->getUserPermission(Auth::user()->id, '24');
        $title = 'Sub-Sub Head';

        return view('chart-of-accounts.sub-sub-head.create', compact('permission', 'dropDownData', 'controlHead', 'pageTitle', 'title', 'mainHeads'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreSubHeadRequest $request)
    {

        // $data = $request->except('_token', 'id');
        // $data['created_by'] = Auth::user()->id;
        // $data['updated_by'] = Auth::user()->id;
        // $this->chartOfAccountService->findUpdateOrCreate(CoaSubSubHead::class, ['id' => !empty(request('id')) ? request('id') : null], $data);


        // $detailAccountData = $this->chartOfAccountService->prepareAccountDetailData($request, $accountMasterInsert->id);
        // $this->chartOfAccountService->savePriceTags($detailAccountData);


        // $message = !empty(request('id')) ? config('constants.update') : config('constants.add');
        // session()->flash('message', $message);

        // return redirect('sub-sub-head/list');



        // $request = $request->except('_token', 'id');
        // DB::beginTransaction();
        // try {

            $accountMasterData = $this->chartOfAccountService->prepareAccountMasterData($request);
            $accountMasterInsert = $this->chartOfAccountService->findUpdateOrCreate(CoaSubSubHead::class, ['id' => !empty(request('id')) ? request('id') : null], $accountMasterData);

            if (!empty($request['priceTag'])) {
                $detailAccountData = $this->chartOfAccountService->prepareAccountDetailData($request, $accountMasterInsert->id);
                $this->chartOfAccountService->savePriceTags($detailAccountData);
            }



        //     DB::commit();
        // } catch (\Exception $e) {
        //     DB::rollback();
        //     return redirect('sub-sub-head/create')->with('error', $e->getMessage());
        // }
        $message = config('constants.add');
        return redirect('sub-sub-head/list')->with('message', $message);
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
        $priceTags = InventorySubSubHeadPriceTagModel::where('sub_sub_head_id', $id)->get();
        $dropDownData = $this->chartOfAccountService->DropDownData();
        $mainHeads = $this->chartOfAccountService->getMainHeads();
        $controlHead = $this->chartOfAccountService->getControlHeadsForMainHead($subSubHead->main_head);
        $subHeads = $this->chartOfAccountService->getSubHeadsForControlHead($subSubHead->control_head);
        $permission = $this->permissionService->getUserPermission(Auth::user()->id, '13');

        return view('chart-of-accounts.sub-sub-head.create', compact('subSubHead','dropDownData','priceTags', 'subHeads', 'controlHead', 'mainHeads', 'controlHead', 'permission', 'pageTitle'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {

        // $data = $request->except('_token', 'id');
        // $data['created_by'] = Auth::user()->id;
        // $data['updated_by'] = Auth::user()->id;
        // $this->chartOfAccountService->findUpdateOrCreate(CoaSubSubHead::class, ['id' => !empty(request('id')) ? request('id') : null], $data);


        // $detailAccountData = $this->chartOfAccountService->prepareAccountDetailData($request, $accountMasterInsert->id);
        // $this->chartOfAccountService->savePriceTags($detailAccountData);


        // $message = !empty(request('id')) ? config('constants.update') : config('constants.add');
        // session()->flash('message', $message);

        // return redirect('sub-sub-head/list');
        DB::beginTransaction();
        try {
            InventorySubSubHeadPriceTagModel::where('sub_sub_head_id', $request['id'])->delete();
            $accountMasterData = $this->chartOfAccountService->prepareAccountMasterData($request);
            $accountMasterInsert = $this->chartOfAccountService->findUpdateOrCreate(CoaSubSubHead::class, ['id' => !empty(request('id')) ? request('id') : null], $accountMasterData);

            if (!empty($request['priceTag'])) {
                $detailAccountData = $this->chartOfAccountService->prepareAccountDetailData($request, $accountMasterInsert->id);
                $this->chartOfAccountService->savePriceTags($detailAccountData);
            }



            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            return redirect('sub-sub-head/create')->with('error', $e->getMessage());
        }
        $message = config('constants.add');
        return redirect('sub-sub-head/list')->with('message', $message);
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
        $accountCode = CoaSubHead::where('id', $subHead)->value('account_code');
        $subSubHeadAccount = $this->chartOfAccountService->generateSubSubHeadAccountCode($accountCode);
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
