<?php

namespace App\Http\Controllers;

use App\Models\Business;
use Illuminate\Http\Request;
use App\Services\CommonService;
use App\Services\BusinessService;
use App\Services\PermissionService;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\BusinessStoreRequest;

class BusinessController extends Controller
{
    private $businessService;
    private $permissionService;
    private $commonService;
    const PER_PAGE = 10;

    public function __construct(BusinessService $businessService, CommonService $commonService, PermissionService $permissionService)
    {
        $this->businessService = $businessService;
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
        $pageTitle = 'List Of Business';
        $request = request()->all();
        $busineses = $this->businessService->searchbusiness($request);
        $param = request()->param;
        $permission = $this->permissionService->getUserPermission(Auth::user()->id, '24');

        return view('business.index', compact('busineses','param', 'pageTitle', 'permission'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $pageTitle = 'Add Business';
        $permission = $this->permissionService->getUserPermission(Auth::user()->id, '24');
        return view('business.create', compact('permission','pageTitle'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(BusinessStoreRequest $request)
    {
        $data = $request->except('_token','id');
        $data['created_by'] = Auth::user()->id;
        $data['updated_by'] = Auth::user()->id;

        $this->businessService->findUpdateOrCreate(Business::class, ['id'=>!empty(request('id')) ? request('id') : null], $data);
        $message = config(
            'constants.add'
        );
        if(request('id')){
            $message = config('constants.update');
        }
        session()->flash('message', $message);
        return redirect('business/list');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Business  $business
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $pageTitle = 'Update The Business';
        $business = Business::find($id);
        $permission = $this->permissionService->getUserPermission(Auth::user()->id, '13');

        return view('business.create', compact('business','pageTitle', 'permission'));
    }



    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Business  $business
     * @return \Illuminate\Http\Response
     */
    public function destroy()
    {
        return $this->commonService->deleteResource(Business::class);
    }
}
