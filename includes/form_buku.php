<?php
// Form bersama untuk tambah & edit. Variabel: $aksi, $buku (array), $tombol
?>
<form action="<?= e($aksi) ?>" method="post" enctype="multipart/form-data" class="row g-3" novalidate>
    <?= csrf_field() ?>
    <?php if (!empty($buku['id'])): ?>
        <input type="hidden" name="id" value="<?= (int)$buku['id'] ?>">
    <?php endif; ?>

    <div class="col-12">
        <label for="judul" class="form-label">Judul buku</label>
        <input type="text" class="form-control" id="judul" name="judul" maxlength="150" required value="<?= e($buku['judul']) ?>">
    </div>
    <div class="col-md-6">
        <label for="penulis" class="form-label">Penulis</label>
        <input type="text" class="form-control" id="penulis" name="penulis" maxlength="100" required value="<?= e($buku['penulis']) ?>">
    </div>
    <div class="col-md-6">
        <label for="kategori" class="form-label">Kategori</label>
        <select class="form-select" id="kategori" name="kategori" required>
            <?php foreach (daftar_kategori() as $k): ?>
                <option value="<?= e($k) ?>" <?= $buku['kategori'] === $k ? 'selected' : '' ?>><?= e($k) ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="col-md-6">
        <label for="tahun_terbit" class="form-label">Tahun terbit</label>
        <input type="number" class="form-control" id="tahun_terbit" name="tahun_terbit" min="1500" max="<?= date('Y') + 1 ?>" required value="<?= e($buku['tahun_terbit']) ?>">
    </div>
    <div class="col-md-6">
        <label for="stok" class="form-label">Stok tersedia</label>
        <input type="number" class="form-control" id="stok" name="stok" min="0" max="9999" required value="<?= e($buku['stok']) ?>">
        <div class="form-text">Status otomatis: stok lebih dari 0 = Tersedia, stok 0 = Dipinjam.</div>
    </div>
    <div class="col-12">
        <label for="sampul" class="form-label">Sampul buku (.jpg / .png, maks. 2 MB)</label>
        <div class="d-flex gap-3 align-items-start flex-wrap">
            <img id="preview" class="cover-preview" alt="Pratinjau sampul"
                 src="<?= !empty($buku['sampul']) ? 'uploads/' . e($buku['sampul']) : 'assets/no-cover.svg' ?>">
            <div class="flex-grow-1">
                <input type="file" class="form-control" id="sampul" name="sampul" accept=".jpg,.jpeg,.png,image/jpeg,image/png">
                <?php if (!empty($buku['sampul'])): ?>
                    <div class="form-text">Kosongkan jika tidak ingin mengganti sampul.</div>
                <?php endif; ?>
                <div class="text-danger small mt-1 d-none" id="err-file"></div>
            </div>
        </div>
    </div>
    <div class="col-12 d-flex gap-2 pt-2">
        <button type="submit" class="btn btn-primary"><i class="bi bi-check2-circle me-1"></i><?= e($tombol) ?></button>
        <a href="dashboard.php" class="btn btn-outline-secondary">Batal</a>
    </div>
</form>
