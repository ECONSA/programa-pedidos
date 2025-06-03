<?PHP
include("../bd/inicia_conexion.php");
include("../includes/header.php");
?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
<link href="./js/select2.min.css" rel="stylesheet" />
<script src="./js/select2.min.js"></script>
<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h1 mb-4 text-gray-800 text-center">Agregar Pedido Unhesa</h1>
    

</div>
<!-- /.container-fluid -->

<!-- Inicia Formulario  -->
<div>
    <div class="card shadow-lg">
        <div class="card-body">
            <!-- Nested Row within Card Body -->
            <div class="row" align="center">
                <div class="col-lg">
                    <div class="p-5">
                        <div class="text-center">
                            <h1 class="h5 text-gray-900">Por favor indique el codigo del cliente:</h1>
                        </div>
                    </div>
                    <form name="datos" method="post" action="AddPedido.php" onsubmit="return verifica_codigo(this);">
                        <div class="p-3">
                            <label>Codigo del Cliente:</label>
                            <br>
                            <select class="idCliente browser-default custom-select" name="idCliente"></select>
                        </div>
                            <script type="text/javascript">
                                $('.idCliente').select2({
                                    placeholder: 'Codigo del cliente',
                                    ajax: {
                                        url: 'ajax.php',
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
                        <input type="submit" class="btn btn-primary" value="Seleccionar Cliente">
                        <br>
                        <br>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
<!-- Termina Formulario  -->
<!-- SCRIPT -->
<script type="text/javascript">
    function verifica_codigo(pform) {
        if (pform.idCliente.value == "") {
            Swal.fire({
                icon: 'error',
                title: 'Cliente Inválido',
                text: 'Porfavor seleccione un cliente válido'
            });
            return false;
            pform.idCliente.focus();
        }
    }

    function sweet() {
        Swal.fire({
            icon: 'success',
            title: 'Great',
            text: 'Every is good',
            footer: '<a href></a>'
        });
    }
</script>
<!-- End of Main Content -->
<?PHP
include("../includes/footer.php");
include("../bd/fin_conexion.php");
?>