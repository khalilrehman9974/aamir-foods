<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\SaleMan;
use App\Models\AssignArea;
use Illuminate\Http\Request;
use App\Services\CommonService;
use App\Services\AssignAreaService;
use App\Services\PermissionService;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\AssignAreaStoreRequest;

class AssignAreaController extends Controller
{
    private $assignAreaService;
    private $permissionService;
    private $commonService;

    public function __construct(AssignAreaService $assignAreaService, CommonService $commonService, PermissionService $permissionService)
    {
        $this->assignAreaService = $assignAreaService;
        $this->permissionService = $permissionService;
        $this->commonService = $commonService;
    }

    public function index()
    {
        $pageTitle = 'List Of Asign Areas';
        $request = request()->all();
        $assignAreas = $this->assignAreaService->searchAssignArea($request);
        $param = request()->param;
        $permission = $this->permissionService->getUserPermission(Auth::user()->id, '14');

        return view('assign_area.index', compact('assignAreas','param', 'pageTitle', 'permission','assignAreas'));
    }


    public function create()
    {
        $pageTitle = 'Assign Area';
        $dropDownData = $this->assignAreaService->DropDownData();
        $permission = $this->permissionService->getUserPermission(Auth::user()->id, '14');
        return view('assign_area.create', compact('permission', 'pageTitle', 'dropDownData'));
    }


    public function store(AssignAreaStoreRequest $request)
    {
        $data = $request->except('_token','id');
        $data['created_by'] = Auth::user()->id;
        $data['updated_by'] = Auth::user()->id;
        $this->assignAreaService->findUpdateOrCreate(AssignArea::class, ['id'=>!empty(request('id')) ? request('id') : null], $data);
        $message = config('constants.add');
        if(request('id')){
            $message = config('constants.update');
        }
        session()->flash('message', $message);

        return redirect('assignArea/list');
    }


    public function edit($id)
    {
        $pageTitle = 'Update The Assign Area';
        $dropDownData = $this->assignAreaService->DropDownData();
        $assignArea = AssignArea::find($id);
        $permission = $this->permissionService->getUserPermission(Auth::user()->id, '14');

        return view('assign_area.create', compact('assignArea','pageTitle', 'dropDownData', 'permission'));

    }


    public function destroy()
    {
        return $this->commonService->deleteResource(AssignArea::class);
    }

}
