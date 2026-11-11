<?php
require __DIR__ . '/config/koneksi.php';
require __DIR__ . '/config/helpers.php';
wajib_login();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('dashboard.php');
}
csrf_wajib();

$id = (int)($_POST['id'] ?? 0);

$stmt = $conn->prepare('SELECT sampul FROM buku WHERE id = ?');
$stmt->bind_param('i', $id);
$stmt->execute();
$lama = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$lama) {
    flash('danger', 'Data buku tidak ditemukan.');
    redirect('dashboard.php');
}

$data = [
    'id'           => $id,
    'judul'        => trim($_POST['judul'] ?? ''),
    'kategori'     => $_POST['kategori'] ?? '',
    'penulis'      => trim($_POST['penulis'] ?? ''),
    'tahun_terbit' => trim($_POST['tahun_terbit'] ?? ''),
    'stok'         => trim($_POST['stok'] ?? ''),
];

$errors = validasi_buku($data);
$file_baru = null;

if (!$errors) {
    [$ok, $hasil] = proses_upload_sampul($_FILES['sampul'] ?? null);
    if ($ok) {
        $file_baru = $hasil;
    } else {
        $errors[] = $hasil;
    }
}

if ($errors) {
    $_SESSION['old'] = $data;
    flash('danger', implode(' ', $errors));
    redirect('edit_buku.php?id=' . $id);
}

// Jika ada sampul baru, pakai itu; jika tidak, pertahankan yang lama
$sampul_final = $file_baru ?? $lama['sampul'];
$tahun = (int)$data['tahun_terbit'];
$stok  = (int)$data['stok'];

$stmt = $conn->prepare('UPDATE buku SET judul = ?, kategori = ?, penulis = ?, tahun_terbit = ?, stok = ?, sampul = ? WHERE id = ?');
$stmt->bind_param('sssiisi', $data['judul'], $data['kategori'], $data['penulis'], $tahun, $stok, $sampul_final, $id);

try {
    $stmt->execute();
} catch (mysqli_sql_exception $e) {
    hapus_file_sampul($file_baru);
    flash('danger', 'Perubahan gagal disimpan. Coba lagi.');
    redirect('edit_buku.php?id=' . $id);
}

// Hapus file lama dari server hanya setelah update berhasil
if ($file_baru !== null) {
    hapus_file_sampul($lama['sampul']);
}

flash('success', 'Data buku berhasil diperbarui.');
redirect('dashboard.php');
