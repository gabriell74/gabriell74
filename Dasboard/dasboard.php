<?php
session_start();
if (isset($_SESSION['login_success'])) {
    echo "<script>alert('Login berhasil!');</script>";
    unset($_SESSION['login_success']); 
}
if (!isset($_SESSION['role'])) {
    header("Location: ../Login/login.php?error=not_logged_in");
    exit();
}
if (!isset($_SESSION['role']) || !in_array($_SESSION['role'], ['Staff Gudang'])) {
  echo "<script>
      alert('Anda tidak memiliki akses ke halaman ini.');
      window.location.href = '../Login/login.php';
  </script>";
  exit();
}

$allowed_roles = ['Staff Gudang'];

if (!in_array($_SESSION['role'], $allowed_roles)) {
    echo "<script>
        alert('Anda tidak memiliki akses ke halaman ini.');
        window.location.href = '../Login/login.php';
    </script>";
    exit();
}

require_once '../koneksi.php';
include '../config/function.php';

$total_stok = select("SELECT SUM(kuantitas) AS stok FROM t_bahan_baku");
$stok_masuk = select("SELECT SUM(kuantitas) AS masuk FROM t_bahan_masuk_keluar WHERE id_stok_masuk IS NOT NULL 
                AND MONTH(tanggal) = MONTH(NOW())
                AND YEAR(tanggal) = YEAR(NOW())");
$stok_keluar = select("SELECT SUM(kuantitas) AS keluar FROM t_bahan_masuk_keluar WHERE id_stok_keluar IS NOT NULL 
                AND MONTH(tanggal) = MONTH(NOW())
                AND YEAR(tanggal) = YEAR(NOW())");

$query_kategori = "SELECT * FROM t_kategori";
$result_kategori = mysqli_query($conn, $query_kategori);
if (!$result_kategori) {
    die("Query gagal: " . mysqli_error($conn));
}
$kategori_data = mysqli_fetch_all($result_kategori, MYSQLI_ASSOC);

$selected_kategori = isset($_GET['kategori']) ? $_GET['kategori'] : '';
$selected_bulan = isset($_GET['bulan']) ? $_GET['bulan'] : '';
$selected_tahun = isset($_GET['tahun']) ? $_GET['tahun'] : '';

$where_clause = [];
if ($selected_kategori) {
    $where_clause[] = "id_kategori = '" . mysqli_real_escape_string($conn, $selected_kategori) . "'";
}
if ($selected_bulan && $selected_tahun) {
    $where_clause[] = "MONTH(tanggal) = '" . mysqli_real_escape_string($conn, $selected_bulan) . "' AND YEAR(tanggal) = '" . mysqli_real_escape_string($conn, $selected_tahun) . "'";
}
$where_clause_sql = $where_clause ? "WHERE " . implode(' AND ', $where_clause) : '';

$chart = select("SELECT nama_bahan_baku, 
          MONTH(tanggal) AS bulan,
          YEAR(tanggal) AS tahun,
          SUM(CASE WHEN id_stok_masuk IS NOT NULL THEN kuantitas ELSE 0 END) AS stok_masuk,
          SUM(CASE WHEN id_stok_keluar IS NOT NULL THEN kuantitas ELSE 0 END) AS stok_keluar
          FROM t_bahan_masuk_keluar
          $where_clause_sql
          GROUP BY nama_bahan_baku, MONTH(tanggal), YEAR(tanggal)
          ORDER BY nama_bahan_baku;");

$query_tahun = "SELECT MIN(YEAR(tanggal)) AS tahun_terlama, MAX(YEAR(tanggal)) AS tahun_terbaru FROM t_bahan_masuk_keluar";
$result_tahun = mysqli_query($conn, $query_tahun);
if (!$result_tahun) {
    die("Query gagal: " . mysqli_error($conn));
}
$tahun = mysqli_fetch_assoc($result_tahun);
$tahun_terlama = $tahun['tahun_terlama'] ?? date('Y');
$tahun_terbaru = $tahun['tahun_terbaru'] ?? date('Y');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="../css/dasboard.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>

    <style>
    .chart canvas{
      max-width: 100%;
      max-height: 300px;
    }
    .chart{
      max-width: 100%;
      margin: 0 auto;
      padding: 20px;
      text-align: center;
    }
    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar d-flex justify-content-between align-items-center">
    <div class="d-flex align-items-center">
        <button onclick="openSidebar()" class="me-4">☰</button>
        <!-- <div class="input-group me-4" style="max-width: 300px;">
            <div class="input-group-text">
                <span class="input-group-text bg-light border-0"><i class="fas fa-search"></i></span>
            </div>
            <input type="search" class="form-control" placeholder="Pencarian">
        </div> -->
    </div>
    <div class="d-flex align-items-center">
        <i class="fas fa-user-circle fa-2xl me-2" aria-label="User Icon"></i>
        <span class="navbar-text text-black me-4">
            <?php if (isset($_SESSION['nama_pengguna'])) {
                echo $_SESSION['nama_pengguna'];
                echo "<br>Staff Gudang";
            } ?>
        </span>
    </div>
</nav>

<div id="sidebar" class="sidebar">
    <img src="../Polibatam.png">
    <button class="close-btn" onclick="closeSidebar()">x</button>
    
    <ul>
        <li><a href="../Dasboard/dasboard.php">⏲︎ Beranda</a></li>
        <li><a href="../Kelola Bahan Baku/kelola_bahan.php"><i class="fa-solid fa-table-cells-large"></i> Kelola Stok bahan Baku</a></li>
        <li><a href="../Bahan Baku Masuk/bahan_masuk.php"><i class="fa-solid fa-list-check"></i> Bahan Baku Masuk</a></li>
        <li><a href="../Bahan Baku Keluar/bahan_baku_keluar.php"><i class="fa-regular fa-clipboard"></i> Bahan Baku Keluar</a></li>
        <li><a href="../Kategori/kategori.php"><i class="fa-solid fa-pen-to-square"></i> Kategori Stok Bahan Baku</a></li>
        <div class="exit">
        <li><a href="../Login/logout.php"><i class="fa-solid fa-power-off"></i> Keluar</a></li>
        </div>
    </ul>
  </div>

<!-- Content -->
<div class="main-content">
    <h2>Beranda</h2>
    <div class="row">
        <div class="col-md-6">
            <div class="card text-center">
            <div class="card-body" style="text-align: left;">
                <div class="d-flex justify-content-between">
                    <div class="d-flex align-items-center">
                    <h5><strong>Total Stok Bahan Baku Masuk</strong></h5>
                    </div>
                    <div class="cube d-flex align-items-center">
                        <i class="fa-solid fa-cube"></i>
                    </div>
                </div>
                    <?php foreach ($stok_masuk as $sm): ?>
                        <h2><?= $sm['masuk']; ?></h2>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card text-center">
            <div class="card-body" style="text-align: left;">
                <div class="d-flex justify-content-between">
                    <div class="d-flex align-items-center">
                    <h5><strong>Total Stok Bahan Baku Keluar</strong></h5>
                    </div>
                    <div class="cube d-flex align-items-center">
                    <i class="fa-solid fa-cube icon-background"></i>
                    </div>
                </div>
                    <?php foreach ($stok_keluar as $sk): ?>
                        <h2><?= $sk['keluar']; ?></h2>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Section -->

    <div class="chart mt-4">
        <h3 class="text-center">Grafik Berdasarkan Periode Perbulan (PCS)</h3>
        <div class="row mt-4">
        <form action="" method="get" class="d-flex justify-content-around">
            <div class="col-md-3">
                <select name="kategori" id="kategori" class="form-select">
                    <option value="">Pilih Kategori</option>
                    <?php foreach ($kategori_data as $kategori): ?>
                        <option value="<?= $kategori['id_kategori']; ?>" <?= $selected_kategori == $kategori['id_kategori'] ? 'selected' : ''; ?>>
                            <?= $kategori['nama_kategori']; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-md-3">
                <select name="bulan" id="bulan" class="form-select">
                    <option value="">Pilih Bulan</option>
                    <?php for ($i = 1; $i <= 12; $i++): ?>
                        <option value="<?= $i; ?>" <?= $selected_bulan == $i ? 'selected' : ''; ?>>
                            <?= date('F', mktime(0, 0, 0, $i, 1)); ?>
                        </option>
                    <?php endfor; ?>
                </select>
            </div>

            <div class="col-md-3">
                <select name="tahun" id="tahun" class="form-select">
                    <option value="">Pilih Tahun</option>
                    <?php for ($i = $tahun_terlama; $i <= $tahun_terbaru; $i++): ?>
                        <option value="<?= $i; ?>" <?= $selected_tahun == $i ? 'selected' : ''; ?>>
                            <?= $i; ?>
                        </option>
                    <?php endfor; ?>
                </select>
            </div>

            <div class="col-md-3 align-self-end">
                <button type="submit" class="btn btn-primary w-100"><i class="fas fa-search"></i></button>
            </div>
        </form>
    </div>

        <canvas id="myChart"></canvas>
    </div>
</div>

  <script src="https://kit.fontawesome.com/a076d05399.js"></script>
  <!-- jQuery -->
  <script src="https://code.jquery.com/jquery-3.1.0.js"></script>
  <script src="//cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
  <!-- Bootstrap JS -->
  <!-- Bootstrap 5 -->
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>
  <script src="/sidebar_datatable.js"></script>
  <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    const ctx = document.getElementById('myChart').getContext('2d');
const data = <?= json_encode($chart); ?>;
const labels = data.map(item => item.nama_bahan_baku);
const stokMasuk = data.map(item => item.stok_masuk);
const stokKeluar = data.map(item => item.stok_keluar);

new Chart(ctx, {
    type: 'bar',
    data: {
        labels: labels,
        datasets: [
            {
                label: 'Stok Masuk',
                data: stokMasuk,
                backgroundColor: 'rgba(54, 162, 235, 0.5)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            },
            {
                label: 'Stok Keluar',
                data: stokKeluar,
                backgroundColor: 'rgba(255, 99, 132, 0.5)',
                borderColor: 'rgba(255, 99, 132, 1)',
                borderWidth: 1
            }
        ]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                display: true,
                position: 'bottom' // Label dipindahkan ke bawah
            },
            tooltip: {
                enabled: true // Tooltip tetap aktif
            },
            // Plugin untuk menampilkan angka di atas bar
            datalabels: {
                anchor: 'end',
                align: 'start',
                formatter: (value) => value || '',
                color: '#000',
                font: {
                    size: 12,
                    weight: 'bold'
                }
            }
        },
        scales: {
            x: {
                beginAtZero: true,
                ticks: {
                    autoSkip: false, // Tampilkan semua label di bawah
                    maxRotation: 0,
                    minRotation: 0
                }
            },
            y: {
                beginAtZero: true,
                ticks: {
                    precision: 0 // Bilangan bulat
                }
            }
        }
    },
    plugins: [ChartDataLabels] // Tambahkan plugin datalabels
});
    function openSidebar() {
      document.getElementById("sidebar").style.width = "250px"; // Buka sidebar
      document.querySelector(".main-content").style.marginLeft = "250px"; // Pindahkan konten utama
      document.querySelector(".navbar").style.marginLeft = "250px"; // Pindahkan navbar
    }
    
    function closeSidebar() {
      document.getElementById("sidebar").style.width = "0"; // Tutup sidebar
      document.querySelector(".main-content").style.marginLeft = "0"; // Reset margin konten utama
      document.querySelector(".navbar").style.marginLeft = "0"; // Reset margin navbar
    }
</script>

</body>
</html>
