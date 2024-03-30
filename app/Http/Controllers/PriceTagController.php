<?php

namespace App\Http\Controllers;

use App\Models\PriceTag;
use Illuminate\Http\Request;
use App\Services\CommonService;
use App\Services\PriceTagService;
use App\Services\PermissionService;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\PriceTagRequest;

class PriceTagController extends Controller
{
    private $priceTagService;
    private $permissionService;
    private $commonService;

    public function __construct(PriceTagService $priceTagService, CommonService $commonService, PermissionService $permissionService)
    {
        $this->priceTagService = $priceTagService;
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
        $pageTitle = 'list Of Price Tags';
        $request = request()->all();
        $priceTags = $this->priceTagService->searchPriceTag($request);
        $permission = $this->permissionService->getUserPermission(Auth::user()->id, '24');

        return view('price-tags.index', compact('priceTags', 'pageTitle', 'permission'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $pageTitle = 'Create Price Tag';
        $permission = $this->permissionService->getUserPermission(Auth::user()->id, '24');
        return view('price-tags.create', compact('permission','pageTitle'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PriceTagRequest $request)
    {
        $data = $request->except('_token','id');
        $data['created_by'] = Auth::user()->id;
        $data['updated_by'] = Auth::user()->id;

        $this->priceTagService->findUpdateOrCreate(PriceTag::class, ['id'=>!empty(request('id')) ? request('id') : null], $data);
        $message = config(
            'constants.add'
        );
        if(request('id')){
            $message = config('constants.update');
        }
        session()->flash('message', $message);
        return redirect('priceTag/list');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\PriceTag  $priceTag
     * @return \Illuminate\Http\Response
     */
    public function show(PriceTag $priceTag)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\PriceTag  $priceTag
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $pageTitle = 'Update The Price Tag ';
        $priceTag = PriceTag::find($id);
        $permission = $this->permissionService->getUserPermission(Auth::user()->id, '13');

        return view('price-tags.create', compact('priceTag', 'pageTitle', 'permission'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\PriceTag  $priceTag
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PriceTag $priceTag)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\PriceTag  $priceTag
     * @return \Illuminate\Http\Response
     */
    public function delete()
    {
        return $this->commonService->deleteResource(PriceTag::class);
    }
}
