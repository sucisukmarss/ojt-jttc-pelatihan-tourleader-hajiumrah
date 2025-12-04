<?php
session_start(); 
require_once '../includes/config.php';
require_once '../includes/functions.php';
admin_redirect_if_not_logged_in();

$stmt = $pdo->query("SELECT * FROM posts ORDER BY created_at DESC");
$posts = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Daftar Posting - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        :root {
            --hijau: #1e7e34;
            --hijau-muda: #28a745;
            --emas: #d4af37;
            --emas-gelap: #b8860b;
        }
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f5f9f5;
            margin: 0;
            padding: 0;
        }
        /* Sidebar */
        .sidebar {
            background: linear-gradient(180deg, var(--hijau), var(--hijau-muda));
            min-height: 100vh;
            position: fixed;
            width: 250px;
            z-index: 1000;
            top: 0;
            left: 0;
        }
        .sidebar .nav-link {
            color: rgba(255,255,255,0.85);
            padding: 12px 20px;
            margin: 4px 0;
            border-radius: 0 20px 20px 0;
            transition: all 0.3s;
        }
        .sidebar .nav-link:hover, .sidebar .nav-link.active {
            color: white;
            background: rgba(0,0,0,0.2);
        }
        .sidebar .nav-link i {
            margin-right: 10px;
            width: 20px;
            text-align: center;
        }
        /* Main Content */
        .main-content {
            margin-left: 250px;
            padding: 20px;
            padding-top: 80px; /* Opsional: beri ruang jika ada navbar atas */
        }
        @media (max-width: 768px) {
            .sidebar { width: 70px; }
            .sidebar .nav-link span { display: none; }
            .sidebar .nav-link { text-align: center; padding: 15px 5px; }
            .sidebar .nav-link i { margin: 0; }
            .main-content { margin-left: 70px; }
        }
    </style>
</head>
<body>

<!-- Sidebar -->
<div class="sidebar d-flex flex-column p-3">
    <a href="../index.php" class="text-white text-decoration-none text-center mb-4">
        <img src="../assets/img/logo-jttc.png" height="50" alt="JTTC">
    </a>
    <ul class="nav nav-pills flex-column mt-3">
        <li class="nav-item">
            <a class="nav-link" href="dashboard.php">
                <i class="bi bi-speedometer2"></i> <span>Dashboard</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link active" href="posts.php">
                <i class="bi bi-file-earmark-text"></i> <span>Posting</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="categories.php">
                <i class="bi bi-tags"></i> <span>Kategori</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="pages.php">
                <i class="bi bi-file-earmark"></i> <span>Halaman</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="users.php">
                <i class="bi bi-people"></i> <span>User</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="settings.php">
                <i class="bi bi-gear"></i> <span>Pengaturan</span>
            </a>
        </li>
        <li class="nav-item mt-auto">
            <a class="nav-link text-white" href="logout.php">
                <i class="bi bi-box-arrow-right"></i> <span>Logout</span>
            </a>
        </li>
    </ul>
</div>

<!-- Main Content -->
<div class="main-content">
    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success">Posting berhasil ditambahkan!</div>
    <?php endif; ?>
    <?php if (isset($_GET['deleted'])): ?>
        <div class="alert alert-info">Posting berhasil dihapus!</div>
    <?php endif; ?>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-success">Daftar Posting</h2>
        <a href="add_post.php" class="btn btn-primary">Tambah Posting</a>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered table-hover">
            <thead class="table-success">
                <tr>
                    <th>Judul</th>
                    <th>Gambar</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($posts as $post): ?>
                <tr>
                    <td><?= esc($post['title']) ?></td>
                    <td>
                        <?php if ($post['image']): ?>
                            <img src="../assets/uploads/<?= esc($post['image']) ?>" width="80" class="img-thumbnail">
                        <?php else: ?>
                            <span class="text-muted">Tidak ada</span>
                        <?php endif; ?>
                    </td>
                    <td class="text-center">
                        <a href="edit_post.php?id=<?= $post['id'] ?>" class="btn btn-sm btn-warning me-1">Edit</a>
                        <a href="delete_post.php?id=<?= $post['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Hapus posting ini?')">Hapus</a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>