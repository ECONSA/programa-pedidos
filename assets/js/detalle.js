$(document).ready(function () {
    var usuario = sessionStorage.getItem('usuario');
    var lista = JSON.parse(sessionStorage.getItem('usuario'));

    if (!usuario) {
        window.location.href = './auth-login-basic.html';
    }

    var id_recibo = sessionStorage.getItem('id_recibo');

    $.ajax({
        url: './php/servidor.php',
        type: 'GET',
        data: {
            quest: 'detalle_del_recibo',
            id_recibo
        },
        success: function (res) {
            let list = JSON.parse(res);
            let template = '';
            list.forEach(list => {
                document.getElementById('vendedor').innerHTML = list.id_usuario;
                document.getElementById('cliente').innerHTML = list.id_cliente;
                document.getElementById('estado').innerHTML = list.estado;
                document.getElementById('numero_recibo').innerHTML = list.recibo;
                document.getElementById('banco').innerHTML = list.banco;
                document.getElementById('numero_boleta').innerHTML = list.numero_boleta;
                document.getElementById('fecha_deposito').innerHTML = list.fecha_deposito;
                document.getElementById('monto_depositado').innerHTML = list.monto_deposito;
                if (list.estado == 'recibido en contabilidad') {
                    $("#botones").html(`
                        <div class="col-xl-6 col-md-6 mb-4">
                            <button type="button" class="btn btn-success btn-block"
                                onclick="cambiar_estado('ingresado a sap')">Ingresar a SAP</button>
                        </div>
                        <div class="col-xl-6 col-md-6 mb-4">
                            <button type="button" class="btn btn-danger btn-block"
                                onclick="cambiar_estado('rechazado')">Rechazar</button>
                        </div>
                    `);
                }
            });
        }
    });
    $.ajax({
        url: './php/servidor.php',
        type: 'GET',
        data: {
            quest: 'mostrar_img',
            id_imagen: id_recibo
        },
        success: function (res) {
            if (res != '') {
                document.getElementById('img').src = `./php/servidor.php?quest=mostrar_img&id_imagen=${id_recibo}`;
            }else{
                document.getElementById('img').src = `./assets/img/Econsa 404.jpg`;
            }
        }
    });
});
function cambiar_estado(estado){
    var id_recibo = sessionStorage.getItem('id_recibo');
    $.ajax({
        url: './php/servidor.php',
        type: 'POST',
        data: {
            quest: 'cambiar_estado',
            estado,
            id_recibo
        },
        success: function (res) {
            if (res == 'Successfully') {
                Swal.fire({
                    title: '¡Estado cambiado con éxito!',
                    text: "El recibo ahora tiene un nuevo estado, recargue la página para corroborarlo",
                    icon: 'success'
                });
            }else{
                Swal.fire({
                    title: '¡Ha ocurrdio un problema!',
                    text: "Por favor tomar captura y comunicarse con el departamento de informática",
                    icon: 'error'
                });
            }
        }
    });
}