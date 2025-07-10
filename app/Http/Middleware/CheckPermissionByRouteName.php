<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

class CheckPermissionByRouteName
{
    public function handle($request, Closure $next)
    {

        $routeName = Route::currentRouteName();
        
        if($routeName == "user-logs.index"){
            return $next($request);
        }

        if ($routeName == "posts.store" || $routeName == "posts.update" || $routeName == "posts.create") {
            return $next($request); // Nếu là 2 route này thì bỏ qua kiểm tra quyền
        }

        if (!Auth::user()->can($routeName)) {
            abort(403, 'Bạn không có quyền truy cập!');
        }

        return $next($request);
    }
}
