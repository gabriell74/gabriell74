<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contoh</title>
<?php
    // Query untuk mengambil semua data pengguna
require("../koneksi.php");
$query = "SELECT * FROM pengguna";
$result = mysqli_query($koneksi, $query);
?>
</head>
</html>

