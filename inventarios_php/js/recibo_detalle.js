$(document).ready(function () {

    $.ajax({
        url: './bd/recibo.php',
        type: 'GET',
        data: {
            quest: 'usuario'
        },
        success: function(idUsuario) {
            if(isNaN(idUsuario)) {
                window.location.href = '../login.php';
            }
        }
    })

    var id_recibo = sessionStorage.getItem('id_recibo');
    document.getElementById('id_recibo_foto').value = id_recibo;


    $.ajax({
        url: './bd/recibo.php',
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
                document.getElementById('monto_depositado').innerHTML = parseFloat(list.monto_deposito).toFixed(2);
                document.getElementById('justificacion').innerHTML = list.justificacion;     
                if (list.estado == 'pendiente de deposito') {
                    document.getElementById('datos_deposito').style.display = "none";
                } else {
                    document.getElementById('registro_deposito').style.display = "none";
                }    
            });
        }
    });

    $.ajax({
        url: './bd/recibo.php',
        type: 'GET',
        data: {
            quest: 'mostrar_img',
            id_imagen: id_recibo
        },
        success: function (res) {
            if (res != '') {
                document.getElementById('img').src = `./bd/recibo.php?quest=mostrar_img&id_imagen=${id_recibo}`;
            } else {
                document.getElementById('img').src = `./img/Econsa 404.jpg`;
            }
        }
    });
});

function cambiar_estado(estado) {

    var id_recibo = sessionStorage.getItem('id_recibo');

    $.ajax({
        url: './bd/recibo.php',
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
            } else {
                Swal.fire({
                    title: '¡Ha ocurrdio un problema!',
                    text: "Por favor tomar captura y comunicarse con el departamento de informática",
                    icon: 'error'
                });
            }
        }
    });
}

function guardar_banco() {
    var id_recibo = sessionStorage.getItem('id_recibo');
    var banco_ingresado = document.getElementById('banco_a_ingresar').value;
    var numero_de_boleta = document.getElementById('numero_boleta_banco').value;
    var fecha_de_deposito = document.getElementById('fecha_banco').value;
    var monto_del_banco = document.getElementById('monto_del_banco').value;
    $.ajax({
        url: './bd/recibo.php',
        type: 'POST',
        data: {
            quest: 'cambiar_datos_banco',
            banco_ingresado,
            numero_de_boleta,
            fecha_de_deposito,
            monto_del_banco,
            id_recibo
        },
        success: function (res) {
            if (res == 'Successfuly') {
                document.getElementById("update_picture").click();
                Swal.fire({
                    title: '¡Estado cambiado con éxito!',
                    text: "El recibo ahora tiene un nuevo estado, recargue la página para corroborarlo",
                    icon: 'success'
                });
            } else {
                Swal.fire({
                    title: '¡Ha ocurrdio un problema!',
                    text: "Por favor tomar captura y comunicarse con el departamento de informática",
                    icon: 'error'
                });
            }
        }
    });
}