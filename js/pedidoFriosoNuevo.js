var tiempoDetalle = 0

$(document).ready(function () {

    $.ajax({
        url: '../inventarios_php/bd/servidor.php',
        type: 'GET',
        data: {
            quest: 'usuario'
        },
        success: function (idUsuario) {
            if (isNaN(idUsuario)) {
                window.location.href = '../login.php';
            }
        }
    })
    correlativo();
});

function setearDatos(casilla, idCampo) {
    var id = document.getElementById(`casilla${casilla}`).value;
    document.getElementById(`cantidad${idCampo}`).disabled = false;
    document.getElementById(`precio${idCampo}`).disabled = false;
    $.ajax({
        url: '../inventarios_php/bd/servidor.php',
        type: 'GET',
        data: {
            quest: 'obtener_producto',
            id
        },
        success: function (res) {
            let lista = JSON.parse(res);
            lista.forEach(lista => {
                document.getElementById(`nombre${casilla}`).innerHTML = lista.nombre;

            });
        }
    })
}

function asignarValor() {
    for (let index = 1; index < 16; index++) {
        if (document.getElementById(`cantidad${index}`).value == "") {
            document.getElementById(`cantidad${index}`).value = "0";
        }
    }

    for (let index = 25; index < 30; index++) {
        if (document.getElementById(`cantidad${index}`).value == "") {
            document.getElementById(`cantidad${index}`).value = "0";
        }
    }

    for (let index = 1; index < 16; index++) {
        if (document.getElementById(`precio${index}`).value == "") {
            document.getElementById(`precio${index}`).value = "0";
        }
    }

    for (let index = 25; index < 30; index++) {
        if (document.getElementById(`precio${index}`).value == "") {
            document.getElementById(`precio${index}`).value = "0";
        }
    }

}

function calcularTotales() {
    asignarValor();
    document.getElementById('enviar').disabled = false;
    for (let index = 1; index < 16; index++) {
        document.getElementById(`total${index}`).innerHTML = (document.getElementById(`cantidad${index}`).value * document.getElementById(`precio${index}`).value);
    }
    for (let index = 25; index < 30; index++) {
        document.getElementById(`total${index}`).innerHTML = (document.getElementById(`cantidad${index}`).value * document.getElementById(`precio${index}`).value);
    }

    var cantidadTotal = 0;

    for (let index = 1; index < 16; index++) {
        cantidadTotal = cantidadTotal + parseInt(document.getElementById(`cantidad${index}`).value);
    }

    for (let index = 25; index < 30; index++) {
        cantidadTotal = cantidadTotal + parseInt(document.getElementById(`cantidad${index}`).value)
    }

    document.getElementById('cantidad').innerHTML = cantidadTotal

    var sumaTotales = 0.0;

    for (let index = 1; index < 16; index++) {
        sumaTotales = sumaTotales + parseFloat(document.getElementById(`total${index}`).innerHTML)
    }

    for (let index = 25; index < 30; index++) {
        sumaTotales = sumaTotales + parseFloat(document.getElementById(`total${index}`).innerHTML)
    }

    document.getElementById('total').innerHTML = sumaTotales

    calcularTiempoDetalle();
}

function calcularTotal() {
    asignarValor();
    var cantidadTotal = 0;

    for (let index = 1; index < 16; index++) {
        cantidadTotal = cantidadTotal + parseInt(document.getElementById(`cantidad${index}`).value)
    }

    for (let index = 25; index < 30; index++) {
        cantidadTotal = cantidadTotal + parseInt(document.getElementById(`cantidad${index}`).value)
    }

    document.getElementById('cantidad').innerHTML = cantidadTotal

    var sumaTotales = 0.0;

    for (let index = 1; index < 16; index++) {
        sumaTotales = sumaTotales + parseFloat(document.getElementById(`total${index}`).innerHTML)
    }

    for (let index = 25; index < 30; index++) {
        sumaTotales = sumaTotales + parseFloat(document.getElementById(`total${index}`).innerHTML)
    }

    document.getElementById('total').innerHTML = sumaTotales
}

function limpiarDatos(id) {
    document.getElementById(`cantidad${id}`).value = 0;
    document.getElementById(`total${id}`).innerHTML = 0;

    calcularTotal();
}

function limpiarDatosCombobox(casilla, id) {
    document.getElementById(`casilla${casilla}`).innerHTML = '';
    document.getElementById(`nombre${casilla}`).innerHTML = '';
    document.getElementById(`cantidad${id}`).value = 0;
    document.getElementById(`precio${id}`).value = 0;
    document.getElementById(`total${id}`).innerHTML = 0;
    document.getElementById(`cantidad${id}`).disabled = true;
    document.getElementById(`precio${id}`).disabled = true;
    calcularTotal();
}

