<?php

namespace App\Http\Controllers;

use App\Models\Sector;
use Illuminate\Http\Request;
use App\Services\CommonService;
use App\Services\SectorService;
use App\Services\PermissionService;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreSectorRequest;

class SectorController extends Controller
{
    private $SectorService;
    private $permissionService;
    private $commonService;

    public function __construct(SectorService $SectorService, CommonService $commonService, PermissionService $permissionService)
    {
        $this->SectorService = $SectorService;
        $this->permissionService = $permissionService;
        $this->commonService = $commonService;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pageTitle = 'list Of Belts';
        $request = request()->all();
        $sectors = $this->SectorService->searchSector($request);
        $dropDownData = $this->SectorService->DropDownData();
        $permission = $this->permissionService->getUserPermission(Auth::user()->id, '24');

        return view('sectors.index', compact('sectors', 'pageTitle', 'permission','dropDownData'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $pageTitle = 'Create Belt';
        $dropDownData = $this->SectorService->DropDownData();
        $request = request()->all();
        $sectors = $this->SectorService->searchSector($request);
        $permission = $this->permissionService->getUserPermission(Auth::user()->id, '24');
        return view('sectors.create', compact('permission','sectors','dropDownData','pageTitle'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreSectorRequest $request)
    {
        $data = $request->except('_token','id');
        $data['created_by'] = Auth::user()->id;
        $data['updated_by'] = Auth::user()->id;

        $this->SectorService->findUpdateOrCreate(Sector::class, ['id'=>!empty(request('id')) ? request('id') : null], $data);
        $message = config(
            'constants.add'
        );
        if(request('id')){
            $message = config('constants.update');
        }
        session()->flash('message', $message);
        return redirect('sector/create');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Sector  $sector
     * @return \Illuminate\Http\Response
     */
    public function show(Sector $sector)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Sector  $sector
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $pageTitle = 'Update The Belt ';
        $sector = Sector::find($id);
        $request = request()->all();
        $sectors = $this->SectorService->searchSector($request);
        $dropDownData = $this->SectorService->DropDownData();
        $permission = $this->permissionService->getUserPermission(Auth::user()->id, '13');

        return view('sectors.create', compact('sectors','sector', 'pageTitle','dropDownData','permission'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Sector  $sector
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Sector $sector)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Sector  $sector
     * @return \Illuminate\Http\Response
     */
    public function delete()
    {
        return $this->commonService->deleteResource(Sector::class);
    }

    public function fetchBelt(Request $request)
    {

        $data['belts'] = Sector::where("zone_id", $request->zone_id)->get(["id", "name"]);

        return response()->json($data);
    }
}
