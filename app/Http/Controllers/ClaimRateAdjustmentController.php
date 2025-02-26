<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\Sector;
use App\Models\SaleMan;
use App\Models\PackingType;
use App\Models\SaleManArea;
use Illuminate\Http\Request;
use App\Models\SaleManSector;
use App\Models\MeasurementType;
use App\Models\SaleOrderDetail;
use App\Services\CommonService;
use App\Models\CoaDetailAccount;
use App\Models\DeliveredToParties;
use Illuminate\Support\Facades\DB;
use App\Models\ClaimRateAdjustment;
use App\Models\ClaimRateAdjustmentDetail;
use App\Models\CoaInventoryDetailAccount;
use App\Models\DetailAccountProducts;
use App\Services\ClaimRateAdjustmentService;

class ClaimRateAdjustmentController extends Controller
{
    protected $commonService;
    protected $claimRateService;

    public function __construct(CommonService $commonService, ClaimRateAdjustmentService $claimRateService)
    {
        $this->commonService = $commonService;
        $this->claimRateService = $claimRateService;
    }

    /*
     * Show page of list of sales.
     * */
    public function index()
    {
        $dropDownData = $this->claimRateService->DropDownData();
        $pageTitle = 'List Of Claims';
        $request = request()->all();
        $param = request()->param;
        $claims = $this->claimRateService->searchClaim($request);

        return view('rate-claim-adjustment.index', compact('claims', 'pageTitle','param','dropDownData'));
    }

    /*
     * Show page of create claim.
     * */
    public function create()
    {
        $pageTitle = 'Create Claim $ Rate Adjustment';
        $maxid = ClaimRateAdjustment::max('id') + 1;
        $dropDownData = $this->claimRateService->DropDownData();
        $claimDetails = ClaimRateAdjustmentDetail::where('master_id')->get();
        return view('rate-claim-adjustment.create', compact('pageTitle', 'dropDownData', 'claimDetails', 'maxid'));
    }

    /*
     * Save sale into db.
     * @param: @request
     * */
    public function store(Request $request)
    {
        // dd($request);
        $request = $request->except('_token', 'id');
        // DB::beginTransaction();
        // try {
        //Insert data into sale tables.
        $claimMasterData = $this->claimRateService->prepareClaimMasterData($request);
        $claimMasterInsert = $this->claimRateService->findUpdateOrCreate(ClaimRateAdjustment::class, ['id' => !empty(request('id')) ? request('id') : null], $claimMasterData);
        $claimDetailData = $this->claimRateService->prepareClaimDetailData($request, $claimMasterInsert->id);
        $this->claimRateService->saveClaim($claimDetailData);

        DB::commit();
        // } catch (\Exception $e) {
        //     DB::rollback();
        //     return redirect('claim/create')->with('error', $e->getMessage());
        // }

        $message = config(
            'constants.add'
        );
        if (request('id')) {
            $message = config('constants.update');
        }
        return redirect('claim/list')->with('message', $message);
    }

    /*
     * Show edit page.
     * */
    public function edit($id)
    {

        $pageTitle = 'Update Claim $ Rate Adjustments';
        $currentid = $id;
        $claim = ClaimRateAdjustment::find($id);
        $claimDetails = ClaimRateAdjustmentDetail::where('master_id', $id)->get();
        $dropDownData = $this->claimRateService->DropDownData();

        $fetchSaleManId= CoaDetailAccount::where('id', $claim->party_id)->first('saleMan_id');
        $getSaleman = SaleMan::where('id', $fetchSaleManId->saleMan_id)->get();
        $saleMans = $getSaleman->pluck('name','id');

        $fetchSectors  = SaleManSector::where('master_id', $claim->saleman)->get();
        $sectorsArray = $fetchSectors->pluck('sector_id')->toArray();
        $fetchSectorId = Sector::whereIn('id',$sectorsArray)->get();
        $sectors = $fetchSectorId->pluck('name','id')->toArray();

        $fetchAreas = SaleManArea::where('sector_id', $claim->sector)->get();
        $areasArray = $fetchAreas->pluck('area_id')->toArray();
        $fetchAreaId = Area::whereIn('id',$areasArray)->get();
        $areas = $fetchAreaId->pluck('name','id')->toArray();
        $deliverdToParties = DeliveredToParties::where('detail_account_id', $claim->party_id)->pluck('party_name','id');
        $saleOrderDetails = SaleOrderDetail::where('sale_order_master_id', $id)->get();

        $getProducts = DetailAccountProducts::where('detail_account_id',$claim->party_id)->pluck('product_id');
        $products = CoaInventoryDetailAccount::whereIn('id',$getProducts)->pluck('name','id');
        if (empty($claim)) {
            $message = config('constants.wrong');
        }

        return view('rate-claim-adjustment.create', compact('claim','deliverdToParties','products','areas','sectors','saleMans', 'dropDownData', 'currentid', 'claimDetails', 'pageTitle'));
    }

