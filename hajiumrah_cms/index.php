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



<!-- Hero Section (Inspirasi JTTC) -->
<section class="bg-success text-white py-5 text-center" style="background: linear-gradient(rgba(10, 95, 56, 0.9), rgba(10, 95, 56, 0.95)), url('https://images.unsplash.com/photo-1548906807-9e8f1b8a7a9f?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80'); background-size: cover; background-position: center;">
    <div class="container">
        <h1 class="display-4 fw-bold">Pelatihan Tour Leader Haji & Umrah</h1>
        <p class="lead mb-4">Persiapkan diri Anda menjadi tour leader profesional dengan pelatihan resmi berstandar nasional dan internasional.</p>
        <a href="contact.php" class="btn btn-warning btn-lg px-4 py-2 fw-bold" style="background: #d4af37; color: #0a5f38;">Daftar Sekarang</a>
    </div>
</section>

<!-- Tentang Singkat -->
<section class="py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 mb-4 mb-lg-0">
                <img src="assets/img/Haji.jpg" class="img-fluid rounded shadow" alt="Pelatihan Tour Leader">
            </div>
            <div class="col-lg-6">
                <h2 class="fw-bold" style="color: #0a5f38;">Mengapa Memilih Kami?</h2>
                <p class="text-muted">
                    JTTC Academy menyelenggarakan pelatihan tour leader haji dan umrah sejak 2010.
                    Program kami dirancang oleh praktisi senior dan telah diakui oleh Kementerian Agama RI.
                </p>
                <a href="about.php" class="btn btn-outline-success mt-3" style="border-color: #0a5f38; color: #0a5f38;">Selengkapnya</a>
            </div>
        </div>
    </div>
</section>

<!-- Layanan Utama (3 Fitur) -->
<section class="py-5 bg-light">
    <div class="container">
        <h2 class="text-center fw-bold mb-5" style="color: #0a5f38;">Keunggulan Program</h2>
        <div class="row text-center">
            <div class="col-md-4 mb-4">
                <div class="p-4 bg-white rounded shadow h-100" style="border-top: 4px solid #d4af37;">
                    <i class="bi bi-award fs-1" style="color: #0a5f38;"></i>
                    <h5 class="fw-bold mt-3">Sertifikasi Resmi</h5>
                    <p class="text-muted">Sertifikat dari Kemenag RI & Lembaga Sertifikasi Profesi (LSP) Pariwisata.</p>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="p-4 bg-white rounded shadow h-100" style="border-top: 4px solid #d4af37;">
                    <i class="bi bi-person-video3 fs-1" style="color: #0a5f38;"></i>
                    <h5 class="fw-bold mt-3">Pembimbing Ahli</h5>
                    <p class="text-muted">Dibimbing langsung oleh tour leader senior dengan pengalaman 15+ tahun.</p>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="p-4 bg-white rounded shadow h-100" style="border-top: 4px solid #d4af37;">
                    <i class="bi bi-globe fs-1" style="color: #0a5f38;"></i>
                    <h5 class="fw-bold mt-3">Jaringan Internasional</h5>
                    <p class="text-muted">Kerja sama dengan biro perjalanan di Arab Saudi dan Malaysia.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Testimoni (Carousel) -->
<section class="py-5">
    <div class="container">
        <h2 class="text-center fw-bold mb-5" style="color: #0a5f38;">Testimoni Peserta</h2>
        <div id="testimonialCarousel" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <div class="text-center p-4">
                        <p class="fs-5 text-muted">"Pelatihannya sangat komprehensif, terutama materi manajemen krisis. Alhamdulillah sekarang saya jadi tour leader tetap!"</p>
                        <h6 class="mt-3 fw-bold">— Dewi Lestari, Jakarta</h6>
                    </div>
                </div>
                <div class="carousel-item">
                    <div class="text-center p-4">
                        <p class="fs-5 text-muted">"Materi update sesuai regulasi terbaru dari Kemenag. Sangat direkomendasikan!"</p>
                        <h6 class="mt-3 fw-bold">— Budi Santoso, Surabaya</h6>
                    </div>
                </div>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#testimonialCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#testimonialCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
            </button>
        </div>
    </div>
