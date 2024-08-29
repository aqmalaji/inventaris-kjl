<?php
// Koneksi ke database
$host = 'localhost';
$user = 'root';
$password = '';
$dbname = 'inventory_db';

$conn = new mysqli($host, $user, $password, $dbname);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Fungsi untuk mendapatkan data stok barang
function getStokBarang($conn, $periode) {
    $query = "";

    if ($periode === 'mingguan') {
        $query = "SELECT nama_barang, SUM(jumlah) AS total_jumlah, satuan, DATE_FORMAT(tanggal, '%Y-%m-%d') AS periode
                  FROM stok_barang
                  WHERE tanggal >= DATE_SUB(CURDATE(), INTERVAL 1 WEEK)
                  GROUP BY nama_barang, periode, satuan
                  ORDER BY periode DESC";
    } else if ($periode === 'bulanan') {
        $query = "SELECT nama_barang, SUM(jumlah) AS total_jumlah, satuan, DATE_FORMAT(tanggal, '%Y-%m') AS periode
                  FROM stok_barang
                  WHERE tanggal >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH)
                  GROUP BY nama_barang, periode, satuan
                  ORDER BY periode DESC";
    }

    $result = $conn->query($query);
    $data = [];

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
    }

    return $data;
}

// Mendapatkan data stok barang berdasarkan periode
$periode = isset($_GET['periode']) ? $_GET['periode'] : 'mingguan';
$dataStok = getStokBarang($conn, $periode);

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Stok Barang</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>Laporan Stok Barang <?php echo ucfirst($periode); ?></h1>
        <div class="periode">
            <a href="?periode=mingguan" class="<?php echo $periode === 'mingguan' ? 'active' : ''; ?>">Mingguan</a>
            <a href="?periode=bulanan" class="<?php echo $periode === 'bulanan' ? 'active' : ''; ?>">Bulanan</a>
        </div>
        <table>
            <thead>
                <tr>
                    <th>Nama Barang</th>
                    <th>Jumlah</th>
                    <th>Satuan</th>
                    <th>Periode</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($dataStok)): ?>
                    <?php foreach ($dataStok as $stok): ?>
                    <tr>
                        <td><?php echo $stok['nama_barang']; ?></td>
                        <td><?php echo $stok['total_jumlah']; ?></td>
                        <td><?php echo $stok['satuan']; ?></td>
                        <td><?php echo $stok['periode']; ?></td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4">Tidak ada data stok barang.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>

<?php
$conn->close();
?>