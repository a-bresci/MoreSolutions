document.addEventListener("DOMContentLoaded", () => {
  const menuItems = document.querySelectorAll(".sidebar li[data-section]");
  const contenido = document.getElementById("contenido");

  async function cargarSeccion(nombre) {
    try {
      // siempre intenta primero el HTML
      const res = await fetch(`partials/${nombre}.html`);
      const html = await res.text();
      contenido.innerHTML = html;

      // Cargar JS asociado a la sección
      if (nombre === "calculadora") {
        cargarScript("js/calculadora.js");
      } else if (nombre === "cotizaciones") {
        cargarScript("js/cotizaciones.js");
      } else if (nombre === "documentos") {
        cargarScript("js/documentos.js");
      } else if (nombre === "comisiones") {
        cargarScript("js/comisiones.js");
      }
    } catch (error) {
      contenido.innerHTML = `<p>Error al cargar la sección ${nombre}</p>`;
      console.error(error);
    }
  }

  function cargarScript(src) {
    const script = document.createElement("script");
    script.src = src + "?v=" + Date.now(); // fuerza recarga
    document.body.appendChild(script);
  }

  menuItems.forEach(item => {
    item.addEventListener("click", () => {
      menuItems.forEach(i => i.classList.remove("active"));
      item.classList.add("active");
      cargarSeccion(item.dataset.section);
    });
  });

  // cargar calculadora al inicio
  cargarSeccion("calculadora");
});
