<?php
require __DIR__ . '/config/koneksi.php';
require __DIR__ . '/config/helpers.php';
wajib_login();

$cari = trim($_GET['q'] ?? '');

// Ringkasan
$ringkas = $conn->query('SELECT COUNT(*) AS total, SUM(stok > 0) AS tersedia, SUM(stok = 0) AS dipinjam FROM buku')->fetch_assoc();

// Read: daftar buku (dengan pencarian opsional)
if ($cari !== '') {
    $like = '%' . $cari . '%';
    $stmt = $conn->prepare('SELECT * FROM buku WHERE judul LIKE ? OR penulis LIKE ? OR kategori LIKE ? ORDER BY id DESC');
    $stmt->bind_param('sss', $like, $like, $like);
    $stmt->execute();
    $hasil = $stmt->get_result();
} else {
    $hasil = $conn->query('SELECT * FROM buku ORDER BY id DESC');
}

$judul_halaman = 'Dashboard';
require __DIR__ . '/includes/header.php';
?>
<div class="row g-3 mb-4">
    <div class="col-12 col-md-4"><div class="stat"><span class="stat-num"><?= (int)$ringkas['total'] ?></span><span>Total judul</span></div></div>
    <div class="col-6 col-md-4"><div class="stat"><span class="stat-num"><?= (int)$ringkas['tersedia'] ?></span><span>Tersedia</span></div></div>
    <div class="col-6 col-md-4"><div class="stat"><span class="stat-num"><?= (int)$ringkas['dipinjam'] ?></span><span>Sedang dipinjam</span></div></div>
</div>

<div class="panel">
    <div class="d-flex flex-wrap gap-2 justify-content-between align-items-center mb-3">
        <h1 class="h4 fw-bold mb-0">Koleksi buku</h1>
        <div class="d-flex gap-2">
            <form method="get" class="d-flex gap-2" role="search">
                <input type="search" class="form-control" name="q" placeholder="Cari judul, penulis, kategori" value="<?= e($cari) ?>">
                <button class="btn btn-outline-secondary" aria-label="Cari"><i class="bi bi-search"></i></button>
            </form>
            <a href="tambah_buku.php" class="btn btn-primary text-nowrap"><i class="bi bi-plus-lg me-1"></i>Tambah buku</a>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead>
                <tr><th>#</th><th>Sampul</th><th>Judul</th><th>Kategori</th><th>Penulis</th><th>Tahun</th><th>Stok</th><th>Status</th><th class="text-end">Aksi</th></tr>
            </thead>
            <tbody>
            <?php $no = 1; while ($b = $hasil->fetch_assoc()): $st = label_status((int)$b['stok']); ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><img class="cover-thumb" alt="Sampul <?= e($b['judul']) ?>"
                             src="<?= $b['sampul'] ? 'uploads/' . e($b['sampul']) : 'assets/no-cover.svg' ?>"></td>
                    <td class="fw-semibold"><?= e($b['judul']) ?></td>
                    <td><?= e($b['kategori']) ?></td>
                    <td><?= e($b['penulis']) ?></td>
                    <td><?= (int)$b['tahun_terbit'] ?></td>
                    <td><?= (int)$b['stok'] ?></td>
                    <td><span class="badge <?= $st['kelas'] ?>"><?= $st['label'] ?></span></td>
                    <td class="text-end text-nowrap">
                        <a href="edit_buku.php?id=<?= (int)$b['id'] ?>" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil-square"></i> Edit</a>
                        <form action="hapus_buku.php" method="post" class="d-inline form-hapus" data-judul="<?= e($b['judul']) ?>">
                            <?= csrf_field() ?>
                            <input type="hidden" name="id" value="<?= (int)$b['id'] ?>">
                            <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i> Hapus</button>
                        </form>
                    </td>
                </tr>
            <?php endwhile; ?>
            <?php if ($no === 1): ?>
                <tr><td colspan="9" class="text-center text-secondary py-5">
                    <?= $cari !== '' ? 'Tidak ada buku yang cocok dengan "' . e($cari) . '".' : 'Belum ada buku. Klik "Tambah buku" untuk memulai.' ?>
                </td></tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php require __DIR__ . '/includes/footer.php'; ?>
