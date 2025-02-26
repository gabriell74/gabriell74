<?php
session_start();

if (!isset($_SESSION['nama_pengguna']) || !isset($_SESSION['role'])) {
    header("Location: ../Login/login.php?error=not_logged_in");
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
$kuantitas = select("SELECT * FROM t_bahan_baku WHERE kuantitas BETWEEN 1 AND 30");
if ($kuantitas){
include "fontee.php";
}
// include_once '../API-Fonnte/fontee.php';

// Fetch kategori untuk filter dan modal tambah
$data_kategori = select("SELECT id_kategori, nama_kategori FROM t_kategori");

$data_tabel = select("SELECT * FROM t_bahan_baku");

// Fetch data tabel berdasarkan filter kategori
$filter_kategori = isset($_GET['kategori']) && $_GET['kategori'] !== '' ? mysqli_real_escape_string($conn, $_GET['kategori']) : null;
if ($filter_kategori) {
  $data_tabel = array_filter($data_tabel, function ($item) use ($filter_kategori) {
      return $item['id_kategori'] == $filter_kategori;
  });
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Kelola Bahan Baku</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../css/kelola_bahan.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <link href="https://cdn.datatables.net/2.1.8/css/dataTables.dataTables.css" rel="stylesheet">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script> -->

</head>
<body>
  <!-- Navbar -->
  <nav class="navbar d-flex justify-content-between align-items-center">
    <div class="d-flex align-items-center">
      <button onclick="openSidebar()" class="me-4">&#9776;</button>
      <!-- <div class="input-group me-2" style="max-width: 300px;">
        <div class="input-group-text">
          <span class="input-group-text bg-light border-0"><i class="fas fa-search"></i></span>
        </div>
        <input type="search" class="form-control" placeholder="Pencarian">
      </div> -->
    </div>
    <div class="d-flex align-items-center">
      <i class="fas fa-user-circle fa-2xl me-2" aria-label="User Icon"></i>
      <span class="navbar-text text-black me-4">
        <?= htmlspecialchars($_SESSION['nama_pengguna']) . "<br>Staff Gudang"; ?>
      </span>
    </div>
  </nav>

  <!-- Sidebar -->
  <div id="sidebar" class="sidebar">
    <img src="../Polibatam.png" alt="Logo">
    <button class="close-btn" onclick="closeSidebar()">&times;</button>
    <ul>
      <li><a href="../Dasboard/dasboard.php">⏲︎ Beranda</a></li>
      <li><a href="#"><i class="fa-solid fa-table-cells-large"></i> Kelola Stok bahan Baku</a></li>
      <li><a href="../Bahan Baku Masuk/bahan_masuk.php"><i class="fa-solid fa-list-check"></i> Bahan Baku Masuk</a></li>
      <li><a href="../Bahan Baku Keluar/bahan_baku_keluar.php"><i class="fa-regular fa-clipboard"></i> Bahan Baku Keluar</a></li>
      <li><a href="../Kategori/kategori.php"><i class="fa-solid fa-pen-to-square"></i>Kategori Stok Bahan Baku</a></li>
      <li class="exit"><a href="../Login/logout.php"><i class="fa-solid fa-power-off"></i> Keluar</a></li>
    </ul>
  </div>

  <!-- Content -->
  <div class="container-fluid">
    <div class="content py-4">
      <h2>Kelola Bahan Baku</h2>

      <!-- Filter bar -->
      <div class="d-flex justify-content-between align-items-center mb-3">
        <form method="GET" action="" class="d-flex align-items-center input-group-text">
          <select name="kategori" class="form-select me-2" onchange="this.form.submit()">
            <option value="" hidden>Pilih Kategori</option>
            <?php foreach ($data_kategori as $kategori): ?>
              <option value="<?= htmlspecialchars($kategori['id_kategori']); ?>"
                <?= $filter_kategori == $kategori['id_kategori'] ? 'selected' : ''; ?>>
                <?= htmlspecialchars($kategori['id_kategori']); ?> (<?= htmlspecialchars($kategori['nama_kategori']); ?>)
              </option>
            <?php endforeach; ?>
          </select>
          <a href="kelola_bahan.php" class="btn btn-light border text-danger"><i class="fa-solid fa-rotate-left"></i> Ulangi </a>
        </form>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#myModal">Tambah</button>
      </div>

      <!-- Modal Tambah -->
      <div id="myModal" class="modal fade" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header bg-info">
              <h5 class="modal-title" id="myModalLabel"><strong>Tambah Stok Bahan Baku</strong></h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <form method="POST" action="tambah_barang.php">
                <div class="mb-3">
                  <label for="id_kategori" class="form-label">ID Kategori</label>
                  <select class="form-select" id="id_kategori" name="id_kategori" required>
                    <option value="" hidden>Pilih Kategori</option>
                    <?php foreach ($data_kategori as $kategori): ?>
                      <option value="<?= htmlspecialchars($kategori['id_kategori']); ?>">
                        <?= $kategori['id_kategori']?> (<?= htmlspecialchars($kategori['nama_kategori']) ?>)
                      </option>
                    <?php endforeach; ?>
                  </select>
                </div>
                <div class="mb-3">
                  <label for="nama_bahan_baku" class="form-label">Nama Bahan Baku</label>
                  <input type="text" class="form-control" id="nama_bahan_baku" name="nama_bahan_baku" placeholder="Masukan Nama Bahan Baku" required>
                </div>
                <div class="mb-3">
                  <label for="unit" class="form-label">Unit</label>
                  <input type="text" class="form-control" id="unit" name="unit" value="PCS" readonly>
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

      <!-- Table -->
      <div class="table-responsive">
        <table id="data-table" class="table table-bordered table-striped">
          <thead>
            <tr>
              <th>ID Stok</th>
              <th>Kode Barcode</th>
              <th>Nama Bahan Baku</th>
              <th>Kategori</th>
              <th>Kuantitas</th>
              <th>Unit</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($data_tabel as $tabel): ?>
              <tr>
                <td><?= htmlspecialchars($tabel['id_bahan_baku']); ?></td>
                <td><?= htmlspecialchars($tabel['kode_barcode']); ?></td>
                <td><?= htmlspecialchars($tabel['nama_bahan_baku']); ?></td>
                <td><?= htmlspecialchars($tabel['id_kategori']); ?></td>
                <td><?= htmlspecialchars($tabel['kuantitas']); ?></td>
                <td><?= htmlspecialchars($tabel['unit']); ?></td>
                <td>
                  <button class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#editModal<?= $tabel['id_bahan_baku']; ?>">
                    <i class="fas fa-edit"></i>
                  </button>
                  <button class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#hapusModal<?= $tabel['id_bahan_baku']; ?>">
                    <i class="fa-regular fa-trash-can" style="color: #dd1d1d;"></i>
                  </button>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>

      <?php foreach ($data_tabel as $tabel): ?>
      <div id="editModal<?= $tabel['id_bahan_baku']; ?>" class="modal fade" tabindex="-1" aria-labelledby="editModalLabel<?= $tabel['id_bahan_baku']; ?>" aria-hidden="true">
          <div class="modal-dialog">
              <div class="modal-content">
                  <div class="modal-header bg-info">
                      <h5 class="modal-title" id="editModalLabel<?= $tabel['id_bahan_baku']; ?>">Edit Bahan Baku</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body">
                      <form method="POST" action="update_barang.php">
                          <input type="hidden" name="id_bahan_baku" value="<?= $tabel['id_bahan_baku']; ?>">
                          <div class="mb-3">
                              <label for="edit_nama_bahan_baku<?= $tabel['id_bahan_baku']; ?>" class="form-label">Nama Bahan Baku</label>
                              <input type="text" class="form-control" id="edit_nama_bahan_baku<?= $tabel['id_bahan_baku']; ?>" name="nama_bahan_baku" value="<?= htmlspecialchars($tabel['nama_bahan_baku']); ?>" required>
                          </div>
                          <div class="mb-3">
                              <label for="edit_kategori<?= $tabel['id_bahan_baku']; ?>" class="form-label">Kategori</label>
                              <select class="form-select" id="edit_kategori<?= $tabel['id_bahan_baku']; ?>" name="id_kategori" required>
                                  <?php foreach ($data_kategori as $kategori): ?>
                                    <option value="<?= htmlspecialchars($kategori['id_kategori']); ?>" 
                                      <?= $kategori['id_kategori'] == $tabel['id_kategori'] ? 'selected' : ''; ?>>
                                      <?= htmlspecialchars($kategori['id_kategori'])?> (<?= htmlspecialchars($kategori['nama_kategori']); ?>)
                                  </option>
                                  <?php endforeach; ?>
                              </select>
                          </div>
                          <div class="mb-3">
                              <label for="edit_unit<?= $tabel['id_bahan_baku']; ?>" class="form-label">Unit</label>
                              <input type="text" class="form-control" id="edit_unit<?= $tabel['id_bahan_baku']; ?>" name="unit" value="<?= htmlspecialchars($tabel['unit']); ?>" readonly>
                          </div>
                          <div class="modal-footer">
                              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                              <button type="submit" class="btn btn-primary" name="edit">Simpan Perubahan</button>
                          </div>
                      </form>
                  </div>
              </div>
          </div>
      </div>
      <?php endforeach; ?>

      <!-- Modal Hapus -->
      <?php foreach ($data_tabel as $tabel): ?>
      <div id="hapusModal<?= $tabel['id_bahan_baku']; ?>" class="modal fade" tabindex="-1" aria-labelledby="hapusModalLabel<?= $tabel['id_bahan_baku']; ?>" aria-hidden="true">
          <div class="modal-dialog">
              <div class="modal-content">
                  <div class="modal-header bg-danger  ">
                      <h5 class="modal-title" id="hapusModalLabel<?= $tabel['id_bahan_baku']; ?>">Hapus Bahan Baku</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body">
                      <p>Apakah Anda yakin ingin menghapus <strong><?= htmlspecialchars($tabel['nama_bahan_baku']); ?></strong>?</p>
                  </div>
                  <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                  <a href="hapus_barang.php?id_bahan_baku=<?= $tabel['id_bahan_baku'];?>" class="btn btn-danger">Hapus</a>
                  </div>
              </div>
          </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
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
                        icon: status === 'success' ? 'success' : (status === 'warning' ? 'warning' : 'error'),
                        title: status === 'success' ? 'Berhasil!' : (status === 'warning' ? 'Peringatan!' : 'Gagal!'),
                        text: message,
                        confirmButtonText: 'OK'
                    }).then(() => {
                        history.replaceState(null, '', window.location.pathname);
                    });
                }
                </script>
                
  <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script> -->
  <script src="../js/script.js"></script>
   <script src="https://kit.fontawesome.com/a076d05399.js"></script>
  <!-- jQuery -->
  <script src="https://code.jquery.com/jquery-3.1.0.js"></script>
  <script src="https://cdn.datatables.net/2.1.8/js/dataTables.js"></script>
  <!-- Bootstrap JS -->
  <!-- Bootstrap 5 -->
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>
  <script src="../sidebar_datatable.js"></script>
  <!-- <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script> -->
  <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script> -->
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
