<?php
require_once '../includes/auth.php';
require_once '../includes/config.php';
$id = (int)$_GET['id'];
$menu_id = (int)$_GET['menu_id'];
mysqli_query($conn, "DELETE FROM variants WHERE id=$id");
header("Location: varian.php?menu_id=$menu_id");
exit;