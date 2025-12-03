<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}
require_once '../includes/config.php';

$users = $pdo->query("SELECT * FROM users ORDER BY created_at DESC")->fetchAll();
?>
<?php if (isset($_GET['success'])): ?>
    <div class="alert alert-success">User berhasil ditambahkan!</div>
<?php endif; ?>
<?php if (isset($_GET['updated'])): ?>
    <div class="alert alert-info">User berhasil diupdate!</div>
<?php endif; ?>

<!-- HTML Bootstrap (mirip dashboard, dengan tabel user) -->
<a href="add_user.php" class="btn btn-success mb-3">Tambah User</a>
<table class="table table-bordered">
    <thead class="table-success">
        <tr><th>Username</th><th>Tanggal</th><th>Aksi</th></tr>
    </thead>
    <tbody>
    <?php foreach ($users as $user): ?>
        <tr>
            <td><?= htmlspecialchars($user['username']) ?></td>
            <td><?= date('d M Y', strtotime($user['created_at'])) ?></td>
            <td>
                <a href="edit_user.php?id=<?= $user['id'] ?>" class="btn btn-warning btn-sm">Edit</a>
                <a href="delete_user.php?id=<?= $user['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Hapus?')">Hapus</a>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
