<?php

namespace App\Http\Controllers;

use App\Services\CommonService;
use App\Services\PermissionService;
use App\Services\DistributerService;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreDistributerRequest;
use App\Models\Distributer;

class DistributerController extends Controller
{
    private $distributerService;
    private $permissionService;
    private $commonService;

    public function __construct(DistributerService $distributerService, CommonService $commonService, PermissionService $permissionService)
    {
        $this->distributerService = $distributerService;
        $this->permissionService = $permissionService;
        $this->commonService = $commonService;
    }

    public function index()
    {
        $pageTitle = 'List Of Distributers';
        $request = request()->all();
        $distributers = $this->distributerService->searchDistributer($request);
        $permission = $this->permissionService->getUserPermission(Auth::user()->id, '13');
        return view('distributers.index', compact('distributers', 'pageTitle', 'permission'));
    }


    public function create()
    {

        $pageTitle = 'Add Distributer';
        $permission = $this->permissionService->getUserPermission(Auth::user()->id, '13');
        return view('distributers.create', compact('permission','pageTitle'));
    }


    public function store(StoreDistributerRequest $request)
    {
        $data = $request->except('_token', 'id');
        $data['created_by'] = Auth::user()->id;
        $data['updated_by'] = Auth::user()->id;
        $this->distributerService->findUpdateOrCreate(Distributer::class, ['id' => !empty(request('id')) ? request('id') : null], $data);
        $message = config(
            'constants.add'
        );
        if (request('id')) {
            $message = config('constants.update');
        }
        session()->flash('message', $message);
        return redirect('distributer/list');
    }


    public function edit($id)
    {
        $pageTitle = 'Update Distributer';

        $distributer = Distributer::find($id);
        $permission = $this->permissionService->getUserPermission(Auth::user()->id, '13');

        return view('distributers.create', compact('distributer','pageTitle', 'permission'));
    }

    public function update(StoreDistributerRequest $request)
    {
        $data = $request->except('_token', 'id');
        $data['created_by'] = Auth::user()->id;
        $data['updated_by'] = Auth::user()->id;
        $this->distributerService->findUpdateOrCreate(Distributer::class, ['id' => !empty(request('id')) ? request('id') : null], $data);

        $message = config(
            'constants.update'
        );
        // if (request('id')) {
        //     $message = config('constants.update');
        // }
        session()->flash('message', $message);
        return redirect('distributer/list');
    }


    public function destroy()
    {
        return $this->commonService->deleteResource(Distributer::class);
    }
}
