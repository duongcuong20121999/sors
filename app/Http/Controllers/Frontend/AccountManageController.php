<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class AccountManageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        $currentRole = $request->input('role', 'all');
        $currentPage = $request->input('page', 1);

        $users = User::with('roles');
        if ($currentRole !== 'all') {
            $users->whereHas('roles', function ($q) use ($currentRole) {
                $q->where('id', $currentRole);
            });
        }

        $users = $users->paginate(10, ['*'], 'page', $currentPage);


        $roles = Role::all();


        return view('frontend.account-manage.index', compact('users', 'roles', 'currentRole', 'currentPage'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $currentRole = $request->input('role', 'all');
        $currentPage = $request->input('page', 1);

        $users = User::with('roles');
        if ($currentRole !== 'all') {
            $users->whereHas('roles', function ($q) use ($currentRole) {
                $q->where('id', $currentRole);
            });
        }

        $users = $users->paginate(10, ['*'], 'page', $currentPage);


        $roles = Role::all();

        return view('frontend.account-manage.create', compact('users', 'roles', 'currentRole', 'currentPage'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        // dd($request->has('is_active'));
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'cf_password' => 'required|same:password',
            'zalo_id' => 'nullable|string|max:50',
            'description_service' => 'nullable|string',
            'roles' => 'nullable|array',
        ], [
            'name.required' => 'Họ và tên không được để trống',
            'email.required' => 'Email không được để trống',
            'email.unique' => 'Email đã tồn tại',
            'password.required' => 'Mật khẩu không được để trống',
            'cf_password.same' => 'Mật khẩu xác nhận không khớp',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Vui lòng kiểm tra lại các trường bắt buộc.');
        }


        $avatarPath = null;
        if ($request->hasFile('avatar')) {
            $file = $request->file('avatar');
            $extension = $file->getClientOriginalExtension();
            $filename = time() . '_' . pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME) . '.' . $extension;

            $file->move(public_path('storage/avatar_user'), $filename); // Ví dụ: lưu vào /storage/avatar

            $avatarPath = 'storage/avatar_user/' . $filename;
        }


        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'zalo_id' => $request->zalo_id,
            'description_service' => $request->description_service,
            'avatar' => $avatarPath,
            'is_active' => $request->has('is_active') ? 1 : 0,
        ]);


        if ($request->has('roles')) {
            $user->assignRole($request->roles);
        }


        $notification = [
            'message' => 'Tạo tài khoản thành công!',
            'alert-type' => 'success',
        ];

        return redirect()->route('accounts-manager.index')->with($notification);
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
    public function edit(string $id, Request $request)
    {
        $user_data = User::with('roles')->findOrFail($id);
        $currentRole = $request->input('role', 'all');
        $currentPage = $request->input('page', 1);

        $users = User::with('roles');
        if ($currentRole !== 'all') {
            $users->whereHas('roles', function ($q) use ($currentRole) {
                $q->where('id', $currentRole);
            });
        }
        $users = $users->paginate(10, ['*'], 'page', $currentPage);

        $roles = Role::all();

        return view('frontend.account-manage.edit', compact('roles', 'users', 'user_data', 'currentRole', 'currentPage'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);


        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'password' => 'nullable|min:6',
            'cf_password' => 'same:password',
        ], [
            'name.required' => 'Tên không được để trống',
            'password.min' => 'Mật khẩu tối thiểu 6 ký tự',
            'cf_password.same' => 'Mật khẩu xác nhận không khớp',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Vui lòng kiểm tra lại các trường bắt buộc.');
        }


        $user->name = $request->input('name');
        $user->description_service = $request->input('description_service');
        $user->is_active = $request->has('is_active');


        if ($request->filled('password')) {
            $user->password = Hash::make($request->input('password'));
        }


        if ($request->hasFile('avatar')) {
            $file = $request->file('avatar');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('storage/avatar_user'), $filename);
            $user->avatar = '/storage/avatar_user/' . $filename;
        }

        $user->save();


        if ($request->has('roles')) {
            $roleIds = $request->input('roles');
            $roleNames = Role::whereIn('id', $roleIds)->pluck('name')->toArray();
            $user->syncRoles($roleNames);
        } else {
            $user->syncRoles([]);
        }

        $notification = [
            'message' => 'Cập nhật tài khoản thành công!',
            'alert-type' => 'success',
        ];

        return redirect()->route('accounts-manager.index')
            ->with($notification);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function filter(Request $request)
    {
        $roleId = $request->input('role');

        $users = User::with('roles');

        if ($roleId && $roleId !== 'all') {
            $users->whereHas('roles', function ($q) use ($roleId) {
                $q->where('id', $roleId);
            });
        }

        $users = $users->paginate(10);
        $roles = Role::all(); // 👈 THÊM DÒNG NÀY

        // Nếu là Ajax request, trả về danh sách thôi
        if ($request->ajax()) {
            return response()->json([
                'users' => view('frontend.account-manage.components.user-list', compact('users'))->render(),
                'pagination' => view('frontend.account-manage.components.custom-pagination', compact('users'))->render(),
            ]);
        }

        // Trả view gốc với cả roles
        return view('frontend.account-manage.index', compact('users', 'roles'));
    }
}
