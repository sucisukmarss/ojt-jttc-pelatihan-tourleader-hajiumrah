<?php
session_start(); 
require_once '../includes/config.php';
require_once '../includes/functions.php';
admin_redirect_if_not_logged_in();

$id = $_GET['id'] ?? 0;
$stmt = $pdo->prepare("SELECT image FROM posts WHERE id = ?");
$stmt->execute([$id]);
$post = $stmt->fetch();

if ($post && $post['image']) {
    unlink("../assets/uploads/" . $post['image']);
}

$pdo->prepare("DELETE FROM posts WHERE id = ?")->execute([$id]);
header("Location: posts.php");
exit;
?>s   