<?php
session_start(); 
require_once '../includes/config.php';
require_once '../includes/functions.php';
admin_redirect_if_not_logged_in();

$id = $_GET['id'] ?? 0;
$stmt = $pdo->prepare("SELECT * FROM posts WHERE id = ?");
$stmt->execute([$id]);
$post = $stmt->fetch();

if (!$post) {
    die("Posting tidak ditemukan.");
}
                                                                            
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $image = $post['image']; // tetap pakai yang lama jika tidak diupload

    if (!empty($_FILES['image']['name'])) {
        // Hapus gambar lama
        if ($post['image']) {
            unlink("../assets/uploads/" . $post['image']);
        }
        $image = uniqid() . '_' . basename($_FILES["image"]["name"]);
        move_uploaded_file($_FILES["image"]["tmp_name"], "../assets/uploads/" . $image);
    }

    $stmt = $pdo->prepare("UPDATE posts SET title = ?, content = ?, image = ? WHERE id = ?");
    $stmt->execute([$title, $content, $image, $id]);
    header("Location: posts.php");
    exit;
}
?>

<!-- Form mirip add_post.php, tapi dengan nilai default dan hidden id -->
<!-- (Gunakan value="<?= esc($post['title']) ?>" dll) -->