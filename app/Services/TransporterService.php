<?php

namespace App\Services;



    /*
     * Class BankService
     * @package App\Services
     * */


use App\Models\Transporter;


    // use Illuminate\Support\Facades\Input;

    class TransporterService
{

    const PER_PAGE = 10;

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
    public function findUpdateOrCreate($model, array $where, array $data)
    {
        $object = $model::firstOrNew($where);

        foreach ($data as $property => $value){
            $object->{$property} = $value;
        }
        $object->save();

        return $object;
    }


    public function searchTransporter($request)
    {
        $q = Transporter::query();
        if (!empty($request['param'])) {
            $q = Transporter::where('name', 'like', '%' . $request['param'] . '%')
             ->orWhere('contact_person', 'like', '%' . $request['param'] . '%')
                ->orWhere('city', 'like', '%' . $request['param'] . '%')
                ->orWhere('contact_number', 'like', '%' . $request['param'] . '%')
                ->orWhere('address', 'like', '%' . $request['param'] . '%');
        }
        $transporters = $q->orderBy('id', 'DESC')->paginate(config('constants.PER_PAGE'));

        return $transporters;
    }


}


