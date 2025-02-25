<?php

namespace App\Services;

/*
 * Class CoaDetailAccountService
 * @package App\Services
 * */

use App\Models\Area;
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

class CoaDetailAccountService
{

    protected $commonService;

    public function __construct(CommonService $commonService)
    {
        $this->commonService = $commonService;
    }

    public function getListOfDetailAccounts($param = null)
    {
        $q = CoaDetailAccount::with('getMainHead', 'getControlHead', 'getSubHead', 'getSubSubHead', 'SaleMan');
        if (!empty($param)) {
            $q->where('account_name', 'LIKE', '%' . $param . '%');
        }
        $detailAccounts = $q->orderBy('account_name', 'ASC')->paginate(config('constants.PER_PAGE'));

        return $detailAccounts;
    }

    public function getSubSubHeadsBySubHead($subHead)
    {
        return CoaSubSubHead::where('sub_head', $subHead)->pluck('account_name', 'account_code');
    }

    public function generateDetailAccountCode($subSubHeadCode)
    {
        $getDetailAccount = CoaDetailAccount::where('sub_sub_head', $subSubHeadCode)->max('account_code');
        if ($getDetailAccount) {
            return $getDetailAccount + 1;
        }
        return 1;
    }


    public function DropDownData()
    {
        $result = [
            'saleMans' => SaleMan::pluck('name', 'id'),
            'invetoryThirdLevel' => CoaInventorySubSubHead::pluck('name', 'id'),
            'products' => CoaInventoryDetailAccount::pluck('name', 'id'),
            'priceTags' => PriceTag::pluck('name', 'id'),
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
            'cnic' => $request['cnic'],
            'contact_no_1' => $request['contact_no_1'],
            'contact_no_2' => $request['contact_no_2'],
            'email' => $request['email'],
            'opening_balance' => $request['opening_balance'],
            'credit_limit' => $request['credit_limit'],
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
}
