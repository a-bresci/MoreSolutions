async function cargarCotizaciones() {
  const cont = document.getElementById("lista-cotizaciones");
  cont.innerHTML = "<p style='color:#ccc;'>Cargando cotizaciones...</p>";

  try {
    // ✅ Ruta corregida
    const res = await fetch("php/obtener_cotizaciones.php");
    if (!res.ok) throw new Error("Error HTTP " + res.status);

    const data = await res.json();
    console.log("📦 Datos recibidos:", data);

    if (!Array.isArray(data) || data.length === 0) {
      cont.innerHTML = "<p style='color:#ccc;'>No hay cotizaciones guardadas aún.</p>";
      return;
    }

    let html = `
      <table class="tabla-listado" border="1" cellpadding="8" cellspacing="0" style="width:100%;color:#eee;background:#222;">
        <thead style="background:#333;color:#ffcc00;">
          <tr>
            <th>Patente / DNI</th>
            <th>Fecha</th>
            <th>Total sin descuento</th>
            <th>Total con descuento</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody>
    `;

    data.forEach(c => {
      html += `
        <tr>
          <td>${c.patente}</td>
          <td>${c.fecha}</td>
          <td>$${parseFloat(c.total_general).toLocaleString("es-AR",{minimumFractionDigits:2})}</td>
          <td>$${parseFloat(c.total_descuento).toLocaleString("es-AR",{minimumFractionDigits:2})}</td>
          <td>
            <button class="btn-ver" onclick="ver.Cotizacion('${c.ruta_html}')">👁️ Ver detalles</button>
          </td>
        </tr>
        <tr class="detalle" style="display:none;">
          <td colspan="5">
            <img src="${c.ruta_html}" alt="Cotización ${c.patente}" style="max-width:600px;border:1px solid #555;border-radius:8px;">
          </td>
        </tr>
      `;
    });

    html += "</tbody></table>";
    cont.innerHTML = html;

  } catch (err) {
    console.error("❌ Error al cargar cotizaciones:", err);
    cont.innerHTML = "<p style='color:red;'>Error al cargar cotizaciones.</p>";
  }
}

cargarCotizaciones();
