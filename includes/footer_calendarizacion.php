<!-- Modal Detalle Cita -->
<div id="modalDetalleCita" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h3>Detalle De La Cita</h3>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body container">
        <label for="cliente">Cliente:</label>
        <h5 id="cliente"></h5>
        <br>
        <label for="estado">Estado:</label>
        <h5 id="estado"></h5>
        <br>
        <label for="vendedor">Vendedor:</label>
        <h5 id="vendedor"></h5>
        <br>
        <label for="descripcion">Descripción:</label>
        <h5 id="descripcion"></h5>
        <br>
        <label for="fechaC">Fecha Creación:</label>
        <h5 id="fechaC"></h5>
        <br>
        <label for="fechaP">Proximo Contacto:</label>
        <h5 id="fechaP"></h5>
      </div>
      <div class="modal-footer">
      <button type="button" class="btn btn-primary" onclick="botonEditar()">Editar</button>
      </div>
    </div>
  </div>
</div>

<!-- Footer -->
<footer class="sticky-footer bg-white">
  <div class="container my-auto">
    <div class="copyright text-center my-auto">
      <span> Sistema de Pedidos.<br /> Departamento de Informática 2021 GRUPO ECONSA.</span>
    </div>
  </div>
</footer>
<!-- End of Footer -->

</div>
<!-- End of Content Wrapper -->
</div>
<!-- End of Page Wrapper -->

<!-- Scroll to Top Button-->
<a class="scroll-to-top rounded" href="#page-top">
  <i class="fas fa-angle-up"></i>
</a>

<!-- Logout Modal-->
<div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Desea cerrar sesión?</h5>
        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
      </div>
      <div class="modal-body">Seleccione salir si desea cerrar sesión</div>
      <div class="modal-footer">
        <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
        <a class="btn btn-primary" href="../logout.php">Salir</a>
      </div>
    </div>
  </div>
</div>

<!-- Bootstrap core JavaScript-->
<script src="../vendor/jquery/jquery.min.js"></script>
<script src='http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.5/jquery-ui.min.js'></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.min.js" crossorigin="anonymous"></script>

<script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

<!-- Core plugin JavaScript-->
<script src="../vendor/jquery-easing/jquery.easing.min.js"></script>

<!-- Custom scripts for all pages-->
<script src="../js/sb-admin-2.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

<script src="https://code.jquery.com/jquery-3.5.1.min.js" crossorigin="anonymous"></script>
<script src="../evo-calendar/js/evo-calendar.js"></script>
<script src="../js/calendarizacion.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>

</body>


</html>