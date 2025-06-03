<?PHP
include("../bd/inicia_conexion.php");
include("../includes/header.php");
$sql = "select * from Cliente";
$sql = $sql . " where idCliente = " . $_POST["idCliente"];
$resultado = mysqli_query($con, $sql);
while ($fila = mysqli_fetch_array($resultado)) {
    $nombreCliente = $fila["nombre"];
    $codigoCliente = $fila["codigo"];
    $direccionCliente = $fila["direccion"];
}
$id = $_SESSION["idUsuario"];
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
    <h1 class="h3 mb-4 text-gray-800 text-center">Agregar Nuevo Pedido</h1>

</div>
<!-- /.container-fluid -->

<!-- Inicia Formulario  -->
<div>
    <div class="card o-hidden border-0 shadow-lg my-5">
        <div class="card-body p-0">
            <!-- Nested Row within Card Body -->
            <div class="row" style="width: 100%" align="center">
                <div class="col-lg">
                    <div class="p-5">
                        <div class="text-left">
                            <h1 class="h5 text-gray-900 mb-4 text-center">Por favor llene el siguiente formulario:</h1>
                        </div>
                        <form>
                            <div class="col-sm-10">
                                <h2 class="h6 text-gray-700 mb-4" align="left">Datos del cliente:</h2>
                                <input type="hidden" id="idCliente" value="<?= $_POST["idCliente"]; ?>">
                                <input type="hidden" id="idVendedor" value="<?= $id ?>">
                                <label>Codigo:</label>
                                <input type="text" class="form-control form-control-user" id="codigo" name="codigo" value="<?= $codigoCliente; ?>" disabled required>
                                <br>
                                <label>Cliente:</label>
                                <input type="text" class="form-control form-control-user" id="cliente" name="cliente" value="<?= $nombreCliente; ?>" disabled required>
                                <br>
                                <label>Tipo de entrega:</label>
                                <select class="browser-default custom-select" id="idTipoEntrega" name="idTipoEntrega">
                                    <?php
                                    $sql = "select * from TipoEntrega";
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
                                <label>Dirección:</label>
                                <input type="text" class="form-control form-control-user" id="direccion" name="direccion" value="" placeholder="Dirección de entrega del pedido" required>
                                <br>
                                <label>Telefono:</label>
                                <input type="number" class="form-control form-control-user" id="telefono" name="telefono" placeholder="Teléfono de contacto" required>
                                <br>
                                <label>Fecha de despacho:</label>
                                <input type="date" class="form-control form-control-user" id="fecha" name="fecha" required>
                                <br>
                                <label>Hora de entrega:</label>
                                <input type="time" class="form-control form-control-user" id="hora" name="hora" required>
                                <br>
                                <label>Observaciones:</label>
                                <input type="text" class="form-control form-control-user" id="observacion" name="observacion" placeholder="Observaciones">
                                <br>
                                <label>Observaciones Adicionales:</label>
                                <input type="text" class="form-control form-control-user" id="observacion_A" name="observacion_A" placeholder="Observaciones Adicionales">
                        </form>
                    </div>
                </div>
                <div class="p-10" style="overflow-x:auto;">
                    <br>
                    <table class="table table-striped table-bordered nowrap" class="display" id="dataTable" width="100%" align="center" cellspacing="0" data-role="datatable" data-info="false">
                        <tbody>
                            <thead>
                                <tr>
                                    <th>Código</th>
                                    <th>Nombre</th>
                                    <th>Cantidad</th>
                                    <th>Precio.Q</th>
                                    <th>Total</th>
                                    <th>Opciones</th>
                                </tr>
                            </thead>
                            <tr>
                                <td id="codigo1" value="710">PTPPFR041</td>
                                <td hidden><input id="codigox1" value="710"></td>
                                <td id="nombreP1">Fri Surtido</td>
                                <td><input id="cantidad1" value="0" type="number" min="0" placeholder="0" size=10 style="width:55px"></td>
                                <td><input id="precio1" type="number" min="0" value="70.50" size=10 style="width:54px"></td>
                                <td id="total1"></td>

                                <td><i class="fas fa-trash-alt" style="margin-left: 25px" onclick="limpiarDatos(1)"></i></td>
                            </tr>
                            <tr>
                                <td id="codigo2" value="704">PTPPFR053</td>
                                <td hidden><input id="codigox2" value="704"></td>
                                <td id="nombreP2">FRI Bol Naranja</td>
                                <td><input id="cantidad2" value="0" type="number" min="0" placeholder="0" size=10 style="width:55px"></td>
                                <td><input id="precio2" type="number" min="0" value="70.50" size=10 style="width:54px"></td>
                                <td id="total2"></td>

                                <td><i class="fas fa-trash-alt" style="margin-left: 25px" onclick="limpiarDatos(2)"></i></td>
                            </tr>
                            <tr>
                                <td id="codigo3" value="700">PTPPFR054</td>
                                <td hidden><input id="codigox3" value="700"></td>
                                <td id="nombreP3">FRI Bol Fresa</td>
                                <td><input id="cantidad3" value="0" type="number" min="0" placeholder="0" size=10 style="width:55px"></td>
                                <td><input id="precio3" type="number" min="0" value="70.50" size=10 style="width:54px"></td>
                                <td id="total3"></td>

                                <td><i class="fas fa-trash-alt" style="margin-left: 25px" onclick="limpiarDatos(3)"></i></td>
                            </tr>
                            <tr>
                                <td id="codigo4" value="697">PTPPFR055</td>
                                <td hidden><input id="codigox4" value="697"></td>
                                <td id="nombreP4">FRI Bol Chicle</td>
                                <td><input id="cantidad4" value="0" type="number" min="0" placeholder="0" size=10 style="width:55px"></td>
                                <td><input id="precio4" type="number" min="0" value="70.50" size=10 style="width:54px"></td>
                                <td id="total4"></td>

                                <td><i class="fas fa-trash-alt" style="margin-left: 25px" onclick="limpiarDatos(4)"></i></td>
                            </tr>
                            <tr>
                                <td id="codigo5" value="709">PTPPFR056</td>
                                <td hidden><input id="codigox5" value="709"></td>
                                <td id="nombreP5">FRI Bol Uva</td>
                                <td><input id="cantidad5" value="0" type="number" min="0" placeholder="0" size=10 style="width:55px"></td>
                                <td><input id="precio5" type="number" min="0" value="70.50" size=10 style="width:54px"></td>
                                <td id="total5"></td>

                                <td><i class="fas fa-trash-alt" style="margin-left: 25px" onclick="limpiarDatos(5)"></i></td>
                            </tr>
                            <tr>
                                <td id="codigo6" value="702">PTPPFR057</td>
                                <td hidden><input id="codigox6" value="702"></td>
                                <td id="nombreP6">FRI Bol Manzana</td>
                                <td><input id="cantidad6" value="0" type="number" min="0" placeholder="0" size=10 style="width:55px"></td>
                                <td><input id="precio6" type="number" min="0" value="70.50" size=10 style="width:54px"></td>
                                <td id="total6"></td>

                                <td><i class="fas fa-trash-alt" style="margin-left: 25px" onclick="limpiarDatos(6)"></i></td>
                            </tr>
                            <tr>
                                <td id="codigo7" value="705">PTPPFR058</td>
                                <td hidden><input id="codigox7" value="705"></td>
                                <td id="nombreP7">FRI Bol Piña</td>
                                <td><input id="cantidad7" value="0" type="number" min="0" placeholder="0" size=10 style="width:55px"></td>
                                <td><input id="precio7" type="number" min="0" value="70.50" size=10 style="width:54px"></td>
                                <td id="total7"></td>

                                <td><i class="fas fa-trash-alt" style="margin-left: 25px" onclick="limpiarDatos(7)"></i></td>
                            </tr>
                            <tr>
                                <td id="codigo8" value="706">PTPPFR059</td>
                                <td hidden><input id="codigox8" value="706"></td>
                                <td id="nombreP8">FRI Bol Tamarindo</td>
                                <td><input id="cantidad8" value="0" type="number" min="0" placeholder="0" size=10 style="width:55px"></td>
                                <td><input id="precio8" type="number" min="0" value="70.50" size=10 style="width:54px"></td>
                                <td id="total8"></td>

                                <td><i class="fas fa-trash-alt" style="margin-left: 25px" onclick="limpiarDatos(8)"></i></td>
                            </tr>
                            <tr>
                                <td id="codigo9" value="703">PTPPFR060</td>
                                <td hidden><input id="codigox9" value="703"></td>
                                <td id="nombreP9">Fri Bol Maracuya</td>
                                <td><input id="cantidad9" value="0" type="number" min="0" placeholder="0" size=10 style="width:55px"></td>
                                <td><input id="precio9" type="number" min="0" value="70.50" size=10 style="width:54px"></td>
                                <td id="total9"></td>

                                <td><i class="fas fa-trash-alt" style="margin-left: 25px" onclick="limpiarDatos(9)"></i></td>
                            </tr>
                            <tr>
                                <td id="codigo10" value="707">PTPPFR062</td>
                                <td hidden><input id="codigox10" value="707"></td>
                                <td id="nombreP10">Fri Bol Tamar-Enchil</td>
                                <td><input id="cantidad10" value="0" type="number" min="0" placeholder="0" size=10 style="width:55px"></td>
                                <td><input id="precio10" type="number" min="0" value="70.50" size=10 style="width:54px"></td>
                                <td id="total10"></td>
                                <td><i class="fas fa-trash-alt" style="margin-left: 25px" onclick="limpiarDatos(10)"></i></td>
                            </tr>
                            <tr>
                                <td id="codigo11" value="699">PTPPFR063</td>
                                <td hidden><input id="codigox11" value="699"></td>
                                <td id="nombreP11">Fri Bol Cola</td>
                                <td><input id="cantidad11" value="0" value="0" type="number" min="0" placeholder="0" size=10 style="width:55px"></td>
                                <td><input id="precio11" type="number" min="0" value="70.50" size=10 style="width:54px"></td>
                                <td id="total11"></td>

                                <td><i class="fas fa-trash-alt" style="margin-left: 25px" onclick="limpiarDatos(11)"></i></td>
                            </tr>
                            <tr>
                                <td id="codigo12" value="698">PTPPFR064</td>
                                <td hidden><input id="codigox12" value="698"></td>
                                <td id="nombreP12">Fri Bol Coco-Fresa</td>
                                <td><input id="cantidad12" value="0" type="number" min="0" placeholder="0" size=10 style="width:55px"></td>
                                <td><input id="precio12" type="number" min="0" value="70.50" size=10 style="width:54px"></td>
                                <td id="total12"></td>

                                <td><i class="fas fa-trash-alt" style="margin-left: 25px" onclick="limpiarDatos(12)"></i></td>
                            </tr>
                            <tr>
                                <td id="codigo13" value="764">PTPPGE027</td>
                                <td hidden><input id="codigox13" value="764"></td>
                                <td id="nombreP13">Gel d Surtido</td>
                                <td><input id="cantidad13" value="0" type="number" min="0" placeholder="0" size=10 style="width:55px"></td>
                                <td><input id="precio13" type="number" min="0" value="70.50" size=10 style="width:54px"></td>
                                <td id="total13"></td>

                                <td><i class="fas fa-trash-alt" style="margin-left: 25px" onclick="limpiarDatos(13)"></i></td>
                            </tr>
                            <tr>
                                <td id="codigo14" value="712">PTPPFR051</td>
                                <td hidden><input id="codigox14" value="712"></td>
                                <td id="nombreP13">Fri Surtido Temp.</td>
                                <td><input id="cantidad14" value="0" type="number" min="0" placeholder="0" size=10 style="width:55px"></td>
                                <td><input id="precio14" type="number" min="0" value="70.50" size=10 style="width:54px"></td>
                                <td id="total14"></td>
                                <td><i class="fas fa-trash-alt" style="margin-left: 25px" onclick="limpiarDatos(14)"></i></td>
                            </tr>
                            <tr>
                                <td id="codigo15" value="1279">PTPPGE045</td>
                                <td hidden><input id="codigox15" value="1279"></td>
                                <td id="nombreP15">Gel Surtido 150 uni</td>
                                <td><input id="cantidad15" value="0" type="number" min="0" placeholder="0" size=10 style="width:55px"></td>
                                <td><input id="precio15" type="number" min="0" value="70.50" size=10 style="width:54px"></td>
                                <td id="total15"></td>
                                <td><i class="fas fa-trash-alt" style="margin-left: 25px" onclick="limpiarDatos(15)"></i></td>
                            </tr>
                            <tr>
                                <td>
                                    <form name="datos" method="post" action="AddPedidoPlantilla.php" onsubmit="return verifica_codigo(this);">
                                        <select value="0" id="casilla1" onchange="setearDatos(1,25)" class="idCliente browser-default custom-select" style="width: 90px;" name="idCliente"></selecti>
                                            <script type="text/javascript">
                                                $('.idCliente').select2({
                                                    placeholder: 'Codigo del cliente',
                                                    ajax: {
                                                        url: 'producto.php',
                                                        dataType: 'json',
                                                        delay: 250,
                                                        processResults: function(data) {
                                                            return {
                                                                results: data
                                                            };
                                                        },
                                                        cache: true
                                                    }
                                                });
                                            </script>
                                    </form>
                                </td>
                                <td id="nombre1"></td>
                                <td><input id="cantidad25" value="0" type="number" min="0" placeholder="0" size=10 style="width:55px" disabled></td>
                                <td><input id="precio25" type="number" min="0" value="0" size=10 style="width:54px" disabled></td>
                                <td id="total25"></td>
                                <td><i class="fas fa-trash-alt" style="margin-left: 25px" onclick="limpiarDatosCombobox(1,25)"></i></td>
                            </tr>
                            <tr>
                                <td>
                                    <form name="datos" method="post" action="AddPedidoPlantilla.php" onsubmit="return verifica_codigo(this);">
                                        <select value="0" id="casilla2" onchange="setearDatos(2,26)" class="idCliente browser-default custom-select" style="width: 90px;" name="idCliente"></select>
                                        <script type="text/javascript">
                                            $('.idCliente').select2({
                                                placeholder: 'Codigo del cliente',
                                                ajax: {
                                                    url: 'producto.php',
                                                    dataType: 'json',
                                                    delay: 250,
                                                    processResults: function(data) {
                                                        return {
                                                            results: data
                                                        };
                                                    },
                                                    cache: true
                                                }
                                            });
                                        </script>
                                    </form>
                                </td>
                                <td id="nombre2"></td>
                                <td><input id="cantidad26" value="0" type="number" min="0" placeholder="0" size=10 style="width:55px" disabled></td>
                                <td><input id="precio26" type="number" min="0" value="0" size=10 style="width:54px" disabled></td>
                                <td id="total26"></td>
                                <td><i class="fas fa-trash-alt" style="margin-left: 25px" onclick="limpiarDatosCombobox(2,26)"></i></td>
                            </tr>
                            <tr>
                                <td>
                                    <form name="datos" method="post" action="AddPedidoPlantilla.php" onsubmit="return verifica_codigo(this);">
                                        <select value="0" id="casilla3" onchange="setearDatos(3,27)" class="idCliente browser-default custom-select" style="width: 90px;" name="idCliente"></select>
                                        <script type="text/javascript">
                                            $('.idCliente').select2({
                                                placeholder: 'Codigo del cliente',
                                                ajax: {
                                                    url: 'producto.php',
                                                    dataType: 'json',
                                                    delay: 250,
                                                    processResults: function(data) {
                                                        return {
                                                            results: data
                                                        };
                                                    },
                                                    cache: true
                                                }
                                            });
                                        </script>
                                    </form>
                                </td>
                                <td id="nombre3"></td>
                                <td><input id="cantidad27" value="0" type="number" min="0" placeholder="0" size=10 style="width:55px" disabled></td>
                                <td><input id="precio27" type="number" min="0" value="0" size=10 style="width:54px" disabled></td>
                                <td id="total27"></td>
                                <td><i class="fas fa-trash-alt" style="margin-left: 25px" onclick="limpiarDatosCombobox(3,27)"></i></td>
                            </tr>
                            <tr>
                                <td>
                                    <form name="datos" method="post" action="AddPedidoPlantilla.php" onsubmit="return verifica_codigo(this);">
                                        <select value="0" id="casilla4" onchange="setearDatos(4,28)" class="idCliente browser-default custom-select" style="width: 90px;" name="idCliente"></select>
                                        <script type="text/javascript">
                                            $('.idCliente').select2({
                                                placeholder: 'Codigo del cliente',
                                                ajax: {
                                                    url: 'producto.php',
                                                    dataType: 'json',
                                                    delay: 250,
                                                    processResults: function(data) {
                                                        return {
                                                            results: data
                                                        };
                                                    },
                                                    cache: true
                                                }
                                            });
                                        </script>
                                    </form>
                                </td>
                                <td id="nombre4"></td>
                                <td><input id="cantidad28" value="0" type="number" min="0" placeholder="0" size=10 style="width:55px" disabled></td>
                                <td><input id="precio28" type="number" min="0" value="0" size=10 style="width:54px" disabled></td>
                                <td id="total28"></td>
                                <td><i class="fas fa-trash-alt" style="margin-left: 25px" onclick="limpiarDatosCombobox(4,28)"></i></td>
                            </tr>
                            <tr>
                                <td>
                                    <form name="datos" method="post" action="AddPedidoPlantilla.php" onsubmit="return verifica_codigo(this);">
                                        <select value="0" onchange="setearDatos(5,29)" id="casilla5" class="idCliente browser-default custom-select" style="width: 90px;" name="idCliente"></select>
                                        <script type="text/javascript">
                                            $('.idCliente').select2({
                                                placeholder: 'Codigo del cliente',
                                                ajax: {
                                                    url: 'producto.php',
                                                    dataType: 'json',
                                                    delay: 250,
                                                    processResults: function(data) {
                                                        return {
                                                            results: data
                                                        };
                                                    },
                                                    cache: true
                                                }
                                            });
                                        </script>
                                    </form>
                                </td>
                                <td id="nombre5"></td>
                                <td><input id="cantidad29" value="0" type="number" min="0" placeholder="0" size=10 style="width:55px" disabled></td>
                                <td><input id="precio29" type="number" min="0" value="0" size=10 style="width:54px" disabled></td>
                                <td id="total29"></td>
                                <td><i class="fas fa-trash-alt" style="margin-left: 25px" onclick="limpiarDatosCombobox(5,29)"></i></td>
                            </tr>
                        </tbody>
                    </table>
                    <br>
                </div>
                <div>
                    <table class="table table-striped table-bordered nowrap" id="dataTable" width="100%" align="center" cellspacing="0" data-role="datatable" data-info="false">
                        <thead>
                            <tr>
                                <th>Productos Pedidos</th>
                                <th>Total General</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td id="cantidad"></td>
                                <td id="total"></td>
                            </tr>
                        </tbody>
                    </table>
                    <br>
                    <button type="button" class="btn btn-success" onclick="calcularTotales()">Calcular Total</button>
                    <br><br>
                    <button id="enviar" type="button" class="btn btn-primary" onclick="agregarPedido()" disabled>Enviar Pedido</button>

                </div>
                <br>
            </div>
        </div>
    </div>
</div>
<script src="../js/pedidoFriosoNuevo.js"></script>
<script src="./js/sweetalert.js"></script>
<?PHP
include("../includes/footer.php");
include("../bd/fin_conexion.php");
?>