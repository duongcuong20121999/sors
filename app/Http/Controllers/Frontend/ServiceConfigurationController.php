<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ServiceConfigurationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $services = Service::orderBy('order')->get();
        return view('frontend.service-configurations.index', compact('services'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $services = Service::orderBy('order')->get();
        return view('frontend.service-configurations.create', compact('services'));
    }

    public function show($id)
{
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        
        // Validate input
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'code' => 'required|unique:services,code',
            'order' => 'required|unique:services,order'
            
        ], [
            'name.required' => 'Tên không được để trống',
            'code.required' => 'Mã không được để trống',
            'order.required' => 'Số thứ tự không được dể trống',
            'code.unique' => 'Mã đã tồn tại trong hệ thống',
            'order.unique' => 'Số thứ tự đã tồn tại trong hệ thống'


        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Vui lòng kiểm tra lại các trường bắt buộc.');
        }

        if($request->has('unlimited_duration')){

        }
        $data = [
            'name' => $request->input('name'),
            'code' => $request->input('code'),
            'order' => $request->input('order'),
            'description' => $request->input('description'), 
            'mission' => $request->input('mission'),  
            'unlimited_duration' => $request->has('unlimited_duration') ? 1 : 0,
            'is_active' => $request->has('is_active') ? 0 : 1,
        ];

        if ($request->has('unlimited_duration')) {
            $data['process_hours'] = '';
            $data['process_minutes'] = '';
        } else {
            $data['process_hours'] = $request->input('process_hours');
            $data['process_minutes'] = $request->input('process_minutes');
        }


        if ($request->hasFile('icon')) {
            $file = $request->file('icon');
            $filename = time() . '_' . $file->getClientOriginalName();

            $file->move(public_path('storage/icons'), $filename); // 👈 Lưu trực tiếp vào public

            $data['icon'] = '/storage/icons/' . $filename;
        }



        Service::create($data);


        $notification = [
            'message' => 'Dịch vụ đã được tạo thành công!',
            'alert-type' => 'success',
        ];

        return redirect()->back()->with($notification);
    }

    /**
     * Display the specified resource.
     */


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {

        $service_update = Service::findOrFail($id);
        $services = Service::orderBy('order')->get();
        return view('frontend.service-configurations.edit', compact('service_update', 'services'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {  
        // Validate input
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'code' => 'required|unique:services,code,' . $id, 
            'order' => 'required|unique:services,order,' . $id,
          
        ], [
            'name.required' => 'Tên không được để trống',
            'code.required' => 'Mã không được để trống',
            'order.required' => 'Số thứ tự không được dể trống',
            'code.unique' => 'Mã đã tồn tại trong hệ thống',
            'order.unique' => 'Số thứ tự đã tồn tại trong hệ thống'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Vui lòng kiểm tra lại các trường bắt buộc.');
        }

       
        $service = Service::findOrFail($id);

  
        $data = [
            'name' => $request->input('name'),
            'code' => $request->input('code'),
            'order' => $request->input('order'),
            'description' => $request->input('description'),
            'mission' => $request->input('mission'),  
            'process_hours' => $request->input('process_hours'),
            'process_minutes' => $request->input('process_minutes'),
            'unlimited_duration' => $request->has('unlimited_duration') ? 1 : 0,
            'is_active' => $request->has('is_active') ? 0 : 1,
        ];

      
        if ($request->hasFile('icon')) {
          
            if ($service->icon && file_exists(public_path('storage/icons/' . $service->icon))) {
                unlink(public_path('storage/icons/' . $service->icon));
            }

    
            $file = $request->file('icon');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('storage/icons'), $filename);

            $data['icon'] = '/storage/icons/' . $filename;
        }

        // dd($data);

       
        $service->update($data);

       
        $notification = [
            'message' => 'Dịch vụ đã được cập nhật thành công!',
            'alert-type' => 'success',
        ];

        return redirect()->route('service-configurations.index')->with($notification);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
