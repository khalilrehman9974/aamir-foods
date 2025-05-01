<?php

namespace App\Services;

/*
 * Class CoaDetailAccountService
 * @package App\Services
 * */

use Carbon\Carbon;
use App\Models\Area;
use App\Models\CoaControlHead;
use App\Models\SaleMan;
use App\Models\PriceTag;
use App\Models\CoaSubHead;
use App\Models\CoaSubSubHead;
use App\Models\CoaDetailAccount;
use Illuminate\Support\Facades\DB;
use App\Models\CoaDetAccountDetail;
use App\Models\DetailAccountPrices;
use App\Models\CoaDetailAccountArea;
use Illuminate\Support\Facades\Auth;
use App\Models\DetailAccountProducts;
use App\Models\CoaInventorySubSubHead;
use App\Models\CoaDetailAccountSectors;
use App\Models\CoaInventoryDetailAccount;
use App\Models\CoaMainHead;

class CoaDetailAccountService
{

    protected $commonService;

    public function __construct(CommonService $commonService)
    {
        $this->commonService = $commonService;
    }

    public function getListOfDetailAccountsOld($param = null)
    {
        $q = CoaDetailAccount::with('getMainHead', 'getControlHead', 'getSubHead', 'getSubSubHead', 'SaleMan');
        if (!empty($param)) {
            $q->where('account_name', 'LIKE', '%' . $param . '%');
        }
        $detailAccounts = $q->orderBy('id', 'DESC')->paginate(config('constants.PER_PAGE'));

        return $detailAccounts;
    }


    public function getListOfDetailAccounts($request)
    {
        $q = CoaDetailAccount::query();

        if (!empty($request['mainHead_id'])) {
            $q->where('main_head', $request['mainHead_id']);
        } elseif (!empty($request['controlHead_id'])) {
            $q->where('control_head', $request['controlHead_id']);
        } elseif (!empty($request['subHead_id'])) {
            $q->where('sub_head', $request['subHead_id']);
        } elseif (!empty($request['subSubHead_id'])) {
            $q->where('sub_sub_head', $request['subSubHead_id']);
        } elseif (!empty($request['account_name'])) {
            $q->where('account_name', $request['account_name']);
        }

        $detailAccounts = $q->with('getMainHead', 'getControlHead', 'getSubHead', 'getSubSubHead', 'SaleMan')->orderBy('id', 'DESC')->paginate(config('constants.PER_PAGE'));
        return $detailAccounts;
    }

    public function getListOfDetailAccountsPrice($request)
    {
        $q = DetailAccountProducts::query();

        if (!empty($request['detail_account_id'])) {
            $q->where('detail_account_id', $request['detail_account_id']);
        } elseif (!empty($request['master_third_level'])) {
            $q->where('master_third_level', $request['master_third_level']);
        } elseif (!empty($request['master_price_tag'])) {
            $q->where('master_price_tag', $request['master_price_tag']);
        }  elseif (!empty($request['product_id'])) {
            $q->where('product_id', $request['product_id']);
        }

        $detailAccountsProducts = $q->with('detailAccountCode', 'priceTag', 'getProducts', 'getCoaFourthLevel')->orderBy('id', 'DESC')->paginate(config('constants.PER_PAGE'));
        return $detailAccountsProducts;
    }




    public function getSubSubHeadsBySubHead($subHead)
    {
        return CoaSubSubHead::where('sub_head', $subHead)->pluck('account_name', 'id'); // change Here
    }

    public function generateDetailAccountCode($subSubHeadCode)
    {
        $getDetailAccount = CoaDetailAccount::max('id');
        if ($getDetailAccount) {
            return $getDetailAccount + 1;
        }
        return 1;
    }


    public function DropDownData()
    {
        $result = [
            'saleMans' => SaleMan::pluck('name', 'id'),
            'invetoryThirdLevel' => CoaSubSubHead::pluck('account_name', 'id'),
            'products' => CoaInventoryDetailAccount::pluck('name', 'id'),
            'priceTags' => PriceTag::pluck('name', 'id'),
            'mainHeads' => CoaMainHead::pluck('account_name', 'id'),
            'controlHeads' => CoaControlHead::pluck('account_name', 'id'),
            'subHeads' => CoaSubHead::pluck('account_name', 'id'),
            'subSubHeads' => CoaSubSubHead::pluck('account_name', 'id'),
            'parties' => CoaDetailAccount::pluck('account_name', 'id'),

        ];

        return $result;
    }


