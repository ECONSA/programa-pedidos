<?php
include("../bd/inicia_conexion.php");
include("../includes/header.php");
?>
<!-- Custom styles for this page -->
<link href="../vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">

<!-- Begin Page Content -->
<div class="container-fluid">

  <!-- Page Heading -->

  <h1 class="h3 mb-2 text-gray-800">Pedidos</h1>


  <!-- DataTales Example -->
  <div class="card shadow mb-4">
    <?php
    if (!empty($_GET)) {
      $variable = $_GET['variable'];
      if ($variable == 1) {
        $_POST["minimaFecha"] = "2000-01-01";
        $_POST["maximaFecha"] = date('Y-m-d', strtotime('+1 day'));
        echo '<div class="card-header py-3">
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
              <th>Tipo de entrega</th>
              <th>Total(Q)</th>
              <th>Estado</th>
              <th>Detalles</th>
              <th>Repetir</th>
            </tr>
          </thead>
          <tfoot>
            <tr>
              <th>Id</th>
              <th>Fecha</th>
              <th>Cliente</th>
              <th>Vendedor</th>
              <th>Tipo de entrega</th>
              <th>Total(Q)</th>
              <th>Estado</th>
              <th>Detalles</th>
              <th>Repetir</th>
            </tr>
          </tfoot>
          <tbody>
            <?php
            $sql = "SELECT p.idPedidoUnhesa, 
                      p.fecha_emision, 
                      c.nombre as cliente, 
                      Round(p.total, 2) as total, 
                      e.nombre as estado,
                      u.nombre as 'vendedor',
                      t.nombre as 'tipoentrega'
                      from pedidounhesa p 
                      inner join usuario u on p.idvendedor = u.idusuario 
                      inner join tipoentrega t on p.idtipoentrega = t.idtipoentrega 
                      inner join cliente c on p.idcliente = c.idcliente 
                      inner join  estado e on p.idestado = e.idestado ";

            $sql = $sql . " where p.fecha_emision BETWEEN '" . $_POST["minimaFecha"] . "'";
            $sql = $sql . " and '" . $_POST["maximaFecha"] . "' and p.idVendedor = " . $_SESSION["idUsuario"] . " order by p.idPedidoUnhesa desc";
            $resultado = mysqli_query($con, $sql);

            while ($fila = mysqli_fetch_array($resultado)) {
              if ($fila["estado"] != "Confirmado") {
                echo "<tr>";
                echo "<td>" . $fila["idPedidoUnhesa"] . "</td>";
                echo "<td>" . $fila["fecha_emision"] . "</td>";
                echo "<td>" . $fila["cliente"] . "</td>";
                echo "<td>" . $fila["vendedor"] . "</td>";
                echo "<td>" . $fila["tipoentrega"] . "</td>";
                echo "<td>" . $fila["total"] . "</td>";
                echo "<td>" . $fila["estado"] . "</td>";
                echo "<td align = 'center'>";
                echo "<a href = 'javascript:fun_view(" . $fila["idPedidoUnhesa"] . ");'>";
                echo "<i class=\"fas fa-search\"></i>";
                echo "</td>";
                echo "<td align = 'center'>";
                echo "<a href = 'javascript:fun_view3(" . $fila["idPedidoUnhesa"] . ");'>";
                echo "<i class=\"fas fa-ban\"></i>";
                echo "</td>";
                echo "</tr>";
              } else {
                echo "<tr>";
                echo "<td>" . $fila["idPedidoUnhesa"] . "</td>";
                echo "<td>" . $fila["fecha_emision"] . "</td>";
                echo "<td>" . $fila["cliente"] . "</td>";
                echo "<td>" . $fila["vendedor"] . "</td>";
                echo "<td>" . $fila["tipoentrega"] . "</td>";
                echo "<td>" . $fila["total"] . "</td>";
                echo "<td>" . $fila["estado"] . "</td>";
                echo "<td align = 'center'>";
                echo "<a href = 'javascript:fun_view(" . $fila["idPedidoUnhesa"] . ");'>";
                echo "<i class=\"fas fa-search\"></i>";
                echo "</td>";
                echo "<td align = 'center'>";
                echo "<a href = 'javascript:fun_view2(" . $fila["idPedidoUnhesa"] . ");'>";
                echo "<i class=\"fas fa-sync-alt\"></i>";
                echo "</td>";
                echo "</tr>";
              }
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

<!-- Repetir pedido -->
<form name='fview2' method='post' action='pedido_repeat.php'>
  <input type="hidden" name="idPedido2">
</form>

<script language="javascript">
  function fun_view2(pid) {
    document.fview2.idPedido2.value = pid;
    document.fview2.submit();
  }
</script>

<!-- Error Repetir pedido -->
<form name='fview3' method='post'>
  <input type="hidden" name="idPedido3">
</form>

<script language="javascript">
  function fun_view3(pid) {
    document.fview3.idPedido3.value = pid;
    Swal.fire(
      'Fallo al repetir',
      'El estado del pedido no permite que se repita',
      'error'
    );
    document.fun_view3.submit();
  }
</script>


<?php
include("../includes/footer.php");
include("../bd/fin_conexion.php");
?>