<?PHP
include("../bd/inicia_conexion.php");
include("../includes/header.php");
$sql = "select * from clienteproquima";
$sql = $sql . " where idCliente = " . $_POST["idCliente"];
$resultado = mysqli_query($con, $sql);
while ($fila = mysqli_fetch_array($resultado)) {
    $nombreCliente = $fila["nombre"];
    $codigoCliente = $fila["codigo"];
    $direccionF = $fila["direccion_factura"];
    $direccionE = $fila["direccion_entrega"];
    $contactoCliente = $fila["contacto"];
    $telefonoCliente = $fila["telefono"];
    $correoCliente = $fila["correo"];
    $credito = $fila["credito"];
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

th, td {
  text-align: left;
  padding: 8px;
}

tr:nth-child(even){background-color: #f2f2f2}
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
                                <input type="hidden" id="idCliente" value="<?=$_POST["idCliente"];?>">
                                <input type="hidden" id="idVendedor" value="<?=$id?>">
                                <label>Codigo:</label>
                                <input type="text" class="form-control form-control-user" id="codigo" name="codigo" value="<?=$codigoCliente;?>" disabled required>
                                <br>
                                <label>Cliente:</label>
                                <input type="text" class="form-control form-control-user" id="cliente" name="cliente" value="<?=$nombreCliente;?>" disabled required>
                                <br>
                                <label>Dirección de Factura:</label>
                                <input type="text" class="form-control form-control-user" id="direccionFactura" name="direccionFactura" value="<?=$direccionF;?>">
                                <br>
                                <label>Dirección de Entrega:</label>
                                <input type="text" class="form-control form-control-user" id="direccionEntrega" name="direccionEntrega" value="<?=$direccionE;?>">
                                <br>
                                <label>Contácto Cliente:</label>
                                <input type="text" class="form-control form-control-user" id="contactoCliente" name="contactoCliente" value="<?=$contactoCliente;?>">
                                <br>
                                <label>Teléfono Cliente:</label>
                                <input type="number" class="form-control form-control-user" id="telefonoCliente" name="telefonoCliente" value="<?=$telefonoCliente;?>">
                                <br>
                                <label>Correo Cliente</label>
                                <input type="text" class="form-control form-control-user" id="correoCliente" name="correoCliente" value="<?=$correoCliente;?>">
                                <br>
                                <label>Días de Crédito</label>
                                <input type="text" class="form-control form-control-user" id="credito" name="credito" value="<?=$credito;?>">
                                <br>
                                <br>
                                <label>Horarios:</label>
                                <input type="text" class="form-control form-control-user" id="horarioDias" name="horarioDias" placeholder="Días de entrega" required>
                                <br>
                                <input type="text" class="form-control form-control-user" id="horarioHora" name="horarioHora" placeholder="Hora de entrega" required>
                                <br>
                                <label>Transporte</label>
                                <br>
                                <select class="browser-default custom-select" id="idTransporte" name="idTransporte">
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
                                <label>Contácto Bodega</label>
                                <input type="text" class="form-control form-control-user" id="contactoBodega" name="contactoBodega" placeholder="contácto" required>
                                <br>
                                <label>Teléfono Bodega</label>
                                <input type="number" class="form-control form-control-user" id="telefonoBodega" name="telefonoBodega" placeholder="teléfono" >
                                <br>
                                <label>Fecha de Despacho</label>
                                <input type="date" class="form-control form-control-user" id="fechaDespacho" name="fechaDespacho" placeholder="fechaDespacho" >
                                <br>
                                <label>Observacion Especial del Cliente</label> 
                                <input type="text" class="form-control form-control-user" id="observacionEspecial" name="observacionEspecial" placeholder="Observacion Especial del Cliente" >
                                <br>
                                <label>Agregar en comentarios</label>
                                <input type="text" class="form-control form-control-user" id="observacion" name="observacion" placeholder="Agregar en comentarios" >
                                <br>
                                <hr>
                                <h2 class="h6 text-gray-700 mb-4" align="left">Detalle del pedido:</h2>
                                <label>Codigo del producto:</label>
                                <br>
                                <div class="row">
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
                                </div>
                                <br>
                                <br>
                                <label>Cantidad:</label>
                                <input type="number" id="cantidad" min="0" value=1 class="form-control form-control-user" name="cantidad" placeholder="cantidad" required>
                                <br>

                                <label>Precio:</label>
                                <div class="row">
                                
                                    <!-- The second value will be selected initially -->
                                    <select class="moneda browser-default custom-select col-md-1" id="moneda" name="moneda">
                                        <option value="Q">Q</option> 
                                        <option value="$">$</option>
                                    </select>
                                    
                                    <input type="number" id="precio" min="0" value=1 class="form-control form-control-user col-md-11" name="precio" placeholder="cantidad" required>

                                </div>
                                <br>
                                <label>Observacion:</label>
                                <input type="text" class="form-control form-control-user" id="observacionesProducto" name="observacionesProducto" placeholder="Observaciones">
                                <br>
                                <button class="btn btn-warning" type="button" onclick="myFunction()">Ingresar Detalle</button>

                            </div>
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

                    </form>
                    </div>
                    <br>
                    <input type="text" class="form-control form-control-user" id="observacionesA" name="observacionesA" placeholder="Observaciones Adicionales" >
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
            if (sel.value == "" || document.getElementById('cantidad').value == "" || document.getElementById('cantidad').value == "0"
                || document.getElementById('precio').value == "" || document.getElementById('precio').value == "0" || document.getElementById('moneda').value == "")
            {
                Swal.fire({
                    icon: 'error',
                    title: 'Datos Inválidos',
                    text: 'Los datos que ha ingresado son inválidos, asegurese de haber escogido un código de producto',
                    footer: '<a href></a>'
                });4
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
            cell3.innerHTML = document.getElementById('moneda').value + '. ' + document.getElementById('precio').value;
            cell4.innerHTML = document.getElementById('moneda').value + '. ' + (document.getElementById('cantidad').value * document.getElementById('precio').value);
            cell5.innerHTML = document.getElementById('observacionesProducto').value;
            cell6.innerHTML = '<input type="button" class="btn btn-danger" value="Eliminar componente" onclick="deleteRow(this, ' + aux + ', '+(document.getElementById('cantidad').value * document.getElementById('precio').value)+')"/>';

            elementos.push({
                idelemento: aux,
                idProducto: sel.value,
                cantidad: document.getElementById('cantidad').value,
                precio : document.getElementById('precio').value,
                total : (document.getElementById('cantidad').value * document.getElementById('precio').value),
                observacionesProducto: document.getElementById('observacionesProducto').value,
                moneda: document.getElementById('moneda').value
            });

            totalidad = totalidad + (document.getElementById('cantidad').value * document.getElementById('precio').value);
            aux++;

        }

        function myFunction2() {

            var transporte = document.getElementById('idTransporte');
            var selectTransporte = transporte.options[transporte.selectedIndex];
            if (document.getElementById("fechaDespacho").value == "" ||
                document.getElementById("direccionFactura").value == "" || 
                document.getElementById("direccionEntrega").value == "" ||
                selectTransporte.value == ""
                ) {
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

            data.append("fecha_despacho", document.getElementById("fechaDespacho").value);
            data.append("direccion_factura", document.getElementById("direccionFactura").value);
            data.append("direccion_entrega", document.getElementById("direccionEntrega").value);
            data.append("dias", document.getElementById("horarioDias").value);
            data.append("horario", document.getElementById("horarioHora").value);
            data.append("observacion", document.getElementById("observacion").value);
            data.append("observacion_especial", document.getElementById("observacionEspecial").value);
            data.append("contacto_cliente", document.getElementById("contactoCliente").value);
            data.append("telefono_cliente", document.getElementById("telefonoCliente").value);
            data.append("contacto_bodega", document.getElementById("contactoBodega").value);
            data.append("telefono_bodega", document.getElementById("telefonoBodega").value);
            data.append("correo", document.getElementById("correoCliente").value);
            data.append("credito", document.getElementById("credito").value);
            data.append("total", totalidad);
            data.append("idVendedor", document.getElementById("idVendedor").value);
            data.append("idTransporte", document.getElementById("idTransporte").value);
            data.append("idCliente", document.getElementById("idCliente").value);
            data.append("tablita", JSON.stringify(elementos));
            
            axios.post(urlUsers, data).then(response => {
                console.log(response);
                if (response.status == 200) {
                    if (response.data == "funciono") {
                        window.location.href = "/programa_pedidos/Pedidos_Proquima/ListPedidos.php?variable=1";
                    }
                }
            });
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