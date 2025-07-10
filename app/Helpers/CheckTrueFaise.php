<?php 
if (!function_exists('convertToBool')) {
    /**
     * Chuyển giá trị 1 hoặc 0 thành true hoặc false
     *
     * @param int $value
     * @return bool
     */
    function convertToBool(int $value): bool
    {
        if ($value == 1) {
            return true;
        }
        return false;
    }
}

?>