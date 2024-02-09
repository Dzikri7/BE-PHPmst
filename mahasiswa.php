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

    $sql = "SELECT * FROM mstmahasiswa";
    $result = $conn->query($sql);
    $mahasiswa = array();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $mahasiswa[] = $row;
        }
    }
    echo json_encode($mahasiswa);
}  

elseif ($_SERVER["REQUEST_METHOD"] === "POST") {
    $data = json_decode(file_get_contents("php://input"), true);

    if (isset($data["npm"]) && isset($data["nama_lengkap"]) && isset($data["alamat"])) {
        $npm = $data["npm"];
        $nama_lengkap = $data["nama_lengkap"];
        $alamat = $data["alamat"];

        $sql = "INSERT INTO mstmahasiswa (npm, nama_lengkap, alamat) VALUES ('$npm', '$nama_lengkap', '$alamat')";
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
