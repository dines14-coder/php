<?php

namespace App\Helpers;

class PasswordHelper
{
    public static function tempPassword($length = 8)
    {
        return substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789@#$%&*'), 0, $length);
    }
}