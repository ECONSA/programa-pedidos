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
<script src="./js/sweetalert.js"></script>
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
                                <input type="text" class="form-control form-control-user" id="direccion" name="direccion" value="<?= $direccionCliente; ?>" placeholder="Dirección de entrega del pedido" required>
                                <br>
                                <input type="number" class="form-control form-control-user" id="telefono" name="telefono" placeholder="Teléfono de contacto" required>
                                <br>
                                <label>Fecha de despacho:</label>
                                <input type="date" class="form-control form-control-user" id="fecha" name="fecha" required>
                                <br>
                                <label>Hora de entrega:</label>
                                <input type="time" class="form-control form-control-user" id="hora" name="hora" required>
                                <br>
                                <input type="text" class="form-control form-control-user" id="observaciones" name="observaciones" placeholder="Observaciones">
                                <br>

                                <hr>
                                <h2 class="h6 text-gray-700 mb-4" align="left">Detalle del pedido:</h2>
                                <label>Codigo del producto:</label>
                                <br>
                                <select class="idProducto browser-default custom-select" id="idProducto" name="idProducto"></select>

                                <script type="text/javascript">
                                    $('.idProducto').select2({
                                        placeholder: 'Codigo del Producto',
                                        ajax: {
                                            url: 'productos.php',
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
                                <br>
                                <br>
                                <label>Cantidad:</label>
                                <input type="number" id="cantidad" min="0" value=1 class="form-control form-control-user" name="cantidad" placeholder="cantidad" required>
                                <br>
                                <label>Precio:</label>
                                <input type="number" id="precio" min="0" value=1 class="form-control form-control-user" name="precio" placeholder="cantidad" required>
                                <br>
                                <input type="text" class="form-control form-control-user" id="observacionesProducto" name="observacionesProducto" placeholder="Observaciones">
                                <br>
                                <button class="btn btn-warning" type="button" onclick="myFunction()">Ingresar Detalle</button>

                            </div>
                        </form>
                    </div>
                    <div class="p-5" style="overflow-x:auto;">
                        <table class="table table-striped table-bordered nowrap" id="dataTable" width="100%" align="center" cellspacing="0" data-role="datatable" data-info="false">
                            <thead>
                                <tr>
                                    <th>Producto</th>
                                    <th>Cantidad</th>
                                    <th>Precio</th>
                                    <th>Total</th>
                                    <th>Observaciones</th>
                                    <th><i class="fas fa-trash-alt"></i></th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                        <br>
                    </div>
                    <br>
                    <input type="text" class="form-control form-control-user" id="observacionesA" name="observacionesA" placeholder="Observaciones Adicionales">
                    <br>
                    <form action="../inventarios_php/bd/servidor.php" method="POST" enctype="multipart/form-data">
                        <label class="col-sm-2 control-label">Foto del recibo</label>
                        <input type="file" class="form-control-file" id="imagen" name="imagen">
                        <button type="submit" name="foto_pedido" id="foto_pedido" hidden class="btn btn-primary btn-block">Guardar</button>
                    </form>
                    <br>
                    <input id="btn_enviar" type="button" onclick="myFunction2()" class="btn btn-primary" value="Ingresar Pedido">
                    <br><br><br><br><br>
                </div>
            </div>
        </div>
    </div>
    <!-- Termina Formulario  -->
    <!-- End of Main Content -->
    <script>
        var elementos = new Array();
        var aux = 1;
        totalidad = 0;

        function myFunction() {
            var sel = document.getElementById('idProducto');
            var opt = sel.options[sel.selectedIndex];
            if (sel.value == "" || document.getElementById('cantidad').value == "" || document.getElementById('cantidad').value == "0" ||
                document.getElementById('precio').value == "" || document.getElementById('precio').value == "0") {
                Swal.fire({
                    icon: 'error',
                    title: 'Datos Inválidos',
                    text: 'Los datos que ha ingresado son inválidos, asegurese de haber escogido un código de producto',
                    footer: '<a href></a>'
                });
                return false;
            }
            var table = document.getElementById("dataTable").getElementsByTagName('tbody')[0];
            var row = table.insertRow(0);
            var cell1 = row.insertCell(0);
            var cell2 = row.insertCell(1);
            var cell3 = row.insertCell(2);
            var cell4 = row.insertCell(3);
            var cell5 = row.insertCell(4);
            var cell6 = row.insertCell(5);

            cell1.innerHTML = opt.text;
            cell2.innerHTML = document.getElementById('cantidad').value;
            cell3.innerHTML = document.getElementById('precio').value;
            cell4.innerHTML = (document.getElementById('cantidad').value * document.getElementById('precio').value);
            cell5.innerHTML = document.getElementById('observacionesProducto').value;
            cell6.innerHTML = '<input type="button" class="btn btn-danger" value="Eliminar componente" onclick="deleteRow(this, ' + aux + ', ' + (document.getElementById('cantidad').value * document.getElementById('precio').value) + ')"/>';

            elementos.push({
                idelemento: aux,
                idProducto: sel.value,
                cantidad: document.getElementById('cantidad').value,
                precio: document.getElementById('precio').value,
                total: (document.getElementById('cantidad').value * document.getElementById('precio').value),
                observacionesProducto: document.getElementById('observacionesProducto').value,
            });
            totalidad = totalidad + (document.getElementById('cantidad').value * document.getElementById('precio').value);
            aux++;
            console.log(totalidad);
        }

        var cont = 0;

        function myFunction2() {
            Swal.fire({
                title: 'Ingresar Pedido de Unhesa',
                text: "Seguro que desea añadir este pedido?",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, ingresar pedido!'
            }).then((result) => {
                ingresar_pedido(result);
            });
        }

        function ingresar_pedido(result) {
            if (result.isConfirmed) {
                if (document.getElementById("direccion").value == "" || document.getElementById("telefono").value == "" ||
                    document.getElementById("fecha").value == "" || document.getElementById("hora").value == "") {
                    Swal.fire({
                        icon: 'error',
                        title: 'Datos Inválidos',
                        text: 'Los datos que ha ingresado son inválidos, asegurese de haber escogido un código de producto',
                        footer: '<a href></a>'
                    });
                    return false;
                }
                var urlUsers = 'Pedido_i.php';
                var data = new FormData();

                data.append("fechaDespacho", document.getElementById("fecha").value);
                data.append("direccion", document.getElementById("direccion").value);
                data.append("telefono", document.getElementById("telefono").value);
                data.append("observacion", document.getElementById("observaciones").value);
                data.append("hora", document.getElementById("hora").value);
                data.append("observacionesA", document.getElementById("observacionesA").value);
                data.append("total", totalidad);
                data.append("idVendedor", document.getElementById("idVendedor").value);
                data.append("idTipoEntrega", document.getElementById("idTipoEntrega").value);
                data.append("idCliente", document.getElementById("idCliente").value);
                data.append("tablita", JSON.stringify(elementos));
                if (cont != 0) {
                    return;
                } else {
                    axios.post(urlUsers, data).then(response => {
                        cont++;
                        if (response.status == 200) {
                            if (response.data == "funciono") {
                                document.getElementById("foto_pedido").click();
                            }
                        }
                    });
                }
                let timerInterval
                Swal.fire({
                    title: 'Espere...',
                    html: 'Ingresando Pedido',
                    timer: 3000,
                    timerProgressBar: true,
                    didOpen: () => {
                        Swal.showLoading()
                        const b = Swal.getHtmlContainer().querySelector('b')
                        timerInterval = setInterval(() => {
                            b.textContent = Swal.getTimerLeft()
                        }, 100)
                    },
                    willClose: () => {
                        clearInterval(timerInterval)
                    }
                }).then((result) => {
                    /* Read more about handling dismissals below */
                    if (result.dismiss === Swal.DismissReason.timer) {
                        console.log('I was closed by the timer')
                    }
                })
                setTimeout(function() {
                    window.location.href = './Pedido_busqueda.php';
                }, 3000)

            }
        }

        function deleteRow(btn, aux, total2) {
            var index = elementos.map(function(e) {
                return e.idelemento;
            }).indexOf(aux);
            if (index > -1) {
                elementos.splice(index, 1);
            }
            var row = btn.parentNode.parentNode;
            //console.log(row);
            row.parentNode.removeChild(row);
            totalidad = totalidad - total2;
            console.log(elementos);
            console.log(totalidad);

        }
    </script>
    <?PHP
    include("../includes/footersindatatable.php");
    include("../bd/fin_conexion.php");
    ?>