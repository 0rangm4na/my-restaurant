<?php
require_once '../includes/auth.php';
require_once '../includes/config.php';
require_once '../includes/functions.php';

$resto = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM restaurants WHERE id=1"));

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = escape($_POST['nama_resto']);
    $deskripsi = escape($_POST['deskripsi']);
    $kontak = escape($_POST['kontak']);
    
    // Handle upload logo
    $logo = $resto['logo'];
    if (isset($_FILES['logo']) && $_FILES['logo']['error'] == 0) {
        $target_dir = "../assets/uploads/";
        if (!file_exists($target_dir)) mkdir($target_dir, 0755, true);
        $filename = time() . '_' . basename($_FILES['logo']['name']);
        $target_file = $target_dir . $filename;
        move_uploaded_file($_FILES['logo']['tmp_name'], $target_file);
        $logo = "assets/uploads/" . $filename; // path relatif untuk akses public
    }
    
    mysqli_query($conn, "UPDATE restaurants SET nama_resto='$nama', logo='$logo', deskripsi='$deskripsi', kontak='$kontak' WHERE id=1");
    redirect('resto_setting.php', 'Pengaturan berhasil disimpan');
}
?>
<?php include '../includes/header.php'; ?>
<h1>Pengaturan Restoran</h1>
<?php if (isset($_SESSION['message'])): ?>
    <div class="alert alert-success"><?= $_SESSION['message']; unset($_SESSION['message']); ?></div>
<?php endif; ?>
<form method="POST" enctype="multipart/form-data">
    <div class="mb-3">
        <label>Nama Restoran</label>
        <input type="text" name="nama_resto" class="form-control" value="<?= htmlspecialchars($resto['nama_resto']) ?>" required>
    </div>
    <div class="mb-3">
        <label>Logo (biarkan kosong jika tidak ingin ganti)</label>
        <?php if (!empty($resto['logo'])): ?>
            <br><img src="../<?= htmlspecialchars($resto['logo']) ?>" height="60">
        <?php endif; ?>
        <input type="file" name="logo" class="form-control">
    </div>
    <div class="mb-3">
        <label>Deskripsi</label>
        <textarea name="deskripsi" class="form-control" rows="4"><?= htmlspecialchars($resto['deskripsi']) ?></textarea>
    </div>
    <div class="mb-3">
        <label>Kontak</label>
        <input type="text" name="kontak" class="form-control" value="<?= htmlspecialchars($resto['kontak']) ?>">
    </div>
    <button type="submit" class="btn btn-primary">Simpan</button>
</form>
<?php include '../includes/footer.php'; ?>