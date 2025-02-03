<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\Sector;
use App\Models\SaleManArea;
use Illuminate\Http\Request;
use App\Models\SaleManSector;
use App\Models\DeliveredToParties;
use Illuminate\Support\Facades\DB;
use App\Services\PermissionService;
use Illuminate\Support\Facades\Auth;
use App\Models\DeliveredToPartiesAreas;
use App\Models\DeliveredToPartiesSectors;
use App\Services\DeliveredToPartiesService;
use App\Http\Requests\DeliveredToPartyRequest;

class DeliveredToPartiesController extends Controller
{
    private $permissionService;
    private $deliveredToPartiesService;

    public function __construct(DeliveredToPartiesService $deliveredToPartiesService, PermissionService $permissionService)
    {
        $this->permissionService = $permissionService;
        $this->deliveredToPartiesService = $deliveredToPartiesService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $deliveredToParties = $this->deliveredToPartiesService->getListOfDeliveredToParties($request->search);
        $permission = $this->permissionService->getUserPermission(Auth::user()->id, '24');
        $pageTitle = 'List of Delivered To Parties';
        return view('delivered-to-parties.index', compact('deliveredToParties', 'permission', 'pageTitle'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $pageTitle = 'Create Delivered To Parties';
        $maxid = DeliveredToParties::max('id') + 1;
        $dropDownData = $this->deliveredToPartiesService->DropDownData();
        $permission = $this->permissionService->getUserPermission(Auth::user()->id, '24');

        return view('delivered-to-parties.create', compact('permission','maxid' , 'dropDownData', 'pageTitle'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(DeliveredToPartyRequest $request)
    {
        dd($request->all());
        $request = $request->except('_token', 'id');
        DB::beginTransaction();
        try {

            $deliveredToPartiesMasterData = $this->deliveredToPartiesService->prepareDetailAccountMasterData($request);
            $detailAccountMasterInsert = $this->deliveredToPartiesService->findUpdateOrCreate(DeliveredToParties::class, ['id' => !empty(request('id')) ? request('id') : null], $deliveredToPartiesMasterData);

            $deliveredToPartiesSectorData = $this->deliveredToPartiesService->prepareDetailAccountSectorsData($request, $detailAccountMasterInsert->id);
            $this->deliveredToPartiesService->saveDeliveredToPartiesSectors($deliveredToPartiesSectorData);

            $deliveredToPartiesAreasData = $this->deliveredToPartiesService->prepareDetailAccountAreasData($request, $detailAccountMasterInsert->id);
            $this->deliveredToPartiesService->saveDeliveredToPartiesAreas($deliveredToPartiesAreasData);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            return redirect('delivered-to-parties/create')->with('error', $e->getMessage())->withInput();
        }
        $message = config('constants.add');
        return redirect('delivered-to-parties/list')->with('message', $message);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $currentid = $id;
        $pageTitle = 'Update Delivered To Party';
        $dropDownData = $this->deliveredToPartiesService->DropDownData();
        $deliveredParties = DeliveredToParties::find($id);
        $deliveredPartiesSectors = DeliveredToPartiesSectors::where('delivered_to_party_id', $id)->get();
        $deliveredPartiesAreas = DeliveredToPartiesAreas::where('delivered_to_party_id', $id)->get();
        $detailAccountSaleManSectors =SaleManSector::where("master_id", $deliveredParties->saleMan_id)->get();
        $sectorsArray = $detailAccountSaleManSectors->pluck('sector_id')->toArray();
        $fetchSectors = Sector::whereIn('id',$sectorsArray)->get();
        $sectors = $fetchSectors->pluck('name','id')->toArray();

        $detailAccountSaleManAreas =SaleManArea::where("sector_id", $sectorsArray)->get();
        $areasArray = $detailAccountSaleManAreas->pluck('area_id')->toArray();
        $fetchAreas = Area::whereIn('id',$areasArray)->get();
        $areas = $fetchAreas->pluck('name','id')->toArray();

        if (!$deliveredParties) {
            return abort(404);
        }

        $permission = $this->permissionService->getUserPermission(Auth::user()->id, '13');

        return view('delivered-to-parties.create', compact('pageTitle','currentid','dropDownData','deliveredParties','deliveredPartiesSectors','deliveredPartiesAreas',
            'sectors','areas'));
    }


    public function update(DeliveredToPartyRequest $request)
    {

        DB::beginTransaction();
        try {

            $request = request()->all();
            DeliveredToPartiesSectors::where('delivered_to_party_id', $request['id'])->delete();
            DeliveredToPartiesAreas::where('delivered_to_party_id', $request['id'])->delete();


            $deliveredToPartiesMasterData = $this->deliveredToPartiesService->prepareDetailAccountMasterData($request);
            $detailAccountMasterInsert = $this->deliveredToPartiesService->findUpdateOrCreate(DeliveredToParties::class, ['id' => !empty(request('id')) ? request('id') : null], $deliveredToPartiesMasterData);

            $deliveredToPartiesSectorData = $this->deliveredToPartiesService->prepareDetailAccountSectorsData($request, $detailAccountMasterInsert->id);
            $this->deliveredToPartiesService->saveDeliveredToPartiesSectors($deliveredToPartiesSectorData);

            $deliveredToPartiesAreasData = $this->deliveredToPartiesService->prepareDetailAccountAreasData($request, $detailAccountMasterInsert->id);
            $this->deliveredToPartiesService->saveDeliveredToPartiesAreas($deliveredToPartiesAreasData);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            return redirect('delivered-to-parties/create')->with('error', $e->getMessage());
        }
        if (request('id')) {
            $message = config('constants.update');
        }
        return redirect('delivered-to-parties/list')->with('message', $message);
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


}
