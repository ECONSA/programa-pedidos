<?PHP
include("../bd/inicia_conexion.php");
include("../includes/header.php");
?>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
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
    <h1 class="h3 mb-4 text-gray-800 text-center">Seguimientos Actuales</h1>

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
                        <div class="row m-auto">
                            <div class="col-xxl-6 col-xl-6 mb-6">
                                <strong>Filtrar fecha de seguimientos</strong>
                            </div>
                            <div class="col-xxl-2 col-xl-2 mb-2">
                                <select class="form-control" type="select" id="mes_seguimiento">
                                    <option value="1">Enero</option>
                                    <option value="2">Febrero</option>
                                    <option value="3">Marzo</option>
                                    <option value="4">Abril</option>
                                    <option value="5">Mayo</option>
                                    <option value="6">Junio</option>
                                    <option value="7">Julio</option>
                                    <option value="8">Agosto</option>
                                    <option value="9">Septiembre</option>
                                    <option value="10">Octubre</option>
                                    <option value="11">Noviembre</option>
                                    <option value="12">Diciembre</option>
                                </select>
                            </div>
                            <div class="col-xxl-2 col-xl-2 mb-2">
                                <select class="form-control" type="select" id="año_seguimiento">
                                    <option value="2021">2021</option>
                                    <option value="2022">2022</option>
                                    <option value="2023">2023</option>
                                    <option value="2024">2024</option>
                                </select>
                            </div>
                            <div class="col-xxl-2 col-xl-2 mb-2">
                                <button type="button" class="btn btn-primary" id="btn_buscar_seguimiento_tarjetas" onclick="SeguimientoUNHESA()">Buscar</button>
                            </div>
                        </div>
                        <script src="https://code.jquery.com/jquery-3.5.1.min.js" crossorigin="anonymous"></script>
                        <script src="../evo-calendar/js/evo-calendar.js"></script>
                        <br>
                        <div class="text-left">
                            <input type="text" id="idVendedor" value="<?PHP echo $_SESSION["idUsuario"]; ?>" hidden>
                            <h1 class="h5 text-gray-900 mb-4 text-center">Seguimiento de Clientes de este mes:</h1>
                        </div>

                        <form>
                            <div class="card h-100">
                                <div class="card-body h-100 d-flex flex-column justify-content-center py-5 py-xl-12">
                                    <div class="row align-items-center">
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-striped" id="tablaSeguimiento" width="100%" cellspacing="0">
                                                <thead>
                                                    <tr>
                                                        <th>ID del seguimiento</th>
                                                        <th>Cliente</th>
                                                        <th>Estado</th>
                                                        <th>Fecha de Creación</th>
                                                        <th>Pedido</th>
                                                        <th>Detalle</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="seguimiento">
                                                </tbody>
                                                <tfoot>
                                                    <tr>
                                                        <th>ID del seguimiento</th>
                                                        <th>Cliente</th>
                                                        <th>Estado</th>
                                                        <th>Fecha de Creación</th>
                                                        <th>Pedido</th>
                                                        <th>Detalle</th>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<link href="../vendor/select2/dist/css/select2.min.css" rel="stylesheet" />
<script src="../vendor/select2/dist/js/select2.min.js"></script>
<script src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js" crossorigin="anonymous"></script>
<script src="https://cdn.datatables.net/1.10.22/js/dataTables.bootstrap4.min.js" crossorigin="anonymous"></script>
<script src="./js/sweetalert.js"></script>
<script src="../js/listSeguimiento.js"></script>
<?PHP
include("../includes/footer.php");
include("../bd/fin_conexion.php");
?>