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
$data_kategori = select("SELECT id_kategori, nama_kategori FROM t_kategori");


$searchKeyword = '';
if (isset($_GET['pencarian']) && !empty($_GET['pencarian'])) {
    $searchKeyword = mysqli_real_escape_string($conn, $_GET['pencarian']);
    $query = "SELECT * FROM t_kategori WHERE id_kategori LIKE '%$searchKeyword%' OR nama_kategori LIKE '%$searchKeyword%'";
} else {
    $query = "SELECT * FROM t_kategori";
}
$result = mysqli_query($conn, $query);

if (!$result) {
    die("Query gagal: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Kategori Bahan Baku</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../css/kategori.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <link href="https://cdn.datatables.net/2.1.8/css/dataTables.dataTables.css" rel="stylesheet">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>



</head>
<body>

    <nav class="navbar d-flex justify-content-between align-items-center">
      <div class="d-flex align-items-center">
          <button onclick="openSidebar()">☰</button>
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
            <li><a href="../Kelola Bahan Baku/kelola_bahan.php"><i class="fa-solid fa-table-cells-large"></i> Kelola Stok Bahan Baku</a></li>
            <li><a href="../Bahan Baku Masuk/bahan_masuk.php"><i class="fa-solid fa-list-check"></i> Bahan Baku Masuk</a></li>
            <li><a href="../Bahan Baku Keluar/bahan_baku_keluar.php"><i class="fa-regular fa-clipboard"></i> Bahan Baku Keluar</a></li>
            <li><a href="kategori.php"><i class="fa-solid fa-pen-to-square"></i>Kategori Stok Bahan Baku</a></li>
            <div class="exit">
            <li><a href="../Login/logout.php"><i class="fa-solid fa-power-off"></i> Keluar</a></li>
            </div>
        </ul>
    </div>

    <div class="container-fluid">
        <div class="content py-4">
            <h2>Kategori Bahan Baku</h2>

            <div class="filbar d-flex justify-content-between align-items-center mb-3">
            <div class="filbar d-flex justify-content-between align-items-center mb-3">
                <!-- <form method="GET" class="d-flex" style="width: 100%;">
                  <input type="search" class="form-control me-2" name="pencarian" id="Pencarian" placeholder="Pencarian" >
                <button type="submit" class="btn btn-primary" >Cari</button>
              </form> -->
            </div>
            <!-- Button Tambah Kategori -->
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#myModal">Tambah</button>
            </div>
            <!-- Modal Tambah Kategori -->
            <div id="myModal" class="modal fade" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header bg-info">
                            <h5 class="modal-title" id="myModalLabel"><strong>Tambah Kategori</strong></h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form method="POST" action="tambah_kategori.php">
                                <div class="mb-4">
                                    <label for="nama_kategori" class="form-label">Nama Kategori</label>
                                    <input type="text" class="form-control" id="nama_kategori" name="nama_kategori" placeholder="Nama Kategori" required>
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
        
        <?php foreach ($data_kategori as $data):?>
            
            <!-- Modal Edit Kategori -->
            <div id="editModal<?= $data['id_kategori']?>" class="modal fade" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header bg-info">
                            <h5 class="modal-title" id="myModalLabel"><strong>Edit Kategori</strong></h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form method="POST" action="edit.php">
                                
                                <div class="mb-4">
                                    <label for="id_kategori" class="form-label">ID Kategori</label>
                                    <input type="text" class="form-control" id="id_kategori" name="id_kategori" placeholder="ID Kategori" value="<?= $data['id_kategori'];?>" readonly>
                                </div>
                                <div class="mb-4">
                                    <label for="nama_kategori" class="form-label">Nama Kategori</label>
                                    <input type="text" class="form-control" id="nama_kategori" name="nama_kategori" placeholder="Nama Kategori" value="<?= $data['nama_kategori'];?>" required>
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

             <!-- Modal hapus-->
            <div id="hapusModal<?= $data['id_kategori']; ?>" class="modal fade" tabindex="-1" aria-labelledby="hapusModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header bg-danger">
                            <h5 class="modal-title" id="myModalLabel"><strong>Konfirmasi Hapus Data</strong></h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                                    <h5 class="text-center"> Apakah anda yakin akan menghapus data ini?<br>
                                        <span class="text-primary font-monospace">ID: <?= $data['id_kategori']?> - Kategori: <?= $data['nama_kategori']?></span>
                                    </h5>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                    <a href="delete.php?id_kategori=<?= $data['id_kategori'];?>" class="btn btn-danger">Hapus</a>
                                </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach;?>
                            
                  
                              <!-- Tabel Tampilan -->
                              <div class="table-responsive">
                                  <table id="data-table" class="table table-bordered table-striped">
                                      <thead>
                                          <tr>
                                              <th>ID Kategori</th>
                                              <th>Nama Kategori</th>
                                              <th>Aksi</th>
                                          </tr>
                                      </thead>
                                      <tbody>
                                          <?php foreach ($data_kategori as $data) : ?>
                                           <tr>
                                              <td> <?= $data['id_kategori']; ?> </td> 
                                              <td> <?= $data['nama_kategori']; ?> </td>
                                                  <td>
                                              
                                                <button data-bs-toggle="modal" data-bs-target="#editModal<?= $data['id_kategori'];?>" 
                                                        data-id-kategori="<?php echo $data['id_kategori']; ?>"
                                                        class="btn btn-light btn-sm">
                                                      <i class="fas fa-edit"></i>
                                                </button>
                  
                                                  <button type="button" name="delete" class="btn btn-light btn-sm"
                                                      data-bs-toggle="modal" data-bs-target="#hapusModal<?= $data['id_kategori']?>">
                                                      <i class="fa-regular fa-trash-can" style="color: #dd1d1d;"></i>
                                                  </button>
                                          </td>
                                      </tr>
                                      
                                      <?php endforeach; ?>
                                  </tbody>
                              </table>
                          </div>
                      </div>
                  </div>

                  <!-- Sweet Alert -->
                <?php
                // Ambil status dan pesan dari query string
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
            <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
            <script src="https://cdn.datatables.net/2.1.8/js/dataTables.js"></script>
            <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>
            <script src="../sidebar_datatable.js"></script>
                  <script>
                      function openSidebar() {
                          document.getElementById("sidebar").style.width = "250px";
                          document.querySelector(".content").style.marginLeft = "250px";
                          document.querySelector(".navbar").style.marginLeft = "250px";
                      }
                  
                      function closeSidebar() {
                          document.getElementById("sidebar").style.width = "0";
                          document.querySelector(".content").style.marginLeft = "0";
                          document.querySelector(".navbar").style.marginLeft = "0";
                      }
                  </script>
</body>
</html>

                                
            
