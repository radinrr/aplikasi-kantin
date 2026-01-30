<?php
include 'config.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    
    // Gunakan prepared statement untuk menghindari SQL injection
    $stmt = $conn->prepare("SELECT id_pelanggan, nama, password FROM pelanggan WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        
        // Verifikasi password
        if (password_verify($password, $row['password'])) {
            $_SESSION['id_pelanggan'] = $row['id_pelanggan'];
            $_SESSION['nama'] = $row['nama'];
            $_SESSION['email'] = $email;
            
            // Redirect ke dashboard
            header("Location: dashboard.php");
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
