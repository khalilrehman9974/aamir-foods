<?php

namespace App\Http\Controllers;

use App\Http\Requests\PermissionStoreRequest;
use App\Models\Menu;
use App\Models\Permission;
use App\Services\PermissionService;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class PermissionController extends Controller
{
    private $permissionService;

    public function __construct(PermissionService $permissionService)
    {
        $this->permissionService = $permissionService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $menus = $this->permissionService->getMenusList();
        $users = User::all();
        $user_id = request('userid');

        return view('permission.index', compact('menus', 'users', 'user_id'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(PermissionStoreRequest $request)
    {
        $permissions = $request->all();
        $menus = Menu::all();
        if(!empty($permissions['menu_access'])){
            foreach ($permissions['menu_access'] as $key => $value) {
                $menuIds[] = $key;
            }
        }
        if(!empty($permissions['select_access'])){
             foreach ($permissions['select_access'] as $key => $value) {
                $selectIds[] = $key;
            }
        }
        if(!empty($permissions['insert_access'])){
            foreach ($permissions['insert_access'] as $key => $value) {
                $insertIds[] = $key;
            }
        }
        if(!empty($permissions['edit_access'])){
            foreach ($permissions['edit_access'] as $key => $value) {
                $editIds[] = $key;
            }
        }
        if(!empty($permissions['delete_access'])){
            foreach ($permissions['delete_access'] as $key => $value) {
                $deleteIds[] = $key;
            }
        }
        $userPermission = Permission::where('user_id', $permissions['user_id'])->first();
        if($userPermission){
            Permission::where('user_id', $permissions['user_id'])->delete();
        }
        foreach ($menus as $menu) {
            $menuAccess = 1;
            $selectAccess = 1;
            $insertAccess = 1;
            $editAccess = 1;
            $deleteAccess = 1;
            if (!in_array($menu->id, !empty($menuIds) ? $menuIds : [])) {
                $menuAccess = 0;
            }
            if (!in_array($menu->id, !empty($selectIds) ? $selectIds : [])) {
                $selectAccess = 0;
            }
            if (!in_array($menu->id, !empty($insertIds) ? $insertIds : [])) {
                $insertAccess = 0;
            }
            if (!in_array($menu->id, !empty($editIds) ? $editIds : [])) {
                $editAccess = 0;
            }
            if (!in_array($menu->id, !empty($deleteIds) ? $deleteIds : [])) {
                $deleteAccess = 0;
            }
            $data['user_id'] = $permissions['user_id'];
            $data['menu_id'] = $menu->id;
            $data['menu_access'] = $menuAccess;
            $data['select_access'] = $selectAccess;
            $data['insert_access'] = $insertAccess;
            $data['edit_access'] = $editAccess;
            $data['delete_access'] = $deleteAccess;
            Permission::create($data);
        }

        Session::flash('message', 'Permission has been applied.');

        return redirect('permission/list');
    }

    public function getUserPermissions(Request $request)
    {
        $userPermission = Permission::where('user_id', $request['userid'])->get();
        $users = User::all();
        $menus = $this->permissionService->getMenusList();
        $user_id = $request['userid'];

        return view('permission.index', compact( 'users','userPermission', 'menus', 'user_id'));
    }

    public static function getPermissionByUserAndMenu($userId, $menuId, $action) {
        return Permission::select($action)
        ->where('user_id', $userId)
        ->where('menu_id', $menuId)
        ->first();
    }

}
