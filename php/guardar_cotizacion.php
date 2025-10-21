<?php
session_start();
include "conexion.php";

// Validar sesión
if (!isset($_SESSION["usuario_id"])) {
  echo "Error: sesión no iniciada.";
  exit;
}

$usuario_id = $_SESSION["usuario_id"];
$patente = $_POST["patente"] ?? "";
$total_general = $_POST["total_general"] ?? 0;
$total_con_descuento = $_POST["total_con_descuento"] ?? 0;
$imagen_base64 = $_POST["imagen"] ?? "";

if (!$patente || !$imagen_base64) {
  echo "Faltan datos.";
  exit;
}

// Crear carpeta si no existe
$dir = "../uploads/cotizaciones/png/";
if (!is_dir($dir)) mkdir($dir, 0777, true);

// Guardar imagen (base64 → PNG)
$nombre_archivo = $patente . "_" . time() . ".png";
$ruta_archivo = $dir . $nombre_archivo;
file_put_contents($ruta_archivo, base64_decode(str_replace('data:image/png;base64,', '', $imagen_base64)));

// Guardar registro en base de datos
$stmt = $conn->prepare("
  INSERT INTO cotizaciones (patente, total_general, total_con_descuento, ruta_imagen, usuario_id)
  VALUES (?, ?, ?, ?, ?)
");
$stmt->bind_param("sddsi", $patente, $total_general, $total_con_descuento, $ruta_archivo, $usuario_id);

if ($stmt->execute()) {
  echo "✅ Cotización guardada correctamente.";
} else {
  echo "❌ Error al guardar la cotización: " . $conn->error;
}

$stmt->close();
$conn->close();
?>
