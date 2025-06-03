<?php
include("../bd/inicia_conexion.php");
include("../includes/header.php");
?>
<!-- Custom styles for this page -->
<link href="../vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">

<!-- Begin Page Content -->
<div class="container-fluid">

  <!-- Page Heading -->

  <h1 class="h3 mb-2 text-gray-800">Pedidos Proquima</h1>

  <!-- DataTales Example -->
  <div class="card shadow mb-4">
    <?php
    if (!empty($_GET)) {
      $variable = $_GET['variable'];
      if ($variable == 1) {
        $_POST["minimaFecha"] = "2000-01-01";
        $_POST["maximaFecha"] = date('Y-m-d', strtotime('+1 day'));
        echo
          '<div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">PEDIDO REGISTRADO CON EXITO!</h6>
                  </div>';
      }
    }
    ?>
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
          <thead>
            <tr>
              <th>Id</th>
              <th>Fecha</th>
              <th>Cliente</th>
              <th>Vendedor</th>
              <th>Total(Q)</th>
              <th>Estado</th>
              <th>Detalles</th>
            </tr>
          </thead>
          <tfoot>
            <tr>
              <th>Id</th>
              <th>Fecha</th>
              <th>Cliente</th>
              <th>Vendedor</th>
              <th>Total(Q)</th>
              <th>Estado</th>
              <th>Detalles</th>
            </tr>
          </tfoot>
          <tbody>
            <?php
            $sql = "SELECT p.idPedidoProquima, 
                            p.fecha_emision, 
                            c.nombre AS cliente, 
                            p.total, 
                            e.nombre AS estado, 
                            u.nombre AS 'vendedor' 
                            FROM pedidoproquima p 
                            INNER JOIN usuario u ON p.idvendedor = u.idusuario 
                            INNER JOIN clienteproquima c ON p.idClienteProquima = c.idCliente 
                            INNER JOIN estado e ON p.idestado = e.idestado";

            $sql = $sql . " where p.fecha_emision BETWEEN '" . $_POST["minimaFecha"] . "'";
            $sql = $sql . " and '" . $_POST["maximaFecha"] . "'  and p.idVendedor = " . $_SESSION["idUsuario"];
            $resultado = mysqli_query($con, $sql);
            //echo $sql;
            while ($fila = mysqli_fetch_array($resultado)) {
              echo "<tr>";
              echo "<td>" . $fila["idPedidoProquima"] . "</td>";
              echo "<td>" . $fila["fecha_emision"] . "</td>";
              echo "<td>" . $fila["cliente"] . "</td>";
              echo "<td>" . $fila["vendedor"] . "</td>";
              echo "<td>" . $fila["total"] . "</td>";
              echo "<td>" . $fila["estado"] . "</td>";
              echo "<td align = 'center'>";
              echo "<a href = 'javascript:fun_view(" . $fila["idPedidoProquima"] . ");'>";
              echo "<i class=\"fas fa-search\"></i>";
              echo "</td>";
              echo "</tr>";
            }
            ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>




</div>
<!-- /.container-fluid -->

</div>
<!-- End of Main Content -->

<form name='fview' method='post' action='PedidoView.php'>
  <input type="hidden" name="idPedido">
</form>

<script language="javascript">
  function fun_view(pid) {
    document.fview.idPedido.value = pid;
    document.fview.submit();
  }
</script>


<?php
include("../includes/footer.php");
include("../bd/fin_conexion.php");
?>