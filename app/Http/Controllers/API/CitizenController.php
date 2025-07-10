<?php

// app/Http/Controllers/ServiceController.php
namespace App\Http\Controllers\API;



use App\Http\Controllers\Controller;
use App\Services\CitizenService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Citizen;
use Carbon\Carbon;

class CitizenController extends Controller
{
    protected $citizenService;
    public function __construct(CitizenService $citizenService)
    {
        $this->citizenService = $citizenService;
    }
    public function index()
    {
        return response()->json($this->citizenService->getAll());
    }
    public function store(Request $request)
    {


        $data = $request->validate([
            'name' => 'required|max:150',
            'first_name' => 'required|max:150',
            'address' => 'nullable|max:255',
            'identity_number' => 'nullable|regex:/^\d{12}$/',
            'dob' => 'nullable|date',
            'dop' => 'nullable|date',
            'phone_number' => 'nullable|max:20',
            'last_time_login' => 'nullable|date',
            'zalo_id' => 'nullable|max:255',
        ]);


        $data['id'] = Str::uuid();
        $data['zalo_id'] = $request->input('zalo_id');
        $data['created_date'] = Carbon::now()->toDateString();
        $data['updated_date'] = Carbon::now()->toDateString();
        $data['last_time_login'] = Carbon::now()->toDateString();


        if ($request->hasFile('avatar')) {
            $file = $request->file('avatar');
            $fileName = time() . '_' . $file->getClientOriginalName();

            // Lưu trực tiếp vào public/storage/avatars
            $file->move(public_path('storage/avatars'), $fileName);

            // Đường dẫn lưu vào database
            $data['avatar'] = 'storage/avatars/' . $fileName;
        }


        $citizen = Citizen::create($data);


        return response()->json($citizen, 201);
    }

    public function show($zaloId)
    {
        $citizen = $this->citizenService->getByZaloId($zaloId);

        if (!$citizen) {
            return response()->json(['error' => 'User not found'], 404);
        }

        return response()->json($citizen);
    }
    public function update(Request $request, $id)
    {
        $citizen = Citizen::findOrFail($id);

        $data = $request->validate([
            'name' => 'sometimes|max:150',
            'first_name' => 'sometimes|max:150',
            'address' => 'nullable|max:255',
            'identity_number' => 'nullable|max:12',
            'dob' => 'nullable|date',
            'dop' => 'nullable|date',
            'phone_number' => 'nullable|max:20',
            'last_time_login' => 'nullable|date',
            'zalo_id' => 'nullable|max:255',
            'updated_date' => 'nullable|date',
        ]);



        $data['updated_date'] = Carbon::now(); // Cập nhật updated_date mỗi lần update

        // Nếu có last_time_login thì cập nhật, nếu không có thì giữ nguyên
        if ($request->has('last_time_login')) {
            $data['last_time_login'] = $request->input('last_time_login')->toDateString();
        }

        $citizen->update($data);

        return response()->json($citizen);
    }
    public function destroy($id)
    {
        return response()->json(['deleted' => $this->citizenService->delete($id)]);
    }

    public function checkUserExist($zaloId)
    {

        if (!$zaloId) {
            return response()->json(['error' => 'zalo_id is required'], 400);
        }


        $citizen = $this->citizenService->getByZaloId($zaloId);

        if (!$citizen) {

            return response()->json([
                'exists' => false,

            ]);
        }
        // dd($citizen);

        if (empty($citizen->phone_number)) {
            return response()->json([
                'exists' => false,

            ]);
        }


        return response()->json([
            'exists' => true,

        ]);
    }

    public function InfoCitizen(Request $request)
    {
        $request->validate([
            'zalo_id' => 'required|string',
        ]);

        $citizen = Citizen::where('zalo_id', $request->zalo_id)->first();

        if (!$citizen) {
            return response()->json([
                'message' => 'Citizen not found'
            ], 404);
        }

        // Helper: giữ nguyên phần đầu, ẩn 4 ký tự cuối bằng ****
        function maskLastFourChars($value)
        {
            if (!$value) return null;
            $length = strlen($value);
            if ($length <= 4) {
                return str_repeat('*', $length);
            }
            return substr($value, 0, $length - 4) . '****';
        }

        return response()->json([
            'message' => 'Citizen info retrieved successfully',
            'data' => [
                'name'            => $citizen->name,
                'phone_number'    => maskLastFourChars($citizen->phone_number),
                'identity_number' => maskLastFourChars($citizen->identity_number),
                'address'         => $citizen->address,
                'avatar'          => $citizen->avatar ? asset('storage' . $citizen->avatar) : null,
                'avatar_2'        => $citizen->avatar ? asset($citizen->avatar) : null,
            ]
        ]);
    }
}
