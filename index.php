<?php
require __DIR__ . '/config/helpers.php';

if (!empty($_SESSION['user_id'])) {
    redirect('dashboard.php');
}

// Isi otomatis username dari cookie "Remember Me"
$username_cookie = $_COOKIE[REMEMBER_COOKIE] ?? '';
$flash = ambil_flash();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Masuk · Mini Digital Library</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="assets/style.css" rel="stylesheet">
</head>
<body class="login-page">
<div class="container">
    <div class="row justify-content-center align-items-center min-vh-100 py-4">
        <div class="col-12 col-md-10 col-lg-9">
            <div class="login-card row g-0">
                <div class="col-md-5 login-aside p-4 p-md-5 d-flex flex-column justify-content-end">
                    <i class="bi bi-journal-bookmark-fill display-4 mb-3"></i>
                    <h1 class="h3 fw-bold">Mini Digital Library</h1>
                    <p class="mb-0 opacity-75">Kelola koleksi buku, sampul, dan ketersediaannya dalam satu tempat.</p>
                </div>
                <div class="col-md-7 p-4 p-md-5 bg-white">
                    <h2 class="h4 fw-bold mb-1">Masuk</h2>
                    <p class="text-secondary mb-4">Gunakan akun petugas perpustakaan.</p>

                    <?php if ($flash): ?>
                        <div class="alert alert-<?= e($flash['tipe']) ?>" role="alert"><?= e($flash['pesan']) ?></div>
                    <?php endif; ?>

                    <form action="proses_login.php" method="post" novalidate>
                        <?= csrf_field() ?>
                        <div class="mb-3">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" class="form-control" id="username" name="username" required autofocus
                                   autocomplete="username" value="<?= e($username_cookie) ?>">
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password" required autocomplete="current-password">
                        </div>
                        <div class="form-check mb-4">
                            <input class="form-check-input" type="checkbox" id="remember" name="remember" value="1" <?= $username_cookie !== '' ? 'checked' : '' ?>>
                            <label class="form-check-label" for="remember">Ingat username saya</label>
                        </div>
                        <button type="submit" class="btn btn-primary w-100 py-2">Masuk</button>
                    </form>
                    <p class="text-secondary small mt-4 mb-0">Akun contoh: <code>admin</code> / <code>admin123</code></p>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
