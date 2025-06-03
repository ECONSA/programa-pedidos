<?php
include("bd/inicia_conexion.php");
session_start();
if (!isset($_SESSION["nombreUsuario"]) || !isset($_SESSION["idEmpresaUsuario"]) || !isset($_SESSION["idUsuario"])) {
  header('Location: login.php');
}
$primera_consulta = "SELECT ROUND(SUM(total), 2) AS total FROM pedidounhesa WHERE idEstado = 2 AND DAY(fecha_emision) = DAY(CURRENT_DATE) AND MONTH(fecha_emision) = MONTH(CURRENT_DATE()) AND YEAR(fecha_emision) = YEAR(CURRENT_DATE()) AND idVendedor = " . $_SESSION["idUsuario"];
$primera_respuesta = mysqli_query($con, $primera_consulta);
$vendido_hoy = 0;
while ($fila = mysqli_fetch_array($primera_respuesta)) {
  $vendido_hoy = $fila["total"];
}
$segunda_consulta = "SELECT ROUND(SUM(total), 2) AS Total FROM pedidounhesa WHERE idEstado = 2 AND MONTH(fecha_emision) = MONTH(CURRENT_DATE()) AND YEAR(fecha_emision) = YEAR(CURRENT_DATE()) AND idVendedor = " . $_SESSION["idUsuario"];

$tercera_consulta = "SELECT round(SUM(monto),2) AS Meta FROM meta m INNER JOIN usuario u ON m.idUsuario = u.idUsuario WHERE u.idempresa = 1 AND( NOW() BETWEEN m.fecha_inicio AND m.fecha_final) AND u.idUsuario = " . $_SESSION["idUsuario"];
$tercera_respuesta = mysqli_query($con, $tercera_consulta);
while ($fila = mysqli_fetch_array($tercera_respuesta)) {
  $meta = $fila["Meta"];
}

$cuarta_consulta = "SELECT WEEK(curdate(), 5) - WEEK(DATE_SUB(curdate(), INTERVAL DAYOFMONTH(curdate()) - 1 DAY), 5) + 1 AS Semana";
$cuarta_respuesta = mysqli_query($con, $cuarta_consulta);
while ($fila = mysqli_fetch_array($cuarta_respuesta)) {
  $semana = $fila["Semana"];
}

// Porcentaje Meta
$quinta_consulta = "SELECT ROUND( ( ( SELECT SUM(total) AS Vendido FROM pedidounhesa WHERE MONTH(fecha_emision) = MONTH(CURRENT_DATE()) AND YEAR(fecha_emision) = YEAR(CURRENT_DATE()) AND idEstado = 2 AND idVendedor = " . $_SESSION["idUsuario"] . ") * 100 ) /( SELECT SUM(monto) AS Meta FROM meta m INNER JOIN usuario u ON m.idUsuario = u.idUsuario WHERE u.idempresa = 1 AND u.idUsuario = " . $_SESSION["idUsuario"] . " AND( NOW() BETWEEN m.fecha_inicio AND m.fecha_final) ), 2 ) AS 'Porcentaje'";
$quinta_respuesta = mysqli_query($con, $quinta_consulta);
while ($fila = mysqli_fetch_array($quinta_respuesta)) {
  $porcentaje_meta = $fila["Porcentaje"];
}

