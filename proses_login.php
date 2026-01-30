<?php
include 'config.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];
    
    // Query untuk cek pelanggan (pastikan ada field email di tabel pelanggan)
    $sql = "SELECT * FROM pelanggan WHERE email = '$email'";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        
        // Verifikasi password (gunakan password_verify untuk keamanan)
        if (password_verify($password, $row['password'])) {
            $_SESSION['id_pelanggan'] = $row['id_pelanggan'];
            $_SESSION['nama'] = $row['nama'];
            
            // Redirect ke dashboard
            header("Location: dashboard.html");
            exit;
        } else {
            header("Location: login.html?error=Password%20salah");
            exit;
        }
    } else {
        header("Location: login.html?error=Email%20tidak%20ditemukan");
        exit;
    }
}

$conn->close();
?>
