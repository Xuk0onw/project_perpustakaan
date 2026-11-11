# Mini Digital Library

Aplikasi web pengelolaan koleksi buku perpustakaan digital, dibangun dengan **PHP Native** dan **MySQL (mysqli)** tanpa framework. Dilengkapi autentikasi (session & cookie), CRUD data buku, dan upload sampul buku.

| | |
|---|---|
| **Mata kuliah** | Pemrograman Web |
| **Program studi** | Sistem Informasi |
| **Nama** | khorin berka aditya |
| **NIM** | 2513020068 |
| **Video demo** | __ |

---

## Daftar Isi
1. [Fitur](#fitur)
2. [Teknologi](#teknologi)
3. [Struktur Folder](#struktur-folder)
4. [Struktur Tabel Database](#struktur-tabel-database)
5. [Cara Instalasi](#cara-instalasi)
6. [Screenshot](#screenshot)
7. [Alur Fitur Utama](#alur-fitur-utama)
8. [Keamanan](#keamanan)

---

## Fitur

### 1. Autentikasi & Hak Akses
- **Login** dengan form metode POST (username & password).
- **Session**: hanya pengguna yang sudah login yang bisa membuka Dashboard dan mengelola buku.
- **Cookie "Remember Me"**: menyimpan username di browser selama 30 hari.
- **Logout**: menghapus session dan cookie, lalu kembali ke halaman login.

### 2. CRUD Data Buku
- **Create**: tambah buku (judul, kategori, penulis, tahun terbit, stok).
- **Read**: daftar buku dalam tabel HTML, lengkap dengan pencarian dan ringkasan jumlah.
- **Update**: ubah data buku dan opsi mengganti sampul.
- **Delete**: hapus data dari MySQL sekaligus file gambar di folder `uploads/`.

### 3. Upload File Sampul
- Format yang diizinkan: `.jpg` dan `.png`.
- Ukuran maksimal: **2 MB**.
- Validasi ekstensi, tipe MIME, dan isi gambar.
- File disimpan di `uploads/` dengan nama acak; nama file dicatat di database.
- File lama otomatis dihapus saat sampul diganti atau buku dihapus.

### 4. Logika Bisnis
- Status ketersediaan ditentukan dengan percabangan if-else:
  - stok **> 0** → **Tersedia**
  - stok **= 0** → **Dipinjam**

---

## Teknologi
- PHP 8 (Native) dan MySQL dengan ekstensi `mysqli`
- Bootstrap 5.3 dan Bootstrap Icons (CDN)
- JavaScript (konfirmasi hapus, pratinjau dan validasi sampul di sisi klien)

---

## Struktur Folder

```
project_perpustakaan/
├── config/
│   ├── koneksi.php          # Koneksi ke database MySQL
│   └── helpers.php          # Session, CSRF, validasi, upload, label status
├── includes/
│   ├── header.php           # Navbar & flash message
│   ├── footer.php
│   └── form_buku.php        # Form bersama untuk tambah & edit
├── assets/
│   ├── style.css
│   ├── app.js
│   └── no-cover.svg
├── uploads/                 # Penampung sampul buku
├── screenshots/             # Screenshot untuk README
├── index.php                # Halaman login
├── proses_login.php         # Verifikasi login, session & cookie
├── logout.php               # Hapus session & cookie
├── dashboard.php            # Tampil data (Read) & menu
├── tambah_buku.php          # Form tambah
├── proses_tambah.php        # Insert & upload file
├── edit_buku.php            # Form edit
├── proses_edit.php          # Update & penggantian file
├── hapus_buku.php           # Delete & unlink file
└── db_perpustakaan.sql      # Ekspor database
```

---

## Struktur Tabel Database

Nama database: `db_perpustakaan`

### Tabel `users`
| Kolom | Tipe | Keterangan |
|---|---|---|
| `id` | INT UNSIGNED | Primary key, auto increment |
| `username` | VARCHAR(50) | Unik |
| `password` | VARCHAR(255) | Disimpan dalam bentuk hash |
| `nama` | VARCHAR(100) | Nama tampilan |
| `created_at` | TIMESTAMP | Waktu dibuat |

### Tabel `buku`
| Kolom | Tipe | Keterangan |
|---|---|---|
| `id` | INT UNSIGNED | Primary key, auto increment |
| `judul` | VARCHAR(150) | Judul buku |
| `kategori` | VARCHAR(50) | Kategori buku |
| `penulis` | VARCHAR(100) | Nama penulis |
| `tahun_terbit` | SMALLINT UNSIGNED | Tahun terbit |
| `stok` | INT UNSIGNED | Menentukan status Tersedia / Dipinjam |
| `sampul` | VARCHAR(255) NULL | Nama file di folder `uploads/` |
| `created_at` | TIMESTAMP | Waktu dibuat |
| `updated_at` | TIMESTAMP | Waktu terakhir diubah |

---

## Cara Instalasi

**Kebutuhan:** XAMPP / Laragon (PHP 8+ dan MySQL), serta browser.

1. Clone repositori ke folder web server:
   ```bash
   git clone <link-repositori-anda> project_perpustakaan
   ```
   Letakkan di `htdocs` (XAMPP) atau `www` (Laragon).
2. Jalankan **Apache** dan **MySQL**.
3. Buka phpMyAdmin, pilih **Import**, lalu pilih file `db_perpustakaan.sql`.
4. Jika perlu, sesuaikan kredensial di `config/koneksi.php`.
5. Buka `http://localhost/project_perpustakaan/`.

**Akun contoh**

| Username | Password |
|---|---|
| `admin` | `admin123` |

---

## Screenshot

> Ganti dengan screenshot milik sendiri, simpan di folder `screenshots/`.

### Halaman Login
![Login](screenshots/login.png)

### Login dengan "Remember Me"
![Remember Me](screenshots/remember-me.png)

### Dashboard (Read)
![Dashboard](screenshots/dashboard.png)

### Tambah Buku (Create + Upload Sampul)
![Tambah buku](screenshots/tambah.png)

### Edit Buku (Update)
![Edit buku](screenshots/edit.png)

### Konfirmasi Hapus (Delete)
![Hapus buku](screenshots/hapus.png)

### Validasi Upload (file tidak valid / lebih dari 2 MB)
![Validasi upload](screenshots/validasi-upload.png)

---

## Alur Fitur Utama

**Login**
`index.php` → `proses_login.php` (cek username dengan prepared statement, `password_verify`, buat session, atur cookie) → `dashboard.php`

**Tambah buku**
`tambah_buku.php` → `proses_tambah.php` (validasi input → validasi dan simpan file → insert ke database) → `dashboard.php`

**Edit buku**
`edit_buku.php` → `proses_edit.php` (validasi → jika ada sampul baru: simpan file baru, update database, hapus file lama) → `dashboard.php`

**Hapus buku**
Tombol Hapus (POST + konfirmasi) → `hapus_buku.php` (ambil nama file → delete data → `unlink` file) → `dashboard.php`

---

## Keamanan
- Prepared statement pada semua query (mencegah SQL injection)
- Password disimpan dengan hash dan diverifikasi dengan `password_verify`
- `session_regenerate_id` setelah login (mencegah session fixation)
- Token CSRF pada semua form POST
- Output di-escape dengan `htmlspecialchars` (mencegah XSS)
- Validasi tipe MIME dan isi file upload, bukan hanya ekstensi
- Cookie `HttpOnly` dan hanya menyimpan username

---

## Catatan Pengembang
Tugas individu mata kuliah Pemrograman Web, Program Studi Sistem Informasi.