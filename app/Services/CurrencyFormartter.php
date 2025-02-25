<?php
namespace App\Services;

use Carbon\Carbon;

class CurrencyFormartter
{
    /**
     * Format a number as money with comma separators while preserving the original decimal places.
     *
     * @param string|float $value The original number as a string or float.
     * @return string
     */
    public static function format(string $value): string
    {
        // Convert the value to a string
        $valueString = (string)$value;
        
        // Determine if a decimal point exists and capture the decimals
        if (strpos($valueString, '.') !== false) {
            // Split into integer and decimal parts
            [$integerPart, $decimalPart] = explode('.', $valueString, 2);
            // Use the length of the decimal part as the desired precision.
            $decimals = strlen($decimalPart);
        } else {
            $decimals = 0;
        }
        
        // Convert the value to a float for arithmetic operations.
        $number = (float)$value;
        $multiplier = pow(10, $decimals);
        
        // Truncate the number (do not round).
        if ($number >= 0) {
            $truncated = floor($number * $multiplier) / $multiplier;
        } else {
            $truncated = ceil($number * $multiplier) / $multiplier;
        }
        
        // Format the truncated number with commas and the exact number of decimals.
        return number_format($truncated, $decimals, '.', ',');
    }
}