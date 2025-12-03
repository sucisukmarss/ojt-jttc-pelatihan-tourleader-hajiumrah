<?php
require_once 'includes/config.php';

// Ambil semua kategori
$categories = $pdo->query("SELECT * FROM categories ORDER BY name")->fetchAll();

// Ambil semua posting (terbaru dulu)
$posts = $pdo->query("
    SELECT p.*, c.name AS category_name, c.slug AS category_slug
    FROM posts p
    LEFT JOIN categories c ON p.category_id = c.id
    ORDER BY p.created_at DESC
")->fetchAll();

// Ambil pengaturan situs
$settings = $pdo->query("SELECT * FROM settings WHERE id = 1")->fetch();
$siteTitle = $settings['site_title'] ?? 'Pelatihan Tour Leader Haji & Umrah';
$siteDesc = $settings['site_description'] ?? 'Program pelatihan resmi untuk calon tour leader haji dan umrah.';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($siteTitle) ?></title>
    <meta name="description" content="<?= htmlspecialchars($siteDesc) ?>">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="assets/css/style.css" rel="stylesheet">
    <style>
        :root {
            --hijau: #1e7e34;
            --hijau-muda: #28a745;
            --emas: #d4af37;
            --emas-gelap: #b8860b;
        }
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f9fdf9;
            color: #333;
        }
        /* Navbar */
        .navbar {
            background: rgba(30, 126, 52, 0.95);
            backdrop-filter: blur(10px);
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            transition: all 0.3s;
        }
        .navbar.scrolled {
            background: rgba(30, 126, 52, 1);
            box-shadow: 0 4px 20px rgba(0,0,0,0.2);
        }
        .navbar-brand {
            font-weight: 700;
            font-size: 1.4rem;
        }
        .navbar-brand i {
            color: var(--emas);
            margin-right: 8px;
        }
        /* Hero */
        .hero {
            background: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.6)), url('https://images.unsplash.com/photo-1548906807-9e8f1b8a7a9f?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80');
            background-size: cover;
            background-position: center;
            height: 60vh;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            color: white;
            margin-bottom: 3rem;
        }
        .hero h1 {
            font-weight: 800;
            font-size: 2.8rem;
            text-shadow: 0 2px 4px rgba(0,0,0,0.5);
        }
        .hero p {
            font-size: 1.3rem;
            max-width: 700px;
            margin: 1rem auto;
        }
        .btn-emas {
            background: linear-gradient(135deg, var(--emas), var(--emas-gelap));
            border: none;
            color: #1e7e34;
            font-weight: 600;
            padding: 10px 25px;
            border-radius: 50px;
            transition: all 0.3s;
        }
        .btn-emas:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 15px rgba(212, 175, 55, 0.4);
            color: white;
        }
        /* Posting Card */
        .post-card {
            transition: all 0.3s ease;
            border: none;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        }
        .post-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 20px rgba(0,0,0,0.15);
        }
        .post-image {
            height: 200px;
            object-fit: cover;
        }
        .post-category {
            background: var(--hijau);
            color: white;
            font-size: 0.85rem;
            padding: 4px 10px;
            border-radius: 20px;
            display: inline-block;
            margin-bottom: 8px;
        }
        .post-title {
            font-size: 1.3rem;
            font-weight: 600;
            color: var(--hijau);
            margin: 12px 0;
        }
        .post-excerpt {
            color: #666;
            line-height: 1.6;
        }
        .post-meta {
            color: #777;
            font-size: 0.9rem;
            margin-top: 10px;
        }
        /* Footer */
        .footer {
            background: linear-gradient(90deg, var(--hijau), var(--hijau-muda));
            color: white;
            padding: 3rem 0 2rem;
            margin-top: 4rem;
        }
        .footer a { color: #fff; text-decoration: none; }
        .footer a:hover { color: #ffeb3b; }
        .social-icon {
            width: 36px;
            height: 36px;
            background: rgba(255,255,255,0.2);
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-right: 10px;
            transition: all 0.3s;
        }
        .social-icon:hover {
            background: white;
            color: var(--hijau);
        }
        /* Animasi */
        .fade-in {
            opacity: 0;
            transform: translateY(20px);
            transition: opacity 0.6s ease, transform 0.6s ease;
        }
        .fade-in.appear {
            opacity: 1;
            transform: translateY(0);
        }
    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top">
    <!-- Navbar Brand dengan Logo Gambar -->
    <div class="container">
        <a class="navbar-brand fw-bold" href="index.php" style="color: #0a5f38;">
            <a class="navbar-brand" href="index.php"><img src="assets/img/logo-jttc.png" alt="JTTC Academy" height="40"></a>
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
            </ul>
        </div>
    </div>
</nav>

<!-- Konten Layanan -->
<section class="py-5">
    <div class="container">
        <h1 class="fw-bold text-center mb-5" style="color: #0a5f38;">Layanan Pelatihan</h1>
        
        <div class="row g-4">
            <!-- Layanan 1 -->
            <div class="col-md-6 col-lg-3">
                <div class="card h-100 shadow-sm border-0 text-center" style="border-top: 4px solid #d4af37;">
                    <div class="card-body">
                        <div class="mb-3">
                            <i class="bi bi-mortarboard fs-1" style="color: #0a5f38;"></i>
                        </div>
                        <h5 class="fw-bold">Pelatihan Dasar</h5>
                        <p class="text-muted">Untuk pemula yang ingin memahami dasar-dasar manasik dan tugas tour leader.</p>
                    </div>
                </div>
            </div>
            
            <!-- Layanan 2 -->
            <div class="col-md-6 col-lg-3">
                <div class="card h-100 shadow-sm border-0 text-center" style="border-top: 4px solid #d4af37;">
                    <div class="card-body">
                        <div class="mb-3">
                            <i class="bi bi-award fs-1" style="color: #0a5f38;"></i>
                        </div>
                        <h5 class="fw-bold">Sertifikasi Profesi</h5>
                        <p class="text-muted">Ujian kompetensi untuk mendapatkan sertifikat resmi dari LSP Pariwisata.</p>
                    </div>
                </div>
            </div>
            
            <!-- Layanan 3 -->
            <div class="col-md-6 col-lg-3">
                <div class="card h-100 shadow-sm border-0 text-center" style="border-top: 4px solid #d4af37;">
                    <div class="card-body">
                        <div class="mb-3">
                            <i class="bi bi-person-video3 fs-1" style="color: #0a5f38;"></i>
                        </div>
                        <h5 class="fw-bold">Pelatihan Lanjutan</h5>
                        <p class="text-muted">Fokus pada manajemen kelompok, komunikasi lintas budaya, dan penanganan darurat.</p>
                    </div>
                </div>
            </div>
            
            <!-- Layanan 4 -->
            <div class="col-md-6 col-lg-3">
                <div class="card h-100 shadow-sm border-0 text-center" style="border-top: 4px solid #d4af37;">
                    <div class="card-body">
                        <div class="mb-3">
                            <i class="bi bi-book-half fs-1" style="color: #0a5f38;"></i>
                        </div>
                        <h5 class="fw-bold">Modul Digital</h5>
                        <p class="text-muted">Akses materi pelatihan online seumur hidup melalui platform eksklusif kami.</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="text-center mt-5">
            <a href="contact.php" class="btn btn-warning px-4 py-2 fw-bold" style="background: #d4af37; color: #0a5f38;">Daftar Sekarang</a>
        </div>
    </div>
</section>

<!-- Footer -->
<footer class="footer">
    <div class="container">
        <div class="row">
            <div class="col-md-4 mb-4">
                <h5><i class="bi bi-mosque me-2"></i><?= htmlspecialchars($siteTitle) ?></h5>
                <p><?= htmlspecialchars($siteDesc) ?></p>
            </div>
            <div class="col-md-4 mb-4">
                <h5><i class="bi bi-geo-alt me-2"></i>Kontak</h5>
                <p>
                    <?= nl2br(htmlspecialchars($settings['address'] ?? 'Jl. Contoh No. 123, Jakarta')) ?><br>
                    📞 <?= htmlspecialchars($settings['phone'] ?? '+62 812-3456-7890') ?><br>
                    ✉️ <?= htmlspecialchars($settings['email'] ?? 'info@hajiumrah.test') ?>
                </p>
            </div>
            <div class="col-md-4 mb-4">
                <h5><i class="bi bi-share me-2"></i>Ikuti Kami</h5>
                <div>
                    <a href="#" class="social-icon"><i class="bi bi-facebook"></i></a>
                    <a href="#" class="social-icon"><i class="bi bi-instagram"></i></a>
                    <a href="#" class="social-icon"><i class="bi bi-whatsapp"></i></a>
                </div>
                <div class="mt-3">
                    <small><?= htmlspecialchars($settings['footer_text'] ?? '&copy; 2025 Pelatihan Haji & Umrah') ?></small>
                </div>
            </div>
        </div>
    </div>
</footer>