<?php
require "../koneksi.php";
include_once "../config/function.php"; 

// Ambil semua pengguna dengan role 'admin'
$target = select("SELECT * FROM pengguna WHERE role ='Admin'");
$no = [];

// Simpan semua nomor WA dalam array
foreach ($target as $t) {
    $no[] = $t["no_WA"]; // Menambahkan nomor ke array
}

// Ambil nama bahan baku yang kuantitasnya <= 20
$kuantitas = select("SELECT nama_bahan_baku FROM t_bahan_baku WHERE kuantitas BETWEEN 1 AND 30");
$namaBahanBaku = [];
$stok_minimum = 30;
foreach ($kuantitas as $k) {
    $namaBahanBaku[] = $k['nama_bahan_baku']; // Menyimpan nama bahan baku dalam array
}
$namaBahanBakuString = implode(", ", $namaBahanBaku); // Menggabungkan nama bahan baku menjadi string
$message = "Perhatian: Stok $namaBahanBakuString, telah mencapai atau di bawah batas minimum ($stok_minimum). Segera lakukan restock!";

// Inisialisasi cURL
$curl = curl_init();

// Loop untuk mengirim pesan ke setiap nomor
foreach ($no as $nomor) {
    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://api.fonnte.com/send',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => array(
            'target' => $nomor, // Menggunakan nomor saat ini dalam loop
            'message' => $message,
        ),
        CURLOPT_HTTPHEADER => array(
            'Authorization: 1AmFu4Yfw8p2C8Endzbe' // Ganti TOKEN dengan token Anda yang sebenarnya
        ),
    ));

    // Eksekusi cURL dan ambil respons
    $response = curl_exec($curl);
    // echo "Mengirim pesan ke $nomor: $response\n"; // Menampilkan respons untuk setiap nomor
}

// Tutup cURL
// curl_close($curl);
// ob_end_clean();
?>