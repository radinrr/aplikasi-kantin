<?php
session_start();
include 'config.php';

// Cek apakah user sudah login
if (!isset($_SESSION['id_pelanggan'])) {
    header("Location: login.html");
    exit;
}

$id_pelanggan = $_SESSION['id_pelanggan'];
$nama = $_SESSION['nama'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Manajemen Menu</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body { background-color: #f8f9fa; }
        .sidebar { background-color: #006aff; min-height: 100vh; padding: 20px; }
        .sidebar a { color: white; text-decoration: none; display: block; padding: 10px; border-radius: 5px; margin-bottom: 10px; }
        .sidebar a:hover { background-color: #043d8d; }
        .main-content { padding: 20px; }
        .table-hover tbody tr:hover { background-color: #f5f5f5; cursor: pointer; }
        .btn-action { padding: 5px 10px; font-size: 0.9rem; }
        .modal-header { background-color: #006aff; color: white; }
        .form-control:focus { border-color: #006aff; box-shadow: 0 0 0 0.2rem rgba(0, 106, 255, 0.25); }
        .btn-primary { background-color: #006aff; border-color: #006aff; }
        .btn-primary:hover { background-color: #043d8d; border-color: #043d8d; }
        .user-info { background-color: rgba(255,255,255,0.1); padding: 15px; border-radius: 5px; margin-bottom: 20px; color: white; }
    </style>
</head>
<body>

<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-3 sidebar">
            <h4 class="text-white mb-4"><i class="bi bi-shop"></i> Kantin Digital</h4>
            <div class="user-info">
                <p class="mb-1"><small>Logged in as:</small></p>
                <p class="mb-0"><strong><?php echo htmlspecialchars($nama); ?></strong></p>
            </div>
            <a href="#" onclick="loadMenu()"><i class="bi bi-list"></i> Dashboard Menu</a>
            <a href="#" onclick="loadKantin()"><i class="bi bi-building"></i> Manajemen Kantin</a>
            <a href="#" onclick="loadPelanggan()"><i class="bi bi-people"></i> Pelanggan</a>
            <a href="#" onclick="loadPesanan()"><i class="bi bi-bag"></i> Pesanan</a>
            <hr style="border-color: rgba(255,255,255,0.3);">
            <a href="logout.php" class="mt-4"><i class="bi bi-box-arrow-right"></i> Logout</a>
        </div>

        <!-- Main Content -->
        <div class="col-md-9 main-content">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>Manajemen Menu</h2>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#menuModal" onclick="resetForm()">
                    <i class="bi bi-plus-circle"></i> Tambah Menu
                </button>
            </div>

            <!-- Table Menu -->
            <div class="card">
                <div class="table-responsive">
                    <table class="table table-hover mb-0" id="menuTable">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>Nama Menu</th>
                                <th>Kategori</th>
                                <th>Harga</th>
                                <th>Stok</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="menuTableBody">
                            <!-- Data akan dimuat via AJAX -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah/Edit Menu -->
<div class="modal fade" id="menuModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">Tambah Menu Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="menuForm">
                    <input type="hidden" id="id_menu" name="id_menu">
                    <input type="hidden" id="action" name="action" value="create">

                    <div class="mb-3">
                        <label class="form-label">ID Kantin</label>
                        <select class="form-control" id="id_kantin" name="id_kantin" required>
                            <option value="">Pilih Kantin</option>
                            <option value="1">Kantin 1</option>
                            <option value="2">Kantin 2</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Nama Menu</label>
                        <input type="text" class="form-control" id="nama" name="nama" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Kategori</label>
                        <select class="form-control" id="kategori" name="kategori" required>
                            <option value="">Pilih Kategori</option>
                            <option value="Makanan">Makanan</option>
                            <option value="Minuman">Minuman</option>
                            <option value="Snack">Snack</option>
                            <option value="Dessert">Dessert</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Harga (Rp)</label>
                        <input type="number" class="form-control" id="harga" name="harga" step="0.01" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Stok</label>
                        <input type="number" class="form-control" id="stok" name="stok" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" onclick="saveMenu()">Simpan</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Konfirmasi Hapus -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Konfirmasi Hapus</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus menu ini?</p>
                <input type="hidden" id="deleteMenuId">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-danger" onclick="confirmDelete()">Hapus</button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Load Menu Data
    function loadMenu() {
        fetch('menu_management.php?action=read')
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    displayMenuTable(data.data);
                }
            })
            .catch(error => console.error('Error:', error));
    }

    // Display Menu Table
    function displayMenuTable(menus) {
        const tbody = document.getElementById('menuTableBody');
        tbody.innerHTML = '';

        menus.forEach(menu => {
            const row = `
                <tr>
                    <td>${menu.id_menu}</td>
                    <td>${menu.nama}</td>
                    <td>${menu.kategori}</td>
                    <td>Rp ${parseFloat(menu.harga).toLocaleString('id-ID')}</td>
                    <td>${menu.stok}</td>
                    <td>
                        <button class="btn btn-warning btn-action" onclick="editMenu(${menu.id_menu})">
                            <i class="bi bi-pencil"></i> Edit
                        </button>
                        <button class="btn btn-danger btn-action" onclick="deleteMenu(${menu.id_menu})">
                            <i class="bi bi-trash"></i> Hapus
                        </button>
                    </td>
                </tr>
            `;
            tbody.innerHTML += row;
        });
    }

    // Edit Menu
    function editMenu(id_menu) {
        fetch(`menu_management.php?action=read_by_id&id_menu=${id_menu}`)
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    const menu = data.data;
                    document.getElementById('id_menu').value = menu.id_menu;
                    document.getElementById('id_kantin').value = menu.id_kantin;
                    document.getElementById('nama').value = menu.nama;
                    document.getElementById('kategori').value = menu.kategori;
                    document.getElementById('harga').value = menu.harga;
                    document.getElementById('stok').value = menu.stok;
                    document.getElementById('action').value = 'update';
                    document.getElementById('modalTitle').textContent = 'Edit Menu';
                    
                    const modal = new bootstrap.Modal(document.getElementById('menuModal'));
                    modal.show();
                }
            });
    }

    // Save Menu (Create or Update)
    function saveMenu() {
        const form = document.getElementById('menuForm');
        const formData = new FormData(form);

        fetch('menu_management.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                alert(data.message);
                bootstrap.Modal.getInstance(document.getElementById('menuModal')).hide();
                loadMenu();
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => console.error('Error:', error));
    }

    // Delete Menu
    function deleteMenu(id_menu) {
        document.getElementById('deleteMenuId').value = id_menu;
        const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
        modal.show();
    }

    // Confirm Delete
    function confirmDelete() {
        const id_menu = document.getElementById('deleteMenuId').value;
        const formData = new FormData();
        formData.append('action', 'delete');
        formData.append('id_menu', id_menu);

        fetch('menu_management.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                alert(data.message);
                bootstrap.Modal.getInstance(document.getElementById('deleteModal')).hide();
                loadMenu();
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => console.error('Error:', error));
    }

    // Reset Form
    function resetForm() {
        document.getElementById('menuForm').reset();
        document.getElementById('id_menu').value = '';
        document.getElementById('action').value = 'create';
        document.getElementById('modalTitle').textContent = 'Tambah Menu Baru';
    }

    // Load data saat halaman dibuka
    window.onload = function() {
        loadMenu();
    };

    // Placeholder functions
    function loadKantin() { alert('Fitur manajemen kantin akan segera tersedia'); }
    function loadPelanggan() { alert('Fitur pelanggan akan segera tersedia'); }
    function loadPesanan() { alert('Fitur pesanan akan segera tersedia'); }
</script>

</body>
</html>