// Lunes
$consulta_lunes = "SELECT ( ELT( WEEKDAY(fecha_emision) + 1, 'Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado', 'Domingo' ) ) AS DIA_SEMANA, SUM(total) AS total FROM pedidounhesa WHERE WEEK(fecha_emision) = WEEK(NOW()) AND YEAR(fecha_emision) = YEAR(NOW()) AND idEstado = 2 AND idVendedor = " . $_SESSION["idUsuario"] . " AND( ELT( WEEKDAY(fecha_emision) + 1, 'Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado', 'Domingo' ) ) = 'Lunes' GROUP BY DIA_SEMANA";
$respuesta_lunes = mysqli_query($con, $consulta_lunes);
$lunes = 0;
while ($fila = mysqli_fetch_array($respuesta_lunes)) {
  $lunes = $fila["total"];
}
// Martes
$consulta_martes = "SELECT ( ELT( WEEKDAY(fecha_emision) + 1, 'Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado', 'Domingo' ) ) AS DIA_SEMANA, SUM(total) AS total FROM pedidounhesa WHERE WEEK(fecha_emision) = WEEK(NOW()) AND YEAR(fecha_emision) = YEAR(NOW()) AND idEstado = 2 AND idVendedor = " . $_SESSION["idUsuario"] . " AND( ELT( WEEKDAY(fecha_emision) + 1, 'Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado', 'Domingo' ) ) = 'Martes' GROUP BY DIA_SEMANA";
$respuesta_martes = mysqli_query($con, $consulta_martes);
$martes = 0;
while ($fila = mysqli_fetch_array($respuesta_martes)) {
  $martes = $fila["total"];
}
// Miercoles
$consulta_miercoles = "SELECT ( ELT( WEEKDAY(fecha_emision) + 1, 'Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado', 'Domingo' ) ) AS DIA_SEMANA, SUM(total) AS total FROM pedidounhesa WHERE WEEK(fecha_emision) = WEEK(NOW()) AND YEAR(fecha_emision) = YEAR(NOW()) AND idEstado = 2 AND idVendedor = " . $_SESSION["idUsuario"] . " AND( ELT( WEEKDAY(fecha_emision) + 1, 'Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado', 'Domingo' ) ) = 'Miercoles' GROUP BY DIA_SEMANA";
$respuesta_miercoles = mysqli_query($con, $consulta_miercoles);
$miercoles = 0;
while ($fila = mysqli_fetch_array($respuesta_miercoles)) {
  $miercoles = $fila["total"];
}
// Jueves
$consulta_jueves = "SELECT ( ELT( WEEKDAY(fecha_emision) + 1, 'Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado', 'Domingo' ) ) AS DIA_SEMANA, SUM(total) AS total FROM pedidounhesa WHERE WEEK(fecha_emision) = WEEK(NOW()) AND YEAR(fecha_emision) = YEAR(NOW()) AND idEstado = 2 AND idVendedor = " . $_SESSION["idUsuario"] . " AND( ELT( WEEKDAY(fecha_emision) + 1, 'Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado', 'Domingo' ) ) = 'Jueves' GROUP BY DIA_SEMANA";
$respuesta_jueves = mysqli_query($con, $consulta_jueves);
$jueves = 0;
while ($fila = mysqli_fetch_array($respuesta_jueves)) {
  $jueves = $fila["total"];
}
// Viernes
$consulta_viernes = "SELECT ( ELT( WEEKDAY(fecha_emision) + 1, 'Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado', 'Domingo' ) ) AS DIA_SEMANA, SUM(total) AS total FROM pedidounhesa WHERE WEEK(fecha_emision) = WEEK(NOW()) AND YEAR(fecha_emision) = YEAR(NOW()) AND idEstado = 2 AND idVendedor = " . $_SESSION["idUsuario"] . " AND( ELT( WEEKDAY(fecha_emision) + 1, 'Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado', 'Domingo' ) ) = 'Viernes' GROUP BY DIA_SEMANA";
$respuesta_viernes = mysqli_query($con, $consulta_viernes);
$viernes = 0;
while ($fila = mysqli_fetch_array($respuesta_viernes)) {
  $viernes = $fila["total"];
}
$consulta_semanas = "SELECT idPedidoUnhesa, WEEK(fecha_emision) AS semana, SUM(total) as total FROM pedidounhesa WHERE MONTH(fecha_emision) = MONTH(NOW()) AND YEAR(fecha_emision) = YEAR(NOW()) AND idEstado = 2 AND idVendedor = " . $_SESSION["idUsuario"] . " GROUP BY semana";
$respuesta_semanas = mysqli_query($con, $consulta_semanas);
$numero_semana[0] = "";
$numero_semana[1] = "";
$numero_semana[2] = "";
$numero_semana[3] = "";
$numero_semana[4] = "";
$numero_semana[5] = "";
$total_semana[0] = 0;
$total_semana[1] = 0;
$total_semana[2] = 0;
$total_semana[3] = 0;
$total_semana[4] = 0;
$total_semana[5] = 0;
$i = 0;
while ($fila = mysqli_fetch_array($respuesta_semanas)) {
  $numero_semana[$i] = $fila["semana"];
  $total_semana[$i] = $fila["total"];
  $i++;
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

  <!-- Custom fonts for this template-->
  <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

  <!-- Custom styles for this template-->
  <link href="css/sb-admin-2.min.css" rel="stylesheet">

</head>

<body id="page-top">

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
      <a class="sidebar-brand d-flex align-items-center justify-content-center" href="./index.php">
        <div class="sidebar-brand-icon ">
          <i class="fas fa-shopping-basket"></i>

        </div>
        <div class="sidebar-brand-text mx-3">Pedidos</div> <!-- titulo-->
      </a>

      <!-- Divider -->
      <hr class="sidebar-divider my-0">

      <!-- Inicia Menu lateral  -->
      <li class="nav-item active">
        <a class="nav-link" href="./Pedidos/seleccionar_cliente_frioso.html">
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
        echo '<a class="collapse-item" href="Pedidos/Pedido_busqueda.php"> <i class="fas fa-stream"></i>&nbsp Buscar</a>';
        echo '<a class="collapse-item" href="Pedidos/seleccionar_cliente.html"> <i class="fas fa-plus"></i>&nbsp Agregar</a>';

        echo '<a class="collapse-item" href="Pedidos/seleccionar_cliente_frioso.html"> <i class="fas fa-plus"></i>&nbsp Plantilla Friosos</a>';
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
        echo '<a class="collapse-item" href="Pedidos_Proquima/Pedido_busqueda.php"> <i class="fas fa-stream"></i>&nbsp Buscar</a>';
        echo '<a class="collapse-item" href="Pedidos_Proquima/AddCliente.php"> <i class="fas fa-plus"></i>&nbsp Agregar</a>';
        echo '<a class="collapse-item" href="Pedidos/seleccionar_cliente_frioso.html"> <i class="fas fa-plus"></i>&nbsp Plantilla Friosos</a>';
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
            <a class="collapse-item" href="./inventarios_php/ver_lista_recibos.html"><i class="fas fa-stream"></i>&nbsp Buscar</a>
            <a class="collapse-item" href="./inventarios_php/recibos.html">
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
            <a class="collapse-item" href="./inventarios_php/buscar_pedido_online.html"><i class="fas fa-stream"></i>&nbsp Buscar</a>
            <a class="collapse-item" href="./inventarios_php/agregar_pedido_online.html">
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
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#OpcionesPipeline" aria-expanded="true" aria-controls="collapseUtilities">
          <i class="fas fa-calendar"></i>
          <span>Pipelines</span>
        </a>

        <div id="OpcionesPipeline" class="collapse" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
          <div class="bg-white py-2 collapse-inner rounded">
            <h6 class="collapse-header">Opciones:</h6>
            <a class="collapse-item" href="./pipelines/agregar_pipeline.html"><i class="fas fa-receipt"></i>&nbsp Agregar</a>
            <a class="collapse-item" href="./pipelines/buscar_pipeline.html"><i class="fa fa-search"></i>&nbsp Buscar</a>
          </div>
        </div>
      </li>
      <!-- Divider -->

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

        <!-- Inicia Contenido -->
        <div class="container-fluid">
          <!-- Titulo -->
          <div class="d-sm-flex align-items-center text-center justify-content-center mb-4">
            <h1 class="h3 mb-0 text-gray-800">¡Bienvenido <?php echo $_SESSION['nombreUsuario']; ?>!</h1>
          </div>
        </div>
        <!-- Content Row -->
        <div class="row">

          <!-- Earnings (Monthly) Card Example -->
          <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
              <div class="card-body">
                <div class="row no-gutters align-items-center">
                  <div class="col mr-2">
                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                      Vendido Hoy</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $vendido_hoy; ?></div>
                  </div>
                  <div class="col-auto">
                    <i class="fas fa-calendar fa-2x text-gray-300"></i>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Earnings (Monthly) Card Example -->
          <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
              <div class="card-body">
                <div class="row no-gutters align-items-center">
                  <div class="col mr-2">
                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                      Meta de venta (Mensual)</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $meta ?></div>
                  </div>
                  <div class="col-auto">
                    <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Earnings (Monthly) Card Example -->
          <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
              <div class="card-body">
                <div class="row no-gutters align-items-center">
                  <div class="col mr-2">
                    <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Porcentaje de meta alcanzada
                    </div>
                    <div class="row no-gutters align-items-center">
                      <div class="col-auto">
                        <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800"><?= $porcentaje_meta; ?>%</div>
                      </div>
                      <div class="col">
                        <div class="progress progress-sm mr-2">
                          <div class="progress-bar bg-info" role="progressbar" style="width: <?= $porcentaje_meta; ?>%" aria-valuenow="<?= $porcentaje_meta; ?>" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-auto">
                    <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Pending Requests Card Example -->
          <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
              <div class="card-body">
                <div class="row no-gutters align-items-center">
                  <div class="col mr-2">
                    <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                      Semanas transcurridas del mes</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $semana; ?></div>
                  </div>
                  <div class="col-auto">
                    <i class="fas fa-comments fa-2x text-gray-300"></i>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Content Row -->
        <!-- Content Row -->

        <div class="row">

          <!-- Area Chart -->
          <div class="col-xl-8 col-lg-7">
            <div class="card shadow mb-4">
              <!-- Card Header - Dropdown -->
              <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Venta semana actual</h6>
                <div class="dropdown no-arrow">
                  <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                  </a>
                  <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in" aria-labelledby="dropdownMenuLink">
                    <div class="dropdown-header">Dropdown Header:</div>
                    <a class="dropdown-item" href="#">Action</a>
                    <a class="dropdown-item" href="#">Another action</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="#">Something else here</a>
                  </div>
                </div>
              </div>
              <!-- Card Body -->
              <div class="card-body">
                <div class="chart-area">
                  <canvas id="myChartSemana"></canvas>
                </div>
              </div>
            </div>
          </div>

          <!-- Pie Chart -->
          <div class="col-xl-4 col-lg-5">
            <div class="card shadow mb-4">
              <!-- Card Header - Dropdown -->
              <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Ventas Semanales</h6>
                <div class="dropdown no-arrow">
                  <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                  </a>
                  <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in" aria-labelledby="dropdownMenuLink">
                    <div class="dropdown-header">Dropdown Header:</div>
                    <a class="dropdown-item" href="#">Action</a>
                    <a class="dropdown-item" href="#">Another action</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="#">Something else here</a>
                  </div>
                </div>
              </div>
              <!-- Card Body -->
              <div class="card-body">
                <div class="chart-pie pt-4 pb-2">
                  <canvas id="myPieChart"></canvas>
                </div>
                <div class="mt-4 text-center small">
                  <span class="mr-2">
                    <i class="fas fa-circle text-primary"></i>Semana 1
                  </span>
                  <span class="mr-2">
                    <i class="fas fa-circle text-success"></i>Semana 2
                  </span>
                  <span class="mr-2">
                    <i class="fas fa-circle text-info"></i>Semana 3
                  </span>
                  <span class="mr-2">
                    <i class="fas fa-circle text-warning"></i>Semana 4
                  </span>
                  <span class="mr-2">
                    <i class="fas fa-circle text-danger"></i>Semana 5
                  </span>
                  <span class="mr-2">
                    <i class="fas fa-circle text-secondary"></i>Semana 6
                  </span>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Content Row -->
        <!-- /.container-fluid -->
        <!-- <div class="container mt-5 text-center" align="center">
          <img src="./img/Econsa.png" alt="Grupo Econsa" style="width: 300px;">
        </div> -->
      </div>
      <!-- End of Main Content -->

      <!-- Footer -->
      <footer class="sticky-footer bg-white">
        <div class="container my-auto">
          <div class="copyright text-center my-auto">
            <span> Sistema de Pedidos.<br /> Departamento de Informática 2020 GRUPO ECONSA.</span>
          </div>
        </div>
      </footer>
      <!-- End of Footer -->

    </div>
    <!-- End of Content Wrapper -->

  </div>
  <!-- End of Page Wrapper -->

  <!-- Scroll to Top Button-->
  <a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
  </a>

  <!-- Logout Modal-->
  <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Desea cerrar sesión?</h5>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">Seleccione salir si desea cerrar sesión</div>
        <div class="modal-footer">
          <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
          <a class="btn btn-primary" href="logout.php">Salir</a>
        </div>
      </div>
    </div>
  </div>



  <!-- Bootstrap core JavaScript-->
  <script src="vendor/jquery/jquery.min.js"></script>
  <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  <!-- Core plugin JavaScript-->
  <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

  <!-- Custom scripts for all pages-->
  <script src="js/sb-admin-2.min.js"></script>

  <!-- Page level plugins -->
  <script src="vendor/chart.js/Chart.min.js"></script>

  <!-- Page level custom scripts -->
 <!-- <script src="js/demo/chart-area-demo.js"></script>  -->
  <!-- <script src="js/demo/chart-pie-demo.js"></script> -->

  <script>
    // Set new default font family and font color to mimic Bootstrap's default styling
    Chart.defaults.global.defaultFontFamily = 'Nunito', '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
    Chart.defaults.global.defaultFontColor = '#858796';

    function number_format(number, decimals, dec_point, thousands_sep) {
      // *     example: number_format(1234.56, 2, ',', ' ');
      // *     return: '1 234,56'
      number = (number + '').replace(',', '').replace(' ', '');
      var n = !isFinite(+number) ? 0 : +number,
        prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
        sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
        dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
        s = '',
        toFixedFix = function(n, prec) {
          var k = Math.pow(10, prec);
          return '' + Math.round(n * k) / k;
        };
      // Fix for IE parseFloat(0.55).toFixed(0) = 0;
      s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
      if (s[0].length > 3) {
        s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
      }
      if ((s[1] || '').length < prec) {
        s[1] = s[1] || '';
        s[1] += new Array(prec - s[1].length + 1).join('0');
      }
      return s.join(dec);
    }

    // Area Chart Example
    var ctx = document.getElementById("myChartSemana");
    var myLineChart = new Chart(ctx, {
      type: 'line',
      data: {
        labels: ["Lunes", "Martes", "Miércoles", "Jueves", "Viernes"],
        datasets: [{
          label: "Earnings",
          lineTension: 0.3,
          backgroundColor: "rgba(78, 115, 223, 0.05)",
          borderColor: "rgba(78, 115, 223, 1)",
          pointRadius: 3,
          pointBackgroundColor: "rgba(78, 115, 223, 1)",
          pointBorderColor: "rgba(78, 115, 223, 1)",
          pointHoverRadius: 3,
          pointHoverBackgroundColor: "rgba(78, 115, 223, 1)",
          pointHoverBorderColor: "rgba(78, 115, 223, 1)",
          pointHitRadius: 10,
          pointBorderWidth: 2,
          data: [<?= $lunes; ?>, <?= $martes; ?>, <?= $miercoles; ?>, <?= $jueves; ?>, <?= $viernes; ?>],
        }],
      },
      options: {
        maintainAspectRatio: false,
        layout: {
          padding: {
            left: 10,
            right: 25,
            top: 25,
            bottom: 0
          }
        },
        scales: {
          xAxes: [{
            time: {
              unit: 'date'
            },
            gridLines: {
              display: false,
              drawBorder: false
            },
            ticks: {
              maxTicksLimit: 7
            }
          }],
          yAxes: [{
            ticks: {
              maxTicksLimit: 5,
              padding: 10,
              // Include a dollar sign in the ticks
              callback: function(value, index, values) {
                return 'Q.' + number_format(value);
              }
            },
            gridLines: {
              color: "rgb(234, 236, 244)",
              zeroLineColor: "rgb(234, 236, 244)",
              drawBorder: false,
              borderDash: [2],
              zeroLineBorderDash: [2]
            }
          }],
        },
        legend: {
          display: false
        },
        tooltips: {
          backgroundColor: "rgb(255,255,255)",
          bodyFontColor: "#858796",
          titleMarginBottom: 10,
          titleFontColor: '#6e707e',
          titleFontSize: 14,
          borderColor: '#dddfeb',
          borderWidth: 1,
          xPadding: 15,
          yPadding: 15,
          displayColors: false,
          intersect: false,
          mode: 'index',
          caretPadding: 10,
          callbacks: {
            label: function(tooltipItem, chart) {
              var datasetLabel = chart.datasets[tooltipItem.datasetIndex].label || '';
              return datasetLabel + ': Q.' + number_format(tooltipItem.yLabel);
            }
          }
        }
      }
    });
  </script>

  <script>
    // Set new default font family and font color to mimic Bootstrap's default styling
    Chart.defaults.global.defaultFontFamily = 'Nunito', '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
    Chart.defaults.global.defaultFontColor = '#858796';

    // Pie Chart Example
    var ctx = document.getElementById("myPieChart");
    var myPieChart = new Chart(ctx, {
      type: 'doughnut',
      data: {
        labels: ["Semana #<?= $numero_semana[0]; ?>", "Semana #<?= $numero_semana[1]; ?>", "Semana #<?= $numero_semana[2]; ?>", "Semana #<?= $numero_semana[3]; ?>", "Semana #<?= $numero_semana[4]; ?>", "Semana #<?= $numero_semana[5]; ?>"],
        datasets: [{
          data: [<?= $total_semana[0] ?>, <?= $total_semana[1] ?>, <?= $total_semana[2] ?>, <?= $total_semana[3] ?>, <?= $total_semana[4] ?>, <?= $total_semana[5] ?>],
          backgroundColor: ['#4e73df', '#1cc88a', '#36b9cc', '#6EFFFF', '#FF0000', '#C2C2C2'],
          hoverBackgroundColor: ['#2e59d9', '#17a673', '#2c9faf', '#2c9faf', '#FF0000', '#C2C2C2'],
          hoverBorderColor: "rgba(234, 236, 244, 1)",
        }],
      },
      options: {
        maintainAspectRatio: false,
        tooltips: {
          backgroundColor: "rgb(255,255,255)",
          bodyFontColor: "#858796",
          borderColor: '#dddfeb',
          borderWidth: 1,
          xPadding: 15,
          yPadding: 15,
          displayColors: false,
          caretPadding: 10,
        },
        legend: {
          display: false
        },
        cutoutPercentage: 80,
      },
    });
  </script>

</body>

</html>
<?PHP
include("bd/fin_conexion.php");
?>