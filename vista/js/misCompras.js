document.addEventListener("DOMContentLoaded", () => {
    const tablaBody = document.getElementById("tablaMisCompras");
    const loading = document.getElementById("loading");
    const noCompras = document.getElementById("noCompras");
    const modalEl = document.getElementById('modalHistorialCliente');
    const modal = new bootstrap.Modal(modalEl);

    function cargarMisCompras() {
        fetch("../../ajax/comprasClienteAjax.php?accion=listar_mias")
            .then(response => response.json())
            .then(data => {
                loading.classList.add("d-none");
                if (!Array.isArray(data) || data.length === 0) {
                    noCompras.classList.remove("d-none");
                    return;
                }
                tablaBody.innerHTML = "";
                data.forEach(compra => {
                    const badge = obtenerBadgeEstado(compra.idestadotipo, compra.estado_desc);
                    const fila = `
                        <tr>
                            <td class="fw-bold">#${compra.idcompra}</td>
                            <td>${compra.fecha}</td>
                            <td>${compra.items}</td>
                            <td>${badge}</td>
                            <td>
                                <button class="btn btn-outline-primary btn-sm btnVerEstados" data-id="${compra.idcompra}">
                                    <i class="bi bi-eye"></i> Ver Estados
                                </button>
                            </td>
                        </tr>
                    `;
                    tablaBody.innerHTML += fila;
                });
            })
            .catch(err => console.error(err));
    }

    tablaBody.addEventListener("click", (e) => {
        const btn = e.target.closest(".btnVerEstados");
        if(btn){
            const id = btn.dataset.id;
            verHistorial(id);
        }
    });

    function verHistorial(id) {
        const lista = document.getElementById("listaHistorialCliente");
        lista.innerHTML = '<li class="list-group-item text-center">Cargando...</li>';
        modal.show();

        const formData = new FormData();
        formData.append("accion", "verHistorial");
        formData.append("idcompra", id);

        fetch("../../ajax/comprasClienteAjax.php", {
            method: "POST",
            body: formData
        })
        .then(r => r.json())
        .then(data => {
            lista.innerHTML = "";
            data.forEach(h => {
                // Lógica de íconos: check verde si terminó, círculo azul si está activo
                let icono = '<i class="bi bi-check-circle-fill text-success me-2"></i>';
                let claseTexto = "text-muted";
                
                if(h.activo) {
                    icono = '<i class="bi bi-circle-fill text-primary me-2"></i>';
                    claseTexto = "text-primary fw-bold";
                }
                
                // Mostramos h.fin directamente, que ahora es la fecha actual
                lista.innerHTML += `
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            ${icono} <strong>${h.estado}</strong>
                            <br><small class="text-muted">Inicio: ${h.inicio}</small>
                        </div>
                        <div class="text-end">
                            <span class="badge bg-light text-dark border">${h.fin}</span>
                            <br><small class="${claseTexto}">${h.activo ? 'Actual' : 'Finalizado'}</small>
                        </div>
                    </li>
                `;
            });
        });
    }

    function obtenerBadgeEstado(id, texto) {
        let color = "secondary";
        switch (parseInt(id)) {
            case 1: color = "warning text-dark"; break;
            case 2: color = "primary"; break;
            case 3: color = "success"; break;
            case 4: color = "danger"; break;
        }
        return `<span class="badge bg-${color}">${texto}</span>`;
    }

    cargarMisCompras();
});