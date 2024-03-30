<?php

namespace App\Http\Controllers;

use App\Services\CommonService;
use App\Services\PermissionService;
use App\Services\TransporterService;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\TransporterRequest;
use App\Models\Transporter;

class TransporterController extends Controller
{
    private $transporterService;
    private $permissionService;
    private $commonService;

    public function __construct(TransporterService $transporterService, CommonService $commonService, PermissionService $permissionService)
    {
        $this->transporterService = $transporterService;
        $this->permissionService = $permissionService;
        $this->commonService = $commonService;
    }

    public function index()
    {
        $pageTitle = 'List Of Transporters';
        $request = request()->all();
        $transporters = $this->transporterService->searchTransporter($request);
        // $saleMans = $this->transporterService->search($request);
        $permission = $this->permissionService->getUserPermission(Auth::user()->id, '13');
        return view('transporters.index', compact('transporters', 'pageTitle', 'permission'));
    }


    public function create()
    {

        $pageTitle = 'Add Transporter';
        $permission = $this->permissionService->getUserPermission(Auth::user()->id, '13');
        return view('transporters.create', compact('permission','pageTitle'));
    }


    public function store(TransporterRequest $request)
    {
        $data = $request->except('_token', 'id');
        $data['created_by'] = Auth::user()->id;
        $data['updated_by'] = Auth::user()->id;
        $this->transporterService->findUpdateOrCreate(Transporter::class, ['id' => !empty(request('id')) ? request('id') : null], $data);

        $message = config(
            'constants.add'
        );
        if (request('id')) {
            $message = config('constants.update');
        }
        session()->flash('message', $message);
        return redirect('transporter/list');
    }


    public function edit($id)
    {
        $pageTitle = 'Update Transporter';

        $transporter = Transporter::find($id);
        $permission = $this->permissionService->getUserPermission(Auth::user()->id, '13');

        return view('transporters.create', compact('transporter','pageTitle', 'permission'));
    }


    public function destroy()
    {
        return $this->commonService->deleteResource(Transporter::class);
    }
}
