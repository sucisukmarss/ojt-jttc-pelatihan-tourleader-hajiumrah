<?php
session_start(); 
require_once '../includes/config.php';
require_once '../includes/functions.php';
admin_redirect_if_not_logged_in();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $content = $_POST['content'];

    $image = null;
    if (!empty($_FILES['image']['name'])) {
        $target_dir = "../assets/uploads/";
        $image = uniqid() . '_' . basename($_FILES["image"]["name"]);
        $target_file = $target_dir . $image;
        move_uploaded_file($_FILES["image"]["tmp_name"], $target_file);
    }

    $stmt = $pdo->prepare("INSERT INTO posts (title, content, image) VALUES (?, ?, ?)");
    $stmt->execute([$title, $content, $image]);
    header("Location: posts.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Tambah Posting</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>:root { --bs-success: #28a745; --bs-primary: #d4af37; }</style>
</head>
<body class="bg-light">
<div class="container mt-5">
    <h2 class="text-success">Tambah Posting Baru</h2>
    <form method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label>Judul</label>
            <input type="text" name="title" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Isi Konten</label>
            <textarea name="content" class="form-control" rows="6" required></textarea>
        </div>
        <div class="mb-3">
            <label>Gambar (opsional)</label>
            <input type="file" name="image" class="form-control" accept="image/*">
        </div>
        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="posts.php" class="btn btn-secondary">Batal</a>
    </form>
</div>
</body>
</html>