<?php

namespace App\Http\Controllers;

use session;
use Illuminate\Http\Request;
use App\Services\CommonService;
use App\Models\StoreReturnDetail;
use App\Models\StoreReturnMaster;
use Illuminate\Support\Facades\DB;
use App\Services\StoreReturnService;

class StoreReturnController extends Controller
{
    protected $commonService;
    private $storeReturnService;

    public function __construct(CommonService $commonService, StoreReturnService $storeReturnService)
    {
        $this->storeReturnService = $storeReturnService;
        $this->commonService = $commonService;
    }

    public function index()
    {
        $pageTitle = 'List of Store Returns';
        $request = request()->all();
        $storeReturns = $this->storeReturnService->search($request);
        $param = request()->param;

        return view('store-return.index', compact('storeReturns','param', 'pageTitle'));
    }

    public function create()
    {
        $pageTitle = 'Create Store Return';
        $dropDownData = $this->storeReturnService->DropDownData();
        $storeReturns = StoreReturnDetail::where('store_return_master_id')->get();
        return view('store-return.create', compact('pageTitle','storeReturns', 'dropDownData'));
    }

    public function store(Request $request)
    {
        $session = $this->commonService->getSession();

        DB::beginTransaction();
        // try {
        $storeReturnMasters = $this->storeReturnService->prepareStoreReturnMasterData($request);
        $storeReturnMasterInsert = $this->storeReturnService->findUpdateOrCreate(StoreReturnMaster::class, ['id' => ''], $storeReturnMasters);
        $this->storeReturnService->saveStoreReturn($request, $storeReturnMasterInsert->id);

        DB::commit();
        // } catch (\Exception $e) {
        //     DB::rollback();
        //     return redirect('storeReturn/create')->with('error', $e->getMessage());
        // }
        return redirect('storeReturn/list')->with('message', config('constants.add'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $pageTitle = 'Update Store Returns';
        $dropDownData = $this->storeReturnService->DropDownData();
        $storeReturn = StoreReturnMaster::find($id);
        $storeReturns = StoreReturnDetail::where('store_return_master_id', $id)->get();
        if (empty($storeReturn)) {
            $message = config('constants.wrong');
        }

        return view('store-return.create', compact('dropDownData','storeReturn', 'storeReturns','pageTitle'));
    }

          /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        DB::beginTransaction();
        try {
            $request = request()->all();
            $storeReturnMasters = $this->storeReturnService->prepareStoreReturnMasterData($request);
            $storeReturnMasterInsert = $this->storeReturnService->findUpdateOrCreate(StoreReturnMaster::class, ['id' => request('id')], $storeReturnMasters);
            $this->storeReturnService->saveStoreReturn($request, $storeReturnMasterInsert->id);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            return redirect('storeReturn/create')->with('error', $e->getMessage());
        }

        return redirect('storeReturn/list')->with('message', config('constants.update'));
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // try {
        //     $deleteMaster = StoreReturnMaster::where('id', request()->id)->delete();
        //     $deleteDetail = StoreReturnDetail::where('store_return_master_id', request()->id)->delete();

        //     DB::commit();
        //     // && $deleteStock && $accountEntryDetail
        //     if ($deleteMaster && $deleteDetail) {
                return $this->commonService->deleteResource(StoreReturnMaster::class);
                // , StoreReturnDetail::class
        //     }
        // } catch (\Exception $e) {
        //     DB::rollback();
        //     return redirect('storeReturn/list')->with('error', $e->getMessage());
        // }
    }
}
