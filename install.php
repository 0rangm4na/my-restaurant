<?php
require_once 'includes/config.php';

// Cek apakah sudah ada tabel 'menus' (salah satu tabel utama), jika sudah jangan buat ulang
$tbl_check = mysqli_query($conn, "SHOW TABLES LIKE 'menus'");
if (mysqli_num_rows($tbl_check) > 0) {
    die("Database sudah terinstall. <a href='index.php'>Ke halaman utama</a>");
}

// Baca file SQL
$sql = file_get_contents('database/myrestaurant.sql');
if (!$sql) {
    die("File SQL tidak ditemukan.");
}

// Eksekusi query dipisahkan per titik koma
$queries = explode(';', $sql);
foreach ($queries as $query) {
    $query = trim($query);
    if (!empty($query)) {
        mysqli_query($conn, $query) or die("Error: " . mysqli_error($conn) . "<br>Query: " . $query);
    }
}

// Hash password untuk admin default: admin123
$admin_user = 'admin';
$admin_pass = password_hash('admin123', PASSWORD_DEFAULT);
mysqli_query($conn, "INSERT INTO users (username, password) VALUES ('$admin_user', '$admin_pass')");

echo "Installasi berhasil! <a href='login.php'>Login Admin</a>";