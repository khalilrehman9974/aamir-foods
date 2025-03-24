<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\Sector;
use App\Models\SaleMan;
use Illuminate\Http\Request;
use App\Services\CommonService;
use App\Models\CoaDetailAccount;
use Illuminate\Support\Facades\DB;
use App\Models\CoaDetAccountDetail;
use App\Models\CoaDetailAccountArea;
use App\Models\CoaDetailAccountSectors;
use App\Models\CoaInventoryDetailAccount;
use App\Models\DetailAccountPrices;
use App\Services\PermissionService;
use Illuminate\Support\Facades\Auth;
use App\Models\CoaInventorySubSubHead;
use App\Models\DetailAccountProducts;
use App\Models\InventorySubSubHeadPriceTagModel;
use App\Models\PriceTag;
use App\Models\SaleManArea;
use App\Models\SaleManSector;
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
        $pageTitle = 'List of Detail Accounts';
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
        $dropDownData = $this->coaDetailAccountService->DropDownData();
        $mainHeads = $this->chartOfAccountService->getMainHeads();
        $controlHeads = $this->chartOfAccountService->getControlHeads();
        $subHeads = $this->chartOfAccountService->getSubHeads();
        $subSubHeads = $this->chartOfAccountService->getSubSubHeads();
        $permission = $this->permissionService->getUserPermission(Auth::user()->id, '24');

        return view('chart-of-accounts.detail-account.create', compact('permission', 'controlHeads',  'dropDownData', 'pageTitle', 'title', 'mainHeads', 'subHeads', 'subSubHeads'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request);
        $request = $request->except('_token', 'id');
        DB::beginTransaction();
        try {

            $detailAccountMasterData = $this->coaDetailAccountService->prepareDetailAccountMasterData($request);
            $detailAccountMasterInsert = $this->coaDetailAccountService->findUpdateOrCreate(CoaDetailAccount::class, ['id' => !empty(request('id')) ? request('id') : null], $detailAccountMasterData);

            $detailAccountProductData = $this->coaDetailAccountService->prepareDetailAccountDetailData($request, $detailAccountMasterInsert->id);
            $this->coaDetailAccountService->saveDetailAccount($detailAccountProductData);

            $detailData = $this->coaDetailAccountService->prepareAdditionalInformationData($request, $detailAccountMasterInsert->id);
            $this->coaDetailAccountService->findUpdateOrCreate(CoaDetAccountDetail::class, ['id' => !empty(request('id')) ? request('id') : null], $detailData);

            $detailAccountSectorData = $this->coaDetailAccountService->prepareDetailAccountSectorsData($request, $detailAccountMasterInsert->id);
            $this->coaDetailAccountService->saveDetailAccountSectors($detailAccountSectorData);

            $detailAccountAreasData = $this->coaDetailAccountService->prepareDetailAccountAreasData($request, $detailAccountMasterInsert->id);
            $this->coaDetailAccountService->saveDetailAccountAreas($detailAccountAreasData);

            $detailAccountProductsData = $this->coaDetailAccountService->prepareDetailAccountProductData($request, $detailAccountMasterInsert->id);
            $this->coaDetailAccountService->saveDetailAccountProducts($detailAccountProductsData);


            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            return redirect('detail-account/create')->with('error', $e->getMessage());
        }
        $message = config('constants.add');
        return redirect('detail-account/list')->with('message', $message);
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
        $dropDownData = $this->coaDetailAccountService->DropDownData();
        $detailAccount = CoaDetailAccount::find($id);
        $detailAccountSectors = CoaDetailAccountSectors::where('master_account_id', $id)->get();
        $detailAccountAreas = CoaDetailAccountArea::where('master_account_id', $id)->get();
        $detailAccountSaleManSectors =SaleManSector::where("master_id", $detailAccount->saleMan_id)->get();
        $sectorsArray = $detailAccountSaleManSectors->pluck('sector_id')->toArray();
        $fetchSectors = Sector::whereIn('id',$sectorsArray)->get();
        $sectors = $fetchSectors->pluck('name','id')->toArray();

        $detailAccountSaleManAreas =CoaDetailAccountArea::where("sector_id", $sectorsArray)->get();
        $areasArray = $detailAccountSaleManAreas->pluck('area_id')->toArray();
        $fetchAreas = Area::whereIn('id',$areasArray)->get();
        $areas = $fetchAreas->pluck('name','id')->toArray();

        $detailAccountRecords = DetailAccountPrices::where('coa_detail_account_code', $id)->get();
        $masterPriceTag = $detailAccountRecords->pluck('price_tag_id')->toArray();
        $invThirdLevel = $detailAccountRecords->pluck('inventory_third_level')->toArray();
        $detailAccountProducts = DetailAccountProducts::where('detail_account_id', $id)->whereIn('master_price_tag', $masterPriceTag)->whereIn('master_third_level',$invThirdLevel)->get();

        $products = CoaInventoryDetailAccount::whereIn("sub_sub_head", $invThirdLevel)->whereIn("priceTag_id", $masterPriceTag)->pluck('name', 'id');

        $detailAccountDetails = CoaDetAccountDetail::find($id);
        if (!$detailAccount) {
            return abort(404);
        }
        $mainHeads = $this->chartOfAccountService->getMainHeads();
        $controlHeads = $this->chartOfAccountService->getControlHeadsForMainHead($detailAccount->main_head);
        $subHeads = $this->chartOfAccountService->getSubHeadsForControlHead($detailAccount->control_head);
        $subSubHeads = $this->chartOfAccountService->getSubSubHeadsBySubHead($detailAccount->sub_head);
        $permission = $this->permissionService->getUserPermission(Auth::user()->id, '13');

        return view('chart-of-accounts.detail-account.edit', compact('detailAccount','sectors','areas' ,'detailAccountDetails', 'detailAccountAreas', 'detailAccountRecords','products','detailAccountSectors','detailAccountProducts','subSubHeads', 'dropDownData', 'subHeads', 'controlHeads', 'mainHeads', 'permission', 'pageTitle'));
    }


    public function update(Request $request)
    {
        // dd($request);

        DB::beginTransaction();
        try {

            $request = request()->all();
            DetailAccountPrices::where('coa_detail_account_code', $request['id'])->delete();
            DetailAccountProducts::where('detail_account_id', $request['id'])->delete();
            CoaDetailAccountSectors::where('master_account_id', $request['id'])->delete();
            CoaDetailAccountArea::where('master_account_id', $request['id'])->delete();
            CoaDetAccountDetail::where('det_account_code', $request['id'])->delete();

            $detailAccountMasterData = $this->coaDetailAccountService->prepareDetailAccountMasterData($request);
            $detailAccountMasterInsert = $this->coaDetailAccountService->findUpdateOrCreate(CoaDetailAccount::class, ['id' => !empty(request('id')) ? request('id') : null], $detailAccountMasterData);

            $detailAccountProductData = $this->coaDetailAccountService->prepareDetailAccountDetailData($request, $detailAccountMasterInsert->id);
            $this->coaDetailAccountService->saveDetailAccount($detailAccountProductData);

            $detailData = $this->coaDetailAccountService->prepareAdditionalInformationData($request, $detailAccountMasterInsert->id);
            $this->coaDetailAccountService->findUpdateOrCreate(CoaDetAccountDetail::class, ['id' => !empty(request('id')) ? request('id') : null], $detailData);

            $detailAccountSectorData = $this->coaDetailAccountService->prepareDetailAccountSectorsData($request, $detailAccountMasterInsert->id);
            $this->coaDetailAccountService->saveDetailAccountSectors($detailAccountSectorData);

            $detailAccountAreasData = $this->coaDetailAccountService->prepareDetailAccountAreasData($request, $detailAccountMasterInsert->id);
            $this->coaDetailAccountService->saveDetailAccountAreas($detailAccountAreasData);

            $detailAccountProductsData = $this->coaDetailAccountService->prepareDetailAccountProductData($request, $detailAccountMasterInsert->id);
            $this->coaDetailAccountService->saveDetailAccountProducts($detailAccountProductsData);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            return redirect('detail-account/create')->with('error', $e->getMessage());
        }
        if (request('id')) {
            $message = config('constants.update');
        }
        return redirect('detail-account/list')->with('message', $message);
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
            return response()->json(['status' => 'success', 'data' => $subSubAccounts]);
        }
        return response()->json(['status' => 'fail', 'data' => []]);

    }

    public function getSaleManDetail(Request $request)
    {
        $saleManSector = SaleManSector::where("master_id", $request->saleMan_id)->get(["master_id", "sector_id"]);
        $saleManIds = $saleManSector->pluck('sector_id')->toArray();
        $data['sectors'] = Sector::whereIn("id", $saleManIds)->get();

        return response()->json($data);
    }

    public function getSaleManAreaDetail(Request $request)
    {
        $saleManArea = SaleManArea::whereIn("sector_id", $request->sector)->get();
        $saleManIds = $saleManArea->pluck('area_id')->toArray();
        $data['areas'] = Area::whereIn("id", $saleManIds)->get();
        return response()->json($data);
    }

    public function getProductPrice($name)
    {
        $fetchPrice = CoaInventorySubSubHead::where('name', trim($name))->first('price');

        if ($fetchPrice) {
            return response()->json(['status' => 'success', 'price' => $fetchPrice->price]);
        }
        return response()->json(['status' => 'fail', 'data' => []]);
    }

    public function getProductDetails(Request $request)
    {
        $fetchPriceTags = InventorySubSubHeadPriceTagModel::where("sub_sub_head_id", $request->product_id)->get();
        $priceTagId = $fetchPriceTags->pluck('priceTag')->toArray();
        $data['priceTags'] = PriceTag::whereIn("id", $priceTagId)->get();

        $fetchInvDetailLevel = CoaInventoryDetailAccount::where("sub_sub_head", $request->product_id)->get();
        $thirdLevelId = $fetchInvDetailLevel->pluck('id')->toArray();
        $data['products'] = CoaInventoryDetailAccount::whereIn("id", $thirdLevelId)->get();

        if ($data['priceTags']->isEmpty() && $data['products']->isEmpty()) {
            return response()->json(['message' => 'no data found'], 404);
        }

        return response()->json($data);
    }

    public function getProducts(Request $request)
    {
        $PriceTagId= $request->priceTag_id;
        $productId = $request->product_id;
        $fetchInvDetailLevel = CoaInventoryDetailAccount::where("sub_sub_head", $productId)->where("priceTag_id", $PriceTagId)->get();
        $thirdLevelId = $fetchInvDetailLevel->pluck('id')->toArray();
        $data['products'] = CoaInventoryDetailAccount::whereIn("id", $thirdLevelId)->get();

        if ($data['products']->isEmpty()) {
            return response()->json(['message' => 'no data found'], 404);
        }

        return response()->json($data);
    }
}
