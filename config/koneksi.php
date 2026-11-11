<?php
// Koneksi database MySQL (mysqli). Sesuaikan dengan environment lokal (XAMPP/Laragon).
$db_host = 'localhost';
$db_user = 'root';
$db_pass = '';
$db_name = 'db_perpustakaan';

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);
    $conn->set_charset('utf8mb4');
} catch (mysqli_sql_exception $e) {
    http_response_code(500);
    exit('Koneksi database gagal. Pastikan MySQL aktif dan database "' . $db_name . '" sudah diimpor.');
}
