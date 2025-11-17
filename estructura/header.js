document.addEventListener("DOMContentLoaded", () => {
  let $ul = document.getElementById("ulMenu");
  const rol = $ul.dataset.rol;
  let $paraAgregar = "";
  //llamamos a los submenues para despues juntarlos con logica
  fetch("/tpfinaldinamica/ajax/menuHeaderAjax.php", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({
      accion: "listarSubMenus",
      id: rol,
    }),
  })
    .then((response) => response.json())
    .then(($subMenues) => {

      //llamamos a los menues principales

      fetch("/tpfinaldinamica/ajax/menuHeaderAjax.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({
          id: rol,
          accion: "listarMenus",
        }),
      })
        .then((response) => response.json())
        .then((data) => {

          const subArray = Array.isArray($subMenues) ? $subMenues : [];

          
            data.forEach((elementPrincipal) => {
              let $subMenusLi = "";
              subArray.forEach((elementSub) => {
                if (elementSub.idpadre == elementPrincipal.idmenu) {
                  $subMenusLi += `<li><a class="dropdown-item" href="${elementSub.medescripcion}">${elementSub.menombre}</a></li>`;
                }
              });
              if ($subMenusLi === "") {
                $subMenusLi = `<li><span class="dropdown-item text-muted">Sin submenús</span></li>`;
              }
              $paraAgregar += `
                <li class="nav-item dropdown">
                  <a href="${elementPrincipal.medescripcion}" class="nav-link dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                    ${elementPrincipal.menombre}
                  </a>
                  <ul class="dropdown-menu">
                    ${$subMenusLi}
                  </ul>
                </li>
              `;
            });
          
            
          $ul.insertAdjacentHTML("beforeend", $paraAgregar);
          
        })
        .catch((err) => {
          console.error("Error al cargar menús:", err);
        });
    })
    .catch((err) => {
      console.error("Error al cargar Submenús:", err);
    });
});
