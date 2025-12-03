<?php
session_start(); 
require_once '../includes/config.php';
require_once '../includes/functions.php';
admin_redirect_if_not_logged_in();

$stmt = $pdo->query("SELECT * FROM posts ORDER BY created_at DESC");
$posts = $stmt->fetchAll();
?>

<?php if (isset($_GET['success'])): ?>
    <div class="alert alert-success">User berhasil ditambahkan!</div>
<?php endif; ?>
<?php if (isset($_GET['updated'])): ?>
    <div class="alert alert-info">User berhasil diupdate!</div>
<?php endif; ?>

<!-- HTML with Bootstrap -->
<!DOCTYPE html>
<html>
<head>
    <title>Daftar Posting</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root { --bs-success: #28a745; --bs-primary: #d4af37; }
        body { background-color: #f8fff9; }
    </style>
</head>
<body>
<div class="container mt-5">
    <h2 class="text-success">Daftar Posting</h2>
    <a href="add_post.php" class="btn btn-primary mb-3">Tambah Posting</a>
    <table class="table table-bordered">
        <thead class="table-success">
            <tr>
                <th>Judul</th>
                <th>Gambar</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($posts as $post): ?>
            <tr>
                <td><?= esc($post['title']) ?></td>
                <td>
                    <?php if ($post['image']): ?>
                        <img src="../assets/uploads/<?= esc($post['image']) ?>" width="80" class="img-thumbnail">
                    <?php endif; ?>
                </td>
                <td>
                    <a href="edit_post.php?id=<?= $post['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                    <a href="delete_post.php?id=<?= $post['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Hapus?')">Hapus</a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
</body>
</html>