<?PHP
include("../bd/inicia_conexion.php");
include("../includes/header.php");
$sql = "SELECT 
cp.codigo as 'Codigo', 
cp.nombre as 'Nombre_Cliente', 
pp.direccion_factura as 'Direccion_Factura', 
pp.direccion_entrega as 'Direccion_Entrega', 
pp.contacto_cliente as 'Contacto_Cliente', 
pp.telefono_cliente as 'Telefono_Cliente', 
pp.correo as 'Correo_Cliente', 
pp.credito as 'Dias_Credito', 
pp.dias as 'Horario_Dias', 
pp.horario as 'Horario_Hora', 
pp.idTransporte as 'IdTransporte', 
pp.contacto_bodega as 'Contacto_Bodega', 
pp.telefono_bodega as 'Telefono_Bodega', 
DATE(pp.fecha_despacho) as 'Fecha_Despacho', 
pp.observaciones as 'Observacion', 
pp.observaciones_especiales as 'Observacion_Especial',
pp.total as 'Total'
FROM pedidoproquima pp 
INNER JOIN clienteproquima cp ON pp.idClienteProquima = cp.idCliente";
$sql = $sql . " WHERE pp.idpedidoproquima = " . $_POST["idPedido"];

$resultado = mysqli_query($con, $sql);
while ($fila = mysqli_fetch_array($resultado)) {
    $codigoCliente = $fila["Codigo"];	
    $nombreCliente = $fila["Nombre_Cliente"];
    $direccionFactura = $fila["Direccion_Factura"];
    $direccionEntrega = $fila["Direccion_Entrega"];
    $contactoCliente = $fila["Contacto_Cliente"];
    $telefonoCliente = $fila["Telefono_Cliente"];
    $correoCliente = $fila["Correo_Cliente"];
    $diasCredito = $fila["Dias_Credito"];
    $horarioDias = $fila["Horario_Dias"];
    $horariosHora = $fila["Horario_Hora"];
    $idTransporte = $fila["IdTransporte"];
    $contactoBodega = $fila["Contacto_Bodega"];
    $telefonoBodega = $fila["Telefono_Bodega"];
    $fechaDespacho = $fila["Fecha_Despacho"];
    $observacion = $fila["Observacion"];
    $observacionEspecial = $fila["Observacion_Especial"];
    $total = $fila["Total"];
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

th, td {
  text-align: left;
  padding: 8px;
}

tr:nth-child(even){background-color: #f2f2f2}
</style>

<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800 text-center">Pedido # <?php echo $_POST["idPedido"];?></h1>

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
                                <input type="hidden" id="idCliente" value="<?=$_POST["idCliente"];?>">
                                <input type="hidden" id="idVendedor" value="<?=$id?>">
                                <label>Codigo:</label>
                                <input type="text" class="form-control form-control-user" id="codigo" name="codigo"  value="<?=$codigoCliente;?>" disabled required>
                                <br>
                                <label>Cliente:</label>
                                <input type="text" class="form-control form-control-user" id="cliente" name="cliente"  value="<?=$nombreCliente;?>" disabled required>
                                <br>
                                <label>Direccion Factura:</label>
                                <input type="text" class="form-control form-control-user" id="direccionFactura" name="direccionFactura"  value="<?=$direccionFactura;?>" disabled required>
                                <br>
                                <label>Direccion Entrega:</label>
                                <input type="text" class="form-control form-control-user" id="direccionEntrega" name="direccionEntrega"  value="<?=$direccionEntrega;?>" disabled required>
                                <br>
                                <label>Contacto Cliente</label>
                                <input type="text" class="form-control form-control-user" id="contactoCliente" name="contactoCliente"  value="<?=$contactoCliente;?>" disabled required>
                                <br>
                                <label>Teléfono Cliente</label>
                                <input type="text" class="form-control form-control-user" id="telefonoCliente" name="telefonoCliente"  value="<?=$telefonoCliente;?>" disabled required>
                                <br>
                                <label>Correo Cliente</label>
                                <input type="text" class="form-control form-control-user" id="correoCliente" name="correoCliente"  value="<?=$correoCliente;?>" disabled required>
                                <br>
                                <label>Días Credito</label>
                                <input type="text" class="form-control form-control-user" id="diasCredito" name="diasCredito"  value="<?=$diasCredito;?>" disabled required>
                                <br>
                                <hr>
                                <br>
                                <label>Horarios</label>
                                <input type="text" class="form-control form-control-user" id="horarioDias" name="horarioDias"  value="<?=$horarioDias;?>" disabled required>
                                <br>
                                <input type="text" class="form-control form-control-user" id="horariosHora" name="horariosHora"  value="<?=$horariosHora;?>" disabled required>
                                <br>
                                <label>Transporte:</label>
                                <select class="browser-default custom-select" id="idTransporte" name="idTransporte" disabled>
                                    <?php
                                    $sql = "select * from transporte";
                                    $resultado = mysqli_query($con, $sql);
                                    while ($fila = mysqli_fetch_array($resultado)) {
                                        $seleccionado = "";
                                        if ($idTransporte == $fila["idTransporte"]) {
                                            $seleccionado = "selected";
                                        }
                                        echo "<option value='" . $fila["idTransporte"] . "' " . $seleccionado . ">" . $fila["nombre"] . "</option>";
                                    }
                                    ?>
                                </select>
                                <br>
                                <br>
                                <label>Contácto de Bodega:</label>
                                <input type="text" class="form-control form-control-user" id="contactoBodega" name="contactoBodega" value="<?=$contactoBodega;?>" disabled required>
                                <br>
                                <label>Teléfono de Bodega:</label>
                                <input type="text" class="form-control form-control-user" id="telefonoBodega" name="telefonoBodega" value="<?=$telefonoBodega;?>" disabled required>
                                <br>
                                <label>Fecha de despacho:</label>
                                <input type="date" class="form-control form-control-user" id="fecha" name="fecha" value="<?=$fechaDespacho;?>" disabled  required>
                                <br>
                                <label>Observacion</label>
                                <input type="text" class="form-control form-control-user" id="observacion" name="observacion"  value="<?=$observacion;?>" disabled required>
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
                                $sql = "SELECT dp.idDetallePedidoProquima AS 'ID', pp.codigo AS 'Codigo_Producto', pp.nombre AS 'Descripcion', dp.cantidad AS 'Cantidad', dp.precio AS 'Precio', dp.total AS 'Total' FROM detallepedidoproquima dp INNER JOIN productoproquima pp ON dp.idProductoProquima = pp.idProducto ";
                                $sql = $sql . " where dp.idPedidoProquima = " . $_POST["idPedido"];
                                $resultado = mysqli_query($con, $sql);
                                //echo $sql;
                                while ($fila = mysqli_fetch_array($resultado)) {
                                    echo "<tr>";
                                    echo "<td>" . $fila["ID"] . "</td>";
                                    echo "<td>" . $fila["Codigo_Producto"] . "</td>";
                                    echo "<td>" . $fila["Descripcion"] . "</td>";
                                    echo "<td>" . $fila["Cantidad"] . "</td>";
                                    echo "<td>" . $fila["Precio"] . "</td>";
                                    echo "<td>" . $fila["Total"] . "</td>";
                                    echo "</tr>";                                          
								                }
						?>                
                  </tbody>
                </table>
                <div class="col-sm-10" align="left">
                <br>
                <br>
                                <h1 class="h4 mb-4 text-gray-800">Total del pedido: <b> <?php echo $total;?></b> </h1>
                                <br>
                                <h1 class="h5 mb-4 text-gray-800">Observaciones Adicionales: <b> <?php echo $observacionEspecial;?></b> </h1>
                        </div>
              </div>
            </div>
        </div>
    </div>
    <!-- Termina Formulario  -->
    <!-- End of Main Content -->
    
    <?PHP
    include("../includes/footer.php");
    include("../bd/fin_conexion.php");
    ?>