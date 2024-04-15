<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PurchaseDetail;
use App\Models\PurchaseMaster;
use App\Services\CommonService;
use App\Services\PurchaseService;
use Illuminate\Support\Facades\DB;

class PurchaseController extends Controller
{
    protected $commonService;
    protected $purchaseService;

    public function __construct(CommonService $commonService, PurchaseService $purchaseService)
    {
        $this->commonService = $commonService;
        $this->purchaseService = $purchaseService;

    }

    /*
     * Show page of list of sales.
     * */
    public function index()
    {
        $pageTitle = 'List Of Purchases';
        $request = request()->all();
        $purchases = $this->purchaseService->search($request);

        return view('purchases.index', compact('purchases', 'request','pageTitle'));
    }

    /*
     * Show page of create Purchase.
     * */
    public function create()
    {
        $pageTitle = 'Create Purchase';
        $dropDownData = $this->purchaseService->DropDownData();
        $purchaseDetails = PurchaseDetail::where('purchase_master_id')->get();
        return view('purchases.create', compact('pageTitle','purchaseDetails','dropDownData'));
    }

    /*
     * Save Purchase into db.
     * @param: @request
     * */
    public function store(Request $request)
    {
        $request = $request->except('_token', 'id');
        DB::beginTransaction();
        // try {

            //Insert data into purchase tables.
            $purchaseMasterData = $this->purchaseService->preparePurchaseMasterData($request);
            $purchaseMasterInsert = $this->commonService->findUpdateOrCreate(PurchaseMaster::class, ['id' => ''], $purchaseMasterData);
            $purchaseDetailData = $this->purchaseService->preparePurchaseDetailData($request, $purchaseMasterInsert->id);
            $this->purchaseService->savePurchase($purchaseDetailData);

            DB::commit();
        // } catch (\Exception $e) {
        //     DB::rollback();
        //     return redirect('purchase/create')->with('error', $e->getMessage());
        // }
        return redirect('purchase/list')->with('message', config('constants.add'));
    }

    /*
     * Show edit page.
     * */
    public function edit($id)
    {
        $pageTitle = 'Update Purchase';
        $purchase = PurchaseMaster::find($id);
        $dropDownData = $this->purchaseService->DropDownData();
        $purchaseDetails = PurchaseDetail::where('purchase_master_id', $id)->get();
        if (empty($purchase)) {
            $message = config('constants.wrong');
        }

        return view('purchases.create', compact('pageTitle','purchase','dropDownData', 'purchaseDetails'));
    }

    /*
     * Delete existing resource.
     * @param: request()->id
     * */
    public function delete()
    {
        try {
            DB::beginTransaction();
            $deleteMaster = PurchaseMaster::where('id', request()->id)->delete();
            $deleteDetail = PurchaseDetail::where('purchase_master_id', request()->id)->delete();

            DB::commit();
            // && $deleteStock && $accountEntryDetail
            if ($deleteMaster && $deleteDetail ) {
                return $this->commonService->deleteResource(PurchaseMaster::class, PurchaseDetail::class);
            }

        } catch (\Exception $e) {
            DB::rollback();
            return redirect('purchase/list')->with('error', $e->getMessage());
        }
    }

    /*
    * View sale detail.
    * @param: $id
    * */
    public function view($id)
    {
        $PurchaseMaster = $this->purchaseService->getpurchaseMasterById($id);
        $PurchaseDetail = $this->purchaseService->getPurchaseDetailById($id);
        if (empty($PurchaseMaster)) {
            $message = config('constants.wrong');
        }

        return view('purchases.view', compact('PurchaseMaster', 'PurchaseDetail'));
    }

}
