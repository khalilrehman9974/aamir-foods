<?php

namespace App\Http\Controllers;

use App\Models\PriceTag;
use App\Models\CoaMainHead;
use Illuminate\Http\Request;
use App\Models\AccountLedger;
use App\Services\CommonService;
use App\Models\CoaDetailAccount;
use Illuminate\Support\Facades\DB;
use App\Models\CoaDetAccountDetail;
use App\Models\CoaInventorySubHead;
use App\Services\PermissionService;
use App\Services\UploadFileService;
use Illuminate\Support\Facades\Auth;
use App\Models\DetailAccountProducts;
use App\Models\CoaInventoryDetailAccount;
use App\Services\CoaInventorySubHeadService;
use App\Http\Requests\CoInvDetailAccountRequest;
use App\Models\InventorySubSubHeadPriceTagModel;
use App\Services\CoaInventoryDetailAccountService;

class ChartOfInvDetailAccountController extends Controller
{
    protected $coInvSubHeadService;
    protected $coInventoryDetailAccountService;
    protected $permissionService;
    protected $commonService;
    protected $uploadService;

    public function __construct(
        CoaInventorySubHeadService       $coInvSubHeadService,
        CoaInventoryDetailAccountService $coInventoryDetailAccountService,
        PermissionService                $permissionService,
        CommonService                    $commonService,
        UploadFileService                $uploadService
    ) {
        $this->coInvSubHeadService = $coInvSubHeadService;
        $this->coInventoryDetailAccountService = $coInventoryDetailAccountService;
        $this->permissionService = $permissionService;
        $this->commonService = $commonService;
        $this->uploadService = $uploadService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $pageTitle = 'List of Inventory Detail Account';
        $detailAccounts = $this->coInventoryDetailAccountService->getListOfDetailAccounts($request->search);
        $permission = $this->permissionService->getUserPermission(Auth::user()->id, '24');

        return view('chart-of-inventory.detail-account.index', compact('detailAccounts', 'permission', 'pageTitle'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $pageTitle = 'Create Inventory Detail Accounts';
        $dropDownData = $this->coInventoryDetailAccountService->DropDownData();
        $accountCode = $this->coInventoryDetailAccountService->getMaxAccountCode();
        $mainHeads = $this->commonService->getInventoryMainHeads();
        $subHeads = $this->commonService->getInventorySubHeads();
        $accountArray = [4, 6];
        $coaMainHeadAccounts = CoaMainHead::whereIn('id', $accountArray)->pluck('account_name', 'id');
        $permission = $this->permissionService->getUserPermission(Auth::user()->id, '24');

        return view('chart-of-inventory.detail-account.create', compact('permission', 'coaMainHeadAccounts', 'dropDownData', 'mainHeads', 'subHeads', 'pageTitle', 'accountCode'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */

    public function store(Request $request)
    {


        $inventoryAccount = CoaInventoryDetailAccount::where('id', $request['id'])->value('name');
        $party = CoaDetailAccount::where('account_name', $inventoryAccount)->first();
        $partyId = $party ? $party->id : null;

        $data = $request->except('_token', 'id');

        // ✅ Handle image upload
        if ($request->hasFile('image')) {
            $image = $request->file('image');

            // Get the original name of the uploaded file
            $originalName = $image->getClientOriginalName();

            // Generate a new filename with a timestamp to avoid conflicts
            $fileName = time() . '_' . $originalName;

            // Set the path where the image will be saved
            $destinationPath = public_path('resources/images/inventory');

            // Move the image to the public path
            $image->move($destinationPath, $fileName);

            // Save only the filename to the database
            $data['image'] = $fileName;
        }

        // Save inventory detail account
        $detailAccountMasterData = $this->coInventoryDetailAccountService->prepareDetailAccountMasterData($request);
        $saved = $this->coInventoryDetailAccountService->findUpdateOrCreate(
            CoaInventoryDetailAccount::class,
            ['id' => $request->id ?? null],
            array_merge($detailAccountMasterData, ['image' => $data['image'] ?? null])
        );

        // Attach products
        $partyPriceData = $this->coInventoryDetailAccountService->prepareProductAssingData($request, $saved->id);
        $this->coInventoryDetailAccountService->ProductAssing($partyPriceData);

        // Handle COA detail account
        if ($party != null) {
            $coaDetailAccount = $this->coInventoryDetailAccountService->prepareCoaDetailAccountData($request);
            CoaDetailAccount::where('id', $partyId)->update($coaDetailAccount);

            $coaDetailAccountDetail = $this->coInventoryDetailAccountService->prepareUpdatedCoaDetailAccountDetailData($request, $party);
            CoaDetAccountDetail::where('det_account_code', $partyId)->update($coaDetailAccountDetail);
        } else {
            $coaDetailAccount = $this->coInventoryDetailAccountService->prepareCoaDetailAccountData($request);
            CoaDetailAccount::insert($coaDetailAccount);

            $coaDetailAccountDetail = $this->coInventoryDetailAccountService->prepareCoaDetailAccountDetailData($request);
            CoaDetAccountDetail::insert($coaDetailAccountDetail);
        }

        // Ledger entries
        $debitAccountData = $this->coInventoryDetailAccountService->prepareDetailAccountDebitData($request, $saved->id);
        AccountLedger::insert($debitAccountData);

        $creditAccountData = $this->coInventoryDetailAccountService->prepareDetailAccountCreditData($request, $saved->id);
        AccountLedger::insert($creditAccountData);

        // Redirect with success message
        $message = $request->id ? config('constants.update') : config('constants.add');
        return redirect('co-inv-detail-account/list')->with('message', $message);
    }



    public function edit($id)
    {

        $pageTitle = 'Update Inventory Sub Head';
        $dropDownData = $this->coInventoryDetailAccountService->DropDownData();
        $detailAccount = CoaInventoryDetailAccount::find($id);
        $mainHeads = $this->commonService->getInventoryMainHeads();
        $controlHeads = $this->coInventoryDetailAccountService->getControlHeadsForMainHead($detailAccount->coa_main_head);
        $subHeads = $this->coInventoryDetailAccountService->getSubHeadsForControlHead($detailAccount->control_head);
        $subSubHeads = $this->coInventoryDetailAccountService->getSubSubHeadsBySubHead($detailAccount->sub_head);

        // $subHeads = $this->commonService->getInventorySubHeads($detailAccount->main_head);
        // $subSubHeads = $this->commonService->getInventorySubSubHeads($detailAccount->sub_head);
        $fetchPriceTags = InventorySubSubHeadPriceTagModel::where("sub_sub_head_id", $detailAccount->sub_sub_head)->get();
        $priceTagId = $fetchPriceTags->pluck('priceTag')->toArray();
        $priceTag = PriceTag::whereIn("id", $priceTagId)->get();
        $priceTags = $priceTag->pluck('name', 'id')->toArray();
        // $accountArray = [4, 6];
        // $coaMainHeadAccounts = CoaMainHead::whereIn('id', $accountArray)->pluck('account_name', 'id');




        if (!$detailAccount) {
            return abort(404);
        }
        $permission = $this->permissionService->getUserPermission(Auth::user()->id, '13');

        return view('chart-of-inventory.detail-account.create', compact('detailAccount', 'controlHeads',  'subSubHeads', 'priceTags', 'dropDownData', 'subHeads', 'mainHeads', 'permission', 'pageTitle'));
    }


    public function update(Request $request)
    {
        DB::beginTransaction();
        try {
            // Get existing inventory detail record
            $detailAccount = CoaInventoryDetailAccount::find($request->id);
            if (!$detailAccount) {
                return redirect()->back()->with('error', 'Record not found.');
            }


            $inventoryAccount = CoaInventoryDetailAccount::where('id', $request['id'])->value('name');
            $party = CoaDetailAccount::where('account_name', $inventoryAccount)->first();
            $partyId = $party ? $party->id : null;

            $data = $request->except('_token');

            // Handle image upload
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $fileName = time() . '_' . $image->getClientOriginalName();
                $data['image'] = $fileName;

                // Move file to inventory folder
                $image->move(public_path('resources/images/inventory'), $fileName);

                // Delete old image if it exists
                if (!empty($detailAccount->image)) {
                    $oldPath = public_path('resources/images/inventory/' . $detailAccount->image);
                    if (file_exists($oldPath)) {
                        unlink($oldPath);
                    }
                }
            } else {
                // Keep existing image if not replaced
                $data['image'] = $detailAccount->image;
            }

            // Update the inventory detail account
            $detailAccountMasterData = $this->coInventoryDetailAccountService->prepareDetailAccountMasterData($request);
            $detailAccountMasterData['image'] = $data['image']; // Ensure image is preserved
            $saved = $this->coInventoryDetailAccountService->findUpdateOrCreate(
                CoaInventoryDetailAccount::class,
                ['id' => $request->id],
                $detailAccountMasterData
            );


            // Update products if needed
            if ($request->update == 1) {
                DetailAccountProducts::where('product_id', $request->id)->delete();

                $partyPriceData = $this->coInventoryDetailAccountService->prepareProductAssingData($request, $saved->id);
                $this->coInventoryDetailAccountService->ProductAssing($partyPriceData);
            }

            // Update COA records
            if ($party != null) {
                $coaDetailAccount = $this->coInventoryDetailAccountService->prepareCoaDetailAccountData($request);
                CoaDetailAccount::where('id', $partyId)->update($coaDetailAccount);

                $coaDetailAccountDetail = $this->coInventoryDetailAccountService->prepareUpdatedCoaDetailAccountDetailData($request, $party);
                CoaDetAccountDetail::where('det_account_code', $partyId)->update($coaDetailAccountDetail);
            } else {
                $coaDetailAccount = $this->coInventoryDetailAccountService->prepareCoaDetailAccountData($request);
                CoaDetailAccount::insert($coaDetailAccount);

                $coaDetailAccountDetail = $this->coInventoryDetailAccountService->prepareCoaDetailAccountDetailData($request);
                CoaDetAccountDetail::insert($coaDetailAccountDetail);
            }

            // Ledger updates
            $documentNo = 'OPENING BALANCE';
            AccountLedger::where('document_number', $documentNo)
                ->where('invoice_id', $request->id)
                ->delete();

            $debitAccountData = $this->coInventoryDetailAccountService->updateDetailAccountDebitData($request, $partyId);
            AccountLedger::insert($debitAccountData);

            $creditAccountData = $this->coInventoryDetailAccountService->updateDetailAccountCreditData($request, $partyId);
            AccountLedger::insert($creditAccountData);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            return redirect('co-inv-detail-account/create')->with('error', $e->getMessage());
        }

        $message = config('constants.update');
        return redirect('co-inv-detail-account/list')->with('message', $message);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\CoaInventoryMainHead $coaInventoryMainHead
     * @return \Illuminate\Http\Response
     */
    public function destroy(CoaInventorySubHead $coaInventoryMainHead)
    {
        return $this->commonService->deleteResource(CoaInventoryDetailAccount::class);
    }

    public function getMaxDetailAccountCode($subHead)
    {
        $detailAccount = $this->coInventoryDetailAccountService->getMaxAccountCode($subHead);
        if ($detailAccount) {
            return response()->json(['status' => 'success', 'code' => $detailAccount]);
        }
        return response()->json(['status' => 'fail', 'data' => []]);
    }

    public function getSubHeadAccountsByMainHead($mainHead)
    {
        $detailAccounts = $this->coInventoryDetailAccountService->getSubHeadsByMainHead($mainHead);
        if ($detailAccounts) {
            return response()->json(['status' => 'success', 'data' => $detailAccounts]);
        }
        return response()->json(['status' => 'fail', 'data' => []]);
    }

    public function getSubSubHeadAccountsBySubHead($subHead)
    {
        $detailAccounts = $this->coInventoryDetailAccountService->getSubSubHeadsBySubHead($subHead);
        if ($detailAccounts) {
            return response()->json(['status' => 'success', 'data' => $detailAccounts]);
        }
        return response()->json(['status' => 'fail', 'data' => []]);
    }

    public function getProductDetails(Request $request)
    {
        $fetchPriceTags = InventorySubSubHeadPriceTagModel::where("sub_sub_head_id", $request->product_id)->get();
        $priceTagId = $fetchPriceTags->pluck('priceTag')->toArray();
        $data['priceTags'] = PriceTag::whereIn("id", $priceTagId)->get();

        if ($data['priceTags']->isEmpty()) {
            return response()->json(['message' => 'no data found'], 404);
        }

        return response()->json($data);
    }
}
