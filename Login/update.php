<?php
include '../koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $token = $_POST['token'];
    $password = password_hash(trim($_POST['password']), PASSWORD_DEFAULT);

    $query = "SELECT * FROM pengguna WHERE reset_token = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $update_query = "UPDATE pengguna SET password = ?, reset_token = NULL WHERE reset_token = ?";
        $update_stmt = $conn->prepare($update_query);
        $update_stmt->bind_param("ss", $password, $token);
        $update_stmt->execute();

        echo "Kata sandi Anda berhasil diperbarui!";
    } else {
        echo "Token tidak valid.";
    }
}
?>
