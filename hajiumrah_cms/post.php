<?php
require_once 'includes/config.php';
require_once 'includes/function.php';
$id = $_GET['id'] ?? 0;
$stmt = $pdo->prepare("SELECT * FROM posts WHERE id = ?");
$stmt->execute([$id]);
$post = $stmt->fetch();

if (!$post) {
    http_response_code(404);
    die("Posting tidak ditemukan.");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title><?= htmlspecialchars($post['title']) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top">
    <!-- Navbar Brand dengan Logo Gambar -->
    <div class="container">
        <a class="navbar-brand fw-bold" href="index.php" style="color: #0a5f38;">
            <a class="navbar-brand" href="index.php"><img src="assets/img/logo-jttc.png" alt="JTTC Academy" height="100"></a>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link active" href="index.php">Beranda</a></li>
                <li class="nav-item"><a class="nav-link" href="about.php">Tentang</a></li>
                <li class="nav-item"><a class="nav-link" href="services.php">Layanan</a></li>
                <li class="nav-item"><a class="nav-link" href="blog/index.php">Blog</a></li>
                <li class="nav-item"><a class="nav-link" href="contact.php">Kontak</a></li>
                <li class="nav-item"><a class="nav-link" href="admin/login.php" class="btn btn-warning btn-lg px-4 py-2 fw-bold" style="background: #d4af37; color: #0a5f38;">Login Admin!</a></li>
            </ul>
        </div>
    </div>
</nav>

<div class="container mt-5">
    <h1 class="text-success"><?= htmlspecialchars($post['title']) ?></h1>
    <p class="text-muted"><?= date('d M Y H:i', strtotime($post['created_at'])) ?></p>
    <?php if ($post['image']): ?>
        <img src="assets/uploads/<?= $post['image'] ?>" class="img-fluid mb-4 rounded">
    <?php endif; ?>
    <div class="content">
        <?= nl2br(htmlspecialchars($post['content'])) ?>
    </div>
</div>
</body>
</html>