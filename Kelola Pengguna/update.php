<?php
require '../koneksi.php';
include '../config/function.php';
if (isset($_POST['ubah'])){
    $result = update_pengguna($_POST);
    
    mysqli_close($conn);

    // Redirect dengan parameter hasil
    if ($result['success']) {
        header("Location: kelola_pengguna2.php?status=success&message=" . urlencode($result['message']));
    } else {
        header("Location: kelola_pengguna2.php?status=error&message=" . urlencode($result['message']));
    }
    exit;
}
?>
