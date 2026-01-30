<?php
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama = $_POST['nama'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    
    // Validasi
    if ($password !== $confirm_password) {
        header("Location: signup.html?error=Password%20tidak%20cocok");
        exit;
    }
    
    if (strlen($password) < 6) {
        header("Location: signup.html?error=Password%20minimal%206%20karakter");
        exit;
    }
    
    // Cek email sudah terdaftar menggunakan prepared statement
    $stmt_check = $conn->prepare("SELECT id_pelanggan FROM pelanggan WHERE email = ?");
    $stmt_check->bind_param("s", $email);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();
    
    if ($result_check->num_rows > 0) {
        header("Location: signup.html?error=Email%20sudah%20terdaftar");
        exit;
    }
    
    // Hash password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    
    // Insert pelanggan baru menggunakan prepared statement
    $stmt = $conn->prepare("INSERT INTO pelanggan (nama, email, password, saldo) VALUES (?, ?, ?, ?)");
    $saldo = 0;
    $stmt->bind_param("sssd", $nama, $email, $hashed_password, $saldo);
    
    if ($stmt->execute()) {
        header("Location: login.html?success=Pendaftaran%20berhasil");
        exit;
    } else {
        header("Location: signup.html?error=Terjadi%20kesalahan:%20" . urlencode($conn->error));
        exit;
    }
}

$conn->close();
?>
