<?php
namespace App\Services;

use App\Models\Product;

class ProductService
{
    const PER_PAGE = 10;
    const PRODUCT_SAVED = 'Product saved successfully';
    const PRODUCT_UPDATED = 'Product updated successfully';
    const PRODUCT_DELETED = 'Product is deleted successfully';
    const SOME_THING_WENT_WRONG = 'Oops!Something went wrong';

    protected $commonService;

    public function __construct(CommonService $commonService)
    {
        $this->commonService = $commonService;
    }

    /*
     * Get contract by id.
     * @param $id
     * */
    public function getById($id)
    {
        return Product::leftjoin('brands', 'brands.id', '=', 'products.brand_id')
            ->select('products.*', 'brands.name as brandName')
            ->where('products.id', $id)
            ->first();
    }

    /*
     * Advance Search data on issuance records.
     * @queries: $queries
     * @return: object
     * */
    public function searchProduct($request)
    {
        $products = [];
        if (!empty($request['param'])) {
            $query = Product::where('name', 'like', '%' . $request['param'] . '%');
            $products = $query->get();
        }else{
            $products = Product::get();
        }

        return $this->commonService->paginate($products, Self::PER_PAGE);
    }
}
