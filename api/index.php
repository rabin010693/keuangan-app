<?php
// Tampilkan error jika ada
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Pastikan working directory ada di root
chdir(dirname(__DIR__));

// Panggil index.php CI3
require __DIR__ . '/../index.php';