    /*
     * update existing resource.
     * @param: $data
     * */
    public function update(Request $request)
    {

        // DB::beginTransaction();
        // try {

            $request = request()->all();
            ClaimRateAdjustmentDetail::where('master_id', $request['id'])->delete();

            //Save data into relevant tables.
            $claimMasterData = $this->claimRateService->prepareClaimMasterData($request);
            $claimMasterInsert = $this->commonService->findUpdateOrCreate(ClaimRateAdjustment::class, ['id' => request('id')], $claimMasterData);
            $claimDetailData = $this->claimRateService->prepareClaimDetailData($request, $claimMasterInsert->id);
            $this->claimRateService->saveClaim($claimDetailData);

            DB::commit();
        // } catch (\Exception $e) {
        //     DB::rollback();
        //     return redirect('claim/create')->with('error', $e->getMessage());
        // }

        return redirect('claim/list')->with('message', config('constants.update'));
    }

    /*
     * Delete existing resource.
     * @param: request()->id
     * */
    public function delete()
    {
        DB::beginTransaction();
        try {
            $deleteMaster = ClaimRateAdjustment::where('id', request()->id)->delete();
            $deleteDetail = ClaimRateAdjustmentDetail::where('master_id', request()->id)->delete();

            DB::commit();
            // && $deleteMaster && $deleteDetail
            if ($deleteMaster && $deleteDetail) {
                $message = config('constants.delete');
                return response()->json(['status' => 'success', 'message' => $message]);
            } else {
                $message = config('constants.wrong');
                return response()->json(['status' => 'fail', 'message' => $message]);
            }
        } catch (\Exception $e) {
            DB::rollback();
            return redirect('claim/list')->with('error', $e->getMessage());
        }
    }

    /*
    * View sale detail.
    * @param: $id
    * */
    // public function view($id)
    // {
    //     $saleOrderMaster = $this->claimRateService->getSaleOrderMasterById($id);
    //     if (empty($saleOrderMaster)) {
    //         $message = config('constants.wrong');
    //     }

    //     return view('sale-orders.view', compact('saleOrderMaster'));
    // }

    public function getSaleManDetail($name)
    {
        $fetchSaleMan = CoaDetailAccount::where('account_name', trim($name))->first('saleMan_id');
        $saleManName =  SaleMan::where('id', $fetchSaleMan->saleMan_id)->first('name');

        return response()->json(['status' => 'success', 'name' => $saleManName]);
    }

    public function getSaleManSectorDetail($name)
    {
        $fetchSector = CoaDetailAccount::where('account_name', trim($name))->first('sector');
        if ($fetchSector) {
            return response()->json(['status' => 'success', 'sector' => $fetchSector->sector]);
        }
        return response()->json(['status' => 'fail', 'data' => []]);
    }

    public function getSaleManAreaDetail($name)
    {
        $fetchArea = CoaDetailAccount::where('account_name', trim($name))->first('area');

        if ($fetchArea) {
            return response()->json(['status' => 'success', 'area' => $fetchArea->area]);
        }
        return response()->json(['status' => 'fail', 'data' => []]);
    }

    public function getProductMeasurementType($name)
    {
        $fetchMeasurementType = CoaInventoryDetailAccount::where('name', trim($name))->first('measurement_type_id');
        $measurement =  MeasurementType::where('id', $fetchMeasurementType->measurement_type_id)->first('name');

        return response()->json(['status' => 'success', 'name' => $measurement]);
    }

    public function getProductPackingType($name)
    {
        $fetchPackingType = CoaInventoryDetailAccount::where('name', trim($name))->first('packing_type_id');
        $packing =  PackingType::where('id', @$fetchPackingType->packing_type_id)->first('name');

        return response()->json(['status' => 'success', 'name' => $packing]);
    }
}
