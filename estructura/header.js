document.addEventListener("DOMContentLoaded", () => {
    //Ver Como

  $verComo = document.getElementById("verComo");
  let $idRol1 = parseInt($verComo.dataset.rol);
  let $idRol2 = parseInt($verComo.dataset.rol2);
  let $verAdmin = document.getElementById("verAdmin");
  let $verCliente = document.getElementById("verCliente");
  if($idRol2 === 1){
    $verComo.classList.remove("d-none")
  }
  else{
    $verComo.classList.add("d-none")
  }

  //click en admin
  $verAdmin.addEventListener("click", () =>{
    console.log("ola")
     fetch("/tpfinaldinamica/ajax/menuHeaderAjax.php", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({
      accion: "switchVerComo",
      idRol: 1,
    }),
  })
  location.reload();
})
  //Cambiar a Cliente
  $verCliente.addEventListener("click", () =>{
    console.log("ola2")
     fetch("/tpfinaldinamica/ajax/menuHeaderAjax.php", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({
      accion: "switchVerComo",
      idRol: 2,
    }),
  })
  location.reload();
})

  let $ul = document.getElementById("ulMenu");
  const rol = $ul.dataset.rol;
  let $paraAgregar = "";

/**
   * Consulta el carrito activo y actualiza el contador del header.
   */
  function actualizarContadorCarrito() {
    const $contador = document.getElementById("cart-count");
    if (!$contador) {
      return;
    }

    fetch("/tpfinaldinamica/ajax/carritoAjax.php?accion=listar", {
      method: "GET",
      headers: { "Content-Type": "application/json" }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Respuesta de red no fue ok');
        }
        return response.json();
    })
    .then(items => {
        let totalProductos = 0;
        
        if (Array.isArray(items) && items.length > 0) {
            totalProductos = items.reduce((total, item) => {
                return total + parseInt(item.cantidad, 10); 
            }, 0);
        }

        if (totalProductos > 0) {
            $contador.innerHTML = totalProductos;
            $contador.style.display = 'block'; 
        } else {
            $contador.style.display = 'none'; 
        }
    })
    .catch(err => {
        console.error("Error al cargar contador de carrito:", err);
        $contador.style.display = 'none'; 
    });
  }



  // Llamar a la función al cargar el header
  actualizarContadorCarrito();

  // Escuchar el evento personalizado 'cartUpdated' en todo el documento.
  // Cuando se dispare, vuelve a ejecutar la función del contador.
  document.addEventListener('cartUpdated', function() {
      actualizarContadorCarrito();
  });



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
                <li class="nav-item dropdown px-2 py-1">
                  <a href="${elementPrincipal.medescripcion}" class="nav-link dropdown-toggle fw-semibold text-white d-flex align-items-center gap-2" data-bs-toggle="dropdown" aria-expanded="false">
                    ${elementPrincipal.menombre}
                  </a>
                  <ul class="dropdown-menu xyz-in" xyz="fade up duration-5 ease-in-out">
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



  