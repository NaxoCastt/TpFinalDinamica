<nav class="navbar navbar-expand-lg" style="background: linear-gradient(135deg, #8ec5fc, #e0c3fc); border-bottom: 3px solid #fff;">
  <div class="container-fluid m-2">
    <a class="navbar-brand text-dark fw-bold">
      <img src="../../util/logo.png" style="height: 50px; width: 50px; object-fit: cover; border-radius: 50%; transform: scale(1.7); transform-origin: center;" alt="Logo de iupi">
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menuDinamico">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse gap-5" id="menuDinamico">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0" id="ulMenu" data-rol="<?php echo $_SESSION["idRoles"][0]; ?>">
        <li class="nav-item"><a href="/tpfinaldinamica/vista/Cliente/productos.php" class="nav-link"><i class="bi bi-bag"></i>Juegos</a></li>
        
      </ul>

      <!-- seccion de Ver Como -->

      <?php if ($_SESSION['roles'][0] == "Admin") {

        echo ('<div class="dropdown">
          <button class="btn dropdown-toggle border" type="button" data-bs-toggle="dropdown" aria-expanded="false" style="background: linear-gradient(135deg, #8ec5fc, #e0c3fc);">
            Ver como
          </button>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="#">Administrador</a></li>
            <li><a class="dropdown-item" href="#">Cliente</a></li>
            </ul>
          </div>');
      } ?>
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


<script src="/tpfinaldinamica/estructura/header.js"></script>