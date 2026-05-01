<?php
require_once '../includes/auth.php';
require_once '../includes/config.php';
require_once '../includes/functions.php';

$categories = mysqli_query($conn, "SELECT * FROM categories");
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = escape($_POST['nama_menu']);
    $cat_id = (int)$_POST['category_id'];
    $harga = (float)$_POST['harga'];
    $deskripsi = escape($_POST['deskripsi']);
    mysqli_query($conn, "INSERT INTO menus (category_id, nama_menu, harga, deskripsi) VALUES ($cat_id, '$nama', $harga, '$deskripsi')");
    redirect('menu.php', 'Menu berhasil ditambahkan');
}
?>
<?php include '../includes/header.php'; ?>
<h1>Tambah Menu</h1>
<form method="POST">
    <div class="mb-3">
        <label>Kategori</label>
        <select name="category_id" class="form-select" required>
            <?php while ($cat = mysqli_fetch_assoc($categories)): ?>
                <option value="<?= $cat['id'] ?>"><?= $cat['nama_kategori'] ?></option>
            <?php endwhile; ?>
        </select>
    </div>
    <div class="mb-3">
        <label>Nama Menu</label>
        <input type="text" name="nama_menu" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>Harga (Rp)</label>
        <input type="number" name="harga" class="form-control" required step="0.01">
    </div>
    <div class="mb-3">
        <label>Deskripsi</label>
        <textarea name="deskripsi" class="form-control" rows="3"></textarea>
    </div>
    <button type="submit" class="btn btn-primary">Simpan</button>
    <a href="menu.php" class="btn btn-secondary">Batal</a>
</form>
<?php include '../includes/footer.php'; ?>