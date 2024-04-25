<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Services\CommonService;
use App\Models\CoaInventorySubHead;
use App\Services\PermissionService;
use App\Services\UploadFileService;
use Illuminate\Support\Facades\Auth;
use App\Models\CoaInventoryDetailAccount;
use App\Services\CoaInventorySubHeadService;
use App\Http\Requests\CoInvDetailAccountRequest;
use App\Services\CoaInventoryDetailAccountService;

class ChartOfInvDetailAccountController extends Controller
{
    protected $coInvSubHeadService;
    protected $coInventoryDetailAccountService;
    protected $permissionService;
    protected $commonService;
    protected $uploadService;

    public function __construct(
        CoaInventorySubHeadService       $coInvSubHeadService,
        CoaInventoryDetailAccountService $coInventoryDetailAccountService,
        PermissionService                $permissionService,
        CommonService                    $commonService,
        UploadFileService                $uploadService
    ) {
        $this->coInvSubHeadService = $coInvSubHeadService;
        $this->coInventoryDetailAccountService = $coInventoryDetailAccountService;
        $this->permissionService = $permissionService;
        $this->commonService = $commonService;
        $this->uploadService = $uploadService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $pageTitle = 'List of Inventory Detail Account';
        $detailAccounts = $this->coInventoryDetailAccountService->getListOfDetailAccounts($request->search);
        $permission = $this->permissionService->getUserPermission(Auth::user()->id, '24');

        return view('chart-of-inventory.detail-account.index', compact('detailAccounts', 'permission', 'pageTitle'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $pageTitle = 'Create Inventory Detail Accounts';
        $dropDownData = $this->coInventoryDetailAccountService->DropDownData();
        $accountCode = $this->coInventoryDetailAccountService->getMaxAccountCode();
        $mainHeads = $this->commonService->getInventoryMainHeads();
        $subHeads = $this->commonService->getInventorySubHeads();
        $permission = $this->permissionService->getUserPermission(Auth::user()->id, '24');

        return view('chart-of-inventory.detail-account.create', compact('permission', 'dropDownData', 'mainHeads', 'subHeads', 'pageTitle', 'accountCode'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(CoInvDetailAccountRequest $request)
    {
        $data = $request->except('_token', 'id');
        $data['created_by'] = Auth::user()->id;
        $data['updated_by'] = Auth::user()->id;
        if ($request->image) {
            $fileName = Str::random(20) . '_' . '(' . $request->image->getClientOriginalName() . ')';
            $data['image'] = $fileName;
        }
        $saved = $this->coInventoryDetailAccountService->findUpdateOrCreate(CoaInventoryDetailAccount::class, ['id' => !empty(request('id')) ? request('id') : null], $data);
        if ($saved && $request->file('image')) {
            $this->uploadService->uploadSingleFile($request->image, $fileName, config('constants.file_upload.inventory'));
        }
        $message = request('id') ? config('constants.update') : config('constants.add');
        session()->flash('message', $message);
        return redirect('co-inv-detail-account/list');
    }

    public function edit($id)
    {
        $pageTitle = 'Update Inventory Sub Head';
        $dropDownData = $this->coInventoryDetailAccountService->DropDownData();
        $detailAccount = CoaInventoryDetailAccount::find($id);
        $mainHeads = $this->coInvSubHeadService->getMainHeads();
        $subHeads = $this->commonService->getInventorySubHeads($detailAccount->main_head);
        $subSubHeads = $this->commonService->getInventorySubSubHeads($detailAccount->sub_head);
        if (!$detailAccount) {
            return abort(404);
        }
        $permission = $this->permissionService->getUserPermission(Auth::user()->id, '13');

        return view('chart-of-inventory.detail-account.create', compact('detailAccount','subSubHeads', 'dropDownData', 'subHeads', 'mainHeads', 'permission', 'pageTitle'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\CoaInventoryMainHead $coaInventoryMainHead
     * @return \Illuminate\Http\Response
     */
    public function destroy(CoaInventorySubHead $coaInventoryMainHead)
    {
        return $this->commonService->deleteResource(CoaInventoryDetailAccount::class);
    }

    public function getMaxDetailAccountCode($subHead)
    {
        $detailAccount = $this->coInventoryDetailAccountService->getMaxAccountCode($subHead);
        if ($detailAccount) {
            return response()->json(['status' => 'success', 'code' => $detailAccount]);
        }
        return response()->json(['status' => 'fail', 'data' => []]);
    }

    public function getSubHeadAccountsByMainHead($mainHead)
    {
        $detailAccounts = $this->coInventoryDetailAccountService->getSubHeadsByMainHead($mainHead);
        if ($detailAccounts) {
            return response()->json(['status' => 'success', 'data' => $detailAccounts]);
        }
        return response()->json(['status' => 'fail', 'data' => []]);
    }

    public function getSubSubHeadAccountsBySubHead($subHead)
    {
        $detailAccounts = $this->coInventoryDetailAccountService->getSubSubHeadsBySubHead($subHead);
        if ($detailAccounts) {
            return response()->json(['status' => 'success', 'data' => $detailAccounts]);
        }
        return response()->json(['status' => 'fail', 'data' => []]);
    }
}
