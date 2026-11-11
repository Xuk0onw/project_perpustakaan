<?php
require __DIR__ . '/config/koneksi.php';
require __DIR__ . '/config/helpers.php';
wajib_login();

// Hapus lewat POST + token CSRF agar tidak bisa dipicu dari link biasa
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('dashboard.php');
}
csrf_wajib();

$id = (int)($_POST['id'] ?? 0);

$stmt = $conn->prepare('SELECT judul, sampul FROM buku WHERE id = ?');
$stmt->bind_param('i', $id);
$stmt->execute();
$buku = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$buku) {
    flash('danger', 'Data buku tidak ditemukan.');
    redirect('dashboard.php');
}

$stmt = $conn->prepare('DELETE FROM buku WHERE id = ?');
$stmt->bind_param('i', $id);
$stmt->execute();

hapus_file_sampul($buku['sampul']); // unlink file gambar

flash('success', 'Buku "' . $buku['judul'] . '" berhasil dihapus.');
redirect('dashboard.php');
