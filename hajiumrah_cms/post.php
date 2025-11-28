<?php
require_once 'includes/config.php';
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
<nav class="navbar navbar-expand-lg" style="background-color: #28a745;">
    <div class="container">
        <a class="navbar-brand text-white" href="index.php">← Kembali ke Beranda</a>
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