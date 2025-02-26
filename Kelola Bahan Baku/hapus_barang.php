<?php
require_once '../koneksi.php';
include '../config/function.php';

$id_bahan_baku = $_GET['id_bahan_baku'];


if (delete_bahan($id_bahan_baku)){
    header("Location: kelola_bahan.php?status=success&message=" . urlencode("Berhasil Menghapus Barang"));
}else{
    header("Location: kelola_bahan.php?status=error&message=" . urlencode("Gagal Menghapus Barang"));    
}
mysqli_close($conn);
?>
