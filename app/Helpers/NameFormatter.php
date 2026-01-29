<?php

namespace App\Helpers;

class NameFormatter
{
    /**
     * Format fullname to "FIRSTNAME MI. LASTNAME"
     * e.g., "Jestonie Azurin Lacaden" becomes "JESTONIE A. LACADEN"
     */
    public static function formatWithMiddleInitial($fullname)
    {
        $parts = explode(' ', trim($fullname));

        if (count($parts) < 2) {
            return strtoupper($fullname);
        }

        $firstname = $parts[0];
        $lastname = end($parts);

        // Get middle name/s (everything between first and last)
        $middleParts = array_slice($parts, 1, count($parts) - 2);
        $middleInitial = '';

        if (!empty($middleParts)) {
            $middleInitial = strtoupper(substr($middleParts[0], 0, 1)) . '.';
        }

        return strtoupper(trim($firstname . ' ' . $middleInitial . ' ' . $lastname));
    }
}
