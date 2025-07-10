<?php

namespace App\Helpers;

class PhoneHelper
{
    /**
     * Format số điện thoại từ định dạng quốc tế về nội địa
     *
     * @param string $phoneNumber
     * @return string
     */
    public static function formatPhoneNumber($phoneNumber)
    {
        // Loại bỏ tất cả ký tự không phải số và dấu cộng
        $phoneNumber = preg_replace('/\D/', '', $phoneNumber);

        // Kiểm tra nếu số điện thoại bắt đầu bằng "84" để chuyển thành "0"
        if (substr($phoneNumber, 0, 2) === '84') {
            // Đổi đầu số "84" thành "0"
            $phoneNumber = '0' . substr($phoneNumber, 2);
        }

        return $phoneNumber;
    }
}