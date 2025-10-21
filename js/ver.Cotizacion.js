function verCotizacion(ruta) {
  const filas = document.querySelectorAll(".detalle");
  filas.forEach(f => f.style.display = "none");

  const detalle = document.querySelector(`img[data-ruta='${ruta}']`)?.closest(".detalle");
  if (detalle) {
    const visible = detalle.style.display === "table-row";
    detalle.style.display = visible ? "none" : "table-row";
  }
}
