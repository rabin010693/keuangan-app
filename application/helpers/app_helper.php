<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if (!function_exists('rupiah')) {
    /**
     * Format angka menjadi format Rupiah, contoh: Rp 1.250.000
     */
    function rupiah($angka, $with_prefix = true)
    {
        $hasil = number_format((float) $angka, 0, ',', '.');
        return $with_prefix ? 'Rp ' . $hasil : $hasil;
    }
}

if (!function_exists('nama_bulan')) {
    /**
     * Konversi angka bulan (1-12) menjadi nama bulan Indonesia
     */
    function nama_bulan($bulan)
    {
        $bulan_arr = array(
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        );
        return isset($bulan_arr[(int) $bulan]) ? $bulan_arr[(int) $bulan] : '';
    }
}

if (!function_exists('tgl_indo')) {
    /**
     * Format tanggal Y-m-d menjadi "21 Juli 2026"
     */
    function tgl_indo($tanggal)
    {
        if (empty($tanggal)) return '-';
        $pecah = explode('-', $tanggal);
        if (count($pecah) < 3) return $tanggal;
        return (int) $pecah[2] . ' ' . nama_bulan((int) $pecah[1]) . ' ' . $pecah[0];
    }
}

if (!function_exists('badge_type')) {
    /**
     * Menghasilkan badge HTML untuk tipe transaksi
     */
    function badge_type($type)
    {
        if ($type === 'income') {
            return '<span class="badge bg-success"><i class="fa fa-arrow-down"></i> Pemasukan</span>';
        }
        return '<span class="badge bg-danger"><i class="fa fa-arrow-up"></i> Pengeluaran</span>';
    }
}
