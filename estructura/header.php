<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@animxyz/core" />

<nav class="navbar navbar-expand-lg shadow-lg border-bottom bg-light bg-opacity-75 backdrop-blur"
  style="background: linear-gradient(135deg, #8ec5fc, #e0c3fc); border-bottom: 3px solid #fff; z-index: 1000">
  <div class="container-fluid m-2 gap-5">

    <!-- Logo -->
    <a class="navbar-brand text-white fw-bold d-flex align-items-center gap-2">
      <img src="/tpfinaldinamica/util/logo.png"
        style="height: 50px; width: 50px; object-fit: cover; border-radius: 50%; transform: scale(1.7); transform-origin: center; box-shadow: 0 0 10px rgba(255,255,255,0.4);"
        alt="Logo de iupi">
    </a>

    <!-- Toggler -->
    <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#menuDinamico">
      <span class="navbar-toggler-icon"></span>
    </button>

    <!-- Menú dinámico -->
    <div class="collapse navbar-collapse gap-5" id="menuDinamico">
      <ul class="navbar-nav me-auto mb-2 gap-3 mb-lg-0 px-2 py-1 rounded-2 shadow-sm hover-glow text-white"
        id="ulMenu"
        data-rol="<?php echo $_SESSION["idRoles"][0]; ?>"
        style="background: linear-gradient(135deg, #8ec5fc, #e0c3fc); border: 1px solid rgba(255,255,255,0.3);">
        <li class="nav-item ">
          <a href="/tpfinaldinamica/vista/index.php" class="nav-link text-white fw-semibold d-flex align-items-center gap-2">
            <i class="bi bi-house fs-5 me-1"></i>Inicio

          </a>
        </li>
        <li class="nav-item ">
          <a href="/tpfinaldinamica/vista/Cliente/productos.php" class="nav-link text-white fw-semibold d-flex align-items-center gap-2">
            <i class="bi bi-bag fs-5 me-1"></i>Juegos

          </a>
        </li>
        <li class="nav-item ">
          <a href="/tpfinaldinamica/vista/sobreNosotros.php" class="nav-link text-white fw-semibold d-flex align-items-center gap-2">
            <i class="bi bi-info-circle fs-5 me-1"></i>Sobre nosotros

          </a>
        </li>
        <li class="nav-item ">
          <a href="#" class="nav-link text-white fw-semibold d-flex align-items-center gap-2">
            <i class="bi bi-whatsapp fs-5 me-1"></i>Contacto

          </a>
        </li>
        <li class="nav-item ">
          <a href="https://maps.app.goo.gl/GznYKFWvudzCuhqe8" class="nav-link text-white fw-semibold d-flex align-items-center gap-2">
            <i class="bi bi-geo-alt fs-5 me-1"></i>Donde encontrarnos

          </a>
        </li>
      </ul>



      <!-- Usuario y carrito -->
      <div class="d-flex align-items-center gap-3 px-3 py-2 rounded-3 bg-white bg-opacity-10 shadow-sm"
        style="backdrop-filter: blur(4px); border: 1px solid rgba(255,255,255,0.2);">
        <!-- Ver como -->
        <div class="dropdown d-none" id="verComo"
          data-rol="<?php echo intval($_SESSION['idRoles'][0] ?? 0); ?>"
          data-rol2="<?php echo intval($_SESSION['idRoles2'][0] ?? 0); ?>">

          <button class="btn fw-bold text-white border border-white px-3 py-2 rounded-3"
            type="button" data-bs-toggle="dropdown" aria-expanded="false"
            style="background: linear-gradient(135deg, #8ec5fc, #e0c3fc); box-shadow: 0 0 12px rgba(255,255,255,0.6);">
            <i class="bi bi-person-badge me-1"></i> Ver como
          </button>

          <ul class="dropdown-menu shadow-lg border-0 rounded-4 bg-light bg-opacity-75 text-dark">
            <li>
              <h6 class="dropdown-header text-secondary">Cambiar vista</h6>
            </li>
            <li><button class="dropdown-item fw-semibold" id="verAdmin" style="z-index: 100">
                <i class="bi bi-person-badge-fill me-2 text-primary"></i>Administrador</button></li>
            <li><button class="dropdown-item fw-semibold" id="verCliente" style="z-index: 100">
                <i class="bi bi-person me-2 text-success"></i>Cliente</button></li>
          </ul>
        </div>

        <a href="/tpfinaldinamica/vista/Cliente/carrito.php"
          class="btn btn-outline-light btn-sm position-relative px-3 py-2"
          title="Ver Carrito"
          style="border-radius: 0.5rem;">
          <i class="bi bi-cart fs-5"></i>
          <span id="cart-count"
            class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"
            style="display: none; font-size: 0.6em;">
          </span>
        </a>

        <div class="dropdown ms-2">
          <button class="btn btn-sm text-white dropdown-toggle d-flex align-items-center gap-2 px-3 py-2"
            type="button"
            data-bs-toggle="dropdown"
            aria-expanded="false"
            style="background-color: rgba(255, 255, 255, 0.2); border-radius: 0.5rem; border: 1px solid rgba(255,255,255,0.3);">

            <div class="d-flex flex-column align-items-start lh-1">
              <span class="small opacity-75">Hola,</span>
              <span class="fw-bold"><?php echo $_SESSION['usnombre']; ?></span>
            </div>
            <i class="bi bi-person-circle fs-4 ms-1"></i>
          </button>

          <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 rounded-4 mt-2 overflow-hidden">
            <li>
              <a class="dropdown-item py-2 px-3" href="/tpfinaldinamica/vista/Cliente/modificarUsuario.php">
                <i class="bi bi-person-gear me-2 text-primary"></i> Modificar mis datos
              </a>
            </li>
            <li>
              <a class="dropdown-item py-2 px-3" href="/tpfinaldinamica/vista/Cliente/misCompras.php">
                <i class="bi bi-bag-check me-2 text-success"></i> Mis compras
              </a>
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>
            <li>
              <a class="dropdown-item py-2 px-3 text-danger" href="../accion/cerrarSesion.php">
                <i class="bi bi-box-arrow-right me-2"></i> Salir
              </a>
            </li>
          </ul>
        </div>



      </div>
    </div>
</nav>

<script src="/tpfinaldinamica/estructura/header.js"></script>