<?php

namespace App\Http\Controllers;

use App\Models\SaleMan;
use App\Models\AssignArea;
use App\Models\AssignSector;
use Illuminate\Http\Request;
use App\Services\CommonService;
use App\Services\PermissionService;
use Illuminate\Support\Facades\Auth;
use App\Services\AssignSectorService;
use App\Http\Requests\AssignSectorStoreRequest;

class AssignSectorController extends Controller
{
    private $assignSectorService;
    private $permissionService;
    private $commonService;


    public function __construct(AssignSectorService $assignSectorService, CommonService $commonService, PermissionService $permissionService)
    {
        $this->assignSectorService = $assignSectorService;
        $this->permissionService = $permissionService;
        $this->commonService = $commonService;
    }

    public function index()
    {
        $pageTitle = 'List Of Asign Sectors';
        $sale_mans = SaleMan::pluck('name','id');
        $request = request()->all();
        $assignSectors = $this->assignSectorService->search($request);
        $param = request()->param;
        $permission = $this->permissionService->getUserPermission(Auth::user()->id, '14');

        return view('assign_sector.index', compact('param', 'permission','assignSectors','pageTitle',  'sale_mans'));
    }


    public function create()
    {
        $pageTitle = 'Asign Sector';
        $dropDownData = $this->assignSectorService->DropDownData();
        $permission = $this->permissionService->getUserPermission(Auth::user()->id, '14');
        return view('assign_sector.create', compact('permission','pageTitle',  'dropDownData'));
    }


    public function store(AssignSectorStoreRequest $request)
    {
        $data = $request->except('_token','id');
        $data['created_by'] = Auth::user()->id;
        $data['updated_by'] = Auth::user()->id;
        $this->assignSectorService->findUpdateOrCreate(AssignSector::class, ['id'=>!empty(request('id')) ? request('id') : null], $data);
        $message = config('constants.add');
        if(request('id')){
            $message = config('constants.update');
        }
        session()->flash('message', $message);

        return redirect('assignSector/list');
    }


    public function edit($id)
    {
        $pageTitle = 'Update Asign Sector';
        $dropDownData = $this->assignSectorService->DropDownData();
        $assignSector = AssignSector::find($id);
        $permission = $this->permissionService->getUserPermission(Auth::user()->id, '14');

        return view('assign_sector.create', compact('assignSector' ,'pageTitle', 'dropDownData', 'permission'));

    }


    public function destroy()
    {
        return $this->commonService->deleteResource(AssignSector::class);
    }

}
