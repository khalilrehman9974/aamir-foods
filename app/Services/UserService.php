<?php

namespace App\Services;

    /*
     * Class UserService
     * @package App\Services
     * */

    use App\Models\User;

    class UserService
{


    /*
    * Get List of users.
    * @return object $users.
    * *
    */


    public function getUsersList($request)
    {
        $q = User::query();
        if (!empty($request['param'])) {
            $q = User::where('name', 'like', '%' . $request['param'] . '%');
        }
        $users = $q->orderBy('name', 'ASC')->paginate(config('constants.PER_PAGE'));

        return $users;
    }

}
