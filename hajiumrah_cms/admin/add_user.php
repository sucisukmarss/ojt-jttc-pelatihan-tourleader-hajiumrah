<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}
require_once '../includes/config.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($username) || empty($password)) {
        $error = "Username dan password wajib diisi!";
    } else {
        // Cek apakah username sudah ada
        $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
        $stmt->execute([$username]);
        if ($stmt->fetch()) {
            $error = "Username sudah digunakan. Silakan pilih username lain.";
        } else {
            // ✅ Auto-hash password
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            
            $stmt = $pdo->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
            if ($stmt->execute([$username, $hashedPassword])) {
                header('Location: users.php?success=1');
                exit;
            } else {
                $error = "Terjadi kesalahan saat menyimpan data.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tambah User - Admin</title>
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
    <h2 class="text-success mb-4">Tambah User Admin</h2>

    <?php if ($error): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= htmlspecialchars($error) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="card p-4 shadow-sm">
        <form method="POST">
            <div class="mb-3">
                <label for="username" class="form-label fw-bold">Username</label>
                <input type="text" 
                       name="username" 
                       id="username" 
                       class="form-control" 
                       value="<?= htmlspecialchars($_POST['username'] ?? '') ?>" 
                       placeholder="Masukkan username unik"
                       required>
                <div class="form-text">Username harus unik dan minimal 4 karakter.</div>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label fw-bold">Password</label>
                <input type="password" 
                       name="password" 
                       id="password" 
                       class="form-control" 
                       placeholder="Masukkan password"
                       required>
                <div class="form-text">Gunakan kombinasi huruf, angka, dan simbol untuk keamanan.</div>
            </div>
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-success">
                    <i class="bi bi-person-plus me-1"></i> Tambah User
                </button>
                <a href="users.php" class="btn btn-secondary">
                    <i class="bi bi-arrow-left me-1"></i> Kembali
                </a>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>