<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\Product;
use App\Models\SaleDetail;
use App\Models\SaleMaster;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SaleReportService
{
    const PER_PAGE = 10;

    protected $commonService;

    public function __construct(CommonService $commonService)
    {
        $this->commonService = $commonService;
    }

    /*
     * Get contract by id.
     * @param $id
     * */
    public function getSaleMasterById($id)
    {
        $dateSales = Sale::select(DB::raw('DATE(date) as sale_date'), DB::raw('SUM(amount) as total_amount'))
                ->groupBy(DB::raw('DATE(date)'))
                ->get();
    }

    /*
    * Get contract by id.
    * @param $id
    * */
    public function getSaleDetailById($id)
    {
        $dateSales = SaleDetail::select(DB::raw('DATE(date) as sale_date'), DB::raw('SUM(amount) as total_amount'))
                ->groupBy(DB::raw('DATE(date)'))
                ->get();
    }

    /*
     * Search sale record.
     * @queries: $queries
     * @return: object
     * */
    public function searchSale($request)
    {
        $query = SaleMaster::groupBy(
            'sale_masters.id',
            'sale_masters.date',
            'sale_masters.dispatch_note',
            'sale_masters.type_id',
            'sale_masters.party_id',
            'sale_masters.bilty_no',
            'sale_masters.remarks',
            'sale_masters.created_at',
            'sale_masters.deliverd_to',
            'sale_masters.updated_at',
            'sale_masters.saleman_id',
            'sale_masters.transporter_id',
            'sale_masters.total_amount',
            'sale_masters.freight',
            'sale_masters.scheme',
            'sale_masters.commission',
        );
        if (!empty($request['param'])) {
            $query = $query->where('sale_masters.id', "=", $request['param']);
        }
        //        $query->select('sale_masters.id','sale_masters.date','sale_masters.amount','sale_masters.quantity');
        $sales = $query->orderBy('id', 'DESC')->get();

        return $this->commonService->paginate($sales, Self::PER_PAGE);
    }

}
