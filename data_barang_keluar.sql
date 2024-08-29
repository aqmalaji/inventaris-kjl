CREATE TABLE IF NOT EXISTS barang_keluar (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_barang INT NOT NULL,
    jumlah INT NOT NULL,
    tanggal DATE NOT NULL,
    FOREIGN KEY (id_barang) REFERENCES stok_barang(id)
);