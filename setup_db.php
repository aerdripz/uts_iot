<?php
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "iot_aerosphare";

// 1️⃣ Koneksi ke MySQL tanpa memilih database dulu
$conn = new mysqli($host, $user, $pass);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// 2️⃣ Buat database jika belum ada
$sql = "CREATE DATABASE IF NOT EXISTS $dbname";
if ($conn->query($sql) === TRUE) {
    echo "✅ Database '$dbname' berhasil dibuat atau sudah ada.<br>";
} else {
    echo "❌ Error membuat database: " . $conn->error . "<br>";
}

// 3️⃣ Pilih database
$conn->select_db($dbname);

// 4️⃣ Buat tabel data_sensor jika belum ada
$sql = "CREATE TABLE IF NOT EXISTS data_sensor (
    id INT AUTO_INCREMENT PRIMARY KEY,
    suhu FLOAT,
    humidity FLOAT,
    lux FLOAT,
    timestamp DATETIME DEFAULT CURRENT_TIMESTAMP
)";
if ($conn->query($sql) === TRUE) {
    echo "✅ Tabel 'data_sensor' berhasil dibuat atau sudah ada.<br>";
} else {
    echo "❌ Error membuat tabel: " . $conn->error . "<br>";
}

// 5️⃣ (Opsional) Tambahkan contoh data
$sql = "INSERT INTO data_sensor (suhu, humidity, lux) VALUES (29.5, 70.2, 450)";
$conn->query($sql);

echo "✅ Data contoh berhasil dimasukkan.<br>";

$conn->close();
?>