<?php
require_once 'includes/config.php';

// Cek apakah tabel 'menus' sudah ada
try {
    $stmt = $conn->query("SHOW TABLES LIKE 'menus'");
    if ($stmt->rowCount() > 0) {
        die("Database sudah terinstall. <a href='index.php'>Ke halaman utama</a>");
    }
} catch (PDOException $e) {
    // Tabel belum ada, lanjutkan install
}

// Baca file SQL
$sqlFile = 'database/myrestaurant.sql';
if (!file_exists($sqlFile)) {
    die("File SQL tidak ditemukan.");
}

$sql = file_get_contents($sqlFile);

// Jalankan query satu per satu
$queries = explode(';', $sql);
foreach ($queries as $query) {
    $query = trim($query);
    if (!empty($query)) {
        try {
            $conn->exec($query);
        } catch (PDOException $e) {
            die("Error: " . $e->getMessage() . "<br>Query: " . $query);
        }
    }
}

// Insert admin default
$admin_user = 'admin';
$admin_pass = password_hash('admin123', PASSWORD_DEFAULT);
$stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (:user, :pass)");
$stmt->execute(['user' => $admin_user, 'pass' => $admin_pass]);

echo "Installasi berhasil! <a href='login.php'>Login Admin</a>";
?>