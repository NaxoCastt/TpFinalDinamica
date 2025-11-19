document.addEventListener("DOMContentLoaded", () => {
  
  // OBTENER ROL Y ELEMENTOS
  let $ul = document.getElementById("ulMenu");
  const rol = parseInt($ul.dataset.rol); // 1: Admin, 2: Cliente
  
  const $verComo = document.getElementById("verComo");
  let $idRol2 = parseInt($verComo.dataset.rol2);
  
  // 2. LOGICA "VER COMO" (Visualización del botón)
  if ($idRol2 === 1) { // Si tiene rol secundario (es decir, es un admin real)
    $verComo.classList.remove("d-none");
  } else {
    $verComo.classList.add("d-none");
  }

  // INDICADOR VISUAL DE "MODO ADMIN" 
  if (rol === 1) {
      // Creamos el badge dinámicamente
      const badge = document.createElement("div");
      badge.className = "badge bg-danger text-white border border-light shadow-sm d-flex align-items-center px-3 py-2 me-2"; 
      badge.style.animation = "pulse 2s infinite";
      badge.innerHTML = '<i class="bi bi-shield-lock-fill me-2"></i> MODO ADMIN';
      
      // Inyectamos los estilos de la animación si no existen
      if (!document.getElementById("admin-style")) {
          const style = document.createElement("style");
          style.id = "admin-style";
          style.innerHTML = `
              @keyframes pulse {
                  0% { box-shadow: 0 0 0 0 rgba(220, 53, 69, 0.7); }
                  70% { box-shadow: 0 0 0 10px rgba(220, 53, 69, 0); }
                  100% { box-shadow: 0 0 0 0 rgba(220, 53, 69, 0); }
              }
          `;
          document.head.appendChild(style);
      }

      // Insertamos el badge ANTES del botón "Ver Como"
      if ($verComo) {
          $verComo.parentNode.insertBefore(badge, $verComo);
      }
  }

  // CONTROL DE VISIBILIDAD (Si NO es Cliente, ocultar cosas de compra)
  if (rol !== 2) {
    // Ocultar Botón del Carrito
    const btnCarrito = document.querySelector('a[href*="carrito.php"]');
    if (btnCarrito) {
      btnCarrito.style.display = 'none';
    }

    // Ocultar opciones del Menú de Usuario
    const opcionesCliente = ['modificarUsuario.php', 'misCompras.php'];
    opcionesCliente.forEach(pagina => {
      const link = document.querySelector(`a[href*="${pagina}"]`);
      if (link) {
        const liPadre = link.closest('li');
        if (liPadre) liPadre.style.display = 'none';
      }
    });
  }

  // LISTENERS PARA CAMBIAR DE ROL
  document.getElementById("verAdmin").addEventListener("click", () => cambiarRol(1));
  document.getElementById("verCliente").addEventListener("click", () => cambiarRol(2));

  function cambiarRol(nuevoRol) {
    fetch("/tpfinaldinamica/ajax/menuHeaderAjax.php", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({
        accion: "switchVerComo",
        idRol: nuevoRol,
      }),
    }).then(() => location.reload());
  }

  // CARGA DE MENÚS DESDE BASE DE DATOS
  cargarMenusDinamicos(rol);

  // INICIALIZAR CARRITO
  actualizarContadorCarrito(); // Llamada inicial
  
  // Escuchar eventos globales para actualizar sin recargar
  document.addEventListener('cartUpdated', actualizarContadorCarrito);
  
});



// --- FUNCIONES AUXILIARES ---

function cargarMenusDinamicos(rol) {
    let $ul = document.getElementById("ulMenu");
    let $paraAgregar = "";

    fetch("/tpfinaldinamica/ajax/menuHeaderAjax.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ accion: "listarSubMenus", id: rol }),
    })
    .then((response) => response.json())
    .then(($subMenues) => {
        fetch("/tpfinaldinamica/ajax/menuHeaderAjax.php", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ id: rol, accion: "listarMenus" }),
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
                
                // Si no tiene submenús, es un link simple, si tiene, es un dropdown
                // Tu lógica original siempre creaba dropdowns, mantengo esa estructura:
                if ($subMenusLi === "") {
                   $subMenusLi = `<li><span class="dropdown-item text-muted">Sin opciones</span></li>`;
                }
                
                $paraAgregar += `
                    <li class="nav-item dropdown px-2 py-1">
                      <a href="${elementPrincipal.medescripcion}" class="nav-link dropdown-toggle fw-semibold text-white d-flex align-items-center gap-2" data-bs-toggle="dropdown">
                        ${elementPrincipal.menombre}
                      </a>
                      <ul class="dropdown-menu xyz-in" xyz="fade up duration-5 ease-in-out">
                        ${$subMenusLi}
                      </ul>
                    </li>
                  `;
            });
            $ul.insertAdjacentHTML("beforeend", $paraAgregar);
        });
    });
}


function actualizarContadorCarrito() {
    const cartBadge = document.getElementById('cart-count');
    if (!cartBadge) return; // Si no existe (ej. modo admin)

    fetch('/tpfinaldinamica/ajax/carritoAjax.php?accion=listar')
      .then(response => response.json())
      .then(data => {
        let totalItems = 0;
        if (Array.isArray(data)) {
            data.forEach(item => {
                totalItems += parseInt(item.cantidad);
            });
        }

        if (totalItems > 0) {
            cartBadge.innerText = totalItems;
            cartBadge.style.display = 'inline-block';
            cartBadge.classList.remove('d-none');
        } else {
            cartBadge.style.display = 'none';
        }
      })
      .catch(error => console.error('Error carrito:', error));
}





