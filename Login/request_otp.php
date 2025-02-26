<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Kata Sandi</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h2 class="text-center mt-5">Lupa Kata Sandi</h2>
        <form action="email_otp.php" method="POST">
            <div class="form-group">
                <label for="email">Masukkan Email Anda</label>
                <input type="email" name="email" id="email" class="form-control" placeholder="Email Anda" required>
            </div>
            <button type="submit" class="btn btn-primary">Kirim OTP</button>
        </form>
        <?php if(isset($_GET['error'])): ?>
            <div class="alert alert-danger mt-3">
                <?= htmlspecialchars($_GET['error']); ?>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
