<?php
require_once '../includes/auth.php';
require_once '../includes/config.php';
require_once '../includes/phpqrcode/qrlib.php'; // pastikan path sesuai

// Ambil domain Railway dari environment atau fallback ke localhost
$domain = getenv('RAILWAY_PUBLIC_DOMAIN') ?: $_SERVER['HTTP_HOST'];
// URL yang akan dienkode (halaman depan restoran)
$qr_content = 'http://' . $domain . dirname($_SERVER['SCRIPT_NAME']) . '/../index.php';
// Path penyimpanan
$save_dir = __DIR__ . '/../assets/uploads/';
if (!file_exists($save_dir)) mkdir($save_dir, 0755, true);
$filename = 'qrcode_' . time() . '.png';
$filepath = $save_dir . $filename;

QRcode::png($qr_content, $filepath, QR_ECLEVEL_L, 5);

$qr_url = 'assets/uploads/' . $filename;
?>
<?php include '../includes/header.php'; ?>
<h1>QR Code Restoran</h1>
<p>Scan QR di bawah untuk mengakses menu restoran:</p>
<div class="text-center my-4">
    <img src="../<?= $qr_url ?>" class="img-thumbnail" style="max-width:300px;">
</div>
<a href="../<?= $qr_url ?>" download class="btn btn-success">Download QR Code</a>
<a href="dashboard.php" class="btn btn-secondary">Kembali</a>
<?php include '../includes/footer.php'; ?>