<?php
require_once '../koneksi.php';
include '../config/function.php';

// Untuk menerima id kategori yang dipilih pengguna
$id_kategori = $_GET['id_kategori'];

if (delete_kategori($id_kategori)){
    header("Location: kategori.php?status=success&message=" . urlencode("Berhasil Menghapus Kategori"));
}else{
    header("Location: kategori.php?status=error&message=" . urlencode("Gagal Menghapus Kategori"));    
}
mysqli_close($conn);
?>
