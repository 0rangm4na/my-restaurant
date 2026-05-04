<?php
require_once 'includes/config.php';

// Buat tabel-tabel
$queries = [
    "CREATE TABLE IF NOT EXISTS users (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        username TEXT UNIQUE NOT NULL,
        password TEXT NOT NULL
    )",
    "CREATE TABLE IF NOT EXISTS restaurants (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        nama_resto TEXT DEFAULT 'My Restaurant',
        logo TEXT DEFAULT NULL,
        deskripsi TEXT DEFAULT NULL,
        kontak TEXT DEFAULT NULL
    )",
    "INSERT OR IGNORE INTO restaurants (id, nama_resto, logo, deskripsi, kontak) VALUES (1, 'My Restaurant', NULL, 'Restoran terbaik dengan menu lezat.', '0812-3456-7890')",
    "CREATE TABLE IF NOT EXISTS categories (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        nama_kategori TEXT NOT NULL
    )",
    "INSERT OR IGNORE INTO categories (nama_kategori) VALUES ('Makanan'), ('Minuman')",
    "CREATE TABLE IF NOT EXISTS menus (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        category_id INTEGER NOT NULL,
        nama_menu TEXT NOT NULL,
        harga REAL NOT NULL DEFAULT 0,
        deskripsi TEXT,
        FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE
    )",
    "CREATE TABLE IF NOT EXISTS variants (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        menu_id INTEGER NOT NULL,
        nama_varian TEXT NOT NULL,
        tambahan_harga REAL NOT NULL DEFAULT 0,
        FOREIGN KEY (menu_id) REFERENCES menus(id) ON DELETE CASCADE
    )",
    "CREATE TABLE IF NOT EXISTS orders (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        restaurant_id INTEGER DEFAULT 1,
        nama_pemesan TEXT NOT NULL,
        meja TEXT NOT NULL,
        total_harga REAL NOT NULL DEFAULT 0,
        status TEXT DEFAULT 'pending',
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (restaurant_id) REFERENCES restaurants(id)
    )",
    "CREATE TABLE IF NOT EXISTS order_items (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        order_id INTEGER NOT NULL,
        menu_id INTEGER NOT NULL,
        variant_id INTEGER DEFAULT NULL,
        jumlah INTEGER NOT NULL,
        subtotal REAL NOT NULL,
        FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
        FOREIGN KEY (menu_id) REFERENCES menus(id),
        FOREIGN KEY (variant_id) REFERENCES variants(id) ON DELETE SET NULL
    )"
];

foreach ($queries as $query) {
    try {
        $conn->exec($query);
    } catch (PDOException $e) {
        die("Error: " . $e->getMessage() . "<br>Query: " . $query);
    }
}

// Insert admin default
$admin_user = 'admin';
$admin_pass = password_hash('admin123', PASSWORD_DEFAULT);
$stmt = $conn->prepare("INSERT OR IGNORE INTO users (username, password) VALUES (:user, :pass)");
$stmt->execute(['user' => $admin_user, 'pass' => $admin_pass]);

echo "Installasi SQLite berhasil! <a href='login.php'>Login Admin</a>";
?>