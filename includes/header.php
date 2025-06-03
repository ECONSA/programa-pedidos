<?php
session_start();
if (!isset($_SESSION["nombreUsuario"]) || !isset($_SESSION["idEmpresaUsuario"]) || !isset($_SESSION["idUsuario"])) {
  header('Location: ../login.php');
}
?>

<!DOCTYPE html>
<html lang="es">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>Pedidos</title>


  <!-- Custom fonts for this template -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
  <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

  <!-- Custom styles for this template -->
  <link href="../css/sb-admin-2.min.css" rel="stylesheet">

  <!-- Custom styles for this page -->
  <link href="../vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">

</head>

<body id="page-top" class="sidebar-toggled">

  <!-- Page Wrapper -->
  <div id="wrapper">

    <!-- Sidebar -->
    <ul <?php
        if ($_SESSION["idEmpresaUsuario"] == 1) {
          echo "class= \"navbar-nav bg-gradient-danger sidebar sidebar-dark accordion toggled\"";
        } else if ($_SESSION["idEmpresaUsuario"] == 2) {
          echo "class= \"navbar-nav bg-gradient-primary sidebar sidebar-dark accordion toggled\"";
        }
        ?> id="accordionSidebar">

      <!-- Sidebar - Brand -->
      <a class="sidebar-brand d-flex align-items-center justify-content-center" href="../index.php">
        <div class="sidebar-brand-icon ">
          <i class="fas fa-cocktail"></i>
        </div>
        <div class="sidebar-brand-text mx-3">Pedidos </div> <!-- titulo-->
      </a>

      <!-- Divider -->
      <hr class="sidebar-divider my-0">

      <!-- Inicia Menu lateral  -->
      <li class="nav-item ">
        <a class="nav-link" href="../index.php">
          <i class="fas fa-home"></i>
          <span>Inicio</span></a>
      </li>

      <!-- Divider -->
      <hr class="sidebar-divider">

      <!-- Heading -->
      <div class="sidebar-heading">
        Control Empresarial
      </div>

      <!-- Control Empresarial -->

      <!-- Nav Item - Pedidos -->
      <?php
      if ($_SESSION["idEmpresaUsuario"] == 1) {

        echo '<li class="nav-item">';
        echo '<a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#OpcionesPedidos" aria-expanded="true" aria-controls="collapseUtilities">';
        echo '<i class="fas fa-money-check-alt"></i>';
        echo '<span>Pedidos</span>';
        echo '</a>';

        echo '<div id="OpcionesPedidos" class="collapse" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">';
        echo '<div class="bg-white py-2 collapse-inner rounded">';
        echo '<h6 class="collapse-header">Opciones:</h6>';
        echo '<a class="collapse-item" href="/programa_pedidos/Pedidos/Pedido_busqueda.php"> <i class="fas fa-stream"></i>&nbsp Buscar</a>';
        echo '<a class="collapse-item" href="/programa_pedidos/Pedidos/seleccionar_cliente.html"> <i class="fas fa-plus"></i>&nbsp Agregar</a>';
        echo '<a class="collapse-item" href="/programa_pedidos/Pedidos/seleccionar_cliente_frioso.html"> <i class="fas fa-plus"></i>&nbsp Plantilla Friosos</a>';
        echo '</div>';
        echo '</div>';
        echo '</li>';
      } else if ($_SESSION["idEmpresaUsuario"] == 2) {
        echo '<li class="nav-item">';
        echo '<a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#OpcionesPedidosProquima" aria-expanded="true" aria-controls="collapseUtilities">';
        echo '<i class="fas fa-money-check-alt"></i>';
        echo '<span>Pedidos Proquima</span>';
        echo '</a>';
        echo '<div id="OpcionesPedidosProquima" class="collapse" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">';
        echo '<div class="bg-white py-2 collapse-inner rounded">';
        echo '<h6 class="collapse-header">Opciones:</h6>';
        echo '<a class="collapse-item" href="/programa_pedidos/Pedidos_Proquima/Pedido_busqueda.php"> <i class="fas fa-stream"></i>&nbsp Buscar</a>';
        echo '<a class="collapse-item" href="/programa_pedidos/Pedidos_Proquima/AddCliente.php"> <i class="fas fa-plus"></i>&nbsp Agregar</a>';
        echo '<a class="collapse-item" href="/programa_pedidos/Pedidos/seleccionar_cliente_frioso.html"> <i class="fas fa-plus"></i>&nbsp Plantilla Friosos</a>';
        echo '</div>';
        echo '</div>';
        echo '</li>';
      }
      ?>
      <!-- Nav Item - Recibos -->
      <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#OpcionesRecibos" aria-expanded="true" aria-controls="collapseUtilities">
          <i class="fas fa-money-check-alt"></i>
          <span>Recibos</span>
        </a>

        <div id="OpcionesRecibos" class="collapse" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
          <div class="bg-white py-2 collapse-inner rounded">
            <h6 class="collapse-header">Opciones:</h6>
            <a class="collapse-item" href="/programa_pedidos/inventarios_php/ver_lista_recibos.html"><i class="fas fa-stream"></i>&nbsp Buscar</a>
            <a class="collapse-item" href="/programa_pedidos/inventarios_php/recibos.html">
              <i class="fas fa-receipt"></i>&nbsp Agregar</a>
          </div>
        </div>
      </li>
      <!-- Pedidos Online -->
      <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#OpcionesPedidoOnline" aria-expanded="true" aria-controls="collapseUtilities">
          <i class="fas fa-money-check-alt"></i>
          <span>Pedidos Online</span>
        </a>

        <div id="OpcionesPedidoOnline" class="collapse" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
          <div class="bg-white py-2 collapse-inner rounded">
            <h6 class="collapse-header">Opciones:</h6>
            <a class="collapse-item" href="/programa_pedidos/inventarios_php/buscar_pedido_online.html"><i class="fas fa-stream"></i>&nbsp Buscar</a>
            <a class="collapse-item" href="/programa_pedidos/inventarios_php/agregar_pedido_online.html">
              <i class="fas fa-receipt"></i>&nbsp Agregar</a>
          </div>
        </div>
      </li>
      <!-- Nav Item - Seguimiento -->
      <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#OpcionesSeguimiento" aria-expanded="true" aria-controls="collapseUtilities">
          <i class="fas fa-running"></i>
          <span>Seguimiento</span>
        </a>

        <div id="OpcionesSeguimiento" class="collapse" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
          <div class="bg-white py-2 collapse-inner rounded">
            <h6 class="collapse-header">Opciones:</h6>
            <a class="collapse-item" href="/programa_pedidos/Pedidos/listSeguimiento.php"><i class="fas fa-running"></i>&nbsp Ver Seguimiento</a>
            <a class="collapse-item" href="/programa_pedidos/Pedidos/AddSeguimiento.php">
              <i class="fas fa-receipt"></i>&nbsp Agregar</a>
            <a class="collapse-item" href="/programa_pedidos/Pedidos/Calendarizacion.php"><i class="fa fa-calendar"></i>&nbsp Calendarización</a>
          </div>
        </div>
      </li>
       <!-- Nav Item - Pipeline -->
       <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#OpcionesPipeline"
                    aria-expanded="true" aria-controls="collapseUtilities">
                    <i class="fas fa-calendar"></i>
                    <span>Pipelines</span>
                </a>

                <div id="OpcionesPipeline" class="collapse" aria-labelledby="headingUtilities"
                    data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Opciones:</h6>
                        <a class="collapse-item" href="../pipelines/agregar_pipeline.html"><i class="fas fa-receipt"></i>&nbsp
                            Agregar</a>
                        <a class="collapse-item" href="../pipelines/buscar_pipeline.html"><i class="fa fa-search"></i>&nbsp
                            Buscar</a>
                    </div>
                </div>
            </li>
      <!-- Divider -->
      <hr class="sidebar-divider d-none d-md-block">

      <!-- Sidebar Toggler (Sidebar) -->
      <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
      </div>

    </ul>
    <!-- Termina menu lateral -->

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

      <!-- Main Content -->
      <div id="content">

        <!-- Inicia Barra superior -->
        <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

          <!-- Sidebar Toggle (Topbar) -->
          <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
            <i class="fa fa-bars"></i>
          </button>



          <!-- Topbar Navbar -->
          <ul class="navbar-nav ml-auto">

            <!-- Nav Item - User Information -->
            <!-- Nav Item - User Information -->
            <li class="nav-item dropdown no-arrow">
              <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <span class="mr-2 d-none d-lg-inline text-gray-600 small"><?php echo $_SESSION['nombreUsuario']; ?></span>
                <img class="img-profile rounded-circle" src="https://www.grupoeconsa.com/sites/default/files/Logo-Econsa.png">
              </a>
              <!-- Dropdown - User Information -->
              <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                <a class="dropdown-item" href="logout.php" data-toggle="modal" data-target="#logoutModal">
                  <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                  Logout
                </a>
              </div>
            </li>
          </ul>

        </nav>
        <!-- Termina Barra superior -->