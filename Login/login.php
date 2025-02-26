<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/login1.css">
</head>
<body>
    <div class="container-fluid h-100">
        <div class="row h-100">
            <div class="col-md-6 left-side d-flex flex-column align-items-center justify-content-center">
                <img src="../Polibatam.png" alt="Polibatam Logo" class="polibatam">
                <img src="../pic/Logo Kost.png" alt="Kost Logo" class="kost">
            </div>
            <div class="col-md-6 right-side d-flex align-items-center justify-content-center">
                <div class="login-box">
                    <h2 class="text-center"><strong>Selamat Datang!</strong></h2>
                    <form action="login_process.php" method="POST">
                        <div class="form-group">
                            <label for="username">Nama Pengguna</label>
                            <input name="nama_pengguna" type="text" class="form-control" id="username" placeholder="Masukkan Nama Pengguna" required>
                        </div>
                        <div class="form-group">
                            <label for="password">Kata Sandi</label>
                            <input name="password" type="password" class="form-control" id="password" placeholder="Masukkan Kata sandi" required>
                        </div>
                        <a href="../Otp/otp.php" class="d-block mb-3">Lupa Kata Sandi?</a>
                        <center><button type="submit" class="btn btn-primary btn-block">Masuk</button></center>
                    </form>

                <?php if(isset($_GET['error'])):?>
                    <?php if($_GET['error'] == 'wrong_password'): ?>
                        <div class="alert alert-danger mt-3">Nama Pengguna atau Kata Sandi salah, silakan coba lagi!</div>
                    <?php elseif($_GET['error'] == 'user_not_found'): ?>
                        <div class="alert alert-danger mt-3">Nama Pengguna tidak ditemukan!</div>
                    <?php elseif ($_GET['error'] == 'not_logged_in'): ?>
                        <div class="alert alert-warning mt-3">Anda harus login terlebih dahulu untuk mengakses halaman ini.</div> 
                    <?php endif; ?>
                <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById("showPassword").addEventListener("click", function () {
            var passwordField = document.getElementById("password");
            passwordField.type = this.checked ? "text" : "password";
        });
    </script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>