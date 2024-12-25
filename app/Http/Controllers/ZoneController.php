<?php

namespace App\Http\Controllers;

use App\Models\Zone;
use Illuminate\Http\Request;
use App\Services\ZoneService;
use App\Services\CommonService;
use App\Services\PermissionService;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreZoneRequest;

class ZoneController extends Controller
{
    private $zoneService;
    private $permissionService;
    private $commonService;

    public function __construct(ZoneService $zoneService, PermissionService $permissionService, CommonService $commonService)
    {
        $this->zoneService = $zoneService;
        $this->permissionService = $permissionService;
        $this->commonService = $commonService;

    }

    public function index()
    {
        $pageTitle = 'list Of Zones';
        $request = request()->all();
        $param = request()->param;
        $zones = $this->zoneService->searchZone($request);
        $dropDownData = $this->zoneService->DropDownData();
        $permission = $this->permissionService->getUserPermission(Auth::user()->id, '13');
        return view('zones.index', compact('zones','param','pageTitle', 'permission','dropDownData'));
    }


    public function create()
    {
        $pageTitle = 'Add Zone';
        $dropDownData = $this->zoneService->DropDownData();
        $request = request()->all();
        $zones = $this->zoneService->searchZone($request);
        // $sectors = Sector::pluck('name','id');
        $permission = $this->permissionService->getUserPermission(Auth::user()->id, '13');
        return view('zones.create', compact('permission','zones', 'pageTitle', 'dropDownData'));
    }


    public function store(StoreZoneRequest $request)
    {
        $data = $request->except('_token','id');
        $data['created_by'] = Auth::user()->id;
        $data['updated_by'] = Auth::user()->id;
        $this->zoneService->findUpdateOrCreate(Zone::class, ['id'=>!empty(request('id')) ? request('id') : null], $data);
        $message = config('constants.add');
        if(request('id')){
            $message = config('constants.update');
        }
        session()->flash('message', $message);
        return redirect('zone/create');
    }


    public function edit($id)
    {
        $pageTitle = 'Update The Zone';
        $zone = Zone::find($id);
        $dropDownData = $this->zoneService->DropDownData();
        $request = request()->all();
        $zones = $this->zoneService->searchZone($request);
        $permission = $this->permissionService->getUserPermission(Auth::user()->id, '13');

        return view('zones.create', compact('zone','zones','pageTitle', 'dropDownData','permission'));

    }


    public function destroy()
    {
        return $this->commonService->deleteResource(Zone::class);
    }

    public function fetchZone(Request $request)
    {

        $data['zones'] = Zone::where("country_id", $request->country_id)->get(["id", "name"]);

        return response()->json($data);
    }
}
