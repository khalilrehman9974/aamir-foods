<?php

namespace App\Http\Controllers;

use App\Models\PackingType;
use Illuminate\Http\Request;
use App\Services\CommonService;
use App\Services\PermissionService;
use App\Services\PackingTypeService;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\PackingTypeRequest;

class PackingTypeController extends Controller
{
    private $packingTypeService;
    private $permissionService;
    private $commonService;

    public function __construct(PackingTypeService $packingTypeService, CommonService $commonService, PermissionService $permissionService)
    {
        $this->packingTypeService = $packingTypeService;
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
        $pageTitle = 'list Of Packing Types';
        $request = request()->all();
        $packingTypes = $this->packingTypeService->searchMeasurementType($request);
        $permission = $this->permissionService->getUserPermission(Auth::user()->id, '24');

        return view('packing-type.index', compact('packingTypes', 'pageTitle', 'permission'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $pageTitle = 'Create Packing Type';
        $permission = $this->permissionService->getUserPermission(Auth::user()->id, '24');
        return view('packing-type.create', compact('permission','pageTitle'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PackingTypeRequest $request)
    {
        $data = $request->except('_token','id');
        $data['created_by'] = Auth::user()->id;
        $data['updated_by'] = Auth::user()->id;

        $this->packingTypeService->findUpdateOrCreate(PackingType::class, ['id'=>!empty(request('id')) ? request('id') : null], $data);
        $message = config(
            'constants.add'
        );
        if(request('id')){
            $message = config('constants.update');
        }
        session()->flash('message', $message);
        return redirect('PackingType/list');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\PackingType  $packingType
     * @return \Illuminate\Http\Response
     */
    public function show(PackingType $packingType)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\PackingType  $packingType
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $pageTitle = 'Update The Packing Type ';
        $packingType = PackingType::find($id);
        $permission = $this->permissionService->getUserPermission(Auth::user()->id, '13');

        return view('packing-type.create', compact('packingType', 'pageTitle', 'permission'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\PackingType  $packingType
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PackingType $packingType)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\PackingType  $packingType
     * @return \Illuminate\Http\Response
     */
    public function delete()
    {
        return $this->commonService->deleteResource(PackingType::class);
    }
}
