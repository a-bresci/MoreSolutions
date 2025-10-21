<?php
include "../php/conexion.php";
session_start();

$usuario_id = $_SESSION["usuario_id"] ?? 0;
$result = $conn->query("SELECT * FROM cotizaciones WHERE usuario_id = $usuario_id ORDER BY fecha DESC");
?>

<div class="calc-container">
  <h2>Cotizaciones Guardadas</h2>
  <table class="tabla-cotizaciones">
    <thead>
      <tr>
        <th>Patente / DNI</th>
        <th>Fecha</th>
        <th>Total</th>
        <th>Con Descuento</th>
        <th>Acciones</th>
      </tr>
    </thead>
    <tbody>
      <?php if ($result && $result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()): ?>
          <tr>
            <td><?= htmlspecialchars($row["patente"]) ?></td>
            <td><?= date("d/m/Y H:i", strtotime($row["fecha"])) ?></td>
            <td>$<?= number_format($row["total_general"], 2, ',', '.') ?></td>
            <td style="color:green;font-weight:bold;">$<?= number_format($row["total_descuento"], 2, ',', '.') ?></td>
            <td>
              <button class="btn-secundario" onclick="verCotizacion('<?= $row['ruta_imagen'] ?>')">👁 Ver</button>
              <a href="<?= $row['ruta_imagen'] ?>" download class="btn-principal">⬇ Descargar</a>
            </td>
          </tr>
          <tr class="detalle" id="detalle-<?= $row['id'] ?>" style="display:none;">
            <td colspan="5" style="text-align:center;background:#f9f9f9;">
              <img src="<?= $row['ruta_imagen'] ?>" style="max-width:80%;border-radius:10px;">
            </td>
          </tr>
        <?php endwhile; ?>
      <?php else: ?>
        <tr><td colspan="5">No hay cotizaciones guardadas.</td></tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>

<script src="js/cotizaciones.js?v=<?= time() ?>"></script>
