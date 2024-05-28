<?php

class SanitizationHelper {
    public static function sanitizeInput($data) {
        return filter_var(htmlspecialchars(strip_tags(trim($data)), ENT_QUOTES, 'UTF-8'),FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    }

    public static function sanitizeArray($data) {
        if (is_array($data)) {
            foreach ($data as $key => $value) {
                $data[$key] = self::sanitizeInput($value);
            }
        }
        return $data;
    }
}
?>

