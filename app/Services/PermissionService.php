<?php

namespace App\Services;

/*
 * Class PermissionService
 * @package App\Services
 * */

use App\Models\Menu;
use App\Models\Permission;
use Illuminate\Support\Facades\Auth;

class PermissionService
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

        foreach ($data as $property => $value) {
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

        return $data;
    }

    /*
     * Get Bank Payment Vouchers list.
     * @return object $bpvList.
     * *
     */
    public function getPermissionsList()
    {
        $bpvList = Permission::with([ 'bank'])->paginate(Permission::PER_PAGE);
        return $bpvList;
    }

    /*
     * Get Menus list.
     * @return object $menusList.
     * *
     */
    public function getMenusList()
    {
        $menusList = Menu::all();
        return $menusList;
    }

    /*
     * Get User Permission.
     * @return object permission.
     * *
     */
    public function getUserPermission($userId, $menuId)
    {
        return Permission::where('user_id', $userId)->where('menu_id', $menuId)->first();
    }

}
