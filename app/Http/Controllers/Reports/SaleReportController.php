<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Services\SaleService;
use Illuminate\Http\Request;

class SaleReportController extends Controller
{
    protected $saleService;

    public function __construct(SaleService $saleService){
        $this->saleService = $saleService;
    }

    public function dailySaleReport(Request $request) {
        $th
    }
}
