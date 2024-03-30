<?php

namespace App\Http\Controllers;

use App\Models\SaleMan;
use Illuminate\Http\Request;
use App\Services\CommonService;
use App\Services\SaleManService;
use App\Services\PermissionService;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\SaleManRequest;

class SaleManController extends Controller
{
    private $saleManService;
    private $permissionService;
    private $commonService;

    public function __construct(SaleManService $saleManService, CommonService $commonService, PermissionService $permissionService)
    {
        $this->saleManService = $saleManService;
        $this->permissionService = $permissionService;
        $this->commonService = $commonService;
    }

    public function index()
    {
        $pageTitle = 'List Of SaleMans';
        $request = request()->all();
        $saleMans = $this->saleManService->searchSaleMan($request);
        // $saleMans = $this->saleManService->search($request);
        $permission = $this->permissionService->getUserPermission(Auth::user()->id, '13');
        return view('sale_mans.index', compact('saleMans', 'pageTitle', 'permission'));
    }


    public function create()
    {

        $pageTitle = 'Add SaleMan';
        $permission = $this->permissionService->getUserPermission(Auth::user()->id, '13');
        return view('sale_mans.create', compact('permission','pageTitle'));
    }


    public function store(SaleManRequest $request)
    {
        $data = $request->except('_token', 'id');
        $data['created_by'] = Auth::user()->id;
        $data['updated_by'] = Auth::user()->id;
        $this->saleManService->findUpdateOrCreate(SaleMan::class, ['id' => !empty(request('id')) ? request('id') : null], $data);

        $message = config(
            'constants.add'
        );
        if (request('id')) {
            $message = config('constants.update');
        }
        session()->flash('message', $message);
        return redirect('saleMan/list');
    }


    public function edit($id)
    {
        $pageTitle = 'Update SaleMan';

        $saleMan = SaleMan::find($id);
        $permission = $this->permissionService->getUserPermission(Auth::user()->id, '13');

        return view('sale_mans.create', compact('saleMan','pageTitle', 'permission'));
    }


    public function destroy()
    {
        return $this->commonService->deleteResource(SaleMan::class);
    }
}
