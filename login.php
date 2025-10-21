<?php
// login.php
session_start();
include "php/conexion.php";

$error = "";

// Si ya hay sesión, redirigir al panel
if (isset($_SESSION["usuario_id"])) {
    header("Location: index.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $userInput = trim($_POST["userInput"] ?? "");
    $password  = $_POST["password"] ?? "";

    if ($userInput === "" || $password === "") {
        $error = "Por favor complete usuario/correo y contraseña.";
    } else {
        // Buscar por email o username
        $sql = "SELECT id, nombre, email, username, password, rol FROM usuarios WHERE email = ? OR username = ?";
        $stmt = $conn->prepare($sql);
        if ($stmt === false) {
            $error = "Error interno de la base de datos.";
        } else {
            $stmt->bind_param("ss", $userInput, $userInput);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result && $result->num_rows > 0) {
                $row = $result->fetch_assoc();

                // verificar contraseña hasheada
                if (password_verify($password, $row["password"])) {
                    // guardar datos en sesión
                    $_SESSION["usuario_id"] = $row["id"];
                    $_SESSION["usuario_nombre"] = $row["nombre"];
                    $_SESSION["usuario_email"] = $row["email"];
                    $_SESSION["usuario_username"] = $row["username"];
                    $_SESSION["usuario_rol"] = $row["rol"] ?? "user";

                    // limpiar y redirigir
                    $stmt->close();
                    $conn->close();
                    header("Location: index.php");
                    exit();
                } else {
                    $error = "Contraseña incorrecta.";
                }
            } else {
                $error = "Usuario o correo no encontrado.";
            }
            $stmt->close();
        }
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Login - More Solutions</title>
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <link rel="stylesheet" href="css/login.css">
  <style>
    /* Estilo mínimo inline por si no cargás css */
    body{font-family:Arial,Helvetica,sans-serif;background:#0b2a53;margin:0;display:flex;align-items:center;justify-content:center;height:100vh}
    .login-box{background:#fff;padding:28px;border-radius:10px;box-shadow:0 6px 20px rgba(0,0,0,.15);width:360px;border:3px solid #f5c542}
    .login-box h2{text-align:center;color:#0b2a53;margin:0 0 14px}
    .login-box input{width:100%;padding:10px;margin:8px 0;border-radius:6px;border:1px solid #ccc;font-size:15px}
    .login-box button{width:100%;padding:10px;border-radius:8px;border:none;background:#0b2a53;color:#fff;font-weight:700;cursor:pointer}
    .error{background:#ffdede;color:#990000;padding:8px;border-radius:6px;margin-bottom:8px;font-weight:600}
    .note{font-size:13px;color:#666;margin-top:10px;text-align:center}
  </style>
</head>
<body>
  <div class="login-box">
    <img src="img/logo.png" alt="More Solutions" style="display:block;margin:0 auto 10px;width:120px">
    <h2>Iniciar sesión</h2>

    <?php if (!empty($error)): ?>
      <div class="error"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <form method="post" autocomplete="off">
      <input type="text" name="userInput" placeholder="Usuario o correo" required value="<?php echo isset($_POST['userInput']) ? htmlspecialchars($_POST['userInput']) : ''; ?>">
      <input type="password" name="password" placeholder="Contraseña" required>
      <button type="submit">Ingresar</button>
    </form>

    <div class="note">¿No tenés cuenta? Pedí al administrador que la cree.</div>
  </div>
</body>
</html>
