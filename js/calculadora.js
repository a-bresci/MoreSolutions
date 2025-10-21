(function () {
  console.log("🧮 calculadora.js cargado correctamente");

  let total = 0;
  let totalConDescuento = 0;

  const descuentos = {
    PBA: 0.6,
    CABA: 0.5,
    RETENIDA: 0.3,
    MUNICIPAL: 0.3,
    OTRAS: 0.3,
    JUBILADOS: 0.3
  };

  // --- Cargar html2canvas si no está ---
  function ensureHtml2canvas(cb) {
    if (window.html2canvas) return cb();
    const s = document.createElement("script");
    s.src = "https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js";
    s.onload = cb;
    s.onerror = () => alert("Error al cargar html2canvas");
    document.body.appendChild(s);
  }

  // --- Crear una fila de multa ---
  function crearFilaMulta() {
    const div = document.createElement("div");
    div.className = "multa-row";
    div.innerHTML = `
      <select class="tipo-multa">
        <option value="">Seleccionar tipo de multa</option>
        <option value="PBA">PBA</option>
        <option value="CABA">CABA</option>
        <option value="RETENIDA">Licencia Retenida</option>
        <option value="MUNICIPAL">Municipal</option>
        <option value="OTRAS">Otras Provincias</option>
        <option value="JUBILADOS">Jubilados</option>
      </select>
      <input type="text" class="monto" placeholder="Monto total ($)">
      <input type="number" class="cantidad" placeholder="Cant." min="1" value="1" style="width:70px">
      <button class="btn eliminar">🗑️</button>
    `;
    return div;
  }

  // --- Calcular y mostrar cotización ---
  function calcularYMostrar() {
    const patenteEl = document.getElementById("patente");
    const patente = patenteEl ? patenteEl.value.trim().toUpperCase() : "";
    if (!patente) return alert("Ingrese una patente o DNI.");

    const filas = document.querySelectorAll(".multa-row");
    total = 0;
    totalConDescuento = 0;
    const detalles = [];

    filas.forEach(fila => {
      const tipo = fila.querySelector(".tipo-multa")?.value;
      const montoStr = fila.querySelector(".monto")?.value.trim().replace(/\./g, "").replace(",", ".");
      const cantidad = parseInt(fila.querySelector(".cantidad")?.value) || 1;
      const monto = parseFloat(montoStr);

      if (tipo && !isNaN(monto) && monto > 0) {
        const descuento = descuentos[tipo] || 0;
        const conDescuento = monto * (1 - descuento);
        detalles.push({ tipo, cantidad, monto, conDescuento, descuento });
        total += monto;
        totalConDescuento += conDescuento;
      }
    });

    if (detalles.length === 0) return alert("Agregue al menos una multa válida.");

    const fechaValidez = new Date();
    fechaValidez.setDate(fechaValidez.getDate() + 10);
    const fechaFormateada = fechaValidez.toLocaleDateString("es-AR");

    let html = `
    <div id="cotizacion" style="
      background:white;
      padding:25px;
      border:3px solid #f5c542;
      border-radius:15px;
      max-width:700px;
      margin:auto;
      font-family:'Segoe UI', Arial, sans-serif;
    ">
      <div style="text-align:center;margin-bottom:15px;padding-bottom:10px;border-bottom:2px solid #eee;">
        <img src="img/logo.png" style="width:160px;margin-bottom:8px;">
        <h3 style="margin:0;color:#0b2a53;">Detalle de Cotización - ${patente}</h3>
      </div>
    `;

    detalles.forEach(d => {
      html += `
      <div style="
        display:flex;
        justify-content:space-between;
        align-items:center;
        margin-bottom:10px;
        padding:8px 0;
        border-bottom:1px solid #eee;
      ">
        <div style="display:flex;align-items:center;gap:10px;">
          <img src="img/${d.tipo.toLowerCase()}.png" width="40" onerror="this.style.display='none'">
          <div>
            <strong>${d.tipo}</strong><br>
            <span style="color:#888;font-size:13px;">${(d.descuento * 100).toFixed(0)}% de descuento</span><br>
            <span style="font-weight:bold;color:#555;font-size:13px;">${d.cantidad} multa${d.cantidad > 1 ? 's' : ''}</span>
          </div>
        </div>
        <div style="text-align:right;">
          <strong>Total:</strong> $${d.monto.toLocaleString("es-AR",{minimumFractionDigits:2})}<br>
          <strong style="color:#28a745;">Con descuento:</strong> $${d.conDescuento.toLocaleString("es-AR",{minimumFractionDigits:2})}
        </div>
      </div>`;
    });

    html += `
      <div style="margin-top:20px;padding:12px;border:2px solid #ddd;border-radius:10px;background:#f9f9f9;">
        <div style="display:flex;justify-content:space-between;align-items:center;">
          <div style="display:flex;align-items:center;gap:8px;">
            <img src="img/cross.png" width="24" onerror="this.style.display='none'">
            <strong>Total sin descuento:</strong>
          </div>
          <span style="text-decoration:line-through;color:#888;">
            $${total.toLocaleString("es-AR", { minimumFractionDigits: 2 })}
          </span>
        </div>
      </div>

      <div style="margin-top:10px;padding:12px;border:2px solid #ddd;border-radius:10px;background:#f9f9f9;">
        <div style="display:flex;justify-content:space-between;align-items:center;">
          <div style="display:flex;align-items:center;gap:8px;">
            <img src="img/check.png" width="24" onerror="this.style.display='none'">
            <strong style="color:#28a745;">Total con descuento:</strong>
          </div>
          <span style="font-weight:800;color:#000;font-size:1.1em;">
            $${totalConDescuento.toLocaleString("es-AR", { minimumFractionDigits: 2 })}
          </span>
        </div>
      </div>

      <p style="color:#666;font-size:13px;text-align:center;margin-top:12px;">
        Válido hasta el ${fechaFormateada}
      </p>
    </div>`;

    document.getElementById("resultado").innerHTML = html;
  }

  // --- Inicialización de eventos ---
  function initCalculadora() {
    const cont = document.querySelector(".multas-container");
    const agregarBtn = document.getElementById("agregar-multa");
    const calcularBtn = document.getElementById("calcular");
    const guardarBtn = document.getElementById("guardar");
    const descargarBtn = document.getElementById("descargar");
    const resultado = document.getElementById("resultado");

    // Crear primera fila
    if (cont && !cont.querySelector(".multa-row")) {
      cont.prepend(crearFilaMulta());
    }

    // Botón Agregar
    if (agregarBtn) {
      agregarBtn.addEventListener("click", e => {
        e.preventDefault();
        cont.appendChild(crearFilaMulta());
      });
    }

    // Eliminar fila
    document.addEventListener("click", e => {
      if (e.target.classList.contains("eliminar")) {
        e.preventDefault();
        e.target.closest(".multa-row").remove();
      }
    });

    // Validar monto
    document.addEventListener("input", e => {
      if (e.target.classList.contains("monto")) {
        e.target.value = e.target.value.replace(/[^0-9\.,]/g, "");
      }
    });

    // Calcular
    if (calcularBtn) calcularBtn.addEventListener("click", e => {
      e.preventDefault();
      calcularYMostrar();
    });

    // Descargar imagen
    if (descargarBtn) {
      descargarBtn.addEventListener("click", e => {
        e.preventDefault();
        const patente = document.getElementById("patente").value.trim().toUpperCase();
        const cotizacion = document.querySelector("#resultado #cotizacion");
        if (!cotizacion) return alert("Primero calcule la cotización.");
        ensureHtml2canvas(() => {
          html2canvas(cotizacion, { scale: 2 }).then(canvas => {
            const link = document.createElement("a");
            link.download = `${patente}_cotizacion.png`;
            link.href = canvas.toDataURL("image/png");
            link.click();
          });
        });
      });
    }

    // Guardar cotización (💾 PNG en servidor)
    if (guardarBtn) {
  guardarBtn.addEventListener("click", async e => {
    e.preventDefault();

    const patente = document.getElementById("patente").value.trim().toUpperCase();
    const cotizacion = document.querySelector("#resultado #cotizacion");

    if (!patente || !cotizacion) return alert("Calcule la cotización antes de guardar.");

    ensureHtml2canvas(() => {
      html2canvas(cotizacion, { scale: 2 }).then(async canvas => {
        const imagenBase64 = canvas.toDataURL("image/png");

        const formData = new FormData();
        formData.append("patente", patente);
        formData.append("imagen", imagenBase64);
        formData.append("total_general", total);
        formData.append("total_con_descuento", totalConDescuento);

        // ✅ ruta dinámica, siempre correcta sin importar el contexto
        const ruta = `${window.location.origin}/2025/MoreSolutions/php/guardar_cotizacion.php`;

        try {
          const res = await fetch(ruta, { method: "POST", body: formData });
          const text = await res.text();
          alert(text);
          console.log("💾 Guardado:", text);
        } catch (err) {
          console.error("Error guardando cotización:", err);
          alert("Error al guardar cotización.");
        }
      });
    });
  });
}
  }

  function tryInit(retries = 10, delay = 150) {
    if (document.getElementById("resultado")) initCalculadora();
    else if (retries > 0) setTimeout(() => tryInit(retries - 1, delay), delay);
  }

  tryInit();
})();