</section>

<!-- Blog Preview -->
<section class="py-5 bg-light">
    <div class="container">
        <h2 class="text-center fw-bold mb-5" style="color: #0a5f38;">Artikel Terbaru</h2>
        <div class="row">
            <div class="col-md-6 mb-4">
                <div class="card h-100 shadow-sm border-0">
                    <div class="card-body">
                        <span class="badge bg-success">Manasik</span>
                        <h3 class="mt-2">Panduan Lengkap Manasik Haji 2025</h3>
                        <p class="text-muted">Update terbaru sesuai regulasi Kemenag RI.</p>
                        <a href="blog/post.php" class="btn btn-sm btn-outline-success">Baca Selengkapnya</a>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-4">
                <div class="card h-100 shadow-sm border-0">
                    <div class="card-body">
                        <span class="badge bg-warning text-dark">Tips</span>
                        <h3 class="mt-2">5 Kesalahan Tour Leader Pemula</h3>
                        <p class="text-muted">Hindari kesalahan ini saat mendampingi jamaah.</p>
                        <a href="blog/post.php" class="btn btn-sm btn-outline-success">Baca Selengkapnya</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="text-center">
            <a href="blog/index.php" class="btn btn-success" style="background: #0a5f38; border-color: #0a5f38;">Lihat Semua Artikel</a>
        </div>
    </div>
</section>

<!-- Hero Section -->
<div class="hero">
    <div class="container">
        <h1 class="display-5 fw-bold">Pelatihan Tour Leader Haji & Umrah</h1>
        <p class="lead"><?= htmlspecialchars($siteDesc) ?></p>
        <a href="#posts" class="btn btn-emas">Jelajahi Materi</a>
    </div>
</div>



<!-- Konten Utama -->
<div class="container" id="posts">
    <div class="text-center mb-5">
        <h2 class="text-success fw-bold">Materi Pelatihan Terbaru</h2>
        <p class="text-muted">Pelajari ilmu manasik, etika, dan manajemen perjalanan ibadah</p>
    </div>

    <div class="row">
        <?php if ($posts): ?>
            <?php foreach ($posts as $post): ?>
            <div class="col-md-6 col-lg-4 mb-4 fade-in">
                <div class="post-card h-100">
                    <?php if (!empty($post['image'])): ?>
                        <img src="assets/uploads/posts/<?= htmlspecialchars($post['image']) ?>" 
                             class="post-image w-100" 
                             alt="<?= htmlspecialchars($post['title']) ?>">
                    <?php else: ?>
                        <div class="bg-light d-flex align-items-center justify-content-center" style="height:200px;">
                            <i class="bi bi-card-text text-secondary" style="font-size: 3rem;"></i>
                        </div>
                    <?php endif; ?>
                    <div class="card-body">
                        <?php if ($post['category_name']): ?>
                            <span class="post-category"><?= htmlspecialchars($post['category_name']) ?></span>
                        <?php endif; ?>
                        <h3 class="post-title"><?= htmlspecialchars($post['title']) ?></h3>
                        <p class="post-excerpt"><?= substr(strip_tags($post['content']), 0, 150) ?>...</p>
                        <div class="post-meta">
                            <i class="bi bi-calendar"></i> <?= date('d M Y', strtotime($post['created_at'])) ?>
                        </div>
                        <a href="post.php?id=<?= $post['id'] ?>" class="btn btn-sm btn-success mt-3">Baca Selengkapnya</a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-12 text-center py-5">
                <p class="text-muted">Belum ada materi pelatihan.</p>
            </div>
        <?php endif; ?>
    </div>
</div>

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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Navbar scroll effect
    window.addEventListener('scroll', function() {
        const navbar = document.querySelector('.navbar');
        if (window.scrollY > 50) {
            navbar.classList.add('scrolled');
        } else {
            navbar.classList.remove('scrolled');
        }
    });

    // Fade-in animation
    const fadeElements = document.querySelectorAll('.fade-in');
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('appear');
            }
        });
    }, { threshold: 0.1 });

    fadeElements.forEach(el => observer.observe(el));
</script>
</body>
</html>