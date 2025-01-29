<?php
require_once('tcpdf/tcpdf.php');
include 'koneksi.php';

if (!isset($_GET['tanggal']) && !isset($_GET['bulan']) && !isset($_GET['tahun'])) {
    die("Filter tanggal, bulan, atau tahun harus diisi!");
}

// Ubah format tanggal ke format Indonesia
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

// Query filter laporan pemesanan
$where = [];
if (!empty($_GET['tanggal'])) {
    $where[] = "DATE(p.tanggal_pemesanan) = '" . mysqli_real_escape_string($koneksi, $_GET['tanggal']) . "'";
}
if (!empty($_GET['bulan'])) {
    $where[] = "MONTH(p.tanggal_pemesanan) = '" . mysqli_real_escape_string($koneksi, $_GET['bulan']) . "'";
}
if (!empty($_GET['tahun'])) {
    $where[] = "YEAR(p.tanggal_pemesanan) = '" . mysqli_real_escape_string($koneksi, $_GET['tahun']) . "'";
}
$where_clause = !empty($where) ? " WHERE " . implode(" AND ", $where) : "";

$query_pemesanan = "
    SELECT p.kode_pemesanan, p.tanggal_pemesanan, COALESCE(p.status_pemesanan, 'Tidak Diketahui') AS status_pemesanan, 
           p.jumlah, p.harga_satuan, p.total_harga, m.nama AS nama_pembeli, pr.nama_produk
    FROM pemesanan p
    JOIN member m ON p.id_member = m.id_member
    JOIN produk pr ON p.id_produk = pr.id_produk
    " . $where_clause . "
    ORDER BY p.tanggal_pemesanan DESC";

$result_pemesanan = mysqli_query($koneksi, $query_pemesanan);

// Buat objek PDF
$pdf = new TCPDF('L', 'mm', 'A4', true, 'UTF-8', false);
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Pasaman');
$pdf->SetTitle('Pasaman || Telepon 0821643132');
$pdf->SetMargins(10, 10, 10);
$pdf->SetAutoPageBreak(TRUE, 10);
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);
$pdf->AddPage();

$tanggal_periode = isset($_GET['tanggal']) ? $_GET['tanggal'] : date('Y-m-d');
$nama_pimpinan = isset($_GET['nama_pimpinan']) ? $_GET['nama_pimpinan'] : '_____________________';

$html = ' <h1 style="text-align:center; font-size: 24pt;">NUSA DUA TANI</h1> 
<hr style="margin-bottom: 5px;"> 
<h4 style="text-align:center; font-size: 18pt; margin-bottom: 0;">LAPORAN PEMESANAN</h4> 
<p style="text-align:center; font-size: 12pt; margin-top: 0;"><strong>Periode: ' . formatTanggal($tanggal_periode) . '</strong></p>';

// Tabel Header
$html .= '
<table border="1" cellpadding="5">
    <tr style="background-color:#f2f2f2;">
        <th style="text-align:center;"><strong>No</strong></th>
        <th><strong>Kode Pemesanan</strong></th>
        <th><strong>Nama Pembeli</strong></th>
        <th><strong>Nama Produk</strong></th>
        <th style="text-align:center;"><strong>Jumlah</strong></th>
        <th style="text-align:right;"><strong>Harga Satuan</strong></th>
        <th style="text-align:right;"><strong>Total Harga</strong></th>
        <th ><strong>Status</strong></th>
    </tr>';

// Isi Data Laporan
$no = 1;
$total_grand = 0;
while ($row = mysqli_fetch_assoc($result_pemesanan)) {
    $total_grand += $row['total_harga'];
    $html .= '<tr>
                <td align="center">' . $no++ . '</td>
                <td>' . htmlspecialchars($row['kode_pemesanan']) . '</td>
                <td>' . htmlspecialchars($row['nama_pembeli']) . '</td>
                <td>' . htmlspecialchars($row['nama_produk']) . '</td>
                <td align="center">' . $row['jumlah'] . '</td>
                <td align="right">Rp ' . number_format($row['harga_satuan'], 0, ',', '.') . '</td>
                <td align="right">Rp ' . number_format($row['total_harga'], 0, ',', '.') . '</td>
                <td>' . htmlspecialchars($row['status_pemesanan']) . '</td>
              </tr>';
}

// Total Keseluruhan
$html .= '<tr style="background-color:#f2f2f2;">
            <td colspan="6" align="center"><strong>Total Keseluruhan</strong></td>
            <td align="center" colspan="2" ><strong>Rp ' . number_format($total_grand, 0, ',', '.') . '</strong></td>
            <td></td>
          </tr>';
$html .= '</table>';

$html .= ' <br><br> 
<table width="150%" style="float: right;"> 
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
// Tambahkan HTML ke PDF
$pdf->writeHTML($html, true, false, true, false, '');

// Output PDF
$pdf->Output('Laporan_Pemesanan.pdf', 'I');
