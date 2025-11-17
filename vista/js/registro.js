// Espera a que el DOM esté cargado
document.addEventListener('DOMContentLoaded', function () {
  
  // Selecciona el formulario de registro
  const form = document.getElementById('formRegistro');
  if (!form) return;

  const pass = document.getElementById('uspass');
  const passConfirm = document.getElementById('uspass_confirm');
  const passConfirmError = document.getElementById('passConfirmError');

  form.addEventListener('submit', function (event) {
    let valid = true;

    // Validar coincidencia de contraseñas
    if (pass.value !== passConfirm.value) {
      passConfirm.classList.add('is-invalid'); 
      passConfirmError.style.display = 'block'; 
      valid = false;
    } else {
      passConfirm.classList.remove('is-invalid');
      passConfirmError.style.display = 'none';
      // Si coinciden pero ya estaba inválido
      if (passConfirm.value !== "") {
           passConfirm.classList.add('is-valid');
      }
    }

    // Validar el resto de campos con Bootstrap
    if (!form.checkValidity()) {
      valid = false;
    }

    // Prevenir envío si algo es inválido
    if (!valid) {
      event.preventDefault(); 
      event.stopPropagation();
    }
    
    // Agrega las clases de Bootstrap para mostrar errores
    form.classList.add('was-validated');

  }, false);

  // Validar contraseñas mientras se escribe para feedback inmediato
  function validatePasswordMatch() {
       if (pass.value !== passConfirm.value && passConfirm.value.length > 0) {
            passConfirm.classList.add('is-invalid');
            passConfirmError.style.display = 'block';
       } else {
            passConfirm.classList.remove('is-invalid');
            passConfirmError.style.display = 'none';
       }
  }

  pass.addEventListener('input', validatePasswordMatch);
  passConfirm.addEventListener('input', validatePasswordMatch);

});