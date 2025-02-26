<?php
include '../koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);

    $query = "SELECT * FROM pengguna WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $token = bin2hex(random_bytes(50)); // Generate random token
        $reset_link = "http://example.com/reset_confirm.php?token=" . $token;

        // Simpan token ke database
        $update_query = "UPDATE pengguna SET reset_token = ? WHERE email = ?";
        $update_stmt = $conn->prepare($update_query);
        $update_stmt->bind_param("ss", $token, $email);
        $update_stmt->execute();

        // Kirim email ke pengguna
        $subject = "Reset Kata Sandi";
        $message = "Klik tautan berikut untuk mengatur ulang kata sandi Anda: " . $reset_link;
        $headers = "From: admin@example.com";
        if (mail($email, $subject, $message, $headers)) {
            echo "Tautan reset telah dikirim ke email Anda.";
        } else {
            echo "Gagal mengirim email.";
        }
    } else {
        echo "Email tidak ditemukan.";
    }
}
?>
