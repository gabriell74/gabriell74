<?php
session_start();
include '../koneksi.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['nama_pengguna']) && isset($_POST['password'])) {
        $nama_pengguna = trim($_POST['nama_pengguna']);
        $password = trim($_POST['password']);

        // Query untuk mendapatkan pengguna berdasarkan nama_pengguna
        $query = "SELECT * FROM pengguna WHERE nama_pengguna = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $nama_pengguna);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();

            // Jika password sudah di-hash, gunakan password_verify
            if (strlen($row['password']) >= 60) {
                if (password_verify($password, $row['password'])) {
                    $_SESSION['nama_pengguna'] = $row['nama_pengguna'];
                    $_SESSION['role'] = $row['role'];

                    redirectBasedOnRole($row['role']);
                } else {
                    handleLoginError('Password salah!');
                }
            } else {
                // Jika password belum di-hash
                if ($password === $row['password']) {
                    // Hash password lama
                    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

                    // Update password lama menjadi hash di database
                    $updateQuery = "UPDATE pengguna SET password = ? WHERE no_WA = ?";
                    $updateStmt = $conn->prepare($updateQuery);
                    $updateStmt->bind_param("si", $hashedPassword, $row['no_WA']);
                    if ($updateStmt->execute()) {
                        $_SESSION['nama_pengguna'] = $row['nama_pengguna'];
                        $_SESSION['role'] = $row['role'];

                        redirectBasedOnRole($row['role']);
                    } else {
                        echo "<script>
                                alert('Gagal memperbarui password!');
                                window.location.href = 'login.php';
                            </script>";
                    }
                } else {
                    handleLoginError('Password salah!');
                }
            }
        } else {
            handleLoginError('Nama pengguna tidak ditemukan!');
        }

        $stmt->close();
        $conn->close();
    } else {
        handleLoginError('Form tidak lengkap!');
    }
}

function handleLoginError($message)
{
    echo "<script>
            alert('$message');
            window.location.href = 'login.php';
          </script>";
    exit();
}

function redirectBasedOnRole($role)
{
    switch ($role) {
        case 'Admin':
            echo "<script>
                    window.location.href = '../Dasboard admin/dasboard.php';
                  </script>";
            break;
        case 'Staff Gudang':
            echo "<script>
                    window.location.href = '../Dasboard/dasboard.php';
                  </script>";
            break;
        default:
            echo "<script>
                    alert('Role tidak dikenali!');
                    window.location.href = 'login.php';
                  </script>";
    }
    exit();
}
?>
