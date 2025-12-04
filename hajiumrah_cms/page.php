<?php
require_once 'includes/config.php';

$slug = $_GET['slug'] ?? '';
if (!$page) {
    http_response_code(404);
    echo '
    <!DOCTYPE html>
    <html lang="id">
    <head>
        <meta charset="UTF-8">
        <title>404 - Tidak Ditemukan</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    </head>
    <body class="bg-light">
        <div class="container text-center mt-5">
            <h1 class="text-success">404</h1>
            <h3>Halaman Tidak Ditemukan</h3>
            <p>Maaf, halaman yang Anda cari tidak tersedia.</p>
            <a href="index.php" class="btn btn-success">Kembali ke Beranda</a>
        </div>
    </body>
    </html>
    ';
    exit;
}

$stmt = $pdo->prepare("
    SELECT p.*, s.site_title, s.site_description 
    FROM pages p 
    CROSS JOIN settings s 
    WHERE p.slug = ?
");
$stmt->execute([$slug]);
$page = $stmt->fetch();

if (!$page) {
    http_response_code(404);
    include '404.php';
    exit;
}

// Ambil setting global
$site_title = $page['site_title'] ?? 'Pelatihan Tour Leader Haji & Umrah';
$site_description = $page['site_description'] ?? 'Program pelatihan resmi untuk calon tour leader haji dan umrah.';

// Meta tag dinamis
$page_title = !empty($page['meta_title']) ? $page['meta_title'] : $page['title'];
$page_description = !empty($page['meta_description']) ? $page['meta_description'] : substr(strip_tags($page['content']), 0, 160);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($page_title) ?> | <?= htmlspecialchars($site_title) ?></title>
    
    <!-- SEO Meta Tags -->
    <meta name="description" content="<?= htmlspecialchars($page_description) ?>">
    <meta name="author" content="<?= htmlspecialchars($site_title) ?>">
    <meta property="og:title" content="<?= htmlspecialchars($page_title) ?>">
    <meta property="og:description" content="<?= htmlspecialchars($page_description) ?>">
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?= 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] ?>">
    <?php if (!empty($page['image'])): ?>
        <meta property="og:image" content="<?= 'http://' . $_SERVER['HTTP_HOST'] . '/assets/uploads/pages/' . htmlspecialchars($page['image']) ?>">
    <?php endif; ?>
    
    <!-- Favicon & Fonts -->
    <link rel="icon" href="assets/img/favicon.ico" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@600;700&family=Open+Sans&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        :root {
            --hijau: #28a745;
            --emas: #d4af37;
        }
        body { font-family: 'Open Sans', sans-serif; background-color: #f9fdf9; }
        h1, h2, h3 { font-family: 'Poppins', sans-serif; }
    </style>
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

<!-- Konten Halaman -->
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <h1 class="text-success fw-bold mb-4"><?= htmlspecialchars($page['title']) ?></h1>
            
            <?php if (!empty($page['image'])): ?>
                <div class="text-center mb-4">
                    <img src="assets/uploads/pages/<?= htmlspecialchars($page['image']) ?>" 
                         class="img-fluid rounded shadow" 
                         alt="<?= htmlspecialchars($page['title']) ?>"
                         style="max-height: 400px; object-fit: cover;">
                </div>
            <?php endif; ?>

            <div class="content-page">
                <?= $page['content'] ?>
            </div>

            <!-- Khusus Halaman Kontak: Tambahkan Form & WhatsApp -->
            <?php if ($slug === 'kontak'): ?>
                <hr class="my-5">
                <h3 class="text-success">Kirim Pesan</h3>
                <form method="POST" action="admin/contact_handler.php">
                    <div class="mb-3">
                        <label for="name" class="form-label">Nama</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="message" class="form-label">Pesan</label>
                        <textarea name="message" class="form-control" rows="4" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-success">Kirim</button>
                </form>

                <div class="mt-4 p-3 bg-light rounded">
                    <h5><i class="bi bi-whatsapp text-success me-2"></i>Butuh Respons Cepat?</h5>
                    <p>
                        <a href="https://wa.me/6281234567890?text=Halo%20Hajiumrah%20Pro,%20saya%20ingin%20bertanya%20tentang%20pelatihan." 
                           class="btn btn-success">
                            <i class="bi bi-whatsapp me-1"></i>Chat via WhatsApp
                        </a>
                    </p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Footer -->
<footer class="bg-success text-white py-4 mt-5">
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <h5><i class="bi bi-mosque me-2"></i>Pelatihan Haji & Umrah</h5>
                <p class="mb-0">Membekali tour leader dengan ilmu manasik, etika, dan manajemen perjalanan ibadah.</p>
            </div>
            <div class="col-md-6 text-md-end mt-3 mt-md-0">
                <a href="#" class="text-white me-3"><i class="bi bi-facebook"></i></a>
                <a href="#" class="text-white me-3"><i class="bi bi-instagram"></i></a>
                <a href="#" class="text-white"><i class="bi bi-whatsapp"></i></a>
            </div>
        </div>
        <hr class="my-3 opacity-25">
        <p class="text-center mb-0">&copy; 2025 Pelatihan Tour Leader Haji & Umrah. All rights reserved.</p>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>