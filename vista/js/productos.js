let $tabla = document.getElementById("tablaProductos");

fetch("../../ajax/productoAjax.php?accion=listar")
  .then((response) => response.json())
  .then(($productos) => {
    if ($productos.length === 0) {
      $tabla.innerHTML = `<tr><td colspan="5" class="text-center">No hay productos para mostrar</td></tr>`;
      return;
    }
    $tabla.innerHTML = dibujarTabla($productos);
  });

function dibujarTabla($datos) {
  let $dibujado = "";
  $datos.forEach((element) => {
    $dibujado += `
        <tr>
        <td style="vertical-align: middle">${element.idproducto}</td>
        <td style="vertical-align: middle">${element.pronombre}</td>
        <td style="vertical-align: middle">${element.prodetalle}</td>
        <td style="vertical-align: middle">${element.procantstock}</td>
        <td class="d-flex justify-content-center gap-5">
            <a href=#  class="btn btn-warning btn-sm px-3 py-2" title="Editar">
                <i class="bi bi-pen"></i>
            </a> 
            <a href=#  class="btn btn-danger btn-sm px-3 py-2" title="Borrar">
                <i class="bi bi-trash"></i>
            </a>
        </td>
        </tr>
        `;
  });

  return $dibujado;
}
