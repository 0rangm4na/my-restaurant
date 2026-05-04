<?php
// Konfigurasi database SQLite
$db_path = __DIR__ . '/../database/myrestaurant.sqlite';

try {
    $conn = new PDO("sqlite:$db_path");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // Aktifkan foreign key support untuk SQLite
    $conn->exec("PRAGMA foreign_keys = ON");
} catch (PDOException $e) {
    die("Koneksi gagal: " . $e->getMessage());
}

date_default_timezone_set('Asia/Jakarta');
?>