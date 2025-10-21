<?php
// php/create_admin.php
include "conexion.php";

$nombre = "Administrador";
$email  = "admin@admin.com";
$username = "admin";
$plain_password = "admin"; // contraseña inicial; cambiá luego

// verificar si ya existe el email o username
$stmt = $conn->prepare("SELECT id FROM usuarios WHERE email = ? OR username = ?");
$stmt->bind_param("ss", $email, $username);
$stmt->execute();
$stmt->store_result();
if ($stmt->num_rows > 0) {
    echo "Ya existe un usuario con ese email/username. Si necesitás regenerar la contraseña, editá el registro desde phpMyAdmin.";
    $stmt->close();
    $conn->close();
    exit;
}
$stmt->close();

// insertar con password hasheada
$hash = password_hash($plain_password, PASSWORD_DEFAULT);
$stmt = $conn->prepare("INSERT INTO usuarios (nombre, email, username, password, rol) VALUES (?, ?, ?, ?, ?)");
$role = "admin";
$stmt->bind_param("sssss", $nombre, $email, $username, $hash, $role);

if ($stmt->execute()) {
    echo "Usuario admin creado correctamente.<br>";
    echo "Email: <strong>$email</strong><br>";
    echo "Usuario: <strong>$username</strong><br>";
    echo "Contraseña: <strong>$plain_password</strong><br>";
    echo "<br>Por seguridad borrá o renombrá este archivo create_admin.php después de usarlo.";
} else {
    echo "Error al crear admin: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
