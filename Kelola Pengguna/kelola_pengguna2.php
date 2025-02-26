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
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Kelola Pengguna</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../css/pengguna.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <link href="https://cdn.datatables.net/2.1.8/css/dataTables.dataTables.css" rel="stylesheet">


  <?php 
  require("../koneksi.php"); 
  include '../config/function.php';
  $data_pengguna = select("SELECT * FROM pengguna"); 
  // var_dump($data_pengguna);
  ?>
</head>
<body>
  
<nav class="navbar d-flex justify-content-between align-items-center">
  <div class="d-flex align-items-center">
    <button onclick="openSidebar()">☰</button>
  </div>
  <div class="dropdown d-flex align-items-center"><span class="navbar-text text-black me-4"><?php if (isset($_SESSION['nama_pengguna'])) { echo $_SESSION['nama_pengguna']; 
    echo "<br>Admin";}?></span>
  <i class="fas fa-user-circle fa-2xl me-2 dropdown-toggle" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false" style="cursor: pointer;"></i>
    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
      <li class="dropdown-item disabled">
        <strong><?php if (isset($_SESSION['nama_pengguna'])) { echo $_SESSION['nama_pengguna']; } ?></strong>
      </li>
      <li><hr class="dropdown-divider"></li>
      <li><a class="dropdown-item" data-bs-target="editProfileModal">Edit Profil</a></li>
    </ul>
  </div>
  </div>
</nav>

<div class="modal fade" id="editProfileModal" tabindex="-1" aria-labelledby="editProfileModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editProfileModalLabel">Edit Profil</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="editProfileForm" action="edit_profil_process.php" method="POST">
          <div class="form-group mb-3">
            <label for="editNamaPengguna" class="form-label">Nama Pengguna</label>
            <input type="text" class="form-control" id="editNamaPengguna" name="nama_pengguna" value="<?php echo $_SESSION['nama_pengguna']; ?>" required>
          </div>
          <div class="form-group mb-3">
            <label for="editEmail" class="form-label">Email</label>
            <input type="email" class="form-control" id="editEmail" name="email" value="<?php echo $_SESSION['email']; ?>" required>
          </div>
          <div class="form-group mb-3">
            <label for="editPassword" class="form-label">Password Baru</label>
            <input type="password" class="form-control" id="editPassword" name="password" placeholder="Masukkan Password Baru">
          </div>
          <div class="form-group mb-3">
            <label for="editNoWA" class="form-label">No. WhatsApp</label>
            <input type="number" class="form-control" id="editNoWA" name="no_wa" value="<?php echo $_SESSION['no_WA']; ?>">
          </div>
          <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
        </form>
      </div>
    </div>
  </div>
</div>


<div id="sidebar" class="sidebar">
  <img src="../Polibatam.png">
  <button class="close-btn" onclick="closeSidebar()">x</button>
  <ul>
    <li><a href="../Dasboard admin/dasboard.php">⏲︎ Beranda</a></li>
    <li><a href="../Lihat Stok Bahan Baku/lihat_stok_admin.php"><i class="fa-solid fa-list-check"></i> Lihat Stock Bahan Baku</a></li>
    <li><a href="#"><i class="fa-solid fa-user-plus"></i> Kelola Pengguna</a></li>
    <li><a href="../Login/logout.php"><i class="fa-solid fa-power-off"></i> Keluar</a></li>
  </ul>
</div>

