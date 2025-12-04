<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}
require_once '../includes/config.php';

// Ambil statistik dengan penanganan error (jika tabel belum ada)
try {
    $postCount = $pdo->query("SELECT COUNT(*) FROM posts")->fetchColumn();
} catch (Exception $e) {
    $postCount = 0;
}

try {
    $categoryCount = $pdo->query("SELECT COUNT(*) FROM categories")->fetchColumn();
} catch (Exception $e) {
    $categoryCount = 0;
}

try {
    $pageCount = $pdo->query("SELECT COUNT(*) FROM pages")->fetchColumn();
} catch (Exception $e) {
    $pageCount = 0;
}

// Ambil 5 posting terbaru
try {
    $recentPosts = $pdo->query("
        SELECT p.title, p.created_at, c.name AS category 
        FROM posts p 
        LEFT JOIN categories c ON p.category_id = c.id 
        ORDER BY p.created_at DESC 
        LIMIT 5
    ")->fetchAll();
} catch (Exception $e) {
    $recentPosts = [];
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard Admin - Pelatihan Haji & Umrah</title>
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
        .card-stat {
            border: none;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
            transition: transform 0.3s;
            border-radius: 12px;
            overflow: hidden;
        }
        .card-stat:hover {
            transform: translateY(-5px);
        }
        .card-stat .icon {
            font-size: 2.2rem;
        }
        .bg-hijau { background: linear-gradient(135deg, var(--hijau), var(--hijau-muda)); color: white; }
        .bg-emas { background: linear-gradient(135deg, var(--emas), var(--emas-gelap)); color: white; }
        .bg-biru { background: linear-gradient(135deg, #2c7be5, #50a5f1); color: white; }
        .header-bar {
            background: white;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            padding: 15px 20px;
            border-radius: 10px;
            margin-bottom: 25px;
        }
        .recent-post-item {
            border-left: 4px solid var(--hijau);
            padding-left: 15px;
            margin: 12px 0;
        }
        @media (max-width: 768px) {
            .sidebar { width: 70px; }
            .sidebar .nav-link span { display: none; }
            .sidebar .nav-link { text-align: center; padding: 15px 5px; }
            .sidebar .nav-link i { margin: 0; }
            .main-content { margin-left: 70px; }
            .header-bar { padding: 15px; }
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
            <a class="nav-link active" href="dashboard.php">
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
    <div class="header-bar d-flex justify-content-between align-items-center">
        <div>
            <h2 class="mb-0" style="color: var(--hijau);">Dashboard Admin</h2>
            <p class="mb-0">Selamat datang kembali! Kelola konten pelatihan Anda di sini.</p>
        </div>
        <div class="text-end">
            <div class="fw-bold">Admin</div>
            <small class="text-muted">Tour Leader Haji & Umrah</small>
        </div>
    </div>

    <!-- Statistik -->
    <div class="row mb-4">
        <div class="col-md-3 col-6 mb-3">
            <div class="card card-stat bg-hijau">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5 class="card-title">Posting</h5>
                            <h2 class="mb-0"><?= $postCount ?></h2>
                        </div>
                        <div class="icon"><i class="bi bi-file-text"></i></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-6 mb-3">
            <div class="card card-stat bg-emas">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5 class="card-title">Kategori</h5>
                            <h2 class="mb-0"><?= $categoryCount ?></h2>
                        </div>
                        <div class="icon"><i class="bi bi-tag"></i></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-6 mb-3">
            <div class="card card-stat bg-biru">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5 class="card-title">Halaman</h5>
                            <h2 class="mb-0"><?= $pageCount ?></h2>
                        </div>
                        <div class="icon"><i class="bi bi-file-earmark"></i></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-6 mb-3">
            <div class="card card-stat" style="background: linear-gradient(135deg, #6f42c1, #a17bea); color: white;">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5 class="card-title">Versi</h5>
                            <h2 class="mb-0">1.0</h2>
                        </div>
                        <div class="icon"><i class="bi bi-code-slash"></i></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Posting Terbaru -->
    <div class="card shadow-sm rounded-3">
        <div class="card-header bg-white">
            <h5 class="mb-0" style="color: var(--hijau);">Posting Terbaru</h5>
        </div>
        <div class="card-body">
            <?php if ($recentPosts): ?>
                <?php foreach ($recentPosts as $post): ?>
                <div class="recent-post-item">
                    <h6 class="mb-1"><?= htmlspecialchars($post['title']) ?></h6>
                    <small class="text-muted">
                        <i class="bi bi-calendar"></i> <?= date('d M Y', strtotime($post['created_at'])) ?> |
                        <i class="bi bi-tag"></i> <?= htmlspecialchars($post['category'] ?? 'Umum') ?>
                    </small>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="text-muted">Belum ada posting.</p>
            <?php endif; ?>
            <a href="add_post.php" class="btn btn-success mt-3">
                <i class="bi bi-plus-circle"></i> Tambah Posting Baru
            </a>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>