<?php
require __DIR__ . '/config/koneksi.php';
require __DIR__ . '/config/helpers.php';
wajib_login();

$id = (int)($_GET['id'] ?? 0);
$stmt = $conn->prepare('SELECT * FROM buku WHERE id = ?');
$stmt->bind_param('i', $id);
$stmt->execute();
$buku = $stmt->get_result()->fetch_assoc();

if (!$buku) {
    flash('danger', 'Data buku tidak ditemukan.');
    redirect('dashboard.php');
}

// Pertahankan input jika validasi sebelumnya gagal
if (!empty($_SESSION['old']) && (int)($_SESSION['old']['id'] ?? 0) === $id) {
    $buku = array_merge($buku, $_SESSION['old']);
}
unset($_SESSION['old']);

$judul_halaman = 'Edit buku';
$aksi = 'proses_edit.php';
$tombol = 'Simpan perubahan';
require __DIR__ . '/includes/header.php';
?>
<div class="panel form-panel">
    <h1 class="h4 fw-bold mb-4">Edit buku</h1>
    <?php require __DIR__ . '/includes/form_buku.php'; ?>
</div>
<?php require __DIR__ . '/includes/footer.php'; ?>
