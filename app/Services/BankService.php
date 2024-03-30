<?php

namespace App\Services;

use App\Models\Bank;
use Symfony\Component\Console\Input\Input;

    /*
     * Class BankService
     * @package App\Services
     * */

    use App\Models\Donor;
    // use Illuminate\Support\Facades\Input;

    class BankService
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

    /*
    * Search donor.
    * @param $param
    *
    * @return object $object.
    * */
    public function search($params)
    {
        $q = Bank::query();

        if (Input::has('query'))
        {
            $q->where('name', 'LIKE', Input::get('query'));
        }

     //   $name = $params['query'];
        // $donors = $q->orderBy('name', 'ASC')->paginate(Donor::PER_PAGE);

        //     //Donor::where('name', 'LIKE', "%$name%")->orderBy('name')->paginate(Donor::PER_PAGE);

        // return $donors;
    }

}
