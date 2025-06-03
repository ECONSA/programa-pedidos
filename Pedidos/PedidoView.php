<?PHP
include("../bd/inicia_conexion.php");
include("../includes/header.php");

$sql = "select p.idTipoEntrega, p.direccion, p.telefono, DATE(p.fecha_despacho) as fecha_despacho, DATE(p.fecha_emision) as fecha_emision, p.hora, p.observacion,round(p.total, 2) as total, p.observacion_a, c.nombre as cliente, c.codigo as codigo from pedidounhesa p inner join usuario u on p.idvendedor = u.idusuario inner join tipoentrega t on p.idtipoentrega = t.idtipoentrega inner join cliente c on p.idcliente = c.idcliente inner join estado e on p.idestado = e.idestado";
$sql = $sql . " where p.idpedidounhesa = " . $_POST["idPedido"];
$_SESSION["id_del_pedido"] = $_POST["idPedido"];

$resultado = mysqli_query($con, $sql);
while ($fila = mysqli_fetch_array($resultado)) {
  $nombreCliente = $fila["cliente"];
  $codigoCliente = $fila["codigo"];
  $idTipoEntrega = $fila["idTipoEntrega"];
  $direccion = $fila["direccion"];
  $telefono = $fila["telefono"];
  $fecha_emision = $fila["fecha_emision"];
  $fecha_despacho = $fila["fecha_despacho"];
  $hora = $fila["hora"];
  $observacion = $fila["observacion"];
  $observacion_a = $fila["observacion_a"];
  $total = $fila["total"];
}
$id = $_SESSION["idUsuario"];

$sqlUsuario = "SELECT * FROM usuario WHERE idUsuario = " . $id;

$resultadoUsuario = mysqli_query($con, $sqlUsuario);
while ($fila = mysqli_fetch_array($resultadoUsuario)) {
  $nombreVendedor = $fila["nombre"];
}

//echo $fecha_despacho;
?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
<link href="./js/select2.min.css" rel="stylesheet" />
<script src="./js/select2.min.js"></script>
<style>
  table {
    border-collapse: collapse;
    border-spacing: 0;
    width: 100%;
    border: 1px solid #ddd;
  }

  th,
  td {
    text-align: left;
    padding: 8px;
  }

  tr:nth-child(even) {
    background-color: #f2f2f2
  }
</style>

<!-- Begin Page Content -->
<div class="container-fluid">

  <!-- Page Heading -->
  <h1 class="h3 mb-4 text-gray-800 text-center">Pedido # <?php echo $_POST["idPedido"]; ?></h1>

</div>
<!-- /.container-fluid -->

<!-- Inicia Formulario  -->
<div>
  <div class="card o-hidden border-0 shadow-lg my-5">
    <div class="card-body p-0">
      <!-- Nested Row within Card Body -->
      <div class="row" style="width:'100%'" align="center">
        <div class="col-lg">
          <div class="p-5">
            <div class="text-left">
              <h1 class="h5 text-gray-900 mb-4 text-center ">Detalles del pedido</h1>
            </div>
            <form>
              <div class="col-sm-10">
                <h2 class="h6 text-gray-700 mb-4" align="left">Datos del cliente:</h2>
                
                <input type="hidden" id="idVendedor" value="<?= $id ?>">
                
                <label>Codigo:</label>
                <input type="text" class="form-control form-control-user" id="codigo" name="codigo" value="<?= $codigoCliente; ?>" disabled required>
                <br>
                <label>Cliente:</label>
                <input type="text" class="form-control form-control-user" id="cliente" name="cliente" value="<?= $nombreCliente; ?>" disabled required>
                <br>
                <br>
                <label>Vendedor:</label>
                <input type="text" class="form-control form-control-user" id="vendedor" name="vendedor" value="<?= $nombreVendedor; ?>" disabled required>
                <br>
                <label>Tipo de entrega:</label>
                <select class="browser-default custom-select" id="idTipoEntrega" name="idTipoEntrega" disabled>
                  <?php
                  $sql = "select  *from TipoEntrega";
                  $resultado = mysqli_query($con, $sql);
                  while ($fila = mysqli_fetch_array($resultado)) {
                    $seleccionado = "";
                    if ($idTipoEntrega == $fila["idTipoEntrega"]) {
                      $seleccionado = "selected";
                    }
                    echo "<option value='" . $fila["idTipoEntrega"] . "' " . $seleccionado . ">" . $fila["nombre"] . "</option>";
                  }
                  ?>
                </select>
                <br>
                <br>
                <label>Dirección de despacho:</label>
                <input type="text" class="form-control form-control-user" id="direccion" name="direccion" value="<?= $direccion; ?>" disabled required>
                <br>
                <label>Teléfono:</label>
                <input type="text" class="form-control form-control-user" id="telefono" name="telefono" value="<?= $telefono; ?>" disabled required>
                <br>
                <label>Fecha de emisión:</label>
                <input type="date" class="form-control form-control-user" id="fecha" name="fecha" value="<?= $fecha_emision; ?>" disabled required>
                <br>
                <label>Fecha de despacho:</label>
                <input type="date" class="form-control form-control-user" id="fecha" name="fecha" value="<?= $fecha_despacho; ?>" disabled required>
                <br>
                <label>Hora de entrega:</label>
                <input type="time" class="form-control form-control-user" id="hora" name="hora" value="<?= $hora; ?>" disabled required>
                <br>
                <label>Observaciones:</label>
                <input type="text" class="form-control form-control-user" id="observaciones" name="observaciones" value="<?= $observacion; ?>" disabled>
                <br>
                <hr>
                <h2 class="h6 text-gray-700 mb-4" align="left">Detalle del pedido:</h2>
              </div>
              <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                      <th>Id</th>
                      <th>Codigo</th>
                      <th>Descripción</th>
                      <th>Cantidad</th>
                      <th>Precio(Q)</th>
                      <th>Total(Q)</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $sql = "select d.iddetallepedidounhesa as id, p.codigo, p.nombre, d.cantidad, d.precio, d.total from detallepedidounhesa d inner join producto p on d.idProducto = p.idProducto ";
                    $sql = $sql . " where d.idPedidoUnhesa = " . $_POST["idPedido"];
                    $resultado = mysqli_query($con, $sql);
                    //echo $sql;
                    while ($fila = mysqli_fetch_array($resultado)) {
                      echo "<tr>";
                      echo "<td>" . $fila["id"] . "</td>";
                      echo "<td>" . $fila["codigo"] . "</td>";
                      echo "<td>" . $fila["nombre"] . "</td>";
                      echo "<td>" . $fila["cantidad"] . "</td>";
                      echo "<td>" . $fila["precio"] . "</td>";
                      echo "<td>" . $fila["total"] . "</td>";
                      echo "</tr>";
                    }
                    ?>
                  </tbody>
                </table>
                <div class="col-sm-10" align="left">
                  <br>
                  <br>
                  <h1 class="h4 mb-4 text-gray-800">Total del pedido: <b> <?php echo $total; ?></b> </h1>
                  <br>
                  <h1 class="h5 mb-4 text-gray-800">Observaciones Adicionales: <b> <?php echo $observacion_a; ?></b> </h1>
                </div>
              </div>
              <img src="" id="img" style="width: 100%; height: 100%;">
          </div>
        </div>
      </div>
      <script src="../js/pedido.js"></script>
      <!-- Termina Formulario  -->
      <!-- End of Main Content -->

      <?PHP
      include("../includes/footer.php");
      include("../bd/fin_conexion.php");
      ?>