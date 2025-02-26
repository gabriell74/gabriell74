<?php
session_start();
require ("koneksi.php");

if (isset($_POST['submit-otp'])) {
    $nomor = mysqli_escape_string($conn, $_POST['nomor']);
    if ($nomor) {
        // Ambil id user berdasarkan nomor wa
        $result = mysqli_query($conn, "SELECT id_user FROM pengguna WHERE no_WA = '$nomor'");
        if ($row = mysqli_fetch_assoc($result)) {
            $id_user = $row['id_user']; 
            
            if (!mysqli_query($conn, "DELETE FROM otp WHERE nomor = '$nomor'")) {
                echo ("Error description: " . mysqli_error($conn));
            }

            $otp = rand(1000, 9999);
            $waktu = time();
            
            $insertQuery = "INSERT INTO otp (nomor, otp, waktu, id_user) VALUES ('$nomor', '$otp', '$waktu', '$id_user')";
            if (mysqli_query($conn, $insertQuery)) {
                $data = [
                    'target' => $nomor,
                    'message' => "Your OTP : " . $otp
                ];

                $curl = curl_init();
                curl_setopt($curl, CURLOPT_HTTPHEADER, ["Authorization: 1AmFu4Yfw8p2C8Endzbe"]);
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
                curl_setopt($curl, CURLOPT_URL, "https://api.fonnte.com/send");
                curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
                curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
                $result = curl_exec($curl);
                curl_close($curl);
            } else {
                echo "Error inserting OTP: " . mysqli_error($conn);
            }
        } else {
            echo "<script>alert('Nomor tidak terdaftar!')</script>";
        }
    }
} elseif (isset($_POST['submit-login'])) {
    $otp = mysqli_escape_string($conn, $_POST['otp']);
    $nomor = mysqli_escape_string($conn, $_POST['nomor']);
    $q = mysqli_query($conn, "SELECT * FROM otp WHERE nomor = '$nomor' AND otp = '$otp'");
    $row = mysqli_num_rows($q);
    $r = mysqli_fetch_array($q);
    if ($row) {
        if (time() - $r['waktu'] <= 30) {
            header('Location: ../Login/reset_password.php?no_WA='.$nomor);
            exit;
        } else {
            echo "<script>alert('OTP telah expired')</script>";
        }
    } else {
        echo "<script>alert('OTP anda salah, Masukkan OTP dengan benar')</script>";
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Form OTP</title>
</head>

<body>
    <form method="POST" action="../Login/cek_nomor.php" accept-charset="utf-8" style="margin: 100px auto;box-shadow: 0 0 15px -2px lightgray;width:100%;max-width:600px;padding:20px;box-sizing:border-box;">
        <h1 style="text-align: center;">Verifikasi OTP</h1>
        <center>

            <div style="display: <?php echo isset($_POST['submit-otp']) ? "none" : "flex" ?>;flex-direction:column;margin-bottom:10px;box-sizing:border-box;">
                <label for="nomor" style="text-align: left;margin-bottom:5px;">Nomor</label> 
                <input placeholder="62812xxxx" name="nomor" type="text" id="nomor" required style="padding:8px;border:2px solid lightgray;border-radius:5px;" 
                <?php if (isset($_POST['submit-otp'])) {
                    echo "value='$nomor' hidden";
                } ?> />
            </div>

            <?php
            if (isset($_POST['submit-otp'])) { ?>
                <div style="display: flex;flex-direction:column;margin-bottom:10px;">
                    <label for="otp" style="text-align: left;margin-bottom:5px;box-sizing:border-box;">Verifikasi Otp</label>
                    <input placeholder="xxxx" name="otp" type="text" id="otp" style="padding:8px;border:2px solid lightgray;border-radius:5px;" required />
                </div>
            <?php }

            if (!isset($_POST['submit-otp'])) { ?>
                <button type="submit" id="btn-otp" style="background-color: orange;border:none;padding:8px 16px;color:white;cursor:pointer;" name="submit-otp">Minta otp</button>
            <?php }

            if (isset($_POST['submit-otp'])) { ?>
                <button type="submit" id="btn-login" style="background-color:darkturquoise;border:none;padding:8px 16px;color:white;cursor:pointer;" name="submit-login">Masuk</button>
            <?php }  ?>
        </center>
    </form>

</body>

</html>
