<?php
require_once '../includes/auth.php';
require_once '../includes/config.php';
$menu_id = (int)$_GET['menu_id'];
$menu = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM menus WHERE id=$menu_id"));
if (!$menu) die("Menu tidak ada.");
$variants = mysqli_query($conn, "SELECT * FROM variants WHERE menu_id=$menu_id");
?>
<?php include '../includes/header.php'; ?>
<h1>Varian untuk: <?= htmlspecialchars($menu['nama_menu']) ?></h1>
<a href="varian_tambah.php?menu_id=<?= $menu_id ?>" class="btn btn-success mb-3">Tambah Varian</a>
<table class="table">
    <thead><tr><th>Nama Varian</th><th>Tambahan Harga</th><th>Aksi</th></tr></thead>
    <tbody>
    <?php while ($v = mysqli_fetch_assoc($variants)): ?>
        <tr>
            <td><?= htmlspecialchars($v['nama_varian']) ?></td>
            <td>Rp <?= number_format($v['tambahan_harga'],0,',','.') ?></td>
            <td>
                <a href="varian_hapus.php?id=<?= $v['id'] ?>&menu_id=<?= $menu_id ?>" class="btn btn-sm btn-danger" onclick="return confirm('Hapus?')">Hapus</a>
            </td>
        </tr>
    <?php endwhile; ?>
    </tbody>
</table>
<a href="menu.php" class="btn btn-secondary">Kembali</a>
<?php include '../includes/footer.php'; ?>