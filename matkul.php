<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, GET, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "pemrogdb";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Koneksi Gagal: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] === "GET") {

    $sql = "SELECT * FROM matakuliah";
    $result = $conn->query($sql);
    $matkul = array();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $matkul[] = $row;
        }
    }
    echo json_encode($matkul);
} 

elseif ($_SERVER["REQUEST_METHOD"] === "POST") {
    $data = json_decode(file_get_contents("php://input"), true);

    if (isset($data["nama_matkul"]) && isset($data["kode_matkul"]) && isset($data["jumlah_sks"])) {
        $nama_matkul = $data["nama_matkul"];
        $kode_matkul = $data["kode_matkul"];
        $jumlah_sks = $data["jumlah_sks"];

        $sql = "INSERT INTO matakuliah (nama_matkul, kode_matkul, jumlah_sks) VALUES ('$nama_matkul', '$kode_matkul', '$jumlah_sks')";
        if ($conn->query($sql) === TRUE) {
            echo json_encode(array("message" => "Isi data berhasil disimpan"));
        } else {
            echo json_encode(array("message" => "Error: " . $sql . "<br>" . $conn->error));
        }
    } else {
        echo json_encode(array("message" => "Error: Incomplete data sent in the request."));
    }
}

$conn->close();
?>
