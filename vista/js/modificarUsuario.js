document.addEventListener("DOMContentLoaded", () => {
  const $idUsuario = document.getElementById("datosUsuario").dataset.idUsuario;

  fetch("../../ajax/usuarioAjax.php?accion=buscar", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
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
        document.getElementById("uspass").value = data[0]['uspass'];
        document.getElementById("uspass_confirm").value = data[0]['uspass'];
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

$botonEditar.addEventListener("click", () => {
  let $nombreUsuario = document.getElementById("usnombre").value;
  let $mailUsuario = document.getElementById("usmail").value;
  let $passwordUsuario = document.getElementById("uspass").value;
  let $idUsuario = document.getElementById("idusuarioEscondido").value;

  console.log($passwordUsuario);
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
      if (data) {
        Swal.fire(
            "¡Usuario actualizado!",
            "Se actualizó satisfactoriamente",
            "success"
          ).then(() => {

            window.location.href = "../index.php";
          })
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
