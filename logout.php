<?php
require __DIR__ . '/config/helpers.php';

// Hapus seluruh data session
$_SESSION = [];
if (ini_get('session.use_cookies')) {
    $p = session_get_cookie_params();
    setcookie(session_name(), '', time() - 3600, $p['path'], $p['domain'], $p['secure'], $p['httponly']);
}
session_destroy();

// Hapus cookie Remember Me
setcookie(REMEMBER_COOKIE, '', ['expires' => time() - 3600, 'path' => '/', 'httponly' => true, 'samesite' => 'Lax']);
unset($_COOKIE[REMEMBER_COOKIE]);

// Session baru hanya untuk menampilkan pesan
session_start();
flash('success', 'Anda telah keluar.');
redirect('index.php');
