document.addEventListener('DOMContentLoaded', function () {

    // Referencias a elementos del DOM
    const tablaUsuarios = document.getElementById('tablaUsuarios');
    
    // Instancias de Modales de Bootstrap
    const modalEditarEl = document.getElementById('modalEditar');
    const modalEditarBootstrap = new bootstrap.Modal(modalEditarEl);
    
    const modalRolesEl = document.getElementById('modalRoles');
    const modalRolesBootstrap = new bootstrap.Modal(modalRolesEl);

    // =========================================================
    // FUNCIÓN PARA LISTAR USUARIOS (AJAX)
    // =========================================================
    function listarUsuarios() {
        fetch('../../ajax/usuarioAjax.php?accion=listar')
            .then(response => response.json())
            .then(data => {
                // Limpiar tabla
                tablaUsuarios.innerHTML = '';

                if (data.length === 0) {
                    tablaUsuarios.innerHTML = '<tr><td colspan="6" class="text-center">No hay usuarios registrados</td></tr>';
                    return;
                }

                let html = '';
                data.forEach(user => {
                    // Lógica de estado
                    let badgeEstado = '';
                    let botonEstado = '';
                    
                    // user.usdeshabilitado viene null (activo) o fecha (inactivo)
                    if (user.usdeshabilitado === null) {
                        badgeEstado = '<span class="badge bg-success">Activo</span>';
                        // Botón para Deshabilitar
                        botonEstado = `
                            <button class="btn btn-danger btn-sm px-3 py-2 btnCambiarEstado" 
                                    data-id="${user.idusuario}" 
                                    data-accion="deshabilitar" 
                                    title="Deshabilitar">
                                <i class="bi bi-trash"></i>
                            </button>`;
                    } else {
                        badgeEstado = '<span class="badge bg-danger">Inactivo</span>';
                        // Botón para Habilitar
                        botonEstado = `
                            <button class="btn btn-success btn-sm px-3 py-2 btnCambiarEstado" 
                                    data-id="${user.idusuario}" 
                                    data-accion="habilitar" 
                                    title="Habilitar">
                                <i class="bi bi-check-lg"></i>
                            </button>`;
                    }

                    const rolesJson = JSON.stringify(user.roles_ids);

                    html += `
                        <tr>
                            <td style="vertical-align: middle">${user.idusuario}</td>
                            <td style="vertical-align: middle">${user.usnombre}</td>
                            <td style="vertical-align: middle">${user.usmail}</td>
                            <td style="vertical-align: middle">${user.roles_display}</td>
                            <td style="vertical-align: middle">${badgeEstado}</td>
                            <td style="vertical-align: middle">
                                <div class="d-flex justify-content-center gap-2">
                                    
                                    <button class="btn btn-dark btn-sm px-3 py-2 btnEditarRoles"
                                        title="Gestionar Roles"
                                        data-id="${user.idusuario}"
                                        data-nombre="${user.usnombre}"
                                        data-roles='${rolesJson}'>
                                        <i class="bi bi-shield-lock"></i>
                                    </button>

                                    <button class="btn btn-warning btn-sm px-3 py-2 btnEditar"
                                        title="Editar Datos"
                                        data-id="${user.idusuario}"
                                        data-nombre="${user.usnombre}"
                                        data-mail="${user.usmail}">
                                        <i class="bi bi-pen"></i>
                                    </button>

                                    ${botonEstado}
                                </div>
                            </td>
                        </tr>
                    `;
                });

                tablaUsuarios.innerHTML = html;
            })
            .catch(error => {
                console.error('Error al cargar usuarios:', error);
                tablaUsuarios.innerHTML = '<tr><td colspan="6" class="text-center text-danger">Error de conexión</td></tr>';
            });
    }

    // Cargar la tabla al iniciar
    listarUsuarios();

    // =========================================================
    // DELEGACIÓN DE EVENTOS (Clicks en la tabla)
    // =========================================================
    tablaUsuarios.addEventListener('click', function(e) {
        // A. Click en Editar Datos
        const btnEditar = e.target.closest('.btnEditar');
        if (btnEditar) {
            const id = btnEditar.getAttribute('data-id');
            const nombre = btnEditar.getAttribute('data-nombre');
            const mail = btnEditar.getAttribute('data-mail');

            // Rellenar el modal
            document.getElementById('edit_idusuario').value = id;
            document.getElementById('edit_usnombre').value = nombre;
            document.getElementById('edit_usmail').value = mail;
            document.getElementById('edit_uspass').value = ''; // Limpiar pass

            modalEditarBootstrap.show();
        }

        // Click en Editar Roles
        const btnRoles = e.target.closest('.btnEditarRoles');
        if (btnRoles) {
            const id = btnRoles.getAttribute('data-id');
            const nombre = btnRoles.getAttribute('data-nombre');
            const rolesData = JSON.parse(btnRoles.getAttribute('data-roles'));

            document.getElementById('idUsuarioRolInput').value = id;
            document.getElementById('nombreUsuarioModal').textContent = nombre;

            // Resetear checkboxes
            document.querySelectorAll('.role-checkbox').forEach(cb => cb.checked = false);

            // Marcar los que tiene el usuario
            if (Array.isArray(rolesData)) {
                rolesData.forEach(rolId => {
                    const check = document.getElementById('rol_' + rolId);
                    if (check) check.checked = true;
                });
            }

            modalRolesBootstrap.show();
        }

        // Click en Cambiar Estado (Eliminar/Habilitar)
        const btnEstado = e.target.closest('.btnCambiarEstado');
        if (btnEstado) {
            const id = btnEstado.getAttribute('data-id');
            const accion = btnEstado.getAttribute('data-accion'); 
            const titulo = accion === 'deshabilitar' ? '¿Deshabilitar usuario?' : '¿Habilitar usuario?';
            const texto = accion === 'deshabilitar' ? 'El usuario no podrá iniciar sesión.' : 'El usuario podrá volver a acceder.';
            const colorConfirm = accion === 'deshabilitar' ? '#d33' : '#198754';

            Swal.fire({
                title: titulo,
                text: texto,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: colorConfirm,
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Sí, confirmar'
            }).then((result) => {
                if (result.isConfirmed) {
                    ejecutarCambioEstado(id, accion);
                }
            });
        }
    });

    // =========================================================
    // ENVÍO DE FORMULARIOS (AJAX)
    // =========================================================

    // Formulario Editar Usuario
    const formEditar = document.getElementById('formEditarUsuario');
    if (formEditar) {
        formEditar.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(formEditar);
            formData.append('accion', 'modificar');

            fetch('../../ajax/usuarioAjax.php', {
                method: 'POST',
                body: formData
            })
            .then(r => r.json())
            .then(data => {
                if (data.exito) {
                    Swal.fire('¡Éxito!', data.mensaje, 'success');
                    modalEditarBootstrap.hide();
                    listarUsuarios();
                } else {
                    Swal.fire('Error', data.mensaje, 'error');
                }
            })
            .catch(err => console.error(err));
        });
    }

    // Formulario Editar Roles
    const formRoles = document.getElementById('formEditarRoles');
    if (formRoles) {
        formRoles.addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(formRoles);
            formData.append('accion', 'actualizar_roles');

            // Nota: FormData captura automáticamente los checkboxes marcados como roles[]

            fetch('../../ajax/usuarioAjax.php', {
                method: 'POST',
                body: formData
            })
            .then(r => r.json())
            .then(data => {
                if (data.exito) {
                    Swal.fire('¡Éxito!', data.mensaje, 'success');
                    modalRolesBootstrap.hide();
                    listarUsuarios(); // Recargamos para ver los cambios en la columna roles
                } else {
                    Swal.fire('Error', data.mensaje, 'error');
                }
            })
            .catch(err => console.error(err));
        });
    }

    // =========================================================
    // FUNCIONES AUXILIARES
    // =========================================================
    function ejecutarCambioEstado(id, tipoAccion) {
        const formData = new FormData();
        formData.append('accion', 'cambiar_estado');
        formData.append('idusuario', id);
        formData.append('tipo_accion', tipoAccion);

        fetch('../../ajax/usuarioAjax.php', {
            method: 'POST',
            body: formData
        })
        .then(r => r.json())
        .then(data => {
            if (data.exito) {
                // Alerta pequeña o toast, y recarga tabla
                Swal.fire({
                    icon: 'success',
                    title: 'Actualizado',
                    text: data.mensaje,
                    timer: 1500,
                    showConfirmButton: false
                });
                listarUsuarios();
            } else {
                Swal.fire('Error', data.mensaje, 'error');
            }
        })
        .catch(err => console.error(err));
    }
});