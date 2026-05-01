<?php
// Konfigurasi database dari environment variable Railway (atau fallback ke nilai default lokal)
$host = getenv('DB_HOST') ?: 'localhost';
$port = getenv('DB_PORT') ?: '3306';
$user = getenv('DB_USER') ?: 'root';
$pass = getenv('DB_PASS') ?: '';
$db   = getenv('DB_NAME') ?: 'myrestaurant';

$conn = mysqli_connect($host, $user, $pass, $db, $port);
if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// Set timezone (opsional)
date_default_timezone_set('Asia/Jakarta');
?>