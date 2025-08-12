<?php

if (!function_exists('format_taka')) {
    /**
     * Format a number as Bangladeshi Taka (BDT) with proper thousand, lakh, and crore separators.
     *
     * @param float $amount
     * @param string $symbol (optional) The currency symbol to use (e.g., '৳', 'Tk.', 'BDT')
     * @param bool $include_decimal (optional) Whether to include decimal places
     * @return string
     */
    function format_taka(float $amount, string $symbol = 'Tk.', bool $include_decimal = false): string
    {
        // Handle negative values
        $is_negative = $amount < 0;
        $amount = (float) abs($amount); // Explicitly cast to float

        // Split into whole and decimal parts
        $whole_number = (int) $amount;
        $decimal_part = $include_decimal ? explode('.', number_format($amount, 2, '.', ''))[1] : '';

        // Format the whole number part with Bangladeshi separators
        $formatted_whole_number = '';
        $whole_number_str = (string) $whole_number; // Cast to string for array access
        $length = strlen($whole_number_str);

        // Start from the end and work backwards
        for ($i = $length - 1, $counter = 0; $i >= 0; $i--, $counter++) {
            $formatted_whole_number = $whole_number_str[$i] . $formatted_whole_number;

            // Add commas at the correct positions
            if ($counter === 2 && $i !== 0) {
                // Add a comma after the first three digits (thousands)
                $formatted_whole_number = ',' . $formatted_whole_number;
            } elseif ($counter > 2 && ($counter - 2) % 2 === 0 && $i !== 0) {
                // Add a comma every two digits after the first three digits (lakhs, crores, etc.)
                $formatted_whole_number = ',' . $formatted_whole_number;
            }
        }

        // Combine whole and decimal parts
        $formatted_amount = $formatted_whole_number;
        if ($include_decimal && !empty($decimal_part)) {
            $formatted_amount .= '.' . $decimal_part;
        }

        // Add the currency symbol
        return $symbol . ($is_negative ? ' -' : ' ') . $formatted_amount;
    }
}

// Test cases
//echo format_taka(1234567.89) . "\n"; // Output: Tk. 12,34,567
//echo format_taka(-1234567.89, 'Tk.', true) . "\n"; // Output: Tk. -12,34,567.89
//echo format_taka(10000000, 'BDT') . "\n"; // Output: BDT 1,00,00,000
//echo format_taka(1500) . "\n"; // Output: Tk. 1,500
//echo format_taka(500000) . "\n"; // Output: Tk. 5,00,000
//echo format_taka(123456789) . "\n"; // Output: Tk. 12,34,56,789
