<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\CommonService;
use App\Models\CoaDetailAccount;
use App\Services\PermissionService;
use Illuminate\Support\Facades\Auth;
use App\Services\ChartOfAccountService;
use App\Services\CoaDetailAccountService;
use Illuminate\Database\Eloquent\RelationNotFoundException;

class CoaDetailAccountController extends Controller
{
    private $chartOfAccountService;
    private $permissionService;
    private $commonService;
    private $coaDetailAccountService;

    public function __construct(CoaDetailAccountService $coaDetailAccountService, ChartOfAccountService $chartOfAccountService, PermissionService $permissionService, CommonService $commonService)
    {
        $this->chartOfAccountService = $chartOfAccountService;
        $this->permissionService = $permissionService;
        $this->commonService = $commonService;
        $this->coaDetailAccountService = $coaDetailAccountService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $detailAccounts = $this->coaDetailAccountService->getListOfDetailAccounts($request->search);
        $permission = $this->permissionService->getUserPermission(Auth::user()->id, '24');
        $pageTitle = 'List of Detail accounts';
        return view('chart-of-accounts.detail-account.index', compact('detailAccounts', 'permission', 'pageTitle'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $pageTitle = 'Create Detail Account';
        $title = 'Detail Account';
        $mainHeads = $this->chartOfAccountService->getMainHeads();
        $controlHeads = $this->chartOfAccountService->getControlHeads();
        $subHeads = $this->chartOfAccountService->getSubHeads();
        $subSubHeads = $this->chartOfAccountService->getSubSubHeads();
        $permission = $this->permissionService->getUserPermission(Auth::user()->id, '24');

        return view('chart-of-accounts.detail-account.create', compact('permission', 'controlHeads', 'pageTitle', 'title', 'mainHeads', 'subHeads', 'subSubHeads'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $this->coaDetailAccountService->storeAccountData($request);
            $message = !empty(request('id')) ? config('constants.update') : config('constants.add');
            session()->flash('message', $message);
            return redirect('detail-account/list');
        } catch (\Throwable $exception) {
            return back()->withError($exception->getMessage())->withInput();
        } catch (RelationNotFoundException $exception) {
            return back()->withError($exception->getMessage())->withInput();
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $pageTitle = 'Update Detail Account';
        $detailAccount = CoaDetailAccount::find($id);
        if (!$detailAccount) {
            return abort(404);
        }
        $mainHeads = $this->chartOfAccountService->getMainHeads();
        $controlHeads = $this->chartOfAccountService->getControlHeadsForMainHead($detailAccount->main_head);
        $subHeads = $this->chartOfAccountService->getSubHeadsForControlHead($detailAccount->control_head);
        $subSubHeads = $this->chartOfAccountService->getSubSubHeadsBySubHead($detailAccount->sub_head);
        $permission = $this->permissionService->getUserPermission(Auth::user()->id, '13');

        return view('chart-of-accounts.detail-account.create', compact('detailAccount', 'subSubHeads', 'subHeads', 'controlHeads', 'mainHeads', 'permission', 'pageTitle'));
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
        return $this->commonService->deleteResource(CoaDetailAccount::class);
    }

    /**
     * Get maximum account code
     *
     * @param  $mainHead
     * @return \Illuminate\Http\Response
     */
    public function getMaxDetailAccountCode($subSubHead)
    {
        $detailAccountCode = $this->coaDetailAccountService->generateDetailAccountCode($subSubHead);
        return response()->json(['status' => 'success', 'account_code' => $detailAccountCode]);
    }

    public function getSubSubHeadAccountsBySubHead($subHead)
    {
        $subSubAccounts = $this->coaDetailAccountService->getSubSubHeadsBySubHead($subHead);
        if ($subSubAccounts) {
            return response()->json(['status' => 'success', 'data' => $subSubAccounts ? $subSubAccounts : []]);
        }
    }
}
