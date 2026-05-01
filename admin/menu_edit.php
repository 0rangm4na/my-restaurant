<?php
require_once '../includes/auth.php';
require_once '../includes/config.php';
require_once '../includes/functions.php';

$id = (int)$_GET['id'];
$menu = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM menus WHERE id=$id"));
if (!$menu) {
    die("Menu tidak ditemukan.");
}
$categories = mysqli_query($conn, "SELECT * FROM categories");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = escape($_POST['nama_menu']);
    $cat_id = (int)$_POST['category_id'];
    $harga = (float)$_POST['harga'];
    $deskripsi = escape($_POST['deskripsi']);
    mysqli_query($conn, "UPDATE menus SET category_id=$cat_id, nama_menu='$nama', harga=$harga, deskripsi='$deskripsi' WHERE id=$id");
    redirect('menu.php', 'Menu berhasil diupdate');
}
?>
<?php include '../includes/header.php'; ?>
<h1>Edit Menu</h1>
<form method="POST">
    <!-- sama seperti tambah, tapi value diisi dari $menu -->
    <div class="mb-3">
        <label>Kategori</label>
        <select name="category_id" class="form-select" required>
            <?php while ($cat = mysqli_fetch_assoc($categories)): ?>
                <option value="<?= $cat['id'] ?>" <?= $cat['id'] == $menu['category_id'] ? 'selected' : '' ?>><?= $cat['nama_kategori'] ?></option>
            <?php endwhile; ?>
        </select>
    </div>
    <div class="mb-3">
        <label>Nama Menu</label>
        <input type="text" name="nama_menu" class="form-control" value="<?= htmlspecialchars($menu['nama_menu']) ?>" required>
    </div>
    <div class="mb-3">
        <label>Harga (Rp)</label>
        <input type="number" name="harga" class="form-control" value="<?= $menu['harga'] ?>" required step="0.01">
    </div>
    <div class="mb-3">
        <label>Deskripsi</label>
        <textarea name="deskripsi" class="form-control" rows="3"><?= htmlspecialchars($menu['deskripsi']) ?></textarea>
    </div>
    <button type="submit" class="btn btn-primary">Update</button>
    <a href="menu.php" class="btn btn-secondary">Batal</a>
</form>
<?php include '../includes/footer.php'; ?>