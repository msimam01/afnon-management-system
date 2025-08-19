<?php

if (!function_exists('randomDateInRange')) {
    /**
     * Generate a random date between start and end date.
     *
     * @param string $startDate  Y-m-d
     * @param string $endDate    Y-m-d
     * @return string            Y-m-d
     */
    function randomDateInRange($startDate, $endDate)
    {
        if (!$startDate || !$endDate) {
            return null;
        }

        $start = strtotime($startDate);
        $end = strtotime($endDate);

        if ($start > $end) {
            return null; // Invalid range
        }

        $random = mt_rand($start, $end);
        return date('Y-m-d', $random);
    }
}
