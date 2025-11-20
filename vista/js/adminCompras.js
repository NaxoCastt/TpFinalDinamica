document.addEventListener("DOMContentLoaded", () => {
    const tablaBody = document.getElementById("tablaComprasBody");
    const modalHistorialEl = document.getElementById('modalHistorial');
    const modalHistorial = new bootstrap.Modal(modalHistorialEl);

    // Cambiar el encabezado de la tabla en el PHP si es necesario, o hacerlo aquí
    // (Asumimos que en el PHP el TH dice "ID Usuario", ahora visualmente será "Usuario")

    function cargarCompras() {
        fetch("../../ajax/administrarComprasAjax.php?accion=listarCompras")
            .then(response => response.json())
            .then(data => {
                if (!Array.isArray(data) || data.length === 0) {
                    tablaBody.innerHTML = '<tr><td colspan="7" class="text-center">No hay compras registradas</td></tr>';
                    return;
                }
                tablaBody.innerHTML = "";
                data.forEach(compra => {
                    let botones = generarBotones(compra.idcompra, compra.idestadotipo);
                    let badgeEstado = generarBadgeEstado(compra.idestadotipo, compra.estadodescripcion);

                    // Botón Ver Estados
                    let btnVerEstados = `
                        <button class="btn btn-info btn-sm ms-1 btnVerHistorial text-white" data-id="${compra.idcompra}" title="Ver Historial">
                            <i class="bi bi-clock-history"></i>
                        </button>
                    `;

                    let fila = `
                        <tr>
                            <td>${compra.idcompra}</td>
                            <td class="fw-bold text-secondary">${compra.usnombre}</td> <td><small>${compra.items}</small></td>
                            <td>${compra.fechainicio}</td>
                            <td>${compra.fechafin || '-'}</td>
                            <td>${badgeEstado}</td> 
                            <td>
                                <div class="d-flex gap-1">
                                    ${botones}
                                    ${btnVerEstados}
                                </div>
                            </td>
                        </tr>
                    `;
                    tablaBody.innerHTML += fila;
                });
            });
    }

    // Asegúrate de copiar las funciones existentes generarBadgeEstado y generarBotones aquí

    // DELEGACIÓN DE EVENTOS
    tablaBody.addEventListener("click", (e) => {
        // ... (Lógica existente para btnCambiarEstado) ...
        const btnCambiar = e.target.closest(".btnCambiarEstado");
        if (btnCambiar) {
            // Tu lógica de SweetAlert existente
             const idCompra = btnCambiar.dataset.id;
             const nuevoEstado = btnCambiar.dataset.estado;
             actualizarEstado(idCompra, nuevoEstado);
        }

        // NUEVA LÓGICA: Ver Historial
        const btnHistorial = e.target.closest(".btnVerHistorial");
        if (btnHistorial) {
            const id = btnHistorial.dataset.id;
            verHistorial(id);
        }
    });

    function verHistorial(idCompra) {
        const tbody = document.getElementById("cuerpoTablaHistorial");
        tbody.innerHTML = '<tr><td colspan="3" class="text-center">Cargando...</td></tr>';
        modalHistorial.show();

        const formData = new FormData();
        formData.append("accion", "verHistorial");
        formData.append("idcompra", idCompra);

        fetch("../../ajax/administrarComprasAjax.php", {
            method: "POST",
            body: formData
        })
        .then(r => r.json())
        .then(data => {
            tbody.innerHTML = "";
            data.forEach(h => {
                tbody.innerHTML += `
                    <tr>
                        <td>${h.estado}</td>
                        <td>${h.inicio}</td>
                        <td>${h.fin}</td>
                    </tr>
                `;
            });
        });
    }

    function actualizarEstado(idCompra, idEstado) {
         // Tu función existente
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
                Swal.fire("Éxito", data.msg, "success");
                cargarCompras();
            } else {
                Swal.fire("Error", data.msg, "error");
            }
        });
    }

    // Funciones auxiliares necesarias
    function generarBadgeEstado(idTipo, texto) {
        switch (parseInt(idTipo)) {
            case 1: return `<span class="badge bg-warning text-dark">${texto}</span>`;
            case 2: return `<span class="badge bg-primary">${texto}</span>`;
            case 3: return `<span class="badge bg-success">${texto}</span>`;
            case 4: return `<span class="badge bg-danger">${texto}</span>`;
            default: return `<span class="badge bg-secondary">${texto}</span>`;
        }
    }

    function generarBotones(idCompra, idEstado) {
        let btns = '<div class="btn-group" role="group">';
        if (idEstado == 1) { 
            btns += `<button class="btn btn-success btn-sm btnCambiarEstado" data-id="${idCompra}" data-estado="2" title="Aceptar"><i class="bi bi-check-lg"></i></button>`;
            btns += `<button class="btn btn-danger btn-sm btnCambiarEstado" data-id="${idCompra}" data-estado="4" title="Cancelar"><i class="bi bi-x-lg"></i></button>`;
        } else if (idEstado == 2) { 
            btns += `<button class="btn btn-primary btn-sm btnCambiarEstado" data-id="${idCompra}" data-estado="3" title="Enviar"><i class="bi bi-truck"></i></button>`;
            btns += `<button class="btn btn-danger btn-sm btnCambiarEstado" data-id="${idCompra}" data-estado="4" title="Cancelar"><i class="bi bi-x-lg"></i></button>`;
        }
        btns += '</div>';
        return btns;
    }

    cargarCompras();
});