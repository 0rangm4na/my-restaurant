<?php
// Fungsi bantu: query aman (untuk kemudahan, gunakan prepared statement manual jika perlu)
function escape($str) {
    global $conn;
    return mysqli_real_escape_string($conn, $str);
}

// Redirect dengan pesan (opsional)
function redirect($url, $msg = '') {
    if ($msg) {
        $_SESSION['message'] = $msg;
    }
    header("Location: $url");
    exit;
}
?>