<?php
require_once('tcpdf/tcpdf.php');
include 'koneksi.php';

// Ambil Nama Pimpinan dari GET (untuk menghindari Undefined variable)
$nama_pimpinan = isset($_GET['nama_pimpinan']) ? $_GET['nama_pimpinan'] : '_____________________';

// Query ambil data produk
$query_produk = "SELECT * FROM produk ORDER BY nama_produk ASC";
$result_produk = mysqli_query($koneksi, $query_produk);

function formatTanggal($date)
{
    $bulan = [
        "01" => "Januari",
        "02" => "Februari",
        "03" => "Maret",
        "04" => "April",
        "05" => "Mei",
        "06" => "Juni",
        "07" => "Juli",
        "08" => "Agustus",
        "09" => "September",
        "10" => "Oktober",
        "11" => "November",
        "12" => "Desember"
    ];
    $tanggal = date("d", strtotime($date));
    $bulanIndex = date("m", strtotime($date));
    $tahun = date("Y", strtotime($date));
    return $tanggal . " " . $bulan[$bulanIndex] . " " . $tahun;
}

// Buat objek PDF
$pdf = new TCPDF('L', 'mm', 'A4', true, 'UTF-8', false);
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Admin');
$pdf->SetTitle('Laporan Produk');
$pdf->SetMargins(10, 10, 10);
$pdf->SetAutoPageBreak(TRUE, 10);
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);
$pdf->AddPage();

// Header - Judul Laporan
$html = '
    <h1 style="text-align:center; font-size: 24pt;">NUSA DUA TANI</h1>
    <hr style="margin-bottom: 10px;">
    <h4 style="text-align:center; font-size: 18pt; margin-bottom: 0;">LAPORAN PRODUK</h4>
    <br>
';

// Tabel Header
$html .= '
<table border="1" cellpadding="5">
    <tr style="background-color:#f2f2f2;">
        <th width="5%" style="text-align:center;"><strong>No</strong></th>
        <th width="20%"><strong>Nama Produk</strong></th>
        <th width="15%"><strong>Merek</strong></th>
        <th width="15%" style="text-align:right;"><strong>Harga Satuan</strong></th>
        <th width="10%" style="text-align:center;"><strong>Stok</strong></th>
        <th width="10%"><strong>Satuan</strong></th>
        <th width="25%"><strong>Gambar</strong></th>
    </tr>';

// Isi Data Laporan
$no = 1;
while ($row = mysqli_fetch_assoc($result_produk)) {
    $html .= '<tr>
                <td align="center">' . $no++ . '</td>
                <td>' . htmlspecialchars($row['nama_produk']) . '</td>
                <td>' . htmlspecialchars($row['merek']) . '</td>
                <td align="right">Rp ' . number_format($row['harga_satuan'], 0, ',', '.') . '</td>
                <td align="center">' . htmlspecialchars($row['stok']) . '</td>
                <td>' . htmlspecialchars($row['satuan']) . '</td>
                <td>
                    <img src="image/foto_produk/' . htmlspecialchars($row['gambar']) . '" width="40" height="40" />
                </td>
              </tr>';
}

$html .= '</table>';

// Tambahkan bagian tanda tangan
$html .= '
<br><br> 
<table width="150%" style="margin-left: 100px;"> 
    <tr> 
        <td width="50%" style="text-align:left;"></td> 
        <td width="50%" style="text-align:left;"> 
            <p>Pasaman, ' . formatTanggal(date('Y-m-d')) . '</p> 
            <p>Mengetahui,</p> 
            <br><br><br><br> 
            <p><strong>' . htmlspecialchars($nama_pimpinan) . '</strong></p> 
        </td> 
    </tr> 
</table>';

// Tambahkan ke PDF
$pdf->writeHTML($html, true, false, true, false, '');

// Output PDF
$pdf->Output('Laporan_Produk.pdf', 'I');
