<?php
include 'config.php';
session_start();

// Check if user is logged in (uncomment ketika sudah ada sistem login)
// if (!isset($_SESSION['id_pelanggan'])) {
//     header("Location: login.html");
//     exit;
// }

// CREATE - Tambah Menu
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'create') {
    $id_kantin = $_POST['id_kantin'];
    $nama = $_POST['nama'];
    $kategori = $_POST['kategori'];
    $harga = $_POST['harga'];
    $stok = $_POST['stok'];
    
    $sql = "INSERT INTO menu (id_kantin, nama, kategori, harga, stok) VALUES ('$id_kantin', '$nama', '$kategori', '$harga', '$stok')";
    
    if ($conn->query($sql) === TRUE) {
        $response = ["status" => "success", "message" => "Menu berhasil ditambahkan"];
    } else {
        $response = ["status" => "error", "message" => "Error: " . $conn->error];
    }
    
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}

// READ - Ambil semua menu atau menu spesifik
if (isset($_GET['action']) && $_GET['action'] == 'read') {
    $sql = "SELECT * FROM menu";
    $result = $conn->query($sql);
    
    $menus = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $menus[] = $row;
        }
    }
    
    header('Content-Type: application/json');
    echo json_encode(["status" => "success", "data" => $menus]);
    exit;
}

// READ - Get menu by ID
if (isset($_GET['action']) && $_GET['action'] == 'read_by_id') {
    $id_menu = $_GET['id_menu'];
    $sql = "SELECT * FROM menu WHERE id_menu = $id_menu";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        header('Content-Type: application/json');
        echo json_encode(["status" => "success", "data" => $row]);
    } else {
        header('Content-Type: application/json');
        echo json_encode(["status" => "error", "message" => "Menu tidak ditemukan"]);
    }
    exit;
}

// UPDATE - Update Menu
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'update') {
    $id_menu = $_POST['id_menu'];
    $nama = $_POST['nama'];
    $kategori = $_POST['kategori'];
    $harga = $_POST['harga'];
    $stok = $_POST['stok'];
    
    $sql = "UPDATE menu SET nama='$nama', kategori='$kategori', harga='$harga', stok='$stok' WHERE id_menu=$id_menu";
    
    if ($conn->query($sql) === TRUE) {
        $response = ["status" => "success", "message" => "Menu berhasil diupdate"];
    } else {
        $response = ["status" => "error", "message" => "Error: " . $conn->error];
    }
    
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}

// DELETE - Hapus Menu
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'delete') {
    $id_menu = $_POST['id_menu'];
    
    $sql = "DELETE FROM menu WHERE id_menu=$id_menu";
    
    if ($conn->query($sql) === TRUE) {
        $response = ["status" => "success", "message" => "Menu berhasil dihapus"];
    } else {
        $response = ["status" => "error", "message" => "Error: " . $conn->error];
    }
    
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}

$conn->close();
?>
