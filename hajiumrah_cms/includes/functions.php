<?php
// Fungsi untuk escape output
function esc($str) {
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}

// Fungsi admin (digunakan di halaman lain, BUKAN di login.php)
function is_admin_logged_in() {
    return isset($_SESSION['admin_id']);
}

function admin_redirect_if_not_logged_in() {
    if (!is_admin_logged_in()) {
        header('Location: login.php');
        exit;
    }
}