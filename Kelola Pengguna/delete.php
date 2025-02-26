<?php
require "../koneksi.php";
include '../config/function.php';

$id_user = $_GET['id_user'];

if (delete_pengguna($id_user)) {
    header("Location: kelola_pengguna2.php?status=success&message=" . urlencode("Berhasil Menghapus Pengguna"));

}else{
    header("Location: kelola_pengguna2.php?status=error&message=" . urlencode("Gagal Menghapus Pengguna"));    

}
mysqli_close($conn);
?>
