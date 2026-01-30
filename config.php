<?php
// Konfigurasi Database
$host = "localhost";
$user = "root";
$password = "";
$database = "aplikasi_kantin";

// Membuat koneksi
$conn = new mysqli($host, $user, $password, $database);

// Cek koneksi
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set charset
$conn->set_charset("utf8mb4");
?>
