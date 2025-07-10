<?php

namespace App\Http\Controllers\API;

use App\Helpers\PhoneHelper;
use App\Http\Controllers\Controller;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ZaloController extends Controller
{
    public function getPhone(Request $request)
    {

      
      
        // Nhận token từ mobile app gửi lên
        $userAccessToken = $request->input('access_token');
        $code = $request->input('code');  // Nếu cần
        $secretKey = env('ZALO_SECRET_KEY', 'default_secret_key');  // Lưu trong .env
        // dd($userAccessToken, $code, $secretKey);

        $client = new Client();

        // Đặt URL API Zalo và header
        $url = 'https://graph.zalo.me/v2.0/me/info';

        $headers = [
            "access_token" => $userAccessToken, 
            "code" => $code,
            "secret_key" => $secretKey
        ];

        // Gửi yêu cầu GET tới API
        try {
            $response = $client->request('GET', $url, [
                'headers' => $headers,
            ]);

            // Xử lý phản hồi từ API
            $data = json_decode($response->getBody()->getContents(), true);
            
            if (isset($data['data']['number'])) {
                // Format lại số điện thoại
                $data['data']['number'] = PhoneHelper::formatPhoneNumber($data['data']['number']);
            }


            // Trả về dữ liệu hoặc xử lý theo yêu cầu của bạn
            return response()->json($data);
        } catch (\Exception $e) {
            // Xử lý lỗi nếu có
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }


    
}
