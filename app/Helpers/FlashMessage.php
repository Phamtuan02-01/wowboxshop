<?php

namespace App\Helpers;

class FlashMessage
{
    /**
     * Hiển thị thông báo thành công
     */
    public static function success($message)
    {
        session()->flash('success', $message);
    }

    /**
     * Hiển thị thông báo lỗi
     */
    public static function error($message)
    {
        session()->flash('error', $message);
    }

    /**
     * Hiển thị thông báo cảnh báo
     */
    public static function warning($message)
    {
        session()->flash('warning', $message);
    }

    /**
     * Hiển thị thông báo thông tin
     */
    public static function info($message)
    {
        session()->flash('info', $message);
    }

    /**
     * Hiển thị nhiều thông báo cùng lúc
     */
    public static function multiple($messages)
    {
        foreach ($messages as $type => $message) {
            session()->flash($type, $message);
        }
    }
}