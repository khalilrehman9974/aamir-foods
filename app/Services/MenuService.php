<?php

namespace App\Services;

/*
 * Class MenuService
 * @package App\Services
 * */
use App\Models\Menu;
use App\Models\Permission;
use Illuminate\Support\Facades\Auth;

class MenuService
{
    /*
     * Get Menus list.
     * @return object $menusList.
     * *
     */
    public function getMenus()
    {
        $menus = Menu::all();
        return $menus;
    }

    /*
     * Get Menus by name.
     * @return object $name.
     * *
     */
    public function getMenuName($menuName)
    {
        return Menu::where('name', $menuName)->first();
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
