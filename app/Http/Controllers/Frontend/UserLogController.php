<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserLog;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;

class UserLogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $roleId = $request->input('role_id');
        $date = $request->input('date');

        // Khởi tạo Query cơ bản
        $logsQuery = UserLog::query();

    
        if ($roleId !== "all" && $roleId) {
            $role = Role::find($roleId);

            if ($role) {
                $userIds = $role->users()->pluck('id')->filter()->toArray();
                if (!empty($userIds)) {
                    $logsQuery->whereIn('user_id', $userIds);
                } else {
                    $logsQuery->whereRaw('1 = 0'); // Nếu không có user nào trong role, trả về rỗng
                }
            }
        }

     
        if (!Auth::user()->can("user-logs.index")) {
            $logsQuery->where('user_id', Auth::user()->id);
        }

       
        if ($date) {
            try {
                $parsedDate = Carbon::createFromFormat('d/m/Y', $date);
                $logsQuery->whereDate('created_at', $parsedDate->format('Y-m-d'));
            } catch (\Exception $e) {
                return response()->json(['error' => 'Ngày không hợp lệ'], 400);
            }
        }

      
        $logs = $logsQuery->orderBy('created_at', 'desc')
            ->paginate(10)
            ->appends($request->query());

        $roles = Role::all();

        
        if ($request->ajax()) {
            return response()->json([
                'userLogs' => view('frontend.user-logs.components.user-logs-item', compact('logs'))->render(),
                'pagination' => view('frontend.user-logs.components.custom_pagination', compact('logs'))->render(),
            ]);
        }

     
        return view('frontend.user-logs.index', compact('logs', 'roles', 'roleId'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $log = UserLog::findOrFail($id);

        // if permission view all
        if (!Auth::user()->can("user-logs.index") && $log->zalo_id != Auth::user()->zalo_id) {
            abort(403);
        }


        $phone = Auth::user()->phone;
        return response()->json([
            'title' => "$log->citizen_name",
            'address' => $log->address,
            'action' => $log->action,
            'detail' => $log->details
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroyMultiple(Request $request)
    {
        // Lấy danh sách ID từ request
        $ids = json_decode($request->input('ids'), true);

        if (empty($ids)) {
            return redirect()->back()->with('error', 'Không có bản ghi nào được chọn.');
        }

        // Xóa các log có ID trong danh sách
        UserLog::whereIn('id', $ids)->delete();

        $notification = [
            'message' => 'Xoá Log user thành công!',
            'alert-type' => 'success',
        ];
        return redirect()->back()->with($notification);
    }

    public function getLogsByRole($roleId)
    {
        $role = Role::find($roleId);

        if (!$role) {
            return response()->json([
                'message' => 'Role not found'
            ], 404);
        }

        // Lấy tất cả zalo_id của user trong role
        $zaloIds = $role->users()->pluck('id')->filter()->toArray(); // filter() để tránh null

        // Lọc log theo zalo_id
        $logs = UserLog::whereIn('user_id', $zaloIds)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'message' => 'Logs filtered by role zalo_id',
            'data' => $logs,
        ]);
    }
}
