<?php

namespace App\Http\Controllers;

use session;
use Carbon\Carbon;
use App\Models\PackingType;
use Illuminate\Http\Request;
use App\Models\MeasurementType;
use App\Services\CommonService;
use App\Models\StoreReturnDetail;
use App\Models\StoreReturnMaster;
use Illuminate\Support\Facades\DB;
use App\Services\StoreReturnService;
use App\Models\CoaInventoryDetailAccount;

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
        $dropDownData = $this->storeReturnService->DropDownData();
        $storeReturns = $this->storeReturnService->search($request);
        $param = request()->param;

        return view('store-return.index', compact('storeReturns','dropDownData','param', 'pageTitle'));
    }

    public function create()
    {
        $pageTitle = 'Create Store Return';
        $dropDownData = $this->storeReturnService->DropDownData();

        $request = request()->all();
        $storeIssueNotes = $this->storeReturnService->search($request);
        $param = request()->param;

        $maxid = StoreReturnMaster::max('id')+ 1;
        return view('store-return.create', compact('pageTitle','maxid', 'dropDownData'));
    }

    public function store(Request $request)
    {
        // dd($request);
        $request = request()->except('id', 'token');
        // DB::beginTransaction();
        // try {
        $storeReturnMasters = $this->storeReturnService->prepareStoreReturnMasterData($request);
        $storeReturnMasterInsert = $this->storeReturnService->findUpdateOrCreate(StoreReturnMaster::class, ['id' => ''], $storeReturnMasters);
        $storeReturnDetailData = $this->storeReturnService->preparestoreReturnDetailData($request, $storeReturnMasterInsert->id);
        $this->storeReturnService->saveStoreReturn($storeReturnDetailData);

        // DB::commit();
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
        $storeReturnMaster = StoreReturnMaster::find($id);
        $date = Carbon::parse($storeReturnMaster->date)->format('d-m-Y');
        $storeReturnDetails = StoreReturnDetail::where('store_return_master_id', $id)->get();
        if (empty($storeReturn)) {
            $message = config('constants.wrong');
        }

        return view('store-return.edit', compact('dropDownData','date','storeReturnMaster', 'storeReturnDetails','pageTitle'));
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
        // DB::beginTransaction();
        // try {
            $request = request()->all();
            StoreReturnDetail::where('store_return_master_id', $request['id'])->delete();
            $storeReturnMasters = $this->storeReturnService->prepareStoreReturnMasterData($request);
            $storeReturnMasterInsert = $this->storeReturnService->findUpdateOrCreate(StoreReturnMaster::class, ['id' => request('id')], $storeReturnMasters);
            $storeReturnDetailData = $this->storeReturnService->preparestoreReturnDetailData($request, $storeReturnMasterInsert->id);
            $this->storeReturnService->saveStoreReturn($storeReturnDetailData);

        //     DB::commit();
        // } catch (\Exception $e) {
        //     DB::rollback();
        //     return redirect('storeReturn/create')->with('error', $e->getMessage());
        // }

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

    public function getProductMeasurementType($name)
    {
        $fetchMeasurementType = CoaInventoryDetailAccount::where('id', $name)->first('measurement_type_id');
        $measurement =  MeasurementType::where('id', $fetchMeasurementType->measurement_type_id)->first('name');

        if ($measurement) {
            return response()->json(['status' => 'success', 'name' => $measurement]);
        }
        return response()->json(['status' => 'fail', 'data' => []]);
    }

    public function getProductPackingType($name)
    {
        $fetchPackingType = CoaInventoryDetailAccount::where('id', $name)->first('packing_type_id');
        $packing =  PackingType::where('id', @$fetchPackingType->packing_type_id)->first('name');


        if ($packing) {
            return response()->json(['status' => 'success', 'name' => $packing]);
        }
        return response()->json(['status' => 'fail', 'data' => []]);


    }

    public function getProductSize($name)
    {
        $fetchSize = CoaInventoryDetailAccount::where('id', $name)->first('size');

        if ($fetchSize) {
            return response()->json(['status' => 'success', 'size' => $fetchSize->size]);
        }
        return response()->json(['status' => 'fail', 'data' => []]);
    }
}
