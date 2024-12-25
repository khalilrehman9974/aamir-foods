<?php

namespace App\Services;



/*
     * Class BankService
     * @package App\Services
     * */

use App\Models\Area;
use App\Models\Zone;
use App\Models\Donor;
use App\Models\Sector;
use App\Models\Country;
use App\Models\SaleMan;
use App\Models\SaleManArea;
use App\Models\SaleManDetail;
use App\Models\SaleManSector;
use App\Models\SaleManZone;
use Illuminate\Support\Facades\Auth;

// use Illuminate\Support\Facades\Input;

class SaleManService
{

    const PER_PAGE = 2;

    protected $commonService;

    public function __construct(CommonService $commonService)
    {
        $this->commonService = $commonService;
    }
    /*
    * Store company data.
    * @param $model
    * @param $where
    * @param $data
    *
    * @return object $object.
    * */
    public function findUpdateOrCreate($model, array $where, array $data )
    {
        $object = $model::firstOrNew($where);

        foreach ($data as $property => $value) {
            $object->{$property} = $value;
        }
        $object->save();

        return $object;
    }

    // public function searchSaleMan($params)
    // {
    //     $q = SaleMan::query();
    //     if (!empty($param['name']))
    //     {
    //         $q->where('name', 'LIKE', '%'. $param['name'] . '%');
    //     }

    //     $area = $q->orderBy('name', 'ASC')->paginate(SaleMan::PER_PAGE);
    //     return $area;
    // }

    public function searchSaleMan($request)
    {
        $q = SaleMan::query();
        if (!empty($request['param'])) {
            $q = SaleMan::with('country','zone','sectors','area')->where('name', 'like', '%' . $request['param'] . '%');
        }
        $saleMans = $q->orderBy('name', 'ASC')->paginate(config('constants.PER_PAGE'));

        return $saleMans;
    }

    public function getCountries()
    {
        return Country::pluck('name', 'id');
    }

    public function getZones()
    {
        return Zone::pluck('name', 'id');
    }

    public function getSectors()
    {
        return Sector::pluck('name', 'id');
    }

    public function getAreas()
    {
        return Area::pluck('name', 'id');
    }


    public function prepareSaleManMasterData($request)
    {

        $session = $this->commonService->getSession();
        return [
            'name' => $request['name'],
            'email' => $request['email'],
            'country_id' => $request['country_id'],
            'designation' => $request['designation'],
            'mobile_no' => $request['mobile_no'],
            'whatsapp_no' => $request['whatsapp_no'],
            'mailing_address' => $request['mailing_address'],
            'address' => $request['address'],
            'reference' => $request['reference'],
            'business_id' => $session->business_id,
            'f_year_id' => $session->financial_year,
            'remarks' => $request['remarks'],
            'created_by' => Auth::user()->id,
            'updated_by' => Auth::user()->id
        ];
    }

    public function prepareSaleManZonesData($request, $saleManParentId)
    {
        return [
            'zone_id' => $request['zone_id'],
            'master_id' => $saleManParentId,
        ];
    }

    public function saveSaleManZones($data)
    {
        foreach ($data['zone_id'] as $key => $value) {
            if (!empty($data['zone_id'][$key])) {
                $rec['zone_id'] = $data['zone_id'][$key];
                $rec['master_id'] = $data['master_id'];
                SaleManZone::create($rec);
            }
        }
    }

    public function prepareSaleManSectorData($request, $saleManParentId)
    {
        return [
            'sector_id' => $request['sector_id'],
            'master_id' => $saleManParentId,
        ];
    }

    public function saveSaleManSectors($data)
    {
        foreach ($data['sector_id'] as $key => $value) {
            if (!empty($data['sector_id'][$key])) {
                $rec['sector_id'] = $data['sector_id'][$key];
                $rec['master_id'] = $data['master_id'];
                SaleManSector::create($rec);
            }
        }
    }

    public function prepareSaleManAreaData($request, $saleManParentId)
    {
        return [
            'area_id' => $request['area_id'],
            'master_id' => $saleManParentId,
        ];
    }

    public function saveSaleManAreas($data)
    {
        foreach ($data['area_id'] as $key => $value) {
            if (!empty($data['area_id'][$key])) {
                $rec['area_id'] = $data['area_id'][$key];
                $rec['master_id'] = $data['master_id'];
                SaleManArea::create($rec);
            }
        }
    }



    // public function search($params)
    // {
    //     $q = SaleMan::query();
    //     if (!empty($param['name']))
    //     {
    //         $q->where('name', 'LIKE', '%'. $param['name'] . '%');
    //     }



    //     $saleMan = $q->orderBy('name', 'ASC')->paginate(SaleMan::PER_PAGE);
    //     return $saleMan;
    // }

}
