<?php
session_start();
require '../koneksi.php'; 
require 'mailer.php'; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);

    $query = $conn->prepare("SELECT * FROM pengguna WHERE email = ?");
    $query->bind_param('s', $email);
    $query->execute();
    $result = $query->get_result();

    if ($result->num_rows > 0) {
        $otp = rand(100000, 999999);
        $_SESSION['otp'] = $otp;
        $_SESSION['email'] = $email;

        $subject = "Kode OTP Anda";
        $message = "Kode OTP untuk reset kata sandi Anda adalah: $otp. Jangan berikan kode ini kepada siapapun.";
        if (send_mail($email, $subject, $message)) {
            header("Location: reset_password.php");
        } else {
            header("Location: request_otp.php?error=Gagal mengirim email. Silakan coba lagi.");
        }
    } else {
        header("Location: request_otp.php?error=Email tidak ditemukan.");
    }
}
?>
