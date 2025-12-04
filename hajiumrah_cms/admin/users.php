<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}
require_once '../includes/config.php';

$users = $pdo->query("SELECT * FROM users ORDER BY created_at DESC")->fetchAll();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Manajemen User - Admin</title>
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
            <a class="nav-link active" href="users.php">
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
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            User berhasil ditambahkan!
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
    <?php if (isset($_GET['updated'])): ?>
        <div class="alert alert-info alert-dismissible fade show" role="alert">
            User berhasil diupdate!
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
    <?php if (isset($_GET['deleted'])): ?>
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            User berhasil dihapus!
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-success">Manajemen User</h2>
        <a href="add_user.php" class="btn btn-success">Tambah User</a>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle">
            <thead class="table-success">
                <tr>
                    <th scope="col">Username</th>
                    <th scope="col">Tanggal Dibuat</th>
                    <th scope="col" class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($users): ?>
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td><?= htmlspecialchars($user['username']) ?></td>
                            <td><?= date('d M Y H:i', strtotime($user['created_at'])) ?></td>
                            <td class="text-center">
                                <a href="edit_user.php?id=<?= $user['id'] ?>" class="btn btn-warning btn-sm me-1">
                                    <i class="bi bi-pencil"></i> Edit
                                </a>
                                <?php if ($user['id'] != $_SESSION['admin_id']): ?>
                                    <a href="delete_user.php?id=<?= $user['id'] ?>" 
                                       class="btn btn-danger btn-sm" 
                                       onclick="return confirm('Hapus user ini? Tindakan tidak bisa dikembalikan.')">
                                        <i class="bi bi-trash"></i> Hapus
                                    </a>
                                <?php else: ?>
                                    <button class="btn btn-secondary btn-sm" disabled>
                                        <i class="bi bi-lock"></i> Tidak Bisa Hapus Diri Sendiri
                                    </button>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="3" class="text-center text-muted">Belum ada user.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>