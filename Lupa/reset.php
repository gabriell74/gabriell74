<!-- <!DOCTYPE html>
<html lang="en">

<head>
    <title>Form OTP</title>
</head>

<body>
    <form method="POST" action="../Login/cek_nomor.php" accept-charset="utf-8" style="margin: 100px auto;box-shadow: 0 0 15px -2px lightgray;width:100%;max-width:600px;padding:20px;box-sizing:border-box;">
        <h1 style="text-align: center;">Verifikasi Nomor Whatsapp</h1>
        <center>

            <div style="display: <?php echo isset($_POST['submit-otp']) ? "none" : "flex" ?>;flex-direction:column;margin-bottom:10px;box-sizing:border-box;"><label for="nomor" style="text-align: left;margin-bottom:5px;">Nomor Whatsapp</label> <input placeholder="Masukkan nomor Whatsapp anda" name="nomor" type="text" id="nomor" required style="padding:8px;border:2px solid lightgray;border-radius:5px;" <?php if (isset($_POST['submit-otp'])) {
                                                                                                                                                                                                                                                                                                                                                                                                echo "value='$nomor' hidden";
                                                                                                                                                                                                                                                                                                                                                                                            } ?> /></div>
            <?php
            if (isset($_POST['submit-otp'])) { ?>
                <div style="display: flex;flex-direction:column;margin-bottom:10px;"><label for="otp" style="text-align: left;margin-bottom:5px;box-sizing:border-box;">Verifikasi OTP</label> <input placeholder="xxxx" name="otp" type="text" id="otp" style="padding:8px;border:2px solid lightgray;border-radius:5px;" /></div>
            <?php }
            if (!isset($_POST['submit-otp'])) { ?>
                <button type="submit" id="btn-otp" style="background-color: orange;border:none;padding:8px 16px;color:white;cursor:pointer;" name="submit-otp">Request otp</button>
            <?php }
            if (isset($_POST['submit-otp'])) { ?>
                <button type="submit" id="btn-login" style="background-color:darkturquoise;border:none;padding:8px 16px;color:white;cursor:pointer;" name="submit-login">Masuk</button>
            <?php }  ?>
        </center>
    </form>


</body>

</html> -->