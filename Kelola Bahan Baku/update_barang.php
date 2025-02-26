<?php
require('../koneksi.php');
include '../config/function.php';

if (isset($_POST['edit'])) {
    $result = update_bahan($_POST);
    
    mysqli_close($conn);

    // Redirect dengan parameter hasil
    if ($result['success'] === true) {
        header("Location: kelola_bahan.php?status=success&message=" . urlencode($result['message']));
    } elseif ($result['success'] === null) {
        header("Location: kelola_bahan.php?status=warning&message=" . urlencode($result['message']));
    } else {
        header("Location: kelola_bahan.php?status=error&message=" . urlencode($result['message']));
    }
    exit;
}
?>
