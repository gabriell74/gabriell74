<?php
session_start();

if (!isset($_SESSION['nama_pengguna'])) {
    header("Location: ../Login/login.php");
    exit();
}
if (!isset($_SESSION['role']) || !in_array($_SESSION['role'], ['Staff Gudang'])) {
  echo "<script>
      alert('Anda tidak memiliki akses ke halaman ini.');
      window.location.href = '../Login/login.php';
  </script>";
  exit();
}

require_once '../koneksi.php';
include '../config/function.php';
$dropdown = select("SELECT nama_bahan_baku, (CASE WHEN id_kategori IS NULL THEN 0 ELSE 1 END) AS has_kategori 
FROM t_bahan_baku " );

$query_tahun = "SELECT MIN(YEAR(tanggal)) AS tahun_terlama, MAX(YEAR(tanggal)) AS tahun_terbaru FROM t_bahan_masuk_keluar";
$result_tahun = mysqli_query($conn, $query_tahun);
$tahun = mysqli_fetch_assoc($result_tahun);

$tahun_terlama = $tahun['tahun_terlama'] ?? date('Y');
$tahun_terbaru = $tahun['tahun_terbaru'] ?? date('Y');

$query_kategori = "SELECT * FROM t_kategori";
$result_kategori = mysqli_query($conn, $query_kategori);

$filter_kategori = isset($_GET['kategori']) && $_GET['kategori'] !== '' ? $_GET['kategori'] : null;

$query_data_tabel = "SELECT * FROM t_bahan_masuk_keluar WHERE id_stok_masuk IS NOT NULL";
if ($filter_kategori) {
    $query_data_tabel .= " AND id_kategori = '" . mysqli_real_escape_string($conn, $filter_kategori) . "'";
}

$data_tabel = select($query_data_tabel);

$result = select("SELECT id_transaksi FROM t_bahan_masuk_keluar");

?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Bahan Baku Masuk</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../css/bahan_masuk.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <link href="https://cdn.datatables.net/2.1.8/css/dataTables.dataTables.css" rel="stylesheet">

  
  <!-- jQuery -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <!-- jQuery UI -->
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
  <style>
.input-group{
  height:fit-content ;
  width: 230px;
}
</style>

</head>
<body>
  
    <!-- Navbar -->
    <nav class="navbar d-flex justify-content-between align-items-center">
      <div class="d-flex align-items-center">
          <button onclick="openSidebar()" class="me-4">☰</button>
          <!-- <div class="input-group me-2" style="max-width: 300px;">
              <div class="input-group-text">
                  <span class="input-group-text bg-light border-0"><i class="fas fa-search"></i></span>
              </div>
              <input type="search" class="form-control" placeholder="Pencarian">
          </div> -->
      </div>
      <div class="d-flex align-items-center">
          <i class="fas fa-user-circle fa-2xl me-2" aria-label="User  Icon"></i>
          <span class="navbar-text text-black me-4"><?php if (isset($_SESSION['nama_pengguna'])) { echo $_SESSION['nama_pengguna']; 
              echo "<br>Staff Gudang";}?></span>
      </div>
    </nav>

    
     <!-- Sidebar -->
  <div id="sidebar" class="sidebar">
    <img src="../Polibatam.png">
    <button class="close-btn" onclick="closeSidebar()">x</button>
    
    <ul>
        <li><a href="../Dasboard/dasboard.php">⏲︎ Beranda</a></li>
        <li><a href="../Kelola Bahan Baku/kelola_bahan.php"><i class="fa-solid fa-table-cells-large"></i> Kelola Stok bahan Baku</a></li>
        <li><a href="#"><i class="fa-solid fa-list-check"></i> Bahan Baku Masuk</a></li>
        <li><a href="../Bahan Baku Keluar/bahan_baku_keluar.php"><i class="fa-regular fa-clipboard"></i> Bahan Baku Keluar</a></li>
        <li><a href="../Kategori/kategori.php"><i class="fa-solid fa-pen-to-square"></i>Kategori Stok Bahan Baku</a></li>
        <div class="exit">
        <li><a href="../Login/logout.php"><i class="fa-solid fa-power-off"></i> Keluar</a></li>
        </div>
    </ul>
  </div>

    <!-- Content -->
    <div class="container-fluid">
    <div class="content py-4">
      <h2>Bahan Baku Masuk</h2>
      
          <!-- Filter bar-->
      <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="btn-group" role="group">
                <div class="dropdown">
    <form method="GET" action="">
        <select class="form-select" name="kategori" onchange="this.form.submit()">
            <option value="">Semua Kategori</option>
            <?php while ($kategori = mysqli_fetch_assoc($result_kategori)) { ?>
                <option value="<?= htmlspecialchars($kategori['id_kategori']); ?>" 
                    <?= isset($_GET['kategori']) && $_GET['kategori'] == $kategori['id_kategori'] ? 'selected' : ''; ?>>
                    <?= htmlspecialchars($kategori['id_kategori'])?> (<?= htmlspecialchars($kategori['nama_kategori']); ?>)
                </option>
            <?php } ?>
        </select>
    </form>
</div>
<div class="input-group">
    <input type="month" id="monthpicker" class="form-select" />
    <button type="submit" id="apply-filter" class="btn btn-light border">
        <i class="fas fa-search"></i>
    </button>
                    <a href="bahan_masuk.php"><button type="button" class="btn btn-light border text-danger">
                        <i class="fa-solid fa-rotate-left"></i> Ulangi
                    </button></a>
                </div>
</div>
                <!-- Tombol Tambah -->
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#myModal">Tambah</button>
            </div>

<div id="myModal" class="modal fade" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <!-- Modal Content -->
    <div class="modal-content">
      <!-- Modal Header -->
      <div class="modal-header bg-info">
        <h5 class="modal-title" id="myModalLabel"><strong>Tambah Bahan Baku Masuk</strong></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <!-- Modal Body -->
      <div class="modal-body">
        <form method="POST" action="tambah_stok.php">
          <div class="mb-3">
            <label for="bahanBaku" class="form-label">Nama Bahan Baku</label>
            <div class="dropdown">
              <select class="form-control" name="nama_bahan_baku">
                <option value="" hidden>Pilih Bahan Baku</option>
                 
                 <optgroup label="Bahan Baku">
                  <?php foreach ($dropdown as $d) : ?>
                 <option value="<?= $d['nama_bahan_baku'];?>"
                 <?= $d['has_kategori'] == 0 ? 'disabled' : ''; ?>>
                    <?= $d['nama_bahan_baku'];?>
                    <?= $d['has_kategori'] == 0 ? '(Tidak memiliki kategori)' : ''; ?>
                 </option>
                 <?php endforeach;?>
                </optgroup>
        
                  </select>
            </div>
          </div>

          <div class="mb-3">
            <label for="kuantitas" class="form-label">Kuantitas</label>
            <input type="number" class="form-control" id="kuantitas" name="kuantitas" placeholder="Masukkan Kuantitas" min="0" required>
          </div>
          
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            <button type="submit" class="btn btn-primary" name="tambah">Simpan</button>
          </div>
        </form>
        </div>
      </div>
    </div>
      </div>
      
      <?php foreach ($data_tabel as $tabel):?>
<!--edit modal-->
 <!-- Modal -->
 <div id="editModal<?= $tabel['id_stok_masuk'];?>" class="modal fade" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <!-- Modal Content -->
    <div class="modal-content">
      <!-- Modal Header -->
      <div class="modal-header bg-info">
        <h5 class="modal-title" id="myModalLabel"><strong>Perbarui Bahan Baku Masuk</strong></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <!-- Modal Body -->
      <div class="modal-body">
        <form method="POST" action="update_stok.php">
          
        <input type="hidden" id="id_transaksi" name="id_transaksi" value="<?= $tabel['id_transaksi'];?>" >

          <div class="mb-3">
            <label for="nama_bahan_baku" class="form-label">Nama Bahan Baku</label>
            <input type="text" class="form-control" id="nama_bahan_baku" name="nama_bahan_baku" value="<?= $tabel['nama_bahan_baku']; ?>" readonly>
          </div>

          <div class="mb-3">
            <label for="kuantitas" class="form-label">Kuantitas</label>
            <input type="number" class="form-control" id="kuantitas" name="kuantitas" value="<?= $tabel['kuantitas']; ?>" min="0">
          </div>
          
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            <button type="submit" class="btn btn-primary" name="ubah">Simpan</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
            
            <!-- Info -->
    <div id="userModal<?= $tabel['id_transaksi']; ?>" class="modal fade" tabindex="-1" aria-labelledby="hapusModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-info">
                    <h5 class="modal-title" id="myModalLabel"><strong>Transaksi</strong></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <?php
                    $pengguna = select("SELECT nama_pengguna FROM t_bahan_masuk_keluar WHERE id_transaksi = {$tabel['id_transaksi']}");
                    foreach ($pengguna as $p) {
                        echo "<p>Transaksi ini ditambahkan oleh: <strong>{$p['nama_pengguna']}</strong></p>";
                    }
                    ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-info" data-bs-dismiss="modal">Ok</button>
                </div>
            </div>
        </div>
    </div>
    <?php endforeach; ?>


            
      <!-- Table -->
      <div class="table-responsive">
        <table id="data-table" class="table table-bordered table-striped"> <!--Tipe Border dari bootstrap-->
          <thead>
            <tr>
              <th>ID Stok Masuk</th>
              <th>Kode Barcode</th>
              <th>Nama Bahan Baku</th>
              <th>Kategori</th>
              <th>Kuantitas</th>
              <th>Tanggal Masuk</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($data_tabel as $tabel):?>
            <tr data-tanggal="<?= date('Y-m', strtotime($tabel['tanggal'])); ?>">
              <td><?= $tabel['id_stok_masuk'];?></td>
              <td><?= $tabel['kode_barcode'];?></td>
              <td><?= $tabel['nama_bahan_baku'];?></td>
              <td><?= $tabel['id_kategori'];?></td>
              <td><?= $tabel['kuantitas'];?></td>
              <td><?= date('d-m-Y', strtotime($tabel['tanggal']));?></td>
              <td>
                <button class="btn btn-light btn-sm"  data-bs-toggle="modal" data-bs-target="#editModal<?= $tabel['id_stok_masuk']; ?>"><i class="fas fa-edit"></i></button> 
                <button class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#userModal<?= $tabel['id_transaksi']; ?>"><i class="fas fa-info-circle"></i></button>
              </td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>


              <!-- Sweet Alert -->
      <?php
                // Ambil status dan pesan
                $status = isset($_GET['status']) ? $_GET['status'] : null;
                $message = isset($_GET['message']) ? urldecode($_GET['message']) : null;
                ?>

                <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
                <script>
                    // Cek apakah ada status dan pesan
                    const status = "<?php echo $status; ?>";
                    const message = "<?php echo $message; ?>";

                    if (status) {
                        Swal.fire({
                            icon: status === 'success' ? 'success' : 'error',
                            title: status === 'success' ? 'Berhasil!' : 'Gagal!',
                            text: message,
                            confirmButtonText: 'OK'
                        }).then(() => {
                            history.replaceState(null, '', window.location.pathname);
                        });
                    }
                </script>

  <!-- Font Awesome for icons -->
  <script src="https://kit.fontawesome.com/a076d05399.js"></script>
  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>
  <!-- Datatable script -->
  <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
  <script src="https://cdn.datatables.net/2.1.8/js/dataTables.js"></script>
  <!-- memanggil sidebar dan datatable eksternal -->
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
<script>
$(document).ready(function () {
    // Tombol Terapkan Filter
    $("#apply-filter").on("click", function () {
        const selectedMonth = $("#monthpicker").val();
        if (selectedMonth) {
            $("tbody tr").each(function () {
                const rowDate = $(this).data("tanggal"); // format data tanggal Y-m
                if (rowDate.startsWith(selectedMonth)) {
                    $(this).show(); // Tampilkan baris yang cocok
                } else {
                    $(this).hide(); // Sembunyikan baris yang tidak cocok
                }
            });
        } else {
            $("tbody tr").show(); // Tampilkan semua baris jika tidak ada filter
        }
    });
});

</script>

</body>
</html>
