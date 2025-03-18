<?php

namespace Ababilithub\FlexPhp\Package\Mixin\Security\Data\Validation\V1;

trait V1
{
    /**
     * Validate if a string is not empty.
     *
     * @param string $value
     * @return bool
     */
    public function validateRequired($value)
    {
        return !empty(trim($value));
    }

    /**
     * Validate email format.
     *
     * @param string $email
     * @return bool
     */
    public function validateEmail($email)
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }

    /**
     * Validate URL format.
     *
     * @param string $url
     * @return bool
     */
    public function validateUrl($url)
    {
        return filter_var($url, FILTER_VALIDATE_URL) !== false;
    }

    /**
     * Validate an integer.
     *
     * @param mixed $value
     * @return bool
     */
    public function validateInteger($value)
    {
        return filter_var($value, FILTER_VALIDATE_INT) !== false;
    }

    /**
     * Validate a floating-point number.
     *
     * @param mixed $value
     * @return bool
     */
    public function validateFloat($value)
    {
        return filter_var($value, FILTER_VALIDATE_FLOAT) !== false;
    }

    /**
     * Validate boolean input.
     *
     * @param mixed $value
     * @return bool
     */
    public function validateBoolean($value)
    {
        return is_bool(filter_var($value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE));
    }

    /**
     * Validate a string against a regex pattern.
     *
     * @param string $value
     * @param string $pattern
     * @return bool
     */
    public function validateRegex($value, $pattern)
    {
        return preg_match($pattern, $value) === 1;
    }

    /**
     * Validate a phone number (digits only with optional leading +).
     *
     * @param string $phone
     * @return bool
     */
    public function validatePhone($phone)
    {
        return $this->validateRegex($phone, '/^\+?[0-9]{7,15}$/');
    }

    /**
     * Validate a JSON string.
     *
     * @param string $json
     * @return bool
     */
    public function validateJSON($json)
    {
        json_decode($json);
        return json_last_error() === JSON_ERROR_NONE;
    }

    /**
     * Validate a color hex code.
     *
     * @param string $color
     * @return bool
     */
    public function validateHexColor($color) 
    {
        return $this->validateRegex($color, '/^#([a-fA-F0-9]{3}|[a-fA-F0-9]{6})$/');
    }

    /**
     * Validate a date.
     *
     * @param string $date
     * @param string $format
     * @return bool
     */
    public function validateDate($date, $format = 'Y-m-d')
    {
        $parsedDate = \DateTime::createFromFormat($format, $date);
        return $parsedDate && $parsedDate->format($format) === $date;
    }

    /**
     * Validate a time.
     *
     * @param string $time
     * @param string $format
     * @return bool
     */
    public function validateTime($time, $format = 'H:i:s')
    {
        $parsedTime = \DateTime::createFromFormat($format, $time);
        return $parsedTime && $parsedTime->format($format) === $time;
    }

    /**
     * Validate an array of integers.
     *
     * @param array $array
     * @return bool
     */
    public function validateArrayOfIntegers(array $array)
    {
        foreach ($array as $item) {
            if (!$this->validateInteger($item)) {
                return false;
            }
        }
        return true;
    }

    /**
     * Validate a file extension.
     *
     * @param string $filename
     * @param array $allowedExtensions
     * @return bool
     */
    public function validateFileExtension($filename, array $allowedExtensions)
    {
        $extension = pathinfo($filename, PATHINFO_EXTENSION);
        return in_array(strtolower($extension), array_map('strtolower', $allowedExtensions), true);
    }

    /**
     * Validate a user role against allowed roles.
     *
     * @param string $role
     * @param array $allowedRoles
     * @return bool
     */
    public function validateUserRole($role, array $allowedRoles)
    {
        return in_array($role, $allowedRoles, true);
    }

    /**
     * Validate if a value is within a numeric range.
     *
     * @param float|int $value
     * @param float|int $min
     * @param float|int $max
     * @return bool
     */
    public function validateRange($value, $min, $max)
    {
        return $value >= $min && $value <= $max;
    }

    /**
     * Validate if an array contains unique values.
     *
     * @param array $array
     * @return bool
     */
    public function validateUniqueArray(array $array)
    {
        return count($array) === count(array_unique($array));
    }

    /**
     * Validate SQL-like input (basic syntax validation, not a replacement for prepared statements).
     *
     * @param string $sql
     * @return bool
     */
    public function validateSQLLike($sql)
    {
        return $this->validateRegex($sql, '/^[a-zA-Z0-9_\-% ]*$/');
    }
}
