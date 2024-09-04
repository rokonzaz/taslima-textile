<?php

namespace App\Http\Controllers;

use App\Http\Requests\CommonRequest;
use App\Models\Organization;
use App\Models\Permission;
use App\Models\Role;
use App\Models\RolePermission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\View;

class RoleController extends Controller
{
    public function index()
    {
        if(!userCan('role.view')) return view('error-page.unauthorized');
        $roles=Role::get();
        $permissions = Permission::get()->groupBy('category');
        $rolePermissions=RolePermission::get();
        return view('roles.index', compact('roles', 'permissions', 'rolePermissions'));
    }
    public function store(CommonRequest $request)
    {

        if(!userCan('role.create')) return back()->with('error', 'Unauthorized Access!');
        $name = $request->name;
        $slug = Str::slug($name);
        if(Role::where('name', $name)->orWhere('slug', $slug)->count() > 0) return back()->with('error', 'Already Exists');
        $role=new Role();
        $role->name=$name;
        $role->slug=$slug;
        if($role->save()) return back()->with('success', 'Successfully Added Role');
        else return back()->with('error', 'Error Occurred!');
    }
    public function edit(Request $request, $id)
    {
        if($request->ajax()){
            if(!userCan('role.edit')) return response()->json(['status'=>0,'msg'=>'Unauthorized Access !']);
            $role=Role::find($id);
            if($role->is_changeable){
                $html=View::make('roles.edit', compact('role'))->render();
                $response=[
                    'status'=>1,
                    'html'=>$html,
                ];
                return response()->json($response);
            }else{
                return response()->json(['status'=>0,'msg'=>'Cannot Change This Role !']);
            }

        }
        abort(403);
    }
    public function update(CommonRequest $request, $id){
        if(!userCan('role.edit')) return back()->with('error', 'Unauthorized Access!');
        $role=Role::find($id);
        $name = $request->name;
        $slug = Str::slug($name);
        if($role && $role->is_changeable){
            $isExist=Role::whereNot('id',$id)
            ->where(function($query) use($name, $slug){
                $query->where('name', $name);
                $query->orWhere('slug', $slug);
            })->count();
            if($isExist > 0) return back()->with('error', 'Already Exists');
            $role->name = $name;
            $role->slug=$slug;
            $role->is_active = $request->is_active=='on' ? 1 : 0;
            if ($role->save()) {
                return back()->with('success', "Successfully Updated Role");
            }
            return back()->with('error', "Server Error!");
        }
        return back()->with('error', "Not Found!");
    }
    public function delete($id)
    {
        if(!userCan('role.delete')) return back()->with('error', 'Unauthorized Access!');
        $role = Role::find($id);
        if($role && $role->is_changeable){
            if($id==Auth::user()->role_id) return back()->with('error', 'You can\'t delete yourself.');
            $role->delete();
            return back()->with('success', 'Role deleted successfully.');
        }
        return back()->with('error', 'You can\'t delete yourself.');
    }
    public function editRolePermission(Request $request)
    {
        if(!userCan('role.edit')) return view('error-page.unauthorized');
        $roles=Role::get();
        $permissions = Permission::get()->groupBy('category');
        $rolePermissions=RolePermission::get();
        return view('roles.edit-permission', compact('roles', 'permissions', 'rolePermissions'));
    }
    public function updateRolePermission(Request $request)
    {
        if(!userCan('role.edit')) return back()->with('error', 'Unauthorized Access!');
        $permissions = $request->permission;
        $extractedPermissions = [];
        foreach ($permissions as $roleId => $permissionIds) {
            foreach ($permissionIds as $permissionId => $status) {
                if ($status === 'on') {
                    $extractedPermissions[] = [
                        'role_id' => (int)$roleId,
                        'permission_id' => (int)$permissionId,
                    ];
                }
            }
        }
        $prevPermission=RolePermission::select('id')->pluck('id');
        if(RolePermission::insert($extractedPermissions)){
            RolePermission::whereIn('id', $prevPermission)->delete();
            return redirect()->route('roles.index')->with('success', 'Successfully Updated');
        }
    }


}
