<?php

namespace App\Services;

    /*
     * Class FinancialYearService
     * @package App\Services
     * */

    use Carbon\Carbon;
    use Illuminate\Support\Facades\Auth;

    class FinancialYearService
{

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

    public function dataArray($request)
    {
        $data = $request->except('_token');
        $data['created_by'] = Auth::user()->id;
        $data['updated_by'] = Auth::user()->id;
        $data['start_date'] = Carbon::parse($request['start_date'])->format('Y-m-d');
        $data['end_date'] = Carbon::parse($request['end_date'])->format('Y-m-d');

        return $data;
    }


}
