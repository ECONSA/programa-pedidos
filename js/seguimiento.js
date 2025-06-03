$(document).ready(function () {
    $.ajax({
        url: './inventarios_php/bd/servidor.php',
        type: 'GET',
        data: {
            quest: 'usuario'
        },
        success: function (idUsuario) {
            if (isNaN(idUsuario)) {
                window.location.href = './login.php';
            }
        }
    })

    lista_cliente();
    mostrarInputIdPedido();
});

function mostrarInputIdPedido() {
    var tipoSeguimiento = document.getElementById('idStatus').value
    var inputIDPedido = document.getElementById('idPedidoUnhesa')
    var lblInputIDPedido = document.getElementById('lblIdPedidoUnhesa')
    var brInputIDPedido = document.getElementById('brIdPedidoUnhesa')
    var inputProximoContacto = document.getElementById('proximoContacto')

    if (tipoSeguimiento == "7" || tipoSeguimiento == "8") {
        inputIDPedido.hidden = false;
        lblInputIDPedido.hidden = false;
        brInputIDPedido.hidden = false;
        inputProximoContacto.value = new Date().toISOString().split('T')[0].slice(0, 10);
    } else {
        inputIDPedido.hidden = true;
        lblInputIDPedido.hidden = true;
        brInputIDPedido.hidden = true;
    }
}

function limpiarInput() {
    document.getElementById('observaciones').value = "";
    document.getElementById('proximoContacto').value = "";
    mostrarInputIdPedido();
}

function lista_cliente() {
    var vendedor = document.getElementById('idVendedor').value;
    $.ajax({

        url: '../inventarios_php/bd/servidor.php',
        type: 'GET',
        data: {
            quest: 'ver_clientes_seguimiento',
            vendedor
        },
        success: function (res) {
            var template = '';
            let lista = JSON.parse(res);
            lista.forEach(lista => {
                template += `<option value="${lista.id}">${lista.nombre}</option>`;
            });
            document.getElementById('id_cliente_online').innerHTML = template;
            $('.js-example-basic-single').select2();
        }
    });
}

function buscar_cliente() {
    var vendedor = document.getElementById('idVendedor').value;
    $.ajax({
        url: '../inventarios_php/bd/servidor.php',
        type: 'GET',
        data: {
            quest: 'buscar_clientes_seguimiento',
            nombre: document.getElementById('browser').value,
            vendedor
        },
        success: function (res) {
            if (res != 'No') {
                let lista = JSON.parse(res);
                lista.forEach(lista => {
                    document.getElementById('id_cliente_online').value = lista.id;
                });
            } else {
                document.getElementById('id_cliente_online').value = '';
            }
        }
    });
}

function validarIDPedido() {
    var idPedido = document.getElementById('idPedidoUnhesa').value
    var tipoSeguimiento = document.getElementById('idStatus').value
    if(tipoSeguimiento == "7" || tipoSeguimiento == "8") {
        if (idPedido != "") {
            $.ajax({
                url: '../inventarios_php/bd/servidor.php',
                type: 'GET',
                data: {
                    quest: 'validar_id_pedido',
                    idPedido
                },
                success: function (res) {
                    if(res != 'Si'){
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'El pedido ingresado no existe',
                        })
                        return;
                    } else {
                        validarProximoContacto()
                    }
                }
            });
        } else {
            Swal.fire({
                icon: 'warning',
                title: 'ID Pedido Vacio',
                text: "A dejado el campo ID Pedido vacio",
            })
        }
    } else {
        validarProximoContacto()
    }
    
}


function validarProximoContacto() {
    var proximoContacto = document.getElementById('proximoContacto').value;
    var mesCreacion = moment().month()+1;
    var mesProximoContacto = moment(proximoContacto).format('MM');
    var anoCreacion = moment().year();
    var anoProximoContacto = moment(proximoContacto).format('YYYY');
    if(proximoContacto != "") {
        if(mesProximoContacto != mesCreacion || anoProximoContacto != anoCreacion) {
            Swal.fire({
                icon: 'warning',
                title: 'Proximo Contacto Invalido',
                text: 'El mes y año del proximo contacto deben ser igual a la fecha de creación',
            })
            return;
        } else {
            validarProximoC()
        }
    } else {
        validarProximoC()
    }
   
}

