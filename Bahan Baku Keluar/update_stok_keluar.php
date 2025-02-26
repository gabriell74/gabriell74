<?php
require_once '../koneksi.php';
include '../config/function.php';

if (isset($_POST["ubah"])) { 
    $result = update_stok_keluar($_POST);
    
    mysqli_close($conn);

    // Redirect dengan parameter hasil
    if ($result['success']) {
        header("Location: bahan_baku_keluar.php?status=success&message=" . urlencode($result['message']));
    } else {
        header("Location: bahan_baku_keluar.php?status=error&message=" . urlencode($result['message']));
    }
    exit;
}
 