<?php
session_start();
require '../koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Mengambil data dari form
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Validasi konfirmasi password
    if ($new_password !== $confirm_password) {
        echo "<script>alert('Password dan konfirmasi password tidak sama.')</script>";
    }
    if (strlen($new_password) < 8) {
        echo "<script>alert('Kata sandi harus minimal 8 karakter.')</script>";
    }else{
        // Hash password baru
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

        // Mengambil nomor WhatsApp dari parameter URL
        if (isset($_GET['no_WA'])) {
            $nomor = $_GET['no_WA'];

            // Update password jika nomor ditemukan
            $update_sql = "UPDATE pengguna SET password = ? WHERE no_WA = ?";
             $update_stmt = $conn->prepare($update_sql);
            $update_stmt->bind_param("ss", $hashed_password, $nomor);

            if ($update_stmt->execute()) {
                echo "<script>alert('Katasandi berhasil diubah.');
                window.location.href = 'login.php';</script>";
                exit();
            } else {
                echo "<script>alert('Gagal mengubah Katasandi.');
                window.location.href = 'login.php';</script>";
            }
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Form Reset Password</title>
</head>
<body>
    <div class="container d-flex justify-content-center align-items-center vh-100">
        <div class="card shadow-lg p-4" style="width: 100%; max-width: 500px;">
            <h2 class="text-center mb-4">Perbarui Katasandi</h2>
            <form method="POST" action="">
                <div class="mb-3">
                    <label for="new_password" class="form-label">Katasandi Baru</label>
                    <input type="password" class="form-control" id="new_password" name="new_password" placeholder="Masukkan katasandi minimal 8 karakter" required>
                </div>
                <div class="mb-3">
                    <label for="confirm_password" class="form-label">Konfirmasi Katasandi Baru</label>
                    <input type="password" class="form-control" id="confirm_password" name="confirm_password" placeholder="Masukkin katasandi baru " required>
                </div>
                <div class="d-grid gap-2 col-6 mx-auto mt-4">
                    <button type="submit" class="btn btn-primary" style="padding: 10px 20px; background-color: blue; color: white; border: none; border-radius: 5px;" 
                    onmouseover="this.style.backgroundColor='green'; this.style.color='white';" 
                    onmouseout="this.style.backgroundColor='blue'; this.style.color='white';">Ubah Katasandi</button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

