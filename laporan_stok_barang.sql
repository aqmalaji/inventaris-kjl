CREATE TABLE IF NOT EXISTS laporan_stok_barang (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_barang INT NOT NULL,
    stok_awal INT NOT NULL,
    stok_masuk INT NOT NULL,
    stok_keluar INT NOT NULL,
    stok_akhir INT NOT NULL,
    tanggal DATE NOT NULL,
    FOREIGN KEY (id_barang) REFERENCES stok_barang(id)

INSERT INTO laporan_stok_barang (id_barang, stok_awal, stok_masuk, stok_keluar, stok_akhir, tanggal)
SELECT 
    id_barang,
    IFNULL((SELECT stok_akhir FROM laporan_stok_barang WHERE id_barang = b.id_barang ORDER BY tanggal DESC LIMIT 1), 0) AS stok_awal,
    IFNULL(SUM(masuk.jumlah), 0) AS stok_masuk,
    IFNULL(SUM(keluar.jumlah), 0) AS stok_keluar,
    (IFNULL((SELECT stok_akhir FROM laporan_stok_barang WHERE id_barang = b.id_barang ORDER BY tanggal DESC LIMIT 1), 0) + IFNULL(SUM(masuk.jumlah), 0) - IFNULL(SUM(keluar.jumlah), 0)) AS stok_akhir,
    CURDATE() AS tanggal
FROM 
    stok_barang b
LEFT JOIN 
    barang_masuk masuk ON b.id = masuk.id_barang AND masuk.tanggal = CURDATE()
LEFT JOIN 
    barang_keluar keluar ON b.id = keluar.id_barang AND keluar.tanggal = CURDATE()
GROUP BY 
    b.id
ON DUPLICATE KEY UPDATE 
    stok_awal = VALUES(stok_awal),
    stok_masuk = VALUES(stok_masuk),
    stok_keluar = VALUES(stok_keluar),
    stok_akhir = VALUES(stok_akhir),
    tanggal = VALUES(tanggal);

);