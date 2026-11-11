-- Ekspor database Mini Digital Library
-- Import lewat phpMyAdmin (tab Import) atau: mysql -u root < db_perpustakaan.sql
CREATE DATABASE IF NOT EXISTS db_perpustakaan CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE db_perpustakaan;

DROP TABLE IF EXISTS buku;
DROP TABLE IF EXISTS users;

CREATE TABLE users (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    nama VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE buku (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    judul VARCHAR(150) NOT NULL,
    kategori VARCHAR(50) NOT NULL,
    penulis VARCHAR(100) NOT NULL,
    tahun_terbit SMALLINT UNSIGNED NOT NULL,
    stok INT UNSIGNED NOT NULL DEFAULT 0,
    sampul VARCHAR(255) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Akun contoh: admin / admin123 (hash kompatibel dengan password_verify)
INSERT INTO users (username, password, nama) VALUES
('admin', '$6$abcdefghijklmnop$3pm2jX0VbSznjYaMgtxsUnC/HOVhk6SBYQ5G5lJRcYuH9mZ4PCqZAZHKYlVXVZ57RreC7.8QH54XLEM9aIcdi0', 'Petugas Perpustakaan');

INSERT INTO buku (judul, kategori, penulis, tahun_terbit, stok) VALUES
('Pemrograman Web dengan PHP', 'Teknologi', 'Andi Prasetyo', 2021, 3),
('Dasar-dasar Basis Data', 'Teknologi', 'Siti Rahmawati', 2019, 0),
('Laskar Pelangi', 'Sastra', 'Andrea Hirata', 2005, 2);
