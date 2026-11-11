<?php
// Session, autentikasi, CSRF, flash message, logika bisnis & upload file.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

const UPLOAD_DIR  = __DIR__ . '/../uploads/';
const MAX_UPLOAD  = 2 * 1024 * 1024; // 2 MB
const REMEMBER_COOKIE = 'remember_username';

function e($teks) {
    return htmlspecialchars((string)$teks, ENT_QUOTES, 'UTF-8');
}

function redirect($url) {
    header('Location: ' . $url);
    exit;
}

/* ---------- Autentikasi ---------- */
function wajib_login() {
    if (empty($_SESSION['user_id'])) {
        flash('warning', 'Silakan login terlebih dahulu.');
        redirect('index.php');
    }
}

/* ---------- Flash message ---------- */
function flash($tipe, $pesan) {
    $_SESSION['flash'] = ['tipe' => $tipe, 'pesan' => $pesan];
}

function ambil_flash() {
    $f = $_SESSION['flash'] ?? null;
    unset($_SESSION['flash']);
    return $f;
}

/* ---------- CSRF ---------- */
function csrf_token() {
    if (empty($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf'];
}

function csrf_field() {
    return '<input type="hidden" name="csrf" value="' . csrf_token() . '">';
}

function csrf_wajib() {
    $kirim = $_POST['csrf'] ?? '';
    if (!hash_equals($_SESSION['csrf'] ?? '', $kirim)) {
        http_response_code(419);
        exit('Token keamanan tidak valid. Silakan kembali dan coba lagi.');
    }
}

/* ---------- Logika bisnis: label status (if-else) ---------- */
function label_status($stok) {
    if ($stok > 0) {
        return ['label' => 'Tersedia', 'kelas' => 'text-bg-success'];
    } else {
        return ['label' => 'Dipinjam', 'kelas' => 'text-bg-warning'];
    }
}

function daftar_kategori() {
    return ['Teknologi', 'Sains', 'Sastra', 'Bisnis', 'Sejarah', 'Pendidikan', 'Lainnya'];
}

/* ---------- Validasi input buku ---------- */
function validasi_buku($data) {
    $err = [];
    $tahun_maks = (int)date('Y') + 1;

    if (trim($data['judul']) === '' || mb_strlen($data['judul']) > 150) {
        $err[] = 'Judul wajib diisi (maksimal 150 karakter).';
    }
    if (!in_array($data['kategori'], daftar_kategori(), true)) {
        $err[] = 'Kategori tidak valid.';
    }
    if (trim($data['penulis']) === '' || mb_strlen($data['penulis']) > 100) {
        $err[] = 'Penulis wajib diisi (maksimal 100 karakter).';
    }
    if (!ctype_digit($data['tahun_terbit']) || (int)$data['tahun_terbit'] < 1500 || (int)$data['tahun_terbit'] > $tahun_maks) {
        $err[] = 'Tahun terbit harus berupa angka antara 1500 dan ' . $tahun_maks . '.';
    }
    if (!ctype_digit($data['stok']) || (int)$data['stok'] > 9999) {
        $err[] = 'Stok harus berupa angka 0 sampai 9999.';
    }
    return $err;
}

/* ---------- Upload sampul ----------
   Return: [true, nama_file_baru|null] atau [false, pesan_error]
   Jika tidak ada file dipilih => [true, null] */
function proses_upload_sampul($file) {
    if (!isset($file) || $file['error'] === UPLOAD_ERR_NO_FILE) {
        return [true, null];
    }
    if ($file['error'] === UPLOAD_ERR_INI_SIZE || $file['error'] === UPLOAD_ERR_FORM_SIZE) {
        return [false, 'Ukuran file melebihi batas server (maksimal 2 MB).'];
    }
    if ($file['error'] !== UPLOAD_ERR_OK) {
        return [false, 'Upload gagal (kode error ' . $file['error'] . ').'];
    }
    if ($file['size'] > MAX_UPLOAD) {
        return [false, 'Ukuran sampul maksimal 2 MB.'];
    }

    $ekstensi_ok = ['jpg', 'jpeg', 'png'];
    $ekstensi = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if (!in_array($ekstensi, $ekstensi_ok, true)) {
        return [false, 'Sampul harus berformat .jpg atau .png.'];
    }

    // Cek isi file yang sebenarnya, bukan hanya ekstensi
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mime  = $finfo->file($file['tmp_name']);
    if (!in_array($mime, ['image/jpeg', 'image/png'], true) || @getimagesize($file['tmp_name']) === false) {
        return [false, 'Isi file bukan gambar JPG/PNG yang valid.'];
    }

    if (!is_dir(UPLOAD_DIR)) {
        mkdir(UPLOAD_DIR, 0755, true);
    }
    $nama_baru = 'sampul_' . bin2hex(random_bytes(8)) . '.' . ($mime === 'image/png' ? 'png' : 'jpg');
    if (!move_uploaded_file($file['tmp_name'], UPLOAD_DIR . $nama_baru)) {
        return [false, 'File gagal disimpan ke folder uploads.'];
    }
    return [true, $nama_baru];
}

function hapus_file_sampul($nama) {
    if ($nama) {
        $path = UPLOAD_DIR . basename($nama); // basename mencegah path traversal
        if (is_file($path)) {
            unlink($path);
        }
    }
}
