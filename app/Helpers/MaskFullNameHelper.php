<?php


namespace App\Helpers;

if (!function_exists('maskFullName')) {
    function maskFullName($fullName) {
        $words = explode(' ', $fullName);
        $maskedWords = array_map(function ($word) {
            if (strlen($word) > 2) {
                return substr($word, 0, 1) . '***' . substr($word, -1);
            }
            return $word;
        }, $words);
        return implode(' ', $maskedWords);
    }
}