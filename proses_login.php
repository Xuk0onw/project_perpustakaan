<?php
require __DIR__ . '/config/koneksi.php';
require __DIR__ . '/config/helpers.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('index.php');
}
csrf_wajib();

$username = trim($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';
$remember = isset($_POST['remember']);

if ($username === '' || $password === '') {
    flash('danger', 'Username dan password wajib diisi.');
    redirect('index.php');
}

$stmt = $conn->prepare('SELECT id, username, password, nama FROM users WHERE username = ? LIMIT 1');
$stmt->bind_param('s', $username);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$user || !password_verify($password, $user['password'])) {
    flash('danger', 'Username atau password salah.');
    redirect('index.php');
}

// Login berhasil: ganti ID session untuk mencegah session fixation
session_regenerate_id(true);
$_SESSION['user_id']  = (int)$user['id'];
$_SESSION['username'] = $user['username'];
$_SESSION['nama']     = $user['nama'];

// Cookie "Remember Me": hanya menyimpan username (30 hari), bukan password
$opsi = [
    'expires'  => time() + 60 * 60 * 24 * 30,
    'path'     => '/',
    'httponly' => true,
    'samesite' => 'Lax',
];
if ($remember) {
    setcookie(REMEMBER_COOKIE, $user['username'], $opsi);
} else {
    $opsi['expires'] = time() - 3600;
    setcookie(REMEMBER_COOKIE, '', $opsi);
}

redirect('dashboard.php');
