<?php
namespace App\Helpers;

class InputHelper {
    public static function sanitizeInt($data) {
        return filter_var($data, FILTER_VALIDATE_INT);
    }
    
    public static function sanitizeArray($data) {
        if (is_array($data)) {
            foreach ($data as $key => $value) {
                $data[$key] = self::sanitizeString($value);
            }
        }
        return $data;
    }

    public static function sanitizeString($data) {
        // Trim the input data to remove any extra spaces
        $data = trim($data);
        // Strip HTML tags
        $data = strip_tags($data);
        return $data;
    }
    
    public static function sanitizeEmail($data) {
        return filter_var($data, FILTER_VALIDATE_EMAIL);
    }
}

?>