<?php

session_start(); // ← WAJIB

if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

require_once '../includes/functions.php';
admin_redirect_if_not_logged_in();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --bs-success: #28a745;
            --bs-primary: #d4af37;
        }
        body { background-color: #f8fff9; }
        .sidebar { background: #28a745; color: white; }
        .sidebar a { color: white; text-decoration: none; }
        .sidebar a:hover { background: #1e7e34; }
        .navbar-brand { color: white !important; }
    </style>
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-2 p-0">
            <nav class="sidebar vh-100 p-3">
                <h4 class="text-center py-3">Admin Panel</h4>
                <ul class="nav flex-column">
                    <li class="nav-item"><a class="nav-link" href="posts.php">Daftar Posting</a></li>
                    <li class="nav-item"><a class="nav-link" href="add_post.php">Tambah Posting</a></li>
                    <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
                </ul>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="col-md-10 p-4">
            <h2>Selamat Datang, Admin!</h2>
            <p>Gunakan menu di samping untuk mengelola konten pelatihan Haji & Umrah.</p>
        </div>
    </div>
</div>
</body>
</html>