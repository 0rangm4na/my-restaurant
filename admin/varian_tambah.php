<?php
require_once '../includes/auth.php';
require_once '../includes/config.php';
$menu_id = (int)$_GET['menu_id'];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = escape($_POST['nama_varian']);
    $tambahan = (float)$_POST['tambahan_harga'];
    mysqli_query($conn, "INSERT INTO variants (menu_id, nama_varian, tambahan_harga) VALUES ($menu_id, '$nama', $tambahan)");
    header("Location: varian.php?menu_id=$menu_id");
    exit;
}
?>
<?php include '../includes/header.php'; ?>
<h1>Tambah Varian</h1>
<form method="POST">
    <div class="mb-3"><label>Nama Varian</label><input type="text" name="nama_varian" class="form-control" required></div>
    <div class="mb-3"><label>Tambahan Harga (Rp)</label><input type="number" name="tambahan_harga" class="form-control" step="0.01" value="0"></div>
    <button type="submit" class="btn btn-primary">Simpan</button>
    <a href="varian.php?menu_id=<?= $menu_id ?>" class="btn btn-secondary">Batal</a>
</form>
<?php include '../includes/footer.php'; ?>