<?php
require_once('tcpdf/tcpdf.php');
include 'koneksi.php';

if (!isset($_GET['kode_pemesanan'])) {
    die("Kode pemesanan tidak ditemukan!");
}

$kode_pemesanan = $_GET['kode_pemesanan'];

// Ambil data pemesanan utama
$query_pemesanan = "SELECT p.kode_pemesanan, p.tanggal_pemesanan, p.status_pemesanan, m.nama AS nama_pembeli
                    FROM pemesanan p
                    JOIN member m ON p.id_member = m.id_member
                    WHERE p.kode_pemesanan = '$kode_pemesanan'";

$result_pemesanan = mysqli_query($koneksi, $query_pemesanan);
$pemesanan = mysqli_fetch_assoc($result_pemesanan);

if (!$pemesanan) {
    die("Data pemesanan tidak ditemukan!");
}

// Ubah format tanggal menjadi "01 Januari 2025"
function formatTanggal($date) {
    $bulan = [
        "01" => "Januari", "02" => "Februari", "03" => "Maret",
        "04" => "April", "05" => "Mei", "06" => "Juni",
        "07" => "Juli", "08" => "Agustus", "09" => "September",
        "10" => "Oktober", "11" => "November", "12" => "Desember"
    ];
    $tanggal = date("d", strtotime($date));
    $bulanIndex = date("m", strtotime($date));
    $tahun = date("Y", strtotime($date));
    return $tanggal . " " . $bulan[$bulanIndex] . " " . $tahun;
}

// Ambil detail produk dalam pemesanan
$query_detail = "SELECT pr.nama_produk, p.jumlah, p.harga_satuan
                 FROM pemesanan p
                 JOIN produk pr ON p.id_produk = pr.id_produk
                 WHERE p.kode_pemesanan = '$kode_pemesanan'";

$result_detail = mysqli_query($koneksi, $query_detail);

// Hitung total harga
$total_item = 0;
$grand_total = 0;

// Buat objek PDF
$pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('NUSA DUA TANI');
$pdf->SetTitle('Faktur Pembelian');
$pdf->SetMargins(15, 15, 15);
$pdf->SetAutoPageBreak(TRUE, 15);

// Hapus header dan footer default
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);

$pdf->AddPage();

// Set default font
$pdf->SetFont('helvetica', '', 10);

// Header Faktur
$html = '
<style>
    table { border-collapse: collapse; width: 100%; margin-top: 15px; margin-bottom: 15px; }
    th { background-color: #f8f9fa; color: #2d3748; }
    td, th { padding: 8px; }
    .header { color: #1a365d; }
    .total-row { background-color: #f8f9fa; font-weight: bold; }
    .thank-you { color: #2d3748; }
</style>

<div style="text-align: center; margin-bottom: 20px;">
    <h1 style="font-size: 22pt; color: #1a365d; margin-bottom: 5px;">NUSA DUA TANI</h1>
    <div style="border-bottom: 2px solid #4a5568; margin: 10px 0;"></div>
    <br>
    <h2 style="font-size: 14pt; color: #2d3748; margin-top: 10px;">FAKTUR PEMBELIAN</h2>
</div>

<table cellpadding="5" style="margin-bottom: 20px;">
    <tr>
        <td width="20%" style="font-weight: bold; color: #4a5568;">No. Faktur:</td>
        <td width="80%">' . htmlspecialchars($pemesanan['kode_pemesanan']) . '</td>
    </tr>
    <tr>
        <td style="font-weight: bold; color: #4a5568;">Nama Pembeli:</td>
        <td>' . htmlspecialchars($pemesanan['nama_pembeli']) . '</td>
    </tr>
    <tr>
        <td style="font-weight: bold; color: #4a5568;">Tanggal:</td>
        <td>' . formatTanggal($pemesanan['tanggal_pemesanan']) . '</td>
    </tr>
</table>

<table border="1" cellpadding="6">
    <tr>
        <th width="5%" style="text-align: center; background-color: #edf2f7;">No</th>
        <th width="50%" style="background-color: #edf2f7;">Nama Produk</th>
        <th width="15%" style="text-align: right; background-color: #edf2f7;">Harga Satuan</th>
        <th width="10%" style="text-align: center; background-color: #edf2f7;">Qty</th>
        <th width="20%" style="text-align: right; background-color: #edf2f7;">Total</th>
    </tr>';

// Tambahkan data produk ke tabel
$no = 1;
while ($row = mysqli_fetch_assoc($result_detail)) {
    $total_harga = $row['jumlah'] * $row['harga_satuan'];
    $total_item += $row['jumlah'];
    $grand_total += $total_harga;

    $html .= '<tr' . ($no % 2 == 0 ? ' style="background-color: #f8fafc;"' : '') . '>
                <td style="text-align: center;">' . $no++ . '</td>
                <td>' . htmlspecialchars($row['nama_produk']) . '</td>
                <td style="text-align: right;">Rp ' . number_format($row['harga_satuan'], 0, ',', '.') . '</td>
                <td style="text-align: center;">' . $row['jumlah'] . '</td>
                <td style="text-align: right;">Rp ' . number_format($total_harga, 0, ',', '.') . '</td>
              </tr>';
}

// Tambahkan total harga dan informasi tambahan
$html .= '</table>

<div style="margin-top: 20px;">
    <table cellpadding="5" style="margin-bottom: 20px;">
        <tr>
            <td width="50%" style="text-align: left;">
                <span style="font-weight: bold; color: #4a5568;font-size: 13pt;">Total Item:</span> 
                <span style="font-size: 14pt;">' . $total_item . '</span>
            </td>
            <td width="50%" style="text-align: right;">
                <span style="font-weight: bold; color: #4a5568;font-size: 13pt;">Total:</span> 
                <span style="font-size: 14pt;">Rp ' . number_format($grand_total, 0, ',', '.') . '</span>
            </td>
        </tr>
    </table>
</div>

<div style="border-top: 2px solid #4a5568; margin-top: 30px; padding-top: 20px; text-align: center;">
    <h3 style="color: #1a365d; margin-bottom: 10px;">Terima kasih telah berbelanja di NUSA DUA TANI</h3>
    <p style="color: #4a5568; font-style: italic;">Silakan cek kembali belanjaan Anda</p>
</div>';

// Tambahkan HTML ke PDF
$pdf->writeHTML($html, true, false, true, false, '');

// Output PDF
$pdf->Output('Faktur_' . $kode_pemesanan . '.pdf', 'I');