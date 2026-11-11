<?php
require __DIR__ . '/config/koneksi.php';
require __DIR__ . '/config/helpers.php';
wajib_login();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('tambah_buku.php');
}
csrf_wajib();

$data = [
    'judul'        => trim($_POST['judul'] ?? ''),
    'kategori'     => $_POST['kategori'] ?? '',
    'penulis'      => trim($_POST['penulis'] ?? ''),
    'tahun_terbit' => trim($_POST['tahun_terbit'] ?? ''),
    'stok'         => trim($_POST['stok'] ?? ''),
];

$errors = validasi_buku($data);
$nama_file = null;

if (!$errors) {
    [$ok, $hasil] = proses_upload_sampul($_FILES['sampul'] ?? null);
    if ($ok) {
        $nama_file = $hasil;
    } else {
        $errors[] = $hasil;
    }
}

if ($errors) {
    $_SESSION['old'] = $data + ['sampul' => null];
    flash('danger', implode(' ', $errors));
    redirect('tambah_buku.php');
}

$tahun = (int)$data['tahun_terbit'];
$stok  = (int)$data['stok'];
$stmt = $conn->prepare('INSERT INTO buku (judul, kategori, penulis, tahun_terbit, stok, sampul) VALUES (?, ?, ?, ?, ?, ?)');
$stmt->bind_param('sssiis', $data['judul'], $data['kategori'], $data['penulis'], $tahun, $stok, $nama_file);

try {
    $stmt->execute();
} catch (mysqli_sql_exception $e) {
    hapus_file_sampul($nama_file); // batalkan file jika query gagal
    flash('danger', 'Data gagal disimpan. Coba lagi.');
    redirect('tambah_buku.php');
}

flash('success', 'Buku "' . $data['judul'] . '" berhasil ditambahkan.');
redirect('dashboard.php');
