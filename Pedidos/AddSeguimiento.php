<?PHP
include("../bd/inicia_conexion.php");
include("../includes/header.php");

?>


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
    <h1 class="h3 mb-4 text-gray-800 text-center">Agregar Nuevo Seguimiento</h1>

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
                                    <input type="text" id="idVendedor" value="<?PHP echo $_SESSION["idUsuario"]; ?>" hidden >
                                    
                                    <br>
                                    <label>Nombre del Cliente:</label>
                                    <br>
                                    <select id="id_cliente_online" class="js-example-basic-single" class="browser-default custom-select form-control" name="idCliente"></select>
                                    <br><br>
                                    <label>Estado del Seguimiento:</label>
                                    <select class="browser-default custom-select" onchange="limpiarInput()" id="idStatus" name="idStatus">
                                        <?php
                                        $sql = "select * from estados_llamadas";
                                        $resultado = mysqli_query($con, $sql);
                                        while ($fila = mysqli_fetch_array($resultado)) {
                                            $seleccionado = "";
                                            if ($idStatus == $fila["id_estado"]) {
                                                $seleccionado = "selected";
                                            }
                                            echo "<option value='" . $fila["id_estado"] . "' " . $seleccionado . ">" . $fila["nombre"] . "</option>";
                                        }
                                        ?>
                                    </select>
                                    <br><br>
                                    <label>Observaciones:</label>
                                    <input type="text" class="form-control form-control-user" id="observaciones" name="observaciones">
                                    <br id="brIdPedidoUnhesa" hidden>
                                    <label id="lblIdPedidoUnhesa" hidden>ID del Pedido:</label>
                                    <input type="number" min="0" class="form-control form-control-user" id="idPedidoUnhesa" name="idPedidoUnhesa" placeholder="ID Pedido Unhesa" hidden>
                                    <br>
                                    <label>Proximo Contacto:</label>
                                    <input type="date" class="form-control form-control-user" id="proximoContacto" name="proximoContacto">
                                    <br>
                                    <button type="button" class="btn btn-success btn-lg btn-block" onclick="validarIDPedido()">Ingresar Seguimiento</button>
                                </div>
                                
                                <br>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?PHP
    //prueba
    // include("../includes/footersindatatable.php");
    // include("../bd/fin_conexion.php");
?>

<?PHP
    include("../includes/footer.php");
    include("../bd/fin_conexion.php");
    ?>
    <link href="./js/select2.min.css" rel="stylesheet" />
<script src="./js/select2.min.js"></script>
<script src="../js/seguimiento.js"></script>

