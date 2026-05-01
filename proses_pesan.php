<?php
require_once 'includes/config.php';
header('Content-Type: application/json');

$input = json_decode(file_get_contents('php://input'), true);
if (!$input || empty($input['nama_pemesan']) || empty($input['meja']) || empty($input['items'])) {
    echo json_encode(['success' => false, 'message' => 'Data tidak lengkap']);
    exit;
}

$nama = mysqli_real_escape_string($conn, $input['nama_pemesan']);
$meja = mysqli_real_escape_string($conn, $input['meja']);
$total = 0;

// Hitung total harga dulu
foreach ($input['items'] as $item) {
    $subtotal = (float)$item['totalHarga'];
    $total += $subtotal;
}

// Insert order
mysqli_query($conn, "INSERT INTO orders (restaurant_id, nama_pemesan, meja, total_harga, status) VALUES (1, '$nama', '$meja', $total, 'pending')");
$order_id = mysqli_insert_id($conn);

// Insert order items
foreach ($input['items'] as $item) {
    $menu_id = (int)$item['menuId'];
    $variant_id = !empty($item['variantId']) ? (int)$item['variantId'] : 'NULL';
    $qty = (int)$item['qty'];
    $subtotal = (float)$item['totalHarga'];
    $sql = "INSERT INTO order_items (order_id, menu_id, variant_id, jumlah, subtotal) VALUES ($order_id, $menu_id, $variant_id, $qty, $subtotal)";
    mysqli_query($conn, $sql);
}

echo json_encode(['success' => true]);