<?php
require('fpdf186/fpdf.php');
require('../koneksi.php');
include('../config/function.php');

// Ambil bulan dan tahun jika di input
$bulan = isset($_GET['bulan']) ? $_GET['bulan'] : date('m'); 
$tahun = isset($_GET['tahun']) ? $_GET['tahun'] : date('Y'); 

// Nama bulan
$nama_bulan_array = [
    1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 
    'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
];
$nama_bulan = $nama_bulan_array[intval($bulan)];

// Cek bulan sekarang atau bulan yang lain
$is_current_month = ($bulan == date('m') && $tahun == date('Y'));

// Menyiapkan query untuk mendapatkan data
$query = "
    SELECT 
        bb.nama_bahan_baku,
        bb.unit,
        bb.kuantitas AS kuantitas_awal,
        SUM(CASE WHEN bmk.id_stok_masuk IS NOT NULL THEN bmk.kuantitas ELSE 0 END) AS stok_masuk,
        SUM(CASE WHEN bmk.id_stok_keluar IS NOT NULL THEN bmk.kuantitas ELSE 0 END) AS stok_keluar
    FROM 
        t_bahan_baku bb
    LEFT JOIN 
        t_bahan_masuk_keluar bmk 
    ON 
        bb.id_bahan_baku = bmk.id_bahan_baku
    WHERE 
        MONTH(bmk.tanggal) = $bulan
        AND YEAR(bmk.tanggal) = $tahun
    GROUP BY 
        bb.nama_bahan_baku, bb.unit, bb.kuantitas
";

$data = select($query);

// Membuat PDF
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 14);

// Menambahkan Judul
$judul = "Laporan Stok Masuk dan Keluar Bahan Baku\nBulan $nama_bulan Tahun $tahun";
$pdf->MultiCell(0, 10, $judul, 0, 'C');
$pdf->Ln(10); // Jarak setelah judul

// Header tabel
$pdf->SetFont('Arial', 'B', 12);
$widths = [50, 30, 40, 40, 40];
$table_width = array_sum($widths); 

$page_width = $pdf->GetPageWidth(); 
$x_position = ($page_width - $table_width) / 2; 

$pdf->SetX($x_position);
$pdf->Cell(50, 10, 'Nama Bahan Baku', 1, 0, 'C');
$pdf->Cell(30, 10, 'Unit', 1, 0, 'C');
if ($is_current_month) {
    $pdf->Cell(40, 10, 'Total Kuantitas', 1, 0, 'C');
}
$pdf->Cell(40, 10, 'Stok Masuk', 1, 0, 'C');
$pdf->Cell(40, 10, 'Stok Keluar', 1, 0, 'C');
$pdf->Ln();

// Isi tabel
$pdf->SetFont('Arial', '', 12);
foreach ($data as $q) {
    $pdf->SetX($x_position); 
    
    // Nama bahan baku dengan MultiCell
    $y_position_before = $pdf->GetY(); // Simpan posisi Y sebelum MultiCell
    $pdf->MultiCell(50, 10, $q['nama_bahan_baku'], 1, 'L');
    $y_position_after = $pdf->GetY(); // Posisi Y setelah MultiCell
    
    // Hitung tinggi sel (gunakan nilai tertinggi dari kolom lainnya)
    $cell_height = $y_position_after - $y_position_before;
    $cell_height = max($cell_height, 10); 

    $pdf->SetXY($x_position + 50, $y_position_before);
    
    $pdf->Cell(30, $cell_height, $q['unit'], 1, 0, 'C');

    if ($is_current_month) {
        $pdf->Cell(40, $cell_height, $q['kuantitas_awal'], 1, 0, 'C');
    }

    $pdf->Cell(40, $cell_height, $q['stok_masuk'], 1, 0, 'C');

    $pdf->Cell(40, $cell_height, $q['stok_keluar'], 1, 0, 'C');

    $pdf->Ln();
}

// Output PDF
$nama_file = 'Laporan_Bulan_' . $bulan . '_Tahun_' . $tahun . '.pdf';
$pdf->Output('D', $nama_file);
?>
