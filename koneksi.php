<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "inventory";

// Buat koneksi
$conn = new mysqli($servername, $username, $password, $dbname);

// Cek koneksi
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil data dari formulir
    $name = $_POST['name'];
    $email = $_POST['email'];

    // Masukkan data ke database
    $sql = "INSERT INTO users (name, email) VALUES ('$name', '$email')";

    if ($conn->query($sql) === TRUE) {
        $last_id = $conn->insert_id;
        // Arahkan ke halaman cetak dengan ID yang baru ditambahkan
        header("Location: cetak.php?id=" . $last_id);
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>
