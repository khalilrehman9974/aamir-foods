<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MeasurementType;
use App\Services\CommonService;
use App\Services\PermissionService;
use Illuminate\Support\Facades\Auth;
use App\Services\MeasurementTypeService;
use App\Http\Requests\MeasurementTypeRequest;

class MeasurementTypeController extends Controller
{
    private $measurementTypeService;
    private $permissionService;
    private $commonService;

    public function __construct(MeasurementTypeService $measurementTypeService, CommonService $commonService, PermissionService $permissionService)
    {
        $this->measurementTypeService = $measurementTypeService;
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
        $pageTitle = 'list Of Measurement Types';
        $request = request()->all();
        $measurementTypes = $this->measurementTypeService->searchMeasurementType($request);
        $permission = $this->permissionService->getUserPermission(Auth::user()->id, '24');

        return view('measurement-type.index', compact('measurementTypes', 'pageTitle', 'permission'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $pageTitle = 'Create Measurement Type';
        $permission = $this->permissionService->getUserPermission(Auth::user()->id, '24');
        return view('measurement-type.create', compact('permission','pageTitle'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(MeasurementTypeRequest $request)
    {
        $data = $request->except('_token','id');
        $data['created_by'] = Auth::user()->id;
        $data['updated_by'] = Auth::user()->id;

        $this->measurementTypeService->findUpdateOrCreate(MeasurementType::class, ['id'=>!empty(request('id')) ? request('id') : null], $data);
        $message = config(
            'constants.add'
        );
        if(request('id')){
            $message = config('constants.update');
        }
        session()->flash('message', $message);
        return redirect('MeasurementType/list');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\MeasurementType  $measurementType
     * @return \Illuminate\Http\Response
     */
    public function show(MeasurementType $measurementType)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\MeasurementType  $measurementType
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $pageTitle = 'Update The Measurement Type ';
        $measurementType = MeasurementType::find($id);
        $permission = $this->permissionService->getUserPermission(Auth::user()->id, '13');

        return view('measurement-type.create', compact('measurementType', 'pageTitle', 'permission'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\MeasurementType  $measurementType
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, MeasurementType $measurementType)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\MeasurementType  $measurementType
     * @return \Illuminate\Http\Response
     */
    public function delete()
    {
        return $this->commonService->deleteResource(MeasurementType::class);
    }
}
