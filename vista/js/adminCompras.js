document.addEventListener("DOMContentLoaded", () => {
    const tablaBody = document.getElementById("tablaComprasBody");

    function cargarCompras() {
        fetch("../../ajax/administrarComprasAjax.php?accion=listarCompras")
            .then(response => response.json())
            .then(data => {
                // Si data viene vacío o no es array
                if (!Array.isArray(data) || data.length === 0) {
                    tablaBody.innerHTML = '<tr><td colspan="7" class="text-center">No hay compras registradas</td></tr>';
                    return;
                }
                tablaBody.innerHTML = "";
                data.forEach(compra => {
                    let botones = generarBotones(compra.idcompra, compra.idestadotipo);
                    
                    // Formatear fechas o mostrar guión si es null
                    const fechaFin = compra.fechafin ? compra.fechafin : "-";

                    let badgeEstado = generarBadgeEstado(compra.idestadotipo, compra.estadodescripcion);

                    let fila = `
                        <tr>
                            <td>${compra.idcompra}</td>
                            <td>${compra.idusuario}</td>
                            <td><small>${compra.items}</small></td>
                            <td>${compra.fechainicio}</td>
                            <td>${fechaFin}</td>
                            <td>${badgeEstado}</td> <td>${botones}</td>
                        </tr>
                    `;
                    tablaBody.innerHTML += fila;
                });
            })
            .catch(error => {
                console.error("Error:", error);
                tablaBody.innerHTML = '<tr><td colspan="7" class="text-center text-danger">Error al cargar datos</td></tr>';
            });
    }

    // Se encarga de dar el color según el estado
    function generarBadgeEstado(idTipo, texto) {
        switch (parseInt(idTipo)) {
            case 1: return `<span class="badge bg-warning text-dark">${texto}</span>`; // Iniciada
            case 2: return `<span class="badge bg-primary">${texto}</span>`;      // Aceptada
            case 3: return `<span class="badge bg-success">${texto}</span>`;      // Enviada
            case 4: return `<span class="badge bg-danger">${texto}</span>`;       // Cancelada
            default: return `<span class="badge bg-secondary">${texto}</span>`;   // Desconocido
        }
    }

    function generarBotones(idCompra, idEstado) {
        // Estados: 1:Iniciada, 2:Aceptada, 3:Enviada, 4:Cancelada
        let btns = '<div class="btn-group" role="group">';

        if (idEstado == 1) { // Iniciada
            btns += `<button class="btn btn-success btn-sm btnCambiarEstado" data-id="${idCompra}" data-estado="2" title="Aceptar"><i class="bi bi-check-lg"></i> Aceptar</button>`;
            btns += `<button class="btn btn-danger btn-sm btnCambiarEstado" data-id="${idCompra}" data-estado="4" title="Cancelar"><i class="bi bi-x-lg"></i> Cancelar</button>`;
        } else if (idEstado == 2) { // Aceptada
            btns += `<button class="btn btn-primary btn-sm btnCambiarEstado" data-id="${idCompra}" data-estado="3" title="Confirmar Envío"><i class="bi bi-truck"></i> Enviada</button>`;
            btns += `<button class="btn btn-danger btn-sm btnCambiarEstado" data-id="${idCompra}" data-estado="4" title="Cancelar"><i class="bi bi-x-lg"></i> Cancelar</button>`;
        } else {
            btns += '<span class="text-muted small">Finalizada</span>';
        }
        
        btns += '</div>';
        return btns;
    }

    // Delegación de eventos
    tablaBody.addEventListener("click", (e) => {
        const btn = e.target.closest(".btnCambiarEstado");
        if (btn) {
            const idCompra = btn.dataset.id;
            const nuevoEstado = btn.dataset.estado;
            
            let textoConfirm = "¿Seguro que deseas cambiar el estado?";
            if(nuevoEstado == 4) textoConfirm = "Al cancelar, se devolverá el stock automáticamente. ¿Confirmar?";
            if(nuevoEstado == 3) textoConfirm = "¿Confirmar que la compra ha sido enviada?";

            Swal.fire({
                title: "Confirmar acción",
                text: textoConfirm,
                icon: "question",
                showCancelButton: true,
                confirmButtonText: "Sí, confirmar",
                cancelButtonText: "Volver"
            }).then((result) => {
                if (result.isConfirmed) {
                    actualizarEstado(idCompra, nuevoEstado);
                }
            });
        }
    });

    function actualizarEstado(idCompra, idEstado) {
        const formData = new FormData();
        formData.append("accion", "cambiarEstado");
        formData.append("idcompra", idCompra);
        formData.append("idestadotipo", idEstado);

        fetch("../../ajax/administrarComprasAjax.php", {
            method: "POST",
            body: formData
        })
        .then(r => r.json())
        .then(data => {
            if (data.exito) {
                Swal.fire({
                    icon: 'success',
                    title: 'Actualizado',
                    text: data.msg,
                    timer: 1500,
                    showConfirmButton: false
                });
                cargarCompras();
            } else {
                Swal.fire("Error", data.msg, "error");
            }
        })
        .catch(err => console.error(err));
    }

    cargarCompras();
});