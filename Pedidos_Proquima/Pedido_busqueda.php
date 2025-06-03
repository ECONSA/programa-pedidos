<?PHP
include("../bd/inicia_conexion.php");
include("../includes/header.php");
?>
        <!-- Begin Page Content -->
        <div class="container-fluid">

          <!-- Page Heading -->
          <h1 class="h3 mb-4 text-gray-800 text-center">Buscar Pedido</h1>

        </div>
        <!-- /.container-fluid -->

        <!-- Inicia Formulario  -->
        <div>
            <div class="card o-hidden border-0 shadow-lg my-5">
            <div class="card-body p-0">
                <!-- Nested Row within Card Body -->
                <div class="row width="100%" align="center">
                    <div class="col-lg">
                        <div class="p-5">
                        <div class="text">
                            <h1 class="h5 text-gray-900 mb-4">Ingrese los datos a buscar...</h1>
                        </div>
                        <form name="datos" method="post" action="ListPedidos.php" onsubmit="return verifica_formulario(this);">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Fecha minima:</label>
                                    <input type="date" class="form-control form-control-user" name="minimaFecha" value="2020-01-01">
                                </div>
                                <br>
                                <div class="form-group">
                                    <label>Fecha Maxima:</label>
                                    <input type="date" class="form-control form-control-user" name="maximaFecha" value="<?php echo date('Y-m-d', strtotime('+1 day')); ?>">
                                </div>
                                <br>
                                <input type="submit" class="btn btn-primary" value="Buscar">
                            </div>    
                        </form>               
                    </div>
                </div>
            </div>
            </div>

        </div>
        <!-- Termina Formulario  -->

       

      <!-- End of Main Content -->
      <script type="text/javascript">

        function verifica_formulario(pform) {
        if (pform.maximaFecha.value < pform.minimaFecha.value) {
                Swal.fire({
                    icon: 'error',
                    title: 'Rango de fechas Inválido',
                    text: 'Porfavor seleccione un rango de fecha válido'
                });
                pform.maximaFecha.focus();
                return false;
            }
            return true;
        }
        </script>
<?PHP
include("../includes/footer.php");
include("../bd/fin_conexion.php");
?>

