<?php
namespace Viki\Api\Models;

class Sanitize {
    public static function sanitizeString($string) {
        $string = strip_tags($string);
        $string = trim($string);
        $string = htmlspecialchars($string);
        return $string;
    }
    
    public static function sanitizeArray(array $array) {
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $array[$key] = self::sanitizeArray($value);
            } else {
                $array[$key] = self::sanitizeString($value);
            }
        }
        return $array;
    }
}