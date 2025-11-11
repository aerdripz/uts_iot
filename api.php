<?php
header('Content-Type: application/json; charset=utf-8');

$host = "localhost";
$user = "root";
$pass = "";
$db   = "iot_aerosphare";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
  http_response_code(500);
  echo json_encode(["error" => "Koneksi database gagal"]);
  exit;
}

$sql = "SELECT * FROM data_sensor ORDER BY id DESC LIMIT 20";
$result = $conn->query($sql);

$data = [];
while($row = $result->fetch_assoc()) {
  $data[] = $row;
}

echo json_encode($data);
$conn->close();
?>