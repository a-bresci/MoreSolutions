<?php
// php/conexion.php
$host = "localhost";
$user = "root";
$pass = ""; // si en tu XAMPP usás otra contraseña cambiala
$dbname = "moresolutions";

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}
$conn->set_charset("utf8mb4");
?>
