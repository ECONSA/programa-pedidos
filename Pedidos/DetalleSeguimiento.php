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
    <h1 class="h3 mb-4 text-gray-800 text-center">Detalle Seguimiento</h1>
</div>

<!-- Inicia Formulario  -->
<div>
    <div class="card o-hidden border-0 shadow-lg my-2">
        <div class="card-body p-8">
            <!-- Nested Row within Card Body -->
            <div class="row" style="width: 100%" align="center">
                <div class="col-lg">
                    <form>
                        <div class="col-sm-7">
                            <label>Nombre del Cliente:</label>
                            <input type="text" class="form-control form-control-user" id="nombre_cliente" name="nombre_cliente" placeholder="Nombre Cliente" disabled>
                            <br>
                            <label>Estado del Seguimiento:</label>
                            <select class="browser-default custom-select" id="idStatus" name="idStatus" onchange="limpiarInput()" disabled></select>
                            <br><br>
                            <label>Observaciones:</label>
                            <input type="text" class="form-control form-control-user" id="observaciones" name="observaciones" placeholder="Observaciones" disabled>
                            <br id="brIdPedidoUnhesa" hidden>
                            <label id="lblIdPedidoUnhesa" hidden></label>
                            <input type="number" min="0" class="form-control form-control-user" id="idPedidoUnhesa" name="idPedidoUnhesa" placeholder="ID Pedido Unhesa" hidden disabled>
                            <br>
                            <label>Fecha Creación:</label>
                            <input type="date" min="0" class="form-control form-control-user" id="fechaCreacion" name="fechaCreacion" disabled>
                            <br>
                            <label>Proximo Contacto:</label>
                            <input type="date" class="form-control form-control-user" id="proximoContacto" name="proximoContacto" disabled>
                            <br>
                            <table class="table table-bordered" id="tablaGeneral" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>Id</th>
                                        <th>Estado</th>
                                        <th>Fecha</th>
                                        <th>Observaciones</th>
                                        <th>Tiempo Transcurrido</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <th>Id</th>
                                        <th>Estado</th>
                                        <th>Fecha</th>
                                        <th>Observaciones</th>
                                        <th>Tiempo Transcurrido</th>
                                    </tr>
                                </tfoot>
                                <tbody id="tablaHistorial">
                                </tbody>
                            </table>
                            <br>
                            <h4 id="tiempo_transcurrido">
                            </h4>
                            <br>
                            <button type="button" id="botonEditar" class="btn btn-primary btn-md btn-block" onclick="editarSeguimiento()">Editar Seguimiento</button>
                            <button type="button" id="botonGuardar" class="btn btn-success btn-md btn-block" onclick="validarIDPedido()" hidden>Guardar</button>
                        </div>
                        <br>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?PHP
?>

<?PHP
include("../includes/footer.php");
include("../bd/fin_conexion.php");
?>
<link href="./js/select2.min.css" rel="stylesheet" />
<script src="./js/select2.min.js"></script>
<script src="./js/sweetalert.js"></script>
<script src="../js/detalle_seguimiento.js"></script>