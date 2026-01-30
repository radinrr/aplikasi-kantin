<?php
include 'config.php';

$nama = 'Test User';
$email = 'test@example.com';
$password = '123456';
$saldo = 50000;

$hashed_password = password_hash($password, PASSWORD_DEFAULT);

$stmt = $conn->prepare("INSERT INTO pelanggan (nama, email, password, saldo) VALUES (?, ?, ?, ?)");
$stmt->bind_param("sssd", $nama, $email, $hashed_password, $saldo);

if ($stmt->execute()) {
    echo "✅ User berhasil dibuat!\n";
    echo "Email: $email\n";
    echo "Password: $password\n";
} else {
    echo "❌ Error: " . $conn->error . "\n";
}

$conn->close();
?>