    public function prepareMainAccountData($request)
    {
        return [
            'main_head' => $request->main_head,
            'control_head' => $request->control_head,
            'sub_head' => $request->sub_head,
            'sub_sub_head' => $request->sub_sub_head,
            'account_code' => $request->account_code,
            'account_name' => $request->account_name,
            'saleMan_id' => $request->saleMan_id,
            'product_id' => $request->product_id,
            'price' => $request->price,
            'discount' => $request->discount,
            'scheme' => $request->scheme,
            'commision' => $request->commision,
            'mode' => $request->mode,
            'status' => $request->status,
            'created_by' => Auth::user()->id,
            'updated_by' => Auth::user()->id
        ];
    }

    public function prepareAdditionalInformationData($request, $detailAccountMasterInsert)
    {
        return [
            'address' => $request['address'],
            'remarks' => $request['remarks'],
            'cnic' => $request['cnic'],
            'contact_no_1' => $request['contact_no_1'],
            'contact_no_2' => $request['contact_no_2'],
            'email' => $request['email'],
            'opening_balance' => $request['opening_balance'],
            'credit_limit' => $request['credit_limit'],
            'credit_days' => $request['credit_days'],
            'created_by' => Auth::user()->id,
            'updated_by' => Auth::user()->id,
            'det_account_code' => $detailAccountMasterInsert
        ];
    }

    /*
    * Store company data.
    * @param $model
    * @param $where
    * @param $data
    *
    * @return object $object.
    * */
    public function findUpdateOrCreate($model, array $where, array $data)
    {
        $object = $model::firstOrNew($where);

        foreach ($data as $property => $value) {
            $object->{$property} = $value;
        }
        $object->save();

        return $object;
    }

    public function prepareDetailAccountMasterData($request)
    {
        $session = $this->commonService->getSession();
        return [
            'main_head' => $request['main_head'],
            'business_id' => $session->business_id,
            'f_year_id' => $session->financial_year,
            'control_head' => $request['control_head'],
            'sub_head' => $request['sub_head'],
            'sub_sub_head' => $request['sub_sub_head'],
            'account_code' => $request['account_code'],
            'account_name' => $request['account_name'],
            'saleMan_id' => $request['saleMan_id'],
            'commision' => $request['commision'],
            'mode' => $request['mode'] ?? null,
            'status' => $request['status'] ?? null,
            'created_by' => Auth::user()->id,
            'updated_by' => Auth::user()->id
        ];
    }

    public function prepareDetailAccountSectorsData($request, $detailAccountMasterInsert)
    {

        return [
            'sector_id' => $request['sector_id'] ?? null,
            'master_account_id' => $detailAccountMasterInsert,
        ];
    }

    /*
     * Save sale data.
     * @param: $data
     * */
    public function saveDetailAccountSectors($data)
    {

        if (empty($data['sector_id'])) {
            $rec['sector_id'] = null;
            $rec['master_account_id'] = $data['master_account_id'];
            CoaDetailAccountSectors::create($rec);
        } else {
            foreach ($data['sector_id'] as $key => $value) {
                if (!empty($data['sector_id'][$key])) {
                    $rec['sector_id'] = $data['sector_id'][$key];
                    $rec['master_account_id'] = $data['master_account_id'];
                    CoaDetailAccountSectors::create($rec);
                }
            }
        }
    }

    public function prepareDetailAccountAreasData($request, $detailAccountMasterInsert)
    {
        return [
            'area_id' => $request['area_id'] ?? null,
            'master_account_id' => $detailAccountMasterInsert,
        ];
    }

    /*
     * Save sale data.
     * @param: $data
     * */
    public function saveDetailAccountAreas($data)
    {

        if (empty($data['area_id'])) {
            $rec['area_id'] = null;
            $rec['sector_id'] = null;
            $rec['master_account_id'] = $data['master_account_id'];
            CoaDetailAccountArea::create($rec);
        } else {
            foreach ($data['area_id'] as $key => $value) {
                if (!empty($data['area_id'][$key])) {
                    $rec['area_id'] = $data['area_id'][$key];
                    $arrayId = $rec['area_id'];
                    $rec['sector_id'] = Area::where("id", $arrayId)->value("sector_id");
                    $rec['master_account_id'] = $data['master_account_id'];
                    CoaDetailAccountArea::create($rec);
                }
            }
        }
    }

