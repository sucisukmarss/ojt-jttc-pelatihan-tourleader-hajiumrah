<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}
require_once '../includes/config.php';

$id = $_GET['id'] ?? 0;
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$id]);
$user = $stmt->fetch();

if (!$user) die("User tidak ditemukan.");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($username)) {
        $error = "Username wajib diisi!";
    } else {
        if (!empty($password)) {
            // Ganti password
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $pdo->prepare("UPDATE users SET username = ?, password = ? WHERE id = ?")
                ->execute([$username, $hashedPassword, $id]);
        } else {
            // Pertahankan password lama
            $pdo->prepare("UPDATE users SET username = ? WHERE id = ?")
                ->execute([$username, $id]);
        }
        header('Location: users.php?updated=1');
        exit;
    }
}
?>

<h2>Edit User</h2>
<form method="POST">
    <input type="text" name="username" value="<?= htmlspecialchars($user['username']) ?>" class="form-control mb-2" required>
    <input type="password" name="password" placeholder="Biarkan kosong jika tidak ganti password" class="form-control mb-2">
    <button type="submit" class="btn btn-warning">Update</button>
    <a href="users.php" class="btn btn-secondary">Batal</a>
</form>