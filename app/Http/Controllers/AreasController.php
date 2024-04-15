<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\Sector;
use App\Services\AreaService;
use App\Services\CommonService;
use App\Services\PermissionService;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\AreaStoreRequest;

class AreasController extends Controller
{
    private $areaService;
    private $permissionService;
    private $commonService;

    public function __construct(AreaService $areaService, PermissionService $permissionService, CommonService $commonService)
    {
        $this->areaService = $areaService;
        $this->permissionService = $permissionService;
        $this->commonService = $commonService;

    }

    public function index()
    {
        $pageTitle = 'list Of Areas';
        $request = request()->all();
        $param = request()->param;
        $areas = $this->areaService->searchArea($request);
        $sectors = Sector::pluck('name');
        $permission = $this->permissionService->getUserPermission(Auth::user()->id, '13');
        return view('areas.index', compact('areas','param','pageTitle', 'permission','sectors'));
    }


    public function create()
    {
        $pageTitle = 'Create Area';
        $dropDownData = $this->areaService->DropDownData();
        $sectors = Sector::pluck('name','id');
        $permission = $this->permissionService->getUserPermission(Auth::user()->id, '13');
        return view('areas.create', compact('permission', 'pageTitle', 'dropDownData', 'sectors'));
    }


    public function store(AreaStoreRequest $request)
    {
        $data = $request->except('_token','id');
        $data['created_by'] = Auth::user()->id;
        $data['updated_by'] = Auth::user()->id;
        $this->areaService->findUpdateOrCreate(Area::class, ['id'=>!empty(request('id')) ? request('id') : null], $data);
        $message = config('constants.add');
        if(request('id')){
            $message = config('constants.update');
        }
        session()->flash('message', $message);
        return redirect('area/list');
    }

    
    public function edit($id)
    {
        $pageTitle = 'Update The Area';
        $area = Area::find($id);
        $dropDownData = $this->areaService->DropDownData();
        $permission = $this->permissionService->getUserPermission(Auth::user()->id, '13');

        return view('areas.create', compact('area', 'pageTitle', 'dropDownData','permission'));

    }


    public function destroy()
    {
        return $this->commonService->deleteResource(Area::class);
    }
}