    public function prepareDetailAccountDetailData($request, $detailAccountMasterInsert)
    {
        return [
            'inventory_third_level' => $request['inventory_third_level'] ?? null,
            'price_tag_id' => $request['price_tag_id'] ?? null,
            'coa_detail_account_code' => $detailAccountMasterInsert,
        ];
    }

    /*
     * Save sale data.
     * @param: $data
     * */
    public function saveDetailAccount($data)
    {

        if (empty($data['inventory_third_level'])) {
            $rec['inventory_third_level'] = null;
            $rec['price_tag_id'] = null;
            $rec['coa_detail_account_code'] = null;
            DetailAccountPrices::create($rec);
        } else {
            foreach ($data['inventory_third_level'] as $key => $value) {
                if (!empty($data['inventory_third_level'][$key])) {
                    $rec['inventory_third_level'] = $data['inventory_third_level'][$key];
                    $rec['price_tag_id'] = $data['price_tag_id'][$key];
                    $rec['coa_detail_account_code'] = $data['coa_detail_account_code'];
                    DetailAccountPrices::create($rec);
                }
            }
        }
    }

    public function prepareDetailAccountProductData($request, $detailAccountMasterInsert)
    {


        return [
            'product_id' => $request['product_id'] ?? null,
            'master_price_tag' => $request['master_price_tag'] ?? null,
            'master_third_level' => $request['master_third_level'] ?? null,
            'price' => $request['price'] ?? null,
            'scheme' => $request['scheme'] ?? null,
            'discount' => $request['discount'] ?? null,
            'detail_account_id' => $detailAccountMasterInsert,
        ];
    }

    /*
     * Save sale data.
     * @param: $data
     * */
    public function saveDetailAccountProducts($data)
    {
        // dd($data);

        if (empty($data['master_price_tag'])) {
            $rec['product_id'] = null;
            $rec['master_price_tag'] = null;
            $rec['master_third_level'] = null;
            $rec['price'] = null;
            $rec['scheme'] = null;
            $rec['discount'] = null;
            $rec['detail_account_id'] = $data['detail_account_id'];
            DetailAccountProducts::create($rec);
        } else {
            foreach ($data['master_price_tag'] as $key => $value) {
                if (!empty($data['master_price_tag'][$key])) {
                    $rec['product_id'] = $data['product_id'][$key];
                    $rec['master_price_tag'] = $data['master_price_tag'][$key];
                    $rec['master_third_level'] = $data['master_third_level'][$key];
                    $rec['price'] = $data['price'][$key];
                    $rec['scheme'] = $data['scheme'][$key];
                    $rec['discount'] = $data['discount'][$key];
                    $rec['detail_account_id'] = $data['detail_account_id'];
                    DetailAccountProducts::create($rec);
                }
            }
        }
    }

