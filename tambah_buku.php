<?php
require __DIR__ . '/config/helpers.php';
wajib_login();

$buku = $_SESSION['old'] ?? ['judul' => '', 'kategori' => 'Teknologi', 'penulis' => '', 'tahun_terbit' => date('Y'), 'stok' => 1, 'sampul' => null];
unset($_SESSION['old']);

$judul_halaman = 'Tambah buku';
$aksi = 'proses_tambah.php';
$tombol = 'Simpan buku';
require __DIR__ . '/includes/header.php';
?>
<div class="panel form-panel">
    <h1 class="h4 fw-bold mb-4">Tambah buku</h1>
    <?php require __DIR__ . '/includes/form_buku.php'; ?>
</div>
<?php require __DIR__ . '/includes/footer.php'; ?>
