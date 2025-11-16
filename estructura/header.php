<nav class="navbar navbar-expand-lg" style="background: linear-gradient(135deg, #8ec5fc, #e0c3fc); border-bottom: 3px solid #fff;">
  <div class="container-fluid">
    <a class="navbar-brand text-dark fw-bold" href="/tpfinaldinamica/vista/Cliente/productos.php">
      <img src="../../util/logo.png"  style="height: 50px; width: 50px; object-fit: cover; border-radius: 50%; transform: scale(1.7); transform-origin: center;" alt="Logo de iupi">
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menuDinamico">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="menuDinamico">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0" id="menuItems">
        <!-- Ítems dinámicos por rol -->
      </ul>

      <div class="d-flex align-items-center">
        <span class="me-3 text-dark fw-semibold">
          <i class="bi bi-person-circle me-1"></i>Hola, <?php echo $_SESSION['usnombre']; ?>
        </span>
        <a href="../accion/cerrarSesion.php" class="btn btn-outline-danger btn-sm">
          <i class="bi bi-box-arrow-right"></i> Salir
        </a>
      </div>
    </div>
  </div>
</nav>
