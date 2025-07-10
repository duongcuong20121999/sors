<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Spatie\Permission\Models\Role;

use Illuminate\Support\Facades\Auth;
class UserController extends Controller
{
      public function redirectToHighestPermission()
    {
        // Lấy danh sách quyền từ config
        $listRoleGroups = config('group_permissions');
        
        // Lấy thông tin user đang đăng nhập
        $user = Auth::user();
        if (!$user) {
            abort(403, 'Bạn không có quyền truy cập');
        }
        
        
        // Lấy danh sách roles của user
        $roles = $user->roles;
        
        // Lấy danh sách các quyền
        $permissions = [];
        foreach ($roles as $role) {
            $perItems = Role::find($role->id)->permissions->pluck('name')->toArray();
            if ($perItems) $permissions = array_merge($permissions, $perItems);
        }

        // Nếu user không có quyền nào, trả về lỗi 403
        if (empty($permissions)) {
            abort(403, 'Bạn không có quyền truy cập');
        }

        // Lọc những nhóm quyền mà user không có đủ
        foreach ($listRoleGroups as $key => $listRoleGroup) {
            $itemPermissions = $listRoleGroup["permissions"];
            if (!collect($itemPermissions)->every(fn($perm) => in_array($perm, $permissions))) {
                unset($listRoleGroups[$key]);
            }
        }

        // Sắp xếp theo thứ tự ưu tiên
        usort($listRoleGroups, fn($a, $b) => $a['priority'] <=> $b['priority']);

        // Redirect tới URL quyền cao nhất
        if (!empty($listRoleGroups)) {
            return redirect()->route($listRoleGroups[0]['url']);
        }

        // Nếu không có quyền nào khớp
        abort(403, 'Bạn không có quyền truy cập');
    }
}
