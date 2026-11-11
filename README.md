# Mini Digital Library

Aplikasi pengelolaan koleksi buku berbasis **PHP Native + MySQL (mysqli)** dengan autentikasi session, cookie "Remember Me", CRUD, dan upload sampul buku. UI memakai Bootstrap 5.

- **Mata kuliah:** Pemrograman Web
- **Nama / NIM:** khorin berka aditya
- **Video demo:** 

## Fitur
- Login (POST), session, cookie Remember Me (menyimpan username), logout yang menghapus session & cookie
- CRUD buku: tambah, tampil, edit, hapus
- Upload sampul `.jpg/.png` maksimal 2 MB (validasi ekstensi, MIME, dan ukuran); file lama otomatis dihapus saat diganti atau saat data dihapus
- Status buku otomatis dari stok: `stok > 0` = Tersedia, `stok = 0` = Dipinjam
- Pengamanan: prepared statement, `password_hash/password_verify`, token CSRF, `htmlspecialchars`, `session_regenerate_id`

## Cara menjalankan
1. Letakkan folder ini di `htdocs` (XAMPP) atau `www` (Laragon).
2. Import `db_perpustakaan.sql` lewat phpMyAdmin.
3. Sesuaikan kredensial di `config/koneksi.php` bila perlu.
4. Buka `http://localhost/project_perpustakaan/`
5. Login: **admin / admin123**

## Struktur file
```
project_perpustakaan/
├── config/koneksi.php      # koneksi mysqli
├── config/helpers.php      # session, CSRF, validasi, upload, label status
├── includes/               # header, footer, form buku (dipakai tambah & edit)
├── assets/                 # style.css, app.js, no-cover.svg
├── uploads/                # sampul buku
├── index.php               # login
├── proses_login.php        # verifikasi + session/cookie
├── logout.php
├── dashboard.php           # Read + menu
├── tambah_buku.php / proses_tambah.php
├── edit_buku.php / proses_edit.php
└── hapus_buku.php
```

## Struktur tabel
**users**
| Kolom | Tipe | Keterangan |
|---|---|---|
| id | INT UNSIGNED, PK, AI | |
| username | VARCHAR(50), UNIQUE | |
| password | VARCHAR(255) | hash |
| nama | VARCHAR(100) | |
| created_at | TIMESTAMP | |

**buku**
| Kolom | Tipe | Keterangan |
|---|---|---|
| id | INT UNSIGNED, PK, AI | |
| judul | VARCHAR(150) | |
| kategori | VARCHAR(50) | |
| penulis | VARCHAR(100) | |
| tahun_terbit | SMALLINT UNSIGNED | |
| stok | INT UNSIGNED | menentukan status |
| sampul | VARCHAR(255) NULL | nama file di `uploads/` |
| created_at / updated_at | TIMESTAMP | |

## Screenshot
_Ganti dengan screenshot milikmu (simpan di folder `screenshots/`)._

| Halaman | Gambar |
|---|---|
| Login | `![login](screenshots/login.png)` |
| Dashboard | `![dashboard](screenshots/dashboard.png)` |
| Tambah buku | `![tambah](screenshots/tambah.png)` |
| Edit buku | `![edit](screenshots/edit.png)` |
| Hapus (konfirmasi) | `![hapus](screenshots/hapus.png)` |
