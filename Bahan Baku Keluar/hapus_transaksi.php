<?php
require_once '../koneksi.php';
include '../config/function.php';

// Untuk menerima id transaksi yang dipilih pengguna
$id_bahan_baku = $_GET['id_transaksi'];

if (delete_transaksi($id_bahan_baku)){
    header("Location: bahan_baku_keluar.php?status=success&message=" . urlencode("Berhasil Menghapus Transaksi"));
}else{
    header("Location: bahan_baku_keluar.php?status=error&message=" . urlencode("Gagal Menghapus Transaksi"));    
}
mysqli_close($conn);
?>
