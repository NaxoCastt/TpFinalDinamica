document.addEventListener('DOMContentLoaded', function () {
    
    // ---------------------------------------------------------
    // LÓGICA DEL MODAL DE EDICIÓN (Rellenar datos)
    // ---------------------------------------------------------
    const modalEditar = document.getElementById('modalEditar');
    
    if (modalEditar) {
        modalEditar.addEventListener('show.bs.modal', function (event) {
            // Botón que disparó el modal
            const button = event.relatedTarget;
            
            // Extraer info de los atributos data-*
            const id = button.getAttribute('data-id');
            const nombre = button.getAttribute('data-nombre');
            const mail = button.getAttribute('data-mail');
            
            // Actualizar los inputs del modal
            const modalIdInput = modalEditar.querySelector('#edit_idusuario');
            const modalNombreInput = modalEditar.querySelector('#edit_usnombre');
            const modalMailInput = modalEditar.querySelector('#edit_usmail');
            
            // Limpiamos contraseña siempre por seguridad
            const modalPassInput = modalEditar.querySelector('input[name="uspass"]');
            if(modalPassInput) modalPassInput.value = "";

            modalIdInput.value = id;
            modalNombreInput.value = nombre;
            modalMailInput.value = mail;
        });
    }

    // ---------------------------------------------------------
    // LÓGICA DE ALERTAS (Leer URL y mostrar SweetAlert)
    // ---------------------------------------------------------
    const urlParams = new URLSearchParams(window.location.search);
    const mensaje = urlParams.get('mensaje');
    const exito = urlParams.get('exito');

    if (mensaje) {
        // Definimos el tipo de ícono según el éxito
        let iconType = 'info';
        let titleText = 'Información';
        
        if (exito === '1' || exito === 'true') {
            iconType = 'success';
            titleText = '¡Éxito!';
        } else if (exito === '0' || exito === 'false') {
            iconType = 'error';
            titleText = 'Error';
        }

        // Disparamos la alerta
        Swal.fire({
            icon: iconType,
            title: titleText,
            text: mensaje,
            confirmButtonColor: '#3085d6',
            confirmButtonText: 'Aceptar'
        }).then((result) => {
            // Limpiar la URL para que al recargar (F5) no salga de nuevo el cartel
            if (result.isConfirmed || result.isDismissed) {
                window.history.replaceState(null, null, window.location.pathname);
            }
        });
    }
});