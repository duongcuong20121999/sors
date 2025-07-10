<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $setting = Setting::first();
        return view('frontend.setting.index', compact('setting'));
    }
    public function showHeader()
    {
        $setting = Setting::first();
        return view('body.header', compact('setting'));
    }

    public function store(Request $request)
    {


        $request->validate([
            'system_name' => 'required|string',
            'ward_name' => 'required|string',
            'logo' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
            'qr_code' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
            'instruction' => 'required|string',
            'url_api_print' => 'required|string',

        ], [
            'system_name.required' => 'Tên hệ thống không được để trống',
            'ward_name.required' => 'Tên phường không được để trống',
            'logo.required' => 'Logo không được để trống',
            'qr_code.required' => 'Qr code không được để trống',
            'instruction.required' => 'Hướng dẫn kích hoạt không được để trống',



        ]);


        $setting = Setting::first() ?? new Setting();


        if ($request->hasFile('logo')) {
            if ($setting->logo && file_exists(public_path($setting->logo))) {
                unlink(public_path($setting->logo));
            }
            $file = $request->file('logo');
            $fileName = $file->getClientOriginalName(); // giữ nguyên tên file gốc

            $file->move(public_path('frontend/assets/images'), $fileName);

            $setting->logo = 'frontend/assets/images/' . $fileName;
        } else {
            $setting->logo = $setting->logo ?? 'frontend/assets/images/logo.png';
        }

     
        if ($request->hasFile('qr_code')) {
            if ($setting->qr_code && file_exists(public_path($setting->qr_code))) {
                unlink(public_path($setting->qr_code));
            }
            $file = $request->file('qr_code');
            $fileName = time() . '_qr_' . $file->getClientOriginalName();

            $file->move(public_path('frontend/assets/images'), $fileName);

            $setting->qr_code = 'frontend/assets/images/' . $fileName;
        } else {

            $setting->logo = $setting->logo ?? '/image/QR.png';
        }


        $setting->system_name = $request->system_name;
        $setting->ward_name = $request->ward_name;
        $setting->url_api_print = $request->url_api_print;

        $setting->time_update = $request->time_update;

        $setting->instruction = preg_replace("/(\r\n|\n|\r){2,}/", "\n", $request->instruction);

        $setting->save();

        $notification = [
            'message' => 'Cấu hình hệ thống thành công!',
            'alert-type' => 'success',
        ];

        return redirect()->back()->with($notification);
    }
}