function validarProximoC() {
    var proximoContacto = document.getElementById('proximoContacto').value
    if(proximoContacto == "") {
        Swal.fire({
            icon: 'warning',
            title: 'Proximo Contacto Vacio',
            text: "A dejado el campo de proximo contacto vacio, ¿Desea Continuar?",
            confirmButtonText: "Continuar",
            showDenyButton: true,
            denyButtonText: "Cancelar",
            showCloseButton: false
        }).then((result) => {
            if(result.isConfirmed) {
                validarObservacion();
            }
        })
    } else {
        validarObservacion();
    }
}

function validarObservacion() {
    var obs = document.getElementById('observaciones').value
    if(obs == "") {
        Swal.fire({
            icon: 'warning',
            title: 'Observacion Vacia',
            text: "A dejado el campo de observación vacio, ¿Desea Continuar?",
            confirmButtonText: "Continuar",
            showDenyButton: true,
            denyButtonText: "Cancelar",
            showCloseButton: false
        }).then((result) => {
            if(result.isConfirmed) {
                guardar_seguimiento();
            }
        })
    } else{
        guardar_seguimiento();
    }
}

function guardar_seguimiento() {
    var cliente = document.getElementById('id_cliente_online').value;
    var vendedor = document.getElementById('idVendedor').value;
    var estatus = document.getElementById('idStatus').value;
    var observaciones = document.getElementById('observaciones').value;
    var fecha = 'now()';
    var pedido = document.getElementById('idPedidoUnhesa').value;
    var proximoContacto = document.getElementById('proximoContacto').value;
    if (pedido == '') {
        pedido = "null";
        $.ajax({
            url: '../inventarios_php/bd/servidor.php',
            type: 'POST',
            data: {
                quest: 'guardar_seguimiento',
                cliente,
                vendedor,
                estatus,
                observaciones,
                fecha,
                pedido,
                proximoContacto
            },
            success: function (res) {
                if (res == 'Success') {
                    ingresar_historial_seguimiento(estatus, observaciones)
                } else {
                    Swal.fire(
                        'Falló',
                        'El seguimiento del cliente no ha sido ingresado correctamente',
                        'error'
                    )
                }
            }
        });
    } else {
        $.ajax({
            url: '../inventarios_php/bd/servidor.php',
            type: 'POST',
            data: {
                quest: 'guardar_seguimiento',
                cliente,
                vendedor,
                estatus,
                observaciones,
                fecha,
                pedido,
                proximoContacto
            },
            success: function (res) {
                if (res == 'Success') {
                    ingresar_historial_seguimiento(estatus, observaciones)
                } else {
                    Swal.fire(
                        'Falló',
                        'El seguimiento del cliente no ha sido ingresado correctamente',
                        'error'
                    )
                }
            }
        });
    }
}

function ingresar_historial_seguimiento(estado, observaciones) {
    return new Promise(resolve => {
        cargando();
        setTimeout(() => {
            $.ajax({
                url: '../inventarios_php/bd/servidor.php',
                type: 'POST',
                data: {
                    quest: 'ingresar_historial_seguimiento',
                    estado,
                    observaciones
                },
                success: function (res) {
                    if (res == "Successfuly") {
                        Swal.fire({
                            title: 'Ingresado',
                            text: 'El seguimiento ha sido ingresado',
                            icon: 'success',
                            confirmButtonText: 'Ok'
                        }).then(() => {
                            resolve(window.location.href = './ListSeguimiento.php')
                        });
                    } else {
                    }
                }
            });
        }, 3000)

    })
}

function cargando() {
    Swal.fire({
        title: 'Procesando...',
        html: 'Esto puede demorar unos momentos',
        allowOutsideClick: false,
        showConfirmButton: false,
        willOpen: () => {
            Swal.showLoading()
        }
    });
}