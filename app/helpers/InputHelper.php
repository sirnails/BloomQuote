<?php
namespace App\Helpers;

class InputHelper {
    public static function sanitizeInt($data) {
        return filter_var($data, FILTER_VALIDATE_INT);
    }

    public static function sanitizeString($data) {
        return filter_var($data, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    }

    public static function sanitizeEmail($data) {
        return filter_var($data, FILTER_VALIDATE_EMAIL);
    }
}

?>