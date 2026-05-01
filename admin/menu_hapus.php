<?php
require_once '../includes/auth.php';
require_once '../includes/config.php';
$id = (int)$_GET['id'];
mysqli_query($conn, "DELETE FROM menus WHERE id=$id");
header('Location: menu.php');
exit;