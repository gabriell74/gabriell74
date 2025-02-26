<?php
require ("../koneksi.php");
include ("../config/function.php");
$no = $_POST['nomor'];
$cek = select("SELECT * FROM pengguna WHERE no_WA = $no");
if ($cek){
    include "../Otp/otp.php";
}else{
    echo "<script>alert('Nomor anda tidak terdaftar'); window.history.back();  </script>";
    exit;
    // header("Location: login.php");
} 
?>