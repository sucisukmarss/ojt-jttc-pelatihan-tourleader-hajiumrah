<?php
session_start(); 
require_once '../includes/config.php';
require_once '../includes/functions.php';
admin_redirect_if_not_logged_in();

// Ambil kategori untuk dropdown (jika ada)
$categories = [];
try {
    $categories = $pdo->query("SELECT * FROM categories ORDER BY name")->fetchAll();
} catch (Exception $e) {
    // Jika tabel categories belum ada, biarkan kosong
}

// Proses form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $content = $_POST['content'] ?? '';
    $category_id = $_POST['category_id'] ?? 1;

    // Validasi
    if (empty($title) || empty($content)) {
        $error = "Judul dan konten wajib diisi.";
    } else {
        $image = null;
        if (!empty($_FILES['image']['name'])) {
            $allowed_types = ['jpg', 'jpeg', 'png', 'webp'];
            $file_ext = strtolower(pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION));
            
            if (in_array($file_ext, $allowed_types) && $_FILES["image"]["size"] <= 5000000) { // max 5MB
                $image = uniqid() . '_' . basename($_FILES["image"]["name"]);
                $target_file = "../assets/uploads/" . $image;
                if (!move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                    $error = "Gagal mengunggah gambar.";
                }
            } else {
                $error = "Gambar harus berformat JPG, JPEG, PNG, atau WebP (maks. 5MB).";
            }
        }

        if (!isset($error)) {
            $stmt = $pdo->prepare("INSERT INTO posts (title, content, image, category_id) VALUES (?, ?, ?, ?)");
            if ($stmt->execute([$title, $content, $image, $category_id])) {
                header("Location: posts.php?success=1");
                exit;
            } else {
                $error = "Gagal menyimpan posting.";
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
    <title>Tambah Posting - Admin</title>
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
            <a class="nav-link active" href="posts.php">
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
    <h2 class="text-success mb-4">Tambah Posting Baru</h2>

    <?php if (isset($error)): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= htmlspecialchars($error) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="card p-4 shadow-sm">
        <form method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="title" class="form-label fw-bold">Judul</label>
                <input type="text" name="title" id="title" class="form-control" value="<?= htmlspecialchars($_POST['title'] ?? '') ?>" required>
            </div>
            <div class="mb-3">
                <label for="content" class="form-label fw-bold">Isi Konten</label>
                <textarea name="content" id="content" class="form-control" rows="8" required><?= htmlspecialchars($_POST['content'] ?? '') ?></textarea>
            </div>
            
            <?php if (!empty($categories)): ?>
            <div class="mb-3">
                <label for="category_id" class="form-label fw-bold">Kategori</label>
                <select name="category_id" id="category_id" class="form-select">
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <?php endif; ?>

            <div class="mb-3">
                <label for="image" class="form-label fw-bold">Gambar (opsional)</label>
                <input type="file" name="image" id="image" class="form-control" accept="image/jpeg,image/png,image/webp">
                <div class="form-text">Format: JPG, JPEG, PNG, WebP (maks. 5MB)</div>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-success">
                    <i class="bi bi-save me-1"></i> Simpan
                </button>
                <a href="posts.php" class="btn btn-secondary">
                    <i class="bi bi-x-circle me-1"></i> Batal
                </a>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>