<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Cookie;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {

        $request->authenticate();


        $request->session()->regenerate();
        Cookie::queue(Cookie::forget('XSRF-TOKEN'));
        // custom 
        $listRoleGroups = config('group_permissions');
        $userId = Auth::user()->id;
        $user = User::find($userId);

        if ($user && ! $user->is_active) {
            // Đăng xuất nếu đã login rồi (an toàn)
            Auth::logout();

            // Gán thông báo vào session
            Session::flash('message', 'Bạn không có quyền đăng nhập');
            Session::flash('alert-type', 'error');

            // Redirect quay lại trang login
            return redirect()->back();
        }


        $roles = $user->roles;

        if ($roles->count() === 0) {
            Auth::logout();

            Session::flash('message', 'Bạn không có quyền đăng nhập');
            Session::flash('alert-type', 'error');

            return redirect()->back();
        };

        $permissions = [];
        if (!empty($roles)) {

            if ($roles->count() === 1 && $roles->first()->is_active === 0) {
                Auth::logout();

                Session::flash('message', 'Bạn không có quyền đăng nhập');
                Session::flash('alert-type', 'error');

                return redirect()->back();
            }
            $isActive = false;
            foreach ($roles as $role) {
                $perItems = Role::find($role->id)->permissions->pluck('name')->toArray();
                if ($perItems) $permissions = array_merge($permissions, $perItems);
                if ($role->is_active === 1) {
                    $isActive = true;
                }
            }
            if (!$isActive) {
                Auth::logout();

                Session::flash('message', 'Bạn không có quyền đăng nhập');
                Session::flash('alert-type', 'error');

                return redirect()->back();
            }

            if ($role->name == "posts.index" || $role->name == "posts.edit" || $role->name == "posts.update") {
                return redirect()->route('posts');
            }
        }



        if (!$permissions) {
            Auth::logout();

            Session::flash('message', 'Bạn không có quyền đăng nhập');
            Session::flash('alert-type', 'error');

            return redirect()->back();
        }



        foreach ($listRoleGroups as $key => $listRoleGroup) {
            $itemPermissions = $listRoleGroup["permissions"];


            if (!collect($itemPermissions)->every(fn($perm) => in_array($perm, $permissions))) {
                unset($listRoleGroups[$key]);
            }
        }

        usort($listRoleGroups, fn($a, $b) => $a['priority'] <=> $b['priority']);



        if (!empty($listRoleGroups)) {
            return redirect()->intended(route($listRoleGroups[0]['url']));
        }

        abort(403);
    }

    protected function getFallbackUrlForUser($user)
    {
        // Danh sách các route theo thứ tự từ trên xuống dưới của sidebar
        $routes = [
            'dashboard',
            'service-configurations.index',
            'posts.index',
            'accounts-manager.index',
            'user-roles-manager.index',
            'user-logs.index',
            'request-history.index',
        ];

        // Lặp qua các route, kiểm tra quyền
        foreach ($routes as $route) {
            if ($user->can($route)) {
                return route($route, [], false);
            }
        }

        // Mặc định quay lại trang chủ nếu không có quyền truy cập vào bất kỳ trang nào
        return route('home', [], false);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();
        return redirect('/');
    }
}
