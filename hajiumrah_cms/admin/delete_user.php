<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}
require_once '../includes/config.php';

$id = $_GET['id'] ?? 0;
// Jangan hapus user sendiri!
if ($id == $_SESSION['admin_id']) {
    die("Tidak bisa menghapus diri sendiri!");
}

$pdo->prepare("DELETE FROM users WHERE id = ?")->execute([$id]);
header('Location: users.php?deleted=1');
exit;
?>