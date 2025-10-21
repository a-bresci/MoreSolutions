<?php
// php/register_user.php
header("Content-Type: application/json");
require_once "conexion.php";

$nombre = trim($_POST['nombre'] ?? '');
$email = trim($_POST['email'] ?? '');
$username = trim($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';

if (!$nombre || !$email || !$username || !$password) {
    echo json_encode(['success' => false, 'message' => 'Faltan datos']);
    exit;
}

// verificar unicidad
$stmt = $conn->prepare("SELECT id FROM usuarios WHERE email = ? OR username = ? LIMIT 1");
$stmt->bind_param("ss", $email, $username);
$stmt->execute();
$stmt->store_result();
if ($stmt->num_rows > 0) {
    echo json_encode(['success' => false, 'message' => 'Email o usuario ya registrado']);
    $stmt->close();
    exit;
}
$stmt->close();

// crear usuario
$hash = password_hash($password, PASSWORD_DEFAULT);
$stmt = $conn->prepare("INSERT INTO usuarios (nombre, email, username, password_hash, rol) VALUES (?, ?, ?, ?, 'user')");
$stmt->bind_param("ssss", $nombre, $email, $username, $hash);
if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Usuario creado']);
} else {
    echo json_encode(['success' => false, 'message' => 'Error al crear usuario']);
}
$stmt->close();
$conn->close();
?>
