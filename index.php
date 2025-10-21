<?php
session_start();
if (!isset($_SESSION["usuario_id"])) {
  header("Location: login.php");
  exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Panel - More Solutions</title>
  <link rel="stylesheet" href="css/panel.css">
</head>
<body>
  <div class="dashboard">
    <!-- MENÚ LATERAL -->
    <aside class="sidebar">
      <div class="logo">
        <img src="img/logo.png" alt="More Solutions">
      </div>
      <h3>Hola, <?php echo htmlspecialchars($_SESSION["usuario_nombre"]); ?></h3>
      <ul>
        <li data-section="calculadora" class="active">🧮 Calculadora de Infracciones</li>
        <li data-section="cotizaciones">💰 Cotizaciones Guardadas</li>
        <li data-section="documentos">📎 Documentación Clientes</li>
        <li data-section="comisiones">💼 Calcular Comisión</li>
        <li><a href="php/logout.php" class="logout">🚪 Cerrar Sesión</a></li>
      </ul>
    </aside>

    <!-- CONTENIDO PRINCIPAL -->
    <main id="contenido" class="content-area">
      <!-- Secciones se cargan dinámicamente aquí -->
    </main>
  </div>

  <script src="js/main.js"></script>
</body>
</html>