function correlativo() {
    $.ajax({
        url: '../inventarios_php/bd/servidor.php',
        type: 'GET',
        data: {
            quest: 'obtener_correlativo'
        },
        success: function (res) {
            let lista = JSON.parse(res);
            lista.forEach(lista => {
                sessionStorage.setItem('correlativo', lista.correlativo);
            });
        }
    });
}

function agregarPedido() {
    var correlativo = sessionStorage.getItem('correlativo');
    var fecha_despacho = document.getElementById('fecha').value;
    var direccion = document.getElementById('direccion').value;
    var observacion = document.getElementById('observacion').value;
    var telefono = document.getElementById('telefono').value;
    var hora = document.getElementById('hora').value;
    var observacion_A = document.getElementById('observacion_A').value;
    var total = document.getElementById('total').innerHTML;
    var idVendedor = document.getElementById('idVendedor').value;
    var idTipoEntrega = document.getElementById('idTipoEntrega').value;
    var idCliente = document.getElementById('idCliente').value;

    if ((fecha_despacho && direccion && hora && total && idTipoEntrega && idCliente && idVendedor) == "") {
        Swal.fire({
            icon: 'error',
            title: 'Oopss...',
            text: 'A dejado algun campo vacio',
        })
    } else {
        const promesa_pedido = new Promise((resolve, reject) => {
            cargando();
            setTimeout(() => {
                $.ajax({
                    url: '../inventarios_php/bd/servidor.php',
                    type: 'POST',
                    data: {
                        quest: 'agregar_pedido_frioso',
                        correlativo,
                        fecha_despacho,
                        direccion,
                        observacion,
                        telefono,
                        hora,
                        observacion_A,
                        total,
                        idVendedor,
                        idTipoEntrega,
                        idCliente
                    },
                    success: function (resp) {
                        if (resp == 'Successfully') {
                            resolve('Successfully');
                        } else {
                            reject('No reject');
                        }
                    }
                })
            }, tiempoDetalle);
        });
        promesa_pedido.then(res => {
            if (res == 'Successfully') {
                calcularTiempoDetalle();
                for (let i = 1; i <= 15; i++) {
                    if (document.getElementById(`cantidad${i}`).value != 0 && document.getElementById(`precio${i}`).value != 0) {
                        var cantidad = document.getElementById(`cantidad${i}`).value;
                        var precio = document.getElementById(`precio${i}`).value;
                        var codigo = document.getElementById(`codigox${i}`).value;
                            $.ajax({
                                url: '../inventarios_php/bd/servidor.php',
                                type: 'POST',
                                data: {
                                    quest: 'agregar_detalle_pedido_frioso',
                                    cantidad: document.getElementById(`cantidad${i}`).value,
                                    precio: document.getElementById(`precio${i}`).value,
                                    id_producto: document.getElementById(`codigox${i}`).value
                                },
                                success: function (resp) {
                                    if (resp == 'Successfully') {
                                        console.log(resp);
                                    } else {
                                        reject('No reject');
                                    }
                                }
                            });
                    }
                }
                for (let i = 25; i < 30; i++) {
                    if (document.getElementById(`cantidad${i}`).value != 0 && document.getElementById(`precio${i}`).value != 0) {
                            $.ajax({
                                url: '../inventarios_php/bd/servidor.php',
                                type: 'POST',
                                data: {
                                    quest: 'agregar_detalle_pedido_frioso',
                                    cantidad: document.getElementById(`cantidad${i}`).value,
                                    precio: document.getElementById(`precio${i}`).value,
                                    id_producto: document.getElementById(`casilla${i - 24}`).value
                                },
                                success: function (resp) {
                                    if (resp == 'Successfully') {
                                    } else {
                                        reject('No reject');
                                    }
                                }
                            });
                    }
                }
                Swal.fire({
                    title: 'Ingresado Correctamente!',
                    text: 'Su pedido fue creado con éxito!',
                    icon: 'success',
                }).then((result) => {
                    window.location = "./Pedido_busqueda.php";
                })
            } else {
                Swal.fire({
                    title: 'Error Al Ingresar Pedido',
                    text: 'Ha ocurrido un error al ingresar pedido, por favor intentalo de nuevo',
                    icon: 'error',
                })
            }
        })
    }
}

function calcularTiempoDetalle() {
    var cantidadDetalle = 0
    for (let i = 1; i < 16; i++) {
        if (document.getElementById(`cantidad${i}`).value != 0 && document.getElementById(`precio${i}`).value != 0) {
            cantidadDetalle ++
        }
    }
    for (let i = 25; i < 30; i++) {
        if (document.getElementById(`cantidad${i}`).value != 0 && document.getElementById(`precio${i}`).value != 0) {
            cantidadDetalle ++
        }
    }
    milisegundos = (cantidadDetalle * 1000)/2
    tiempoDetalle = milisegundos
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
