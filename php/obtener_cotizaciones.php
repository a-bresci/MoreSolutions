<?php
session_start();
require_once "conexion.php";

header("Content-Type: application/json; charset=utf-8");

// Si no hay sesión, usá ID 1 para pruebas
$usuario_id = $_SESSION["usuario_id"] ?? 1;

try {
    $sql = "SELECT 
                patente,
                fecha,
                total_general,
                total_con_descuento AS total_descuento,
                CONCAT('.../MoreSolutions/uploads/cotizaciones/png/', SUBSTRING_INDEX(ruta_imagen, '/', -1)) AS ruta_html
            FROM cotizaciones
            WHERE usuario_id = ?
            ORDER BY fecha DESC";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $usuario_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $cotizaciones = [];
    while ($row = $result->fetch_assoc()) {
        $cotizaciones[] = $row;
    }

    echo json_encode($cotizaciones, JSON_UNESCAPED_UNICODE);

} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(["error" => $e->getMessage()]);
}
