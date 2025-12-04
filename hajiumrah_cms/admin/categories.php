<?php
session_start(); 
require_once '../includes/config.php';
require_once '../includes/functions.php';
admin_redirect_if_not_logged_in();

// Ambil semua kategori
$categories = [];
try {
    $stmt = $pdo->query("SELECT * FROM categories ORDER BY name");
    $categories = $stmt->fetchAll();
} catch (Exception $e) {
    // Jika tabel belum ada, biarkan kosong
}

// Notifikasi
$success = $_GET['success'] ?? null;
$error = $_GET['error'] ?? null;
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Manajemen Kategori - Admin</title>
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
        .main-content {
            margin-left: 250px;
            padding: 20px;
        }
        @media (max-width: 768px) {
            .sidebar { width: 70px; }
            .sidebar .nav-link span { display: none; }
            .main-content { margin-left: 70px; }
        }
    </style>
</head>
<body>

<!-- Sidebar -->
<div class="sidebar d-flex flex-column p-3">
    <a href="../index.php" class="text-white text-decoration-none text-center mb-4">
        <img src="../assets/img/logo-jttc.png" height="50" alt="JTTC Academy">
    </a>
    <ul class="nav nav-pills flex-column mt-3">
        <li class="nav-item">
            <a class="nav-link" href="dashboard.php">
                <i class="bi bi-speedometer2"></i> <span>Dashboard</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="posts.php">
                <i class="bi bi-file-earmark-text"></i> <span>Posting</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link active" href="categories.php">
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
    <?php if ($success): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= htmlspecialchars($success) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
    <?php if ($error): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= htmlspecialchars($error) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-success">Manajemen Kategori</h2>
        <a href="add_category.php" class="btn btn-success">
            <i class="bi bi-plus-circle me-1"></i> Tambah Kategori
        </a>
    </div>

    <?php if (empty($categories)): ?>
        <div class="alert alert-info">
            <i class="bi bi-info-circle me-1"></i> Belum ada kategori. 
            <a href="add_category.php" class="alert-link">Tambah kategori pertama Anda.</a>
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle">
                <thead class="table-success">
                    <tr>
                        <th scope="col">No</th>
                        <th scope="col">Nama Kategori</th>
                        <th scope="col">Slug</th>
                        <th scope="col" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($categories as $index => $cat): ?>
                        <tr>
                            <td><?= $index + 1 ?></td>
                            <td><?= htmlspecialchars($cat['name']) ?></td>
                            <td><code><?= htmlspecialchars($cat['slug']) ?></code></td>
                            <td class="text-center">
                                <a href="edit_category.php?id=<?= $cat['id'] ?>" class="btn btn-warning btn-sm me-1">
                                    <i class="bi bi-pencil"></i> Edit
                                </a>
                                <a href="delete_category.php?id=<?= $cat['id'] ?>" 
                                   class="btn btn-danger btn-sm"
                                   onclick="return confirm('Hapus kategori ini? Pastikan tidak ada posting yang menggunakan kategori ini.')">
                                    <i class="bi bi-trash"></i> Hapus
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>