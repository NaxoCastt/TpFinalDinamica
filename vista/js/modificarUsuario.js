document.addEventListener("DOMContentLoaded", () => {
    const $idUsuario = document.getElementById("datosUsuario").dataset.idUsuario;

    fetch("../../ajax/usuarioAjax.php?accion=buscar", {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify({
                idUsuario: $idUsuario,
                accion: "buscar",
            }),
        })
        .then((response) => response.json())
        .then((data) => {
            if (data) {
                document.getElementById("usnombre").value = data[0]["usnombre"];
                document.getElementById("usmail").value = data[0]["usmail"];
                document.getElementById("idusuarioEscondido").value = $idUsuario;
                // No cargamos la contraseña por seguridad y para permitir dejarla en blanco
                document.getElementById("uspass").value = ""; 
                document.getElementById("uspass_confirm").value = "";
            } else {
                Swal.fire(
                    "Error",
                    data.message || "No se pudo cargar la data del usuario",
                    "error"
                );
            }
        })
        .catch((error) => {
            console.error("Error AJAX:", error);
            Swal.fire("Error", "Hubo un problema de conexión", "error");
        });
});

let $botonEditar = document.getElementById("editarUsuario");

$botonEditar.addEventListener("click", (event) => {
    // Obtener el formulario y campos
    const form = document.getElementById("formRegistro");
    const pass = document.getElementById("uspass");
    const passConfirm = document.getElementById("uspass_confirm");

    // Validación personalizada: Coincidencia de contraseñas
    if (pass.value !== passConfirm.value) {
        passConfirm.setCustomValidity("Las contraseñas no coinciden");
    } else {
        passConfirm.setCustomValidity(""); // Restablecer validez
    }

    // Validación de Bootstrap
    if (!form.checkValidity()) {
        event.preventDefault();
        event.stopPropagation();
        // Esto agrega la clase que muestra los bordes rojos/verdes de Bootstrap
        form.classList.add('was-validated'); 
        return; // Detenemos la ejecución aquí si no es válido
    }

    //Si pasa la validación, procedemos con la recolección de datos y AJAX
    let $nombreUsuario = document.getElementById("usnombre").value;
    let $mailUsuario = document.getElementById("usmail").value;
    let $passwordUsuario = document.getElementById("uspass").value;
    let $idUsuario = document.getElementById("idusuarioEscondido").value;

    const formData = new FormData();
    formData.append("idusuario", $idUsuario);
    formData.append("usnombre", $nombreUsuario);
    formData.append("usmail", $mailUsuario);
    formData.append("uspass", $passwordUsuario);
    formData.append("accion", "modificar");

    fetch("../../ajax/usuarioAjax.php?accion=modificar", {
            method: "POST",
            body: formData,
        })
        .then((response) => response.json())
        .then((data) => {
            if (data.exito) { // Verificamos la propiedad 'exito' del JSON
                Swal.fire(
                    "¡Usuario actualizado!",
                    "Se actualizó satisfactoriamente",
                    "success"
                ).then(() => {
                    window.location.href = "../index.php";
                });
            } else {
                Swal.fire(
                    "Error",
                    data.mensaje || "No se pudo actualizar el usuario",
                    "error"
                );
            }
        })
        .catch((error) => {
            console.error("Error AJAX:", error);
            Swal.fire("Error", "Hubo un problema de conexión", "error");
        });
});
