document.addEventListener("DOMContentLoaded", () => {
    const $tablaCarrito = document.getElementById("tablaCarritoBody");

    // Función principal para cargar items
    function cargarCarrito() {
        fetch("../../ajax/carritoAjax.php?accion=listar")
            .then((response) => response.json())
            .then((items) => {
                if (!Array.isArray(items) || items.length === 0) {
                    $tablaCarrito.innerHTML = `<tr><td colspan="6" class="text-center">Tu carrito está vacío</td></tr>`;
                    return;
                }

                let html = "";
                items.forEach((item) => {


                    html += `
                    <tr>
                        <td class="align-middle">
                            <img src="../../util/imagenesProductos/${item.imagen}?v=${Date.now()}" 
                                 style="width: 50px; height: 50px; object-fit: cover;" 
                                 onerror="this.src='../../util/imagenesProductos/default.png'">
                        </td>
                        <td class="align-middle">${item.nombre}</td>
                        <td class="align-middle">${item.detalle}</td>
                        <td class="align-middle text-muted">${item.stock}u.</td>
                        <td class="align-middle">
                            <input type="number" 
                                   class="form-control form-control-sm input-cantidad" 
                                   value="${item.cantidad}" 
                                   min="1" 
                                   max="${item.stock}" 
                                   data-idprod="${item.idproducto}"
                                   data-old-value="${item.cantidad}" 
                                   style="width: 80px;">
                        </td>
                        <td class="align-middle">
                            <button class="btn btn-danger btn-sm btn-eliminar-item" 
                                    data-id="${item.idcompraitem}">
                                <i class="bi bi-trash"></i> Eliminar
                            </button>
                        </td>
                    </tr>
                    `;
                });
                $tablaCarrito.innerHTML = html;
            })
            .catch((error) => {
                console.error("Error:", error);
                $tablaCarrito.innerHTML = `<tr><td colspan="6" class="text-center text-danger">Error al cargar</td></tr>`;
            });
    }

    // 2. Evento para detectar cambio de cantidad
    document.addEventListener("change", (e) => {
        if (e.target.classList.contains("input-cantidad")) {
            const input = e.target;
            const idProducto = input.dataset.idprod;
            const nuevaCantidad = parseInt(input.value);
            const maxStock = parseInt(input.getAttribute("max"));
            const oldValue = input.dataset.oldValue;

            // Validación básica frontend
            if (nuevaCantidad < 1) {
                Swal.fire("Error", "La cantidad mínima es 1", "warning");
                input.value = oldValue;
                return;
            }
            if (nuevaCantidad > maxStock) {
                Swal.fire("Stock insuficiente", `Solo hay ${maxStock} disponibles`, "warning");
                input.value = maxStock; // Ajustamos al máximo disponible
            }

            actualizarCantidad(idProducto, input.value, input);
        }
    });

    function actualizarCantidad(idProducto, cantidad, inputElement) {
        const formData = new FormData();
        formData.append("accion", "modificarCantidad");
        formData.append("idproducto", idProducto);
        formData.append("cantidad", cantidad);

        fetch("../../ajax/carritoAjax.php", {
            method: "POST",
            body: formData
        })
            .then(r => r.json())
            .then(data => {
                if (data.exito) {

                    inputElement.dataset.oldValue = cantidad;


                    const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 1000,
                        timerProgressBar: true
                    });
                    Toast.fire({ icon: 'success', title: 'Cantidad actualizada' });

                } else {
                    Swal.fire("Error", data.msg, "error");

                    inputElement.value = inputElement.dataset.oldValue;
                }
            })
            .catch(err => {
                console.error(err);
                inputElement.value = inputElement.dataset.oldValue;
            });
    }

    // Evento para eliminar item
    document.addEventListener("click", (e) => {
        const btn = e.target.closest(".btn-eliminar-item");
        if (btn) {
            const idItem = btn.dataset.id;
            Swal.fire({
                title: "¿Quitar del carrito?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Si, quitar"
            }).then((result) => {
                if (result.isConfirmed) {
                    eliminarItem(idItem);
                }
            });
        }
    });

    function eliminarItem(id) {
        const formData = new FormData();
        formData.append("accion", "borrar");
        formData.append("idcompraitem", id);

        fetch("../../ajax/carritoAjax.php", {
            method: "POST",
            body: formData
        })
            .then(r => r.json())
            .then(data => {
                if (data.exito) {
                    cargarCarrito(); // Recargar tabla
                    Swal.fire({
                        icon: 'success',
                        title: 'Eliminado',
                        showConfirmButton: false,
                        timer: 1000
                    });
                } else {
                    Swal.fire("Error", data.msg, "error");
                }
            });
    }

    // Cargar al inicio
    cargarCarrito();

    const $btnFinalizar = document.querySelector(".card-footer .btn-success");

    // Habilitar botón si no está vacío 
    if ($btnFinalizar) {
        $btnFinalizar.disabled = false;
        $btnFinalizar.addEventListener("click", () => {
            Swal.fire({
                title: "¿Finalizar Compra?",
                text: "Se procesará tu pedido y se descontará el stock.",
                icon: "question",
                showCancelButton: true,
                confirmButtonText: "Sí, comprar",
                cancelButtonText: "Cancelar"
            }).then((result) => {
                if (result.isConfirmed) {
                    finalizarCompra();
                }
            });
        });
    }

    function finalizarCompra() {
        const formData = new FormData();
        formData.append("accion", "finalizarCompra");

        fetch("../../ajax/carritoAjax.php", {
            method: "POST",
            body: formData
        })
            .then(r => r.json())
            .then(data => {
                if (data.exito) {
                    Swal.fire("¡Compra Exitosa! Se te enviará por mail los estados de la compra", data.msg, "success")
                        .then(() => {
                            // Recargar para mostrar carrito vacío y actualizar cabecera si fuera necesario
                            window.location.reload();
                        });
                } else {
                    Swal.fire("Error", data.msg, "error");
                }
            })
            .catch(err => console.error(err));
    }
});