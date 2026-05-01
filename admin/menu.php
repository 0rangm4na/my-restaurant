<?php
require_once '../includes/auth.php';
require_once '../includes/config.php';
require_once '../includes/functions.php';

$menus = mysqli_query($conn, "SELECT menus.*, categories.nama_kategori FROM menus JOIN categories ON menus.category_id = categories.id ORDER BY categories.id, menus.id");
?>
<?php include '../includes/header.php'; ?>
<h1>Daftar Menu</h1>
<a href="menu_tambah.php" class="btn btn-success mb-3">Tambah Menu</a>
<table class="table table-bordered">
    <thead>
        <tr>
            <th>ID</th>
            <th>Kategori</th>
            <th>Nama Menu</th>
            <th>Harga</th>
            <th>Deskripsi</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
    <?php while ($m = mysqli_fetch_assoc($menus)): ?>
        <tr>
            <td><?= $m['id'] ?></td>
            <td><?= htmlspecialchars($m['nama_kategori']) ?></td>
            <td><?= htmlspecialchars($m['nama_menu']) ?></td>
            <td>Rp <?= number_format($m['harga'],0,',','.') ?></td>
            <td><?= nl2br(htmlspecialchars($m['deskripsi'])) ?></td>
            <td>
                <a href="varian.php?menu_id=<?= $m['id'] ?>" class="btn btn-sm btn-secondary">Varian</a>
                <a href="menu_edit.php?id=<?= $m['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                <a href="menu_hapus.php?id=<?= $m['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Hapus menu ini?')">Hapus</a>
            </td>
        </tr>
    <?php endwhile; ?>
    </tbody>
</table>
<?php include '../includes/footer.php'; ?>