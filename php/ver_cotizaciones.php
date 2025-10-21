<?php
include "conexion.php";
header('Content-Type: application/json; charset=utf-8');

$query = "SELECT id, patente, fecha, total_general, total_descuento, ruta_imagen FROM cotizaciones ORDER BY fecha DESC";
$result = $conn->query($query);

$datos = [];

while ($row = $result->fetch_assoc()) {
    $datos[] = $row;
}

echo json_encode($datos, JSON_UNESCAPED_UNICODE);
$conn->close();
?>
