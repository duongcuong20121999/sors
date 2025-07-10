<?php

namespace App\Http\Middleware;

use App\Models\UserLog;
use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class LogUserActions
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Tiếp tục xử lý request
        $response = $next($request);

        // Không lọc theo Auth, log tất cả request (trừ API nếu muốn)
        if (!$request->is('api/*')) {
            $user = Auth::user();

            UserLog::create([
                'citizen_name' => $user->name ?? 'Guest',
                'user_id'      => $user->id ?? 'N/A',
                'action'       => $request->method() . ' ' . $request->path(),
                'details'      => [
                    'CitizenName' => $user->name ?? 'Guest',
                    'ZaloId'      => $user->zalo_id ?? 'N/A',
                    'action'      => $request->path(),
                    'createdDate' => Carbon::now()->format('Y-m-d H:i:s'),
                    'updateDate'  => Carbon::now()->format('Y-m-d H:i:s'),
                ],
            ]);
        }

        return $response;
    }
}
