// Jalankan ini sekali di file terpisah (misal: setup.php)
echo password_hash('rahasia123', PASSWORD_DEFAULT);
// Output: $2y$10$abc123... (panjang, acak)