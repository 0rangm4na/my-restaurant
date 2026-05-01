<?php require_once '../includes/auth.php'; ?>
<?php include '../includes/header.php'; ?>
<h1>Dashboard Admin</h1>
<p>Selamat datang, <strong><?= $_SESSION['admin_username'] ?></strong>.</p>
<div class="row">
    <div class="col-md-3">
        <a href="menu.php" class="btn btn-primary w-100 mb-2">Kelola Menu</a>
    </div>
    <div class="col-md-3">
        <a href="resto_setting.php" class="btn btn-info w-100 mb-2">Pengaturan Restoran</a>
    </div>
    <div class="col-md-3">
        <a href="qr_generate.php" class="btn btn-warning w-100 mb-2">Generate QR Code</a>
    </div>
</div>
<?php include '../includes/footer.php'; ?>