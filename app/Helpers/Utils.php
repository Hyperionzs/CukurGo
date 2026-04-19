<?php


class Utils {
    // Format numbers to rupiah numbers
    public static function formatRupiah($angka) {
        return "Rp " . number_format($angka, 0, ',', '.');
    }

    // Format date to Indonesian format (example: 19 April 2026)
    public static function formatDateIndo($date) {
        $bulan = [
            1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
            'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
        ];
        $split = explode('-', $date);
        return $split[2] . ' ' . $bulan[(int)$split[1]] . ' ' . $split[0];
    }

    // Sanitize input to prevent Cross-Site Scripting (XSS)
    public static function sanitize($data) {
        return htmlspecialchars(strip_tags(trim($data)));
    }
}