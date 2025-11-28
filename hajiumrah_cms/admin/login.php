<?php
session_start();
require_once '../includes/config.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username !== '' && $password !== '') {
        // Gunakan $pdo dari config.php
        $stmt = $pdo->prepare("SELECT * FROM admin WHERE username = ?");
        $stmt->execute([$username]);
        $admin = $stmt->fetch();

        if ($admin && password_verify($password, $admin['password'])) {
            $_SESSION['admin_id'] = $admin['id'];
            header('Location: dashboard.php');
            exit;
        }
    }
    $error = "Username atau password salah!";
}

// Fungsi helper (opsional, tapi lebih baik dari functions.php)
function esc($str) {
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login Admin - Pelatihan Haji & Umrah</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #e8f5e9; }
        .card { border-top: 5px solid #28a745; }
        .btn-primary { 
            background-color: #d4af37; 
            border-color: #b8860b; 
        }
        .btn-primary:hover { 
            background-color: #b8860b; 
        }
    </style>
</head>
<body class="d-flex align-items-center justify-content-center vh-100">
    <div class="card p-4" style="width: 100%; max-width: 400px;">
        <h3 class="text-center text-success mb-4">Login Admin</h3>

        <?php if (!empty($error)): ?>
            <div class="alert alert-danger"><?= esc($error) ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-3">
                <label>Username</label>
                <input type="text" name="username" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Login</button>
        </form>
    </div>
</body>
</html>