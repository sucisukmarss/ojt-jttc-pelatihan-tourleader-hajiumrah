<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';
$stmt = $pdo->query("SELECT * FROM posts ORDER BY created_at DESC");
$posts = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Pelatihan Tour Leader Haji & Umrah</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="assets/css/style.css" rel="stylesheet">
</head>
<body>
<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark" style="background: linear-gradient(90deg, #1e7e34, #28a745);">
    <div class="container">
        <a class="navbar-brand fw-bold fs-4" href="index.php">
            <i class="bi bi-book me-2"></i>Pelatihan Haji & Umrah
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="index.php">Beranda</a></li>
                <li class="nav-item"><a class="nav-link" href="page.php?slug=tentang">Tentang</a></li>
                <li class="nav-item"><a class="nav-link" href="page.php?slug=kontak">Kontak</a></li>
            </ul>
        </div>
    </div>
</nav>

<!-- Hero -->
<div class="bg-success text-white py-5 text-center" style="background: url('assets/img/hero-bg.jpg') center/cover;">
    <div class="container">
        <h1 class="display-4 fw-bold">Pelatihan Tour Leader Haji & Umrah</h1>
        <p class="lead">Membekali calon tour leader dengan ilmu manasik, etika, dan manajemen perjalanan ibadah.</p>
        <a href="category.php?slug=pelatihan-dasar" class="btn btn-light btn-lg mt-3">Mulai Belajar</a>
    </div>
</div>


<!-- Daftar Posting -->
<div class="container mt-5">
    <h2 class="text-center text-success">Artikel Pelatihan</h2>
    <div class="row mt-4">
        <?php foreach ($posts as $post): ?>
        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <?php if ($post['image']): ?>
                    <img src="assets/uploads/<?= $post['image'] ?>" class="card-img-top" style="height: 200px; object-fit: cover;">
                <?php endif; ?>
                <div class="card-body">
                    <h5 class="card-title"><?= htmlspecialchars($post['title']) ?></h5>
                    <p class="card-text"><?= substr(strip_tags($post['content']), 0, 150) ?>...</p>
                    <a href="post.php?id=<?= $post['id'] ?>" class="btn btn-primary">Baca Selengkapnya</a>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<!-- Footer -->
<footer class="footer mt-5">
    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <h5><i class="bi bi-book me-2"></i>Pelatihan Haji & Umrah</h5>
                <p>Membekali tour leader dengan ilmu manasik, etika, dan manajemen perjalanan ibadah.</p>
            </div>
            <div class="col-md-4">
                <h5><i class="bi bi-geo-alt me-2"></i>Lokasi</h5>
                <p>Jl. Contoh No. 123, Jakarta<br>Telp: +62 812-3456-7890</p>
            </div>
            <div class="col-md-4">
                <h5><i class="bi bi-envelope me-2"></i>Kontak</h5>
                <p>info@hajiumrah.test</p>
                <div class="mt-3">
                    <a href="#" class="me-3"><i class="bi bi-facebook fs-4"></i></a>
                    <a href="#" class="me-3"><i class="bi bi-instagram fs-4"></i></a>
                    <a href="#" class="me-3"><i class="bi bi-whatsapp fs-4"></i></a>
                </div>
            </div>
        </div>
        <hr class="my-4 opacity-50">
        <p class="text-center mb-0">&copy; 2025 Pelatihan Tour Leader Haji & Umrah. All rights reserved.</p>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>