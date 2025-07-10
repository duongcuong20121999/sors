<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $roles = Role::all();
        $permissions = Permission::all();
        $groupedPermissions = $permissions->groupBy('name_role');

        // Lấy tối đa 6 nhóm quyền
        $groupedPermissions = $groupedPermissions->take(6);

        // Chia thành 2 cột, mỗi cột 3 item
        $columns = $groupedPermissions->chunk(3);

        return view('frontend.user-roles.index', compact('roles', 'columns'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = Role::all();
        $permissions = Permission::all();
        $groupedPermissions = $permissions->groupBy('name_role');


        $groupedPermissions = $groupedPermissions->take(6);

       
        $columns = $groupedPermissions->chunk(3);
        return view('frontend.user-roles.create', compact('roles', 'columns'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        // Validate the request
        $request->validate([
            'name' => 'required|unique:roles,name',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
            'permission_groups' => 'nullable|array',  // Permission group
        ], [
            'name.required' => 'Tên vai trò không được để trống.',
            'name.unique' => 'Tên vai trò đã tồn tại trên hệ thống.',
        ]);

        // Create the role
        $role = Role::create([
            'name' => $request->name,
            'description' => $request->description,
            'is_active' => $request->is_active ? $request->is_active : false,
            'created_by' => $user->role,
            'guard_name' => 'web',
        ]);


        if ($request->has('permission_groups')) {
        
            $permissions = collect(config('group_permissions'))
                ->filter(function ($item) use ($request) {
                    return in_array($item['code'], $request->permission_groups);
                })
                ->pluck('permissions') 
                ->flatten() 
                ->unique(); 
             

            // Assign permissions to the role
            $role->givePermissionTo($permissions);
        }

        // Return success notification
        $notification = [
            'message' => 'Tạo vai trò thành công!',
            'alert-type' => 'success',
        ];

        return redirect()->route('user-roles-manager.index')->with($notification);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $role_update = Role::findOrFail($id); 
        $roles = Role::all();
    
      
        $assignedPermissions = $role_update->permissions;
        $assignedPermissionNames = $assignedPermissions->pluck('name')->toArray(); 
    
  
        $listRoleGroups = config('group_permissions');
    
      
        foreach ($listRoleGroups as $key => $listRoleGroup) {
            $permissions = $listRoleGroup["permissions"];
            
           
            if (collect($permissions)->every(fn($perm) => in_array($perm, $assignedPermissionNames))) {
                $listRoleGroups[$key]['selected'] = true;
            } else {
                $listRoleGroups[$key]['selected'] = false;
            }
        }
    
  
        $columns = array_chunk($listRoleGroups, ceil(count($listRoleGroups) / 2));
    
        return view('frontend.user-roles.edit', compact(
            'role_update',
            'roles',
            'columns',
            'assignedPermissionNames'
        ));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
{
    $user = Auth::user();
    $role = Role::findOrFail($id);

    $request->validate([
        'name' => 'required|unique:roles,name,' . $id,
        'description' => 'nullable|string',
        'is_active' => 'nullable|boolean',
        'permission_groups' => 'nullable|array', 
    ], [
        'name.required' => 'Tên vai trò không được để trống',
        'name.unique' => 'Tên vai trò đã tồn tại trên hệ thống',
    ]);


    $role->update([
        'name' => $request->name,
        'description' => $request->description,
        'is_active' => $request->is_active ?? false,
        'created_by' => $user->role,
    ]);

   
    $listRoleGroups = config('group_permissions');

   
    $permissionsToSync = [];

    if ($request->has('permission_groups')) {
        foreach ($request->permission_groups as $groupCode) {
        
            $group = collect($listRoleGroups)->firstWhere('code', $groupCode);
            
            if ($group) {
           
                $permissionsToSync = array_merge($permissionsToSync, $group['permissions']);
            }
        }
    }


    $permissionsToSync = array_unique($permissionsToSync);


    $role->syncPermissions($permissionsToSync);

    $notification = [
        'message' => 'Cập nhật vai trò thành công!',
        'alert-type' => 'success',
    ];

    return redirect()->route('user-roles-manager.index')->with($notification);
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