<div class="container-fluid">
  <div class="content py-4">
    <h2>Kelola Pengguna</h2>

    <div class="filbar d-flex justify-content-between align-items-center mb-3">
    <div class="tambah">
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#myModal">Tambah</button>
    </div>
    </div>
  
    <!-- Modal Tambah Pengguna -->
    <div id="myModal" class="modal fade" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title"><strong>Tambah Pengguna</strong></h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form action="register.php" method="POST">

              <div class="form-group mb-3">
                <label for="nama_pengguna" class="form-label">Nama Pengguna</label>
                <input type="text" class="form-control" id="nama_pengguna" name="nama_pengguna" placeholder="Nama Pengguna" required>
              </div>

              <div class="form-group mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="text" class="form-control" id="password" name="password"placeholder="Password" required></input>

              </div>
              <div class="form-group mb-3">
                <label for="no_WA" class="form-label">No. Whatsapp</label>
                <input type="number" class="form-control" id="no_WA" name="no_WA" placeholder="No. Whatsapp">
              </div>

              <div class="form-group mb-3">
                <label for="role" class="form-label">Jenis Pengguna</label>
                <select class="form-control" id="role" name="role" required>
                  <option value="" hidden>-Pilih Jenis Pengguna-</option>
                  <option value="Admin">Admin</option>
                  <option value="Staff Gudang">Staff Gudang</option>
                </select>
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

    <?php foreach ($data_pengguna as $data) : ?>
        <!-- Modal Edit -->
        <div id="editModal<?= $data['id_user']; ?>" class="modal fade" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          
        <div class="modal-header">
            <h5 class="modal-title" id="myModalLabel"><strong>Perbarui Pengguna</strong></h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          
          <div class="modal-body">
            <form action="update.php" method="POST">
              
            <input type="hidden" name="id_user" value="<?= $data['id_user']; ?>">
    
              <div class="mb-3 form-group">
                <label for="nama_pengguna" class="form-label">Nama Pengguna</label>
                <input type="text" class="form-control" id="nama_pengguna" name="nama_pengguna" value="<?= $data['nama_pengguna'] ?>">
              </div>
    
              <div class="mb-3 form-group">
                <label for="password" class="form-label">Kata Sandi</label>
                <input type="text" class="form-control" id="password" name="password" value="<?= $data['password'] ?>">
              </div>
              
              <div class="mb-3 form-group">
                <label for="no_whatsapp" class="form-label">No Whatsapp</label>
                <input type="number" class="form-control" id="no_WA" name="no_WA" value="<?= $data['no_WA'] ?>">
              </div>
            
              <div class="mb-3 form-group">
                <label for="role" class="form-label">Jenis Pengguna</label>
                <select class="form-control" name="role" required>
                  <option value="Admin" <?=$data ['role'] =='Admin'? 'selected': ''?>>Admin</option>
                  <option value="Staff Gudang" <?=$data ['role'] =='Staff Gudang'? 'selected': ''?>>Staff Gudang</option>
                </select>
              </div>
              
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                <button type="submit" class="btn btn-primary" name="ubah">Ubah</button>
              </div>
            
            </form>
        </div>
        </div>
      </div>
    </div>
    
          <!-- Akhir modal edit -->

          <!-- modal hapus-->
        <div id="hapusModal<?= $data['id_user']; ?>" class="modal fade" tabindex="-1" aria-labelledby="hapusModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          
          <div class="modal-header">
            <h5 class="modal-title" id="myModalLabel"><strong>Konfirmasi Hapus Data</strong></h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
        
          <div class="modal-body">
            <h5 class="text-center"> Apakah anda yakin akan menghapus data ini?<br>
              <span class="text-primary font-monospace">Nama: <?= $data['nama_pengguna']?></span>
            </h5>

            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
              <a href="delete.php?id_user=<?= $data['id_user'];?>" class="btn btn-danger">Hapus</a>
            </div>
        </div>
      </div>
    </div>
  </div>
      <?php endforeach;?>

    <!-- Tabel Pengguna -->
    <div class="table-responsive">
      <table id="data-table" class="table table-bordered table-striped">
        <thead>
          <tr>
            <th>Nama Pengguna</th>
            <th>No Whatsapp</th>
            <th>Jenis Pengguna</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($data_pengguna as $data) : ?>
          <tr>
            <td><?= $data['nama_pengguna']; ?></td>
            <td><?= $data['no_WA']; ?></td>
            <td><?= $data['role']; ?></td>
            <td>
              <!-- Tombol Edit --> 
              <button data-bs-toggle="modal" 
                    data-bs-target="#editModal<?php echo $data['id_user']; ?>"
                    data-email-user="<?php echo $data['id_user']; ?>"
                        class="btn btn-light btn-sm mr-1" >
                        <i class="fas fa-edit"></i >
                      </button>
            
                      <!-- Tombol Delete -->
                      <button class="btn btn-light btn-sm" 
                        data-bs-toggle ="modal"
                        data-bs-target = "#hapusModal<?= $data['id_user']; ?>">
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

<!-- Validasi JavaScript -->
<script>
document.getElementById('role').addEventListener('change', function() {
  const noWAInput = document.getElementById('no_WA');
  if (this.value === 'Admin') {
    noWAInput.setAttribute('required', 'required');
    noWAInput.parentElement.querySelector('label').innerText = "No. Whatsapp (Wajib untuk Admin)";
  } else {
    noWAInput.removeAttribute('required');
    noWAInput.parentElement.querySelector('label').innerText = "No. Whatsapp";
  }
});
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://kit.fontawesome.com/a076d05399.js"></script>
<script src="https://code.jquery.com/jquery-3.7.1.js"></script>
<script src="https://cdn.datatables.net/2.1.8/js/dataTables.js"></script>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>

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
  <!--Datatables Script-->
  <script>
    $(document).ready( function () {
      $('#data-table').DataTable();
  } );</script>
</body>
</html>
          
            
