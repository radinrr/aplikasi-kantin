<?php
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama = $_POST['nama'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    
    // Validasi
    if ($password !== $confirm_password) {
        header("Location: signup.html?error=Password%20tidak%20cocok");
        exit;
    }
    
    // Cek email sudah terdaftar
    $sql_check = "SELECT * FROM pelanggan WHERE email = '$email'";
    $result_check = $conn->query($sql_check);
    
    if ($result_check->num_rows > 0) {
        header("Location: signup.html?error=Email%20sudah%20terdaftar");
        exit;
    }
    
    // Hash password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    
    // Insert pelanggan baru
    $sql = "INSERT INTO pelanggan (nama, email, password, saldo) VALUES ('$nama', '$email', '$hashed_password', 0)";
    
    if ($conn->query($sql) === TRUE) {
        header("Location: login.html?success=Pendaftaran%20berhasil");
        exit;
    } else {
        header("Location: signup.html?error=Terjadi%20kesalahan");
        exit;
    }
}

$conn->close();
?>
