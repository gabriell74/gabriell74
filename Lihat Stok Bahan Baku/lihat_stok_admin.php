<?php
session_start();

if (!isset($_SESSION['nama_pengguna'])) {
    header("Location: ../Login/login.php");
    exit();
}
if (!isset($_SESSION['role'])) {
  header("Location: ../Login/login.php?error=not_logged_in");
  exit();
}

$allowed_roles = ['Admin'];

if (!in_array($_SESSION['role'], $allowed_roles)) {
  echo "<script>
      alert('Anda tidak memiliki akses ke halaman ini.');
      window.location.href = '../Login/login.php';
  </script>";
  exit();
}

require_once '../koneksi.php';
include '../config/function.php';
$data = select("SELECT * FROM t_bahan_baku");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Lihat Stok Bahan Baku</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <link rel="stylesheet" href="../css/lihat_stok.css">
  <link href="https://cdn.datatables.net/2.1.8/css/dataTables.dataTables.css" rel="stylesheet">

</head>
<body>
    <!-- Navbar -->
    <nav class="navbar d-flex justify-content-between align-items-center">
      <div class="d-flex align-items-center">
          <button onclick="openSidebar()">☰</button>
      </div>
      <div class="d-flex align-items-center">
          <i class="fas fa-user-circle fa-2xl me-2" aria-label="User Icon"></i>
          <span class="navbar-text text-black me-4">
              <?php
              if (isset($_SESSION['nama_pengguna'])) {
                  echo htmlspecialchars($_SESSION['nama_pengguna']) . "<br>Admin";
              }
              ?>
          </span>
      </div>
    </nav>
    
    <!-- Sidebar -->
    <div id="sidebar" class="sidebar">
        <img src="../Polibatam.png">
        <button class="close-btn" onclick="closeSidebar()">x</button>
        <ul>
            <li><a href="../Dasboard admin/dasboard.php">⏲︎ Beranda</a></li>
            <li><a href="#"><i class="fa-solid fa-list-check"></i> Lihat Stock Bahan Baku</a></li>
            <li><a href="../Kelola Pengguna/kelola_pengguna2.php"><i class="fa-solid fa-user-plus"></i> Kelola Pengguna</a></li>
            <li><a href="../Login/logout.php"><i class="fa-solid fa-power-off"></i> Keluar</a></li>
        </ul>
    </div>

    <div class="container-fluid">
    <!-- Content -->
    <div class="content py-4">
      <h2>Lihat Stok Bahan Baku</h2>

      <!-- Search bar and download icon -->
      <div class="text-end align-items-center mb-3">
        <!-- <input type="search" class="form-control" name="pencarian" id="Pencarian" placeholder="Pencarian"> -->
        <a href="laporan.php"><button class="btn btn-light border-0"><i class="fas fa-download" ></i> Laporan Bulan ini</button></a>
      </div>
      
      <form action="laporan.php" method="GET" class="d-flex">
        <input type="number" class="form-control me-2 mb-2" name="bulan" placeholder=" Laporan Bulan Yang Lain" min="1" max="12" required>
        <input type="number" class="form-control me-2 mb-2" name="tahun" placeholder="Tahun" min="2024" max="2100" required>
        <button type="submit" class="btn btn-light border-0 mb-2"><i class="fas fa-download"></i></button>
      </form>

      <!-- Table -->
      <div class="table-responsive">
        <table id="data-table" class="table table-bordered table-striped">
          <thead>
            <tr>
              <th>Id Bahan Baku</th>
              <th>Kode Barcode</th>
              <th>Nama Bahan Baku</th>
              <th>Kategori</th>
              <th>Kuantitas</th>
              <th>Unit</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($data as $d):?>
            <tr>
              <td><?= $d['id_bahan_baku'];?></td>
              <td><?= $d['kode_barcode'];?></td>
              <td><?= $d['nama_bahan_baku'];?></td>
              <td><?= $d['id_kategori'];?></td>
              <td><?= $d['kuantitas'];?></td>
              <td><?= $d['unit'];?></td>
            </tr>
            <?php endforeach;?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- Font Awesome for icons -->
  <script src="https://kit.fontawesome.com/a076d05399.js"></script>
  <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
  <script src="https://cdn.datatables.net/2.1.8/js/dataTables.js"></script>
  <script src="../sidebar_datatable.js"></script>

  <script>
    function openSidebar() {
      document.getElementById("sidebar").style.width = "250px"; // Buka sidebar
      document.querySelector(".content").style.marginLeft = "250px"; // Pindahkan konten utama
      document.querySelector(".navbar").style.marginLeft = "250px"; // Pindahkan navbar
    }
    
    function closeSidebar() {
      document.getElementById("sidebar").style.width = "0"; // Tutup sidebar
      document.querySelector(".content").style.marginLeft = "0"; // Reset margin konten utama
      document.querySelector(".navbar").style.marginLeft = "0"; // Reset margin navbar
    }
    </script>
</body>
</html>
