<?php

if (!function_exists('formatDate')) {
    /**
     * Format date to DD/MM/YYYY
     *
     * @param mixed $date
     * @return string
     */
    function formatDate($date)
    {
        if (!$date) {
            return '-';
        }

        if (is_string($date)) {
            $date = \Carbon\Carbon::parse($date);
        }

        return $date->format('d/m/Y');
    }
}

if (!function_exists('formatDateTime')) {
    /**
     * Format datetime to DD/MM/YYYY HH:mm
     *
     * @param mixed $date
     * @return string
     */
    function formatDateTime($date)
    {
        if (!$date) {
            return '-';
        }

        if (is_string($date)) {
            $date = \Carbon\Carbon::parse($date);
        }

        return $date->format('d/m/Y H:i');
    }
}

if (!function_exists('formatDateTimeFull')) {
    /**
     * Format datetime to DD/MM/YYYY HH:mm:ss
     *
     * @param mixed $date
     * @return string
     */
    function formatDateTimeFull($date)
    {
        if (!$date) {
            return '-';
        }

        if (is_string($date)) {
            $date = \Carbon\Carbon::parse($date);
        }

        return $date->format('d/m/Y H:i:s');
    }
}

if (!function_exists('formatDateIndonesia')) {
    /**
     * Format date to Indonesian format: DD Bulan YYYY
     *
     * @param mixed $date
     * @return string
     */
    function formatDateIndonesia($date)
    {
        if (!$date) {
            return '-';
        }

        if (is_string($date)) {
            $date = \Carbon\Carbon::parse($date);
        }

        $months = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember'
        ];

        return $date->format('d') . ' ' . $months[(int) $date->format('m')] . ' ' . $date->format('Y');
    }
}

if (!function_exists('parseDate')) {
    /**
     * Parse DD/MM/YYYY to Carbon instance
     *
     * @param string $date
     * @return \Carbon\Carbon|null
     */
    function parseDate($date)
    {
        if (!$date) {
            return null;
        }

        try {
            return \Carbon\Carbon::createFromFormat('d/m/Y', $date);
        } catch (\Exception $e) {
            return null;
        }
    }
}

if (!function_exists('parseDatetime')) {
    /**
     * Parse DD/MM/YYYY HH:mm to Carbon instance
     *
     * @param string $datetime
     * @return \Carbon\Carbon|null
     */
    function parseDatetime($datetime)
    {
        if (!$datetime) {
            return null;
        }

        try {
            return \Carbon\Carbon::createFromFormat('d/m/Y H:i', $datetime);
        } catch (\Exception $e) {
            return null;
        }
    }
}
