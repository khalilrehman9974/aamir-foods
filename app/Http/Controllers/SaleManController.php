<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\Zone;
use App\Models\Sector;
use App\Models\Country;
use App\Models\SaleMan;
use App\Models\SaleManArea;
use App\Models\SaleManZone;
use Illuminate\Http\Request;
use App\Models\SaleManSector;
use App\Services\CommonService;
use App\Services\SaleManService;
use Illuminate\Support\Facades\DB;
use App\Services\PermissionService;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\SaleManRequest;

class SaleManController extends Controller
{
    private $saleManService;
    private $permissionService;
    private $commonService;

    public function __construct(SaleManService $saleManService, CommonService $commonService, PermissionService $permissionService)
    {
        $this->saleManService = $saleManService;
        $this->permissionService = $permissionService;
        $this->commonService = $commonService;
    }

    public function index()
    {
        $pageTitle = 'List Of SaleMans';
        $request = request()->all();
        $saleMans = $this->saleManService->searchSaleMan($request);
        // $saleMans = $this->saleManService->search($request);
        $permission = $this->permissionService->getUserPermission(Auth::user()->id, '13');
        return view('sale_mans.index', compact('saleMans', 'pageTitle', 'permission'));
    }


    public function create()
    {
        $categories = Country::all();
        $countries = $this->saleManService->getCountries();
        $zones = $this->saleManService->getZones();
        $sectors = $this->saleManService->getSectors();
        $areas = $this->saleManService->getAreas();
        $pageTitle = 'Add SaleMan';
        $permission = $this->permissionService->getUserPermission(Auth::user()->id, '13');
        return view('sale_mans.create', compact('permission','categories','countries','zones', 'sectors','areas','pageTitle'));
    }


    public function store(Request $request)
    {

        DB::beginTransaction();
        try {

        $request = $request->except('_token', 'id');

        $saleManMasterData = $this->saleManService->prepareSaleManMasterData($request);
        $saleManMasterInsert = $this->saleManService->findUpdateOrCreate(SaleMan::class, ['id' => !empty(request('id')) ? request('id') : null], $saleManMasterData);

        $saleManZoneData = $this->saleManService->prepareSaleManZonesData($request, $saleManMasterInsert->id);
        $this->saleManService->saveSaleManZones($saleManZoneData);

        $saleManSectorData = $this->saleManService->prepareSaleManSectorData($request, $saleManMasterInsert->id);
        $this->saleManService->saveSaleManSectors($saleManSectorData);

        $saleManAreaData = $this->saleManService->prepareSaleManAreaData($request, $saleManMasterInsert->id);
        $this->saleManService->saveSaleManAreas($saleManAreaData);

        DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            return redirect('saleMan/create')->with('error', $e->getMessage());
        }

        return redirect('saleMan/list')->with('message', config('constants.add'));

    }


    public function edit($id)
    {
        $saleMan = SaleMan::find($id);
        if (!$saleMan) {
            return abort(404);
        }
        $pageTitle = 'Update SaleMan';
        $saleManZones = SaleManZone::with('zones')->where('master_id', $id)->get();
        $saleManSectors = SaleManSector::with('sectors')->where('master_id', $id)->get();
        // dd($saleManSectors);
        $saleManAreas = SaleManArea::with('areas')->where('master_id', $id)->get();

        $zonesArray = $saleManZones->pluck('zone_id')->toArray();
        $sectorsArray = $saleManSectors->pluck('sector_id')->toArray();
        // dd($sectorsArray);

        $countries = $this->saleManService->getCountries();
        $zones = $this->saleManService->getZones();

        $fetchSectors = Sector::whereIn('zone_id', $zonesArray)->get();
        $sectors = $fetchSectors->pluck('name','id')->toArray();
        $fetchAreas = Area::whereIn('sector_id', $sectorsArray)->get();
        $areas = $fetchAreas->pluck('name','id')->toArray();

        $permission = $this->permissionService->getUserPermission(Auth::user()->id, '13');

        return view('sale_mans.edit', compact('saleMan','saleManZones','saleManSectors',
        'saleManAreas','pageTitle','countries','zones','sectors', 'areas','permission'));
    }



    public function update(Request $request)
    {
        DB::beginTransaction();
        try {
        $request = request()->all();
        SaleManZone::where('master_id', $request['id'])->delete();
        SaleManSector::where('master_id', $request['id'])->delete();
        SaleManArea::where('master_id', $request['id'])->delete();

        $saleManMasterData = $this->saleManService->prepareSaleManMasterData($request);
        $saleManMasterInsert = $this->saleManService->findUpdateOrCreate(SaleMan::class, ['id' => !empty(request('id')) ? request('id') : null], $saleManMasterData);

        $saleManZoneData = $this->saleManService->prepareSaleManZonesData($request, $saleManMasterInsert->id);
        $this->saleManService->saveSaleManZones($saleManZoneData);

        $saleManSectorData = $this->saleManService->prepareSaleManSectorData($request, $saleManMasterInsert->id);
        $this->saleManService->saveSaleManSectors($saleManSectorData);

        $saleManAreaData = $this->saleManService->prepareSaleManAreaData($request, $saleManMasterInsert->id);
        $this->saleManService->saveSaleManAreas($saleManAreaData);

         DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            return redirect('saleMan/create')->with('error', $e->getMessage());
        }

        return redirect('saleMan/list')->with('message', config('constants.update'));
    }


    public function destroy()
    {
        return $this->commonService->deleteResource(SaleMan::class);
    }

    public function fetchZone(Request $request)
    {
        $data['zones'] = Zone::whereIn("country_id", [$request->country_id])
            ->get(["name", "id"]);

        return response()->json($data);
    }

    public function fetchSector(Request $request)
    {

        $countries = $request->zone_id;
        $data['sectors'] = Sector::whereIn("zone_id",$countries)
            ->get();

        $data['areas'] = Area::where("sector_id", $request->sector_id)
            ->get(["name", "id"]);

        return response()->json($data);
    }

    public function fetchArea(Request $request)
    {
        $sectors = $request->sector_id;
        $data['areas'] = Area::whereIn("sector_id", $sectors)
            ->get();
        return response()->json($data);
    }

    public function getSubcategories(Request $request)
    {
        $subcategories = Zone::where('country_id', $request->category_id)->get();
        return response()->json($subcategories);
    }

    public function getTypes(Request $request)
    {
        $types = Sector::where('zone_id', $request->subcategory_id)->get();
        return response()->json($types);
    }

    public function getVariations(Request $request)
    {
        $variations = Area::where('sector_id', $request->type_id)->get();
        return response()->json($variations);
    }
}
