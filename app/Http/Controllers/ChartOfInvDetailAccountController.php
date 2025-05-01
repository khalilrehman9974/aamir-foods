<?php

namespace App\Http\Controllers;

use App\Models\PriceTag;
use Illuminate\Http\Request;
use App\Services\CommonService;
use App\Models\CoaDetailAccount;
use App\Models\CoaInventorySubHead;
use App\Services\PermissionService;
use App\Services\UploadFileService;
use Illuminate\Support\Facades\Auth;
use App\Models\CoaInventoryDetailAccount;
use App\Services\CoaInventorySubHeadService;
use App\Http\Requests\CoInvDetailAccountRequest;
use App\Models\CoaDetAccountDetail;
use App\Models\CoaMainHead;
use App\Models\DetailAccountProducts;
use App\Models\InventorySubSubHeadPriceTagModel;
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
        $accountArray = [4, 6];
        $coaMainHeadAccounts = CoaMainHead::whereIn('id', $accountArray)->pluck('account_name', 'id');
        $permission = $this->permissionService->getUserPermission(Auth::user()->id, '24');

        return view('chart-of-inventory.detail-account.create', compact('permission', 'coaMainHeadAccounts', 'dropDownData', 'mainHeads', 'subHeads', 'pageTitle', 'accountCode'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {


        $inventoryAccount = CoaInventoryDetailAccount::where('id', $request['id'])->value('name');
        $party = CoaDetailAccount::where('account_name', $inventoryAccount)->first();
        $partyId = $party ? $party->id : null;
        $session = $this->commonService->getSession();
        $data = $request->except('_token', 'id');
        $data['created_by'] = Auth::user()->id;
        $data['updated_by'] = Auth::user()->id;
        $data['business_id'] = $session->business_id;
        $data['f_year_id'] = $session->financial_year;
        if ($request->image) {
            $fileName = $request->image->getClientOriginalName();
            $data['image'] = $fileName;
        }
        $saved = $this->coInventoryDetailAccountService->findUpdateOrCreate(CoaInventoryDetailAccount::class, ['id' => !empty(request('id')) ? request('id') : null], $data);

        $partyPriceData = $this->coInventoryDetailAccountService->prepareProductAssingData($request, $saved->id);
        $this->coInventoryDetailAccountService->ProductAssing($partyPriceData);

        if ($saved && $request->file('image')) {
            $this->uploadService->uploadSingleFile($request->image, $fileName, config('constants.file_upload.inventory'));
        }

        if ($party != null) {

            $coaDetailAccount = $this->coInventoryDetailAccountService->prepareCoaDetailAccountData($request);
            CoaDetailAccount::where('id', $partyId)->update($coaDetailAccount);

            $coaDetailAccountDetail = $this->coInventoryDetailAccountService->prepareUpdatedCoaDetailAccountDetailData($request, $party);
            CoaDetAccountDetail::where('det_account_code', $partyId)->update($coaDetailAccountDetail);
        } else {

            $coaDetailAccount = $this->coInventoryDetailAccountService->prepareCoaDetailAccountData($request);
            CoaDetailAccount::insert($coaDetailAccount);

            $coaDetailAccountDetail = $this->coInventoryDetailAccountService->prepareCoaDetailAccountDetailData($request);
            CoaDetAccountDetail::insert($coaDetailAccountDetail);
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
        $mainHeads = $this->commonService->getInventoryMainHeads();
        $controlHeads = $this->coInventoryDetailAccountService->getControlHeadsForMainHead($detailAccount->coa_main_head);
        $subHeads = $this->coInventoryDetailAccountService->getSubHeadsForControlHead($detailAccount->control_head);
        $subSubHeads = $this->coInventoryDetailAccountService->getSubSubHeadsBySubHead($detailAccount->sub_head);

        // $subHeads = $this->commonService->getInventorySubHeads($detailAccount->main_head);
        // $subSubHeads = $this->commonService->getInventorySubSubHeads($detailAccount->sub_head);
        $fetchPriceTags = InventorySubSubHeadPriceTagModel::where("sub_sub_head_id", $detailAccount->sub_sub_head)->get();
        $priceTagId = $fetchPriceTags->pluck('priceTag')->toArray();
        $priceTag = PriceTag::whereIn("id", $priceTagId)->get();
        $priceTags = $priceTag->pluck('name', 'id')->toArray();
        // $accountArray = [4, 6];
        // $coaMainHeadAccounts = CoaMainHead::whereIn('id', $accountArray)->pluck('account_name', 'id');




        if (!$detailAccount) {
            return abort(404);
        }
        $permission = $this->permissionService->getUserPermission(Auth::user()->id, '13');

        return view('chart-of-inventory.detail-account.create', compact('detailAccount', 'controlHeads',  'subSubHeads', 'priceTags', 'dropDownData', 'subHeads', 'mainHeads', 'permission', 'pageTitle'));
    }

    public function update(Request $request)
    {

        $session = $this->commonService->getSession();
        $inventoryAccount = CoaInventoryDetailAccount::where('id', $request['id'])->value('name');
        $party = CoaDetailAccount::where('account_name', $inventoryAccount)->first();
        $partyId = $party ? $party->id : null;
        $data = $request->except('_token');
        $data['created_by'] = Auth::user()->id;
        $data['updated_by'] = Auth::user()->id;
        $data['business_id'] = $session->business_id;
        $data['f_year_id'] = $session->financial_year;
        if ($request->image) {
            $fileName = $request->image->getClientOriginalName();
            $data['image'] = $fileName;
        }
        $saved = $this->coInventoryDetailAccountService->findUpdateOrCreate(CoaInventoryDetailAccount::class, ['id' => !empty(request('id')) ? request('id') : null], $data);
        if ($saved && $request->file('image')) {
            $this->uploadService->uploadSingleFile($request->image, $fileName, config('constants.file_upload.inventory'));
        }

        if ($party != null) {

            $coaDetailAccount = $this->coInventoryDetailAccountService->prepareCoaDetailAccountData($request);
            CoaDetailAccount::where('id', $partyId)->update($coaDetailAccount);

            $coaDetailAccountDetail = $this->coInventoryDetailAccountService->prepareUpdatedCoaDetailAccountDetailData($request, $party);
            CoaDetAccountDetail::where('det_account_code', $partyId)->update($coaDetailAccountDetail);
        } else {

            $coaDetailAccount = $this->coInventoryDetailAccountService->prepareCoaDetailAccountData($request);
            CoaDetailAccount::insert($coaDetailAccount);

            $coaDetailAccountDetail = $this->coInventoryDetailAccountService->prepareCoaDetailAccountDetailData($request);
            CoaDetAccountDetail::insert($coaDetailAccountDetail);
        }

        if ($request->update_status === 1) {
            DetailAccountProducts::where('product_id', $request['id'])->delete();
            
            $partyPriceData = $this->coInventoryDetailAccountService->prepareProductAssingData($request, $saved->id);
            $this->coInventoryDetailAccountService->ProductAssing($partyPriceData);
        }

        $message = request('id') ? config('constants.update') : config('constants.add');
        session()->flash('message', $message);
        return redirect('co-inv-detail-account/list');
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

    public function getProductDetails(Request $request)
    {
        $fetchPriceTags = InventorySubSubHeadPriceTagModel::where("sub_sub_head_id", $request->product_id)->get();
        $priceTagId = $fetchPriceTags->pluck('priceTag')->toArray();
        $data['priceTags'] = PriceTag::whereIn("id", $priceTagId)->get();

        if ($data['priceTags']->isEmpty()) {
            return response()->json(['message' => 'no data found'], 404);
        }

        return response()->json($data);
    }


}