    public function prepareAccountCreditData($request, $detailAccountMasterId)
    {
        $creditValue = abs($request['opening_balance']);
        return [
            'date' => Carbon::now()->format('Y-m-d'),
            'invoice_id' => $detailAccountMasterId,
            'party_id' =>  $detailAccountMasterId,
            'document_number' => 'OPENING BALANCE',
            'rate' => config('constants.ZERO'),
            'bilty_no' => null,
            'transporter_id' => null,
            'total_quantity' => config('constants.ZERO'),
            'measurementType' => config('constants.ZERO'),
            'bags' => config('constants.ZERO'),
            'description' => 'OPENING BALANCE',
            'debit' => config('constants.ZERO'),
            'credit' =>  $creditValue ?? 0,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    public function prepareAccountDebitData($request, $detailAccountMasterId)
    {
        $debitValue = abs($request['opening_balance']);
        return [
            'date' => Carbon::now()->format('Y-m-d'),
            'invoice_id' => $detailAccountMasterId,
            'party_id' =>  '107',
            'document_number' => 'OPENING BALANCE',
            'rate' => config('constants.ZERO'),
            'bilty_no' => null,
            'transporter_id' => null,
            'total_quantity' => config('constants.ZERO'),
            'measurementType' => config('constants.ZERO'),
            'bags' => config('constants.ZERO'),
            'description' => 'Opening Balance of ' . $request['account_name'],
            'debit' => $debitValue ?? 0,
            'credit' =>  config('constants.ZERO'),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }


    public function prepareDetailAccountCreditData($request, $detailAccountMasterId)
    {
        $creditValue = abs($request['opening_balance']);

        return [
            'date' => Carbon::now()->format('Y-m-d'),
            'invoice_id' => $detailAccountMasterId,
            'party_id' =>  '107',
            'document_number' => 'OPENING BALANCE',
            'rate' => config('constants.ZERO'),
            'bilty_no' => null,
            'transporter_id' => null,
            'total_quantity' => config('constants.ZERO'),
            'measurementType' => config('constants.ZERO'),
            'bags' => config('constants.ZERO'),
            'description' => 'Opening Balance of ' . $request['account_name'],
            'debit' => config('constants.ZERO'),
            'credit' =>  $creditValue ?? 0,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    public function prepareDetailAccountDebitData($request, $detailAccountMasterId)
    {
        $debitValue = abs($request['opening_balance']);
        return [
            'date' => Carbon::now()->format('Y-m-d'),
            'invoice_id' => $detailAccountMasterId,
            'party_id' =>  $detailAccountMasterId,
            'document_number' => 'OPENING BALANCE',
            'rate' => config('constants.ZERO'),
            'bilty_no' => null,
            'transporter_id' => null,
            'total_quantity' => config('constants.ZERO'),
            'measurementType' => config('constants.ZERO'),
            'bags' => config('constants.ZERO'),
            'description' => 'OPENING BALANCE',
            'debit' => $debitValue ?? 0,
            'credit' =>  config('constants.ZERO'),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    public function prepareDetailAccountPricesData($request)
    {
        dd($request);

        // $productId = null;

        if ($request['product_id'] === 'select-all') {
            $inventoryAccounts = CoaInventoryDetailAccount::where('sub_sub_head', $request['master_third_level'])
                ->where('priceTag_id', $request['master_price_tag'])
                ->get();
            dd($inventoryAccounts);
            $productId = null;
        } else {
            $productId = $request['product_id'];
        }

        return [
            'detail_account_id' => $request['detail_account_id'],
            'master_price_tag' => $request['master_price_tag'],
            'master_third_level' => $request['master_third_level'],
            'product_id' => $productId,
            'price' => $request['price'] ?? null,
            'discount' => $request['discount'] ?? null,
            'scheme' => $request['scheme'],
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }


    // public function prepareProductAssingData($request, $masterId)
    // {

    //     $productIds = CoaInventoryDetailAccount::whereNull('deleted_at')->pluck('id')->toArray();

    //     return [
    //         'detail_account_id' => $masterId,
    //         'master_price_tag' => $request['priceTag_id'],
    //         'master_third_level' => $request['sub_sub_head'],
    //         'product_id' => $productIds,
    //         'price' => $request['rate'],
    //         'discount' => config('constants.ZERO'),
    //         'scheme' => config('constants.ZERO'),
    //         'created_at' => now(),
    //         'updated_at' => now()
    //     ];
    // }

    public function prepareProductAssingData($request, $detailAccountId)
    {
        // Fetch all products that are not soft-deleted
        $products = CoaInventoryDetailAccount::whereNull('deleted_at')->get();
        // dd($products);
        $entries = [];

        foreach ($products as $product) {
            $entries[] = [
                'detail_account_id' => $detailAccountId,
                'master_price_tag' => $product['priceTag_id'], // assuming a single value
                'master_third_level' => $product['sub_sub_head'], // assuming a single value
                'product_id' => $product->id,
                'price' => $product['rate'], // common rate for all products
                'discount' => config('constants.ZERO'),
                'scheme' => config('constants.ZERO'),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }


        // Insert all prepared entries into the database
        DetailAccountProducts::insert($entries);
    }


    public function ProductAssing($data)
    {
        foreach ($data['detail_account_id'] as $key => $value) {
            if (!empty($data['detail_account_id'][$key])) {
                $rec['detail_account_id'] = $data['detail_account_id'][$key];
                $rec['master_price_tag'] = $data['master_price_tag'];
                $rec['master_third_level'] = $data['master_third_level'];
                $rec['product_id'] = $data['product_id'];
                $rec['price'] = $data['price'];
                $rec['discount'] = $data['discount'];
                $rec['scheme'] = $data['scheme'];

                DetailAccountProducts::create($rec);
            }
        }
    }
}
