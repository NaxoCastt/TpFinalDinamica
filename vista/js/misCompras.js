document.addEventListener("DOMContentLoaded", () => {
    const tablaBody = document.getElementById("tablaMisCompras");
    const loading = document.getElementById("loading");
    const noCompras = document.getElementById("noCompras");

    function cargarMisCompras() {
        
        fetch("../../ajax/comprasClienteAjax.php?accion=listar_mias")
            .then(response => {
                if (!response.ok) {
                    throw new Error("Error HTTP: " + response.status);
                }
                return response.json();
            })
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
                            <td class="small text-muted">${compra.fecha_estado}</td>
                        </tr>
                    `;
                    tablaBody.innerHTML += fila;
                });
            })
            .catch(error => {
                console.error("Error:", error);
                loading.classList.add("d-none");
                // Muestra el error específico para ayudar a depurar
                tablaBody.innerHTML = `<tr><td colspan="5" class="text-center text-danger">Error: ${error.message}</td></tr>`;
            });
    }

    function obtenerBadgeEstado(id, texto) {
        let color = "secondary";
        let icono = "circle";

        switch (parseInt(id)) {
            case 1: 
                color = "warning text-dark"; 
                icono = "hourglass-split";
                break;
            case 2: 
                color = "primary"; 
                icono = "box-seam";
                break;
            case 3: 
                color = "success"; 
                icono = "truck";
                break;
            case 4: 
                color = "danger"; 
                icono = "x-circle";
                break;
        }
        return `<span class="badge bg-${color}"><i class="bi bi-${icono}"></i> ${texto}</span>`;
    }

    cargarMisCompras();
});