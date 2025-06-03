$(document).ready(function () {

    $.ajax({
        url: './bd/servidor.php',
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

    $.ajax({
        url: './bd/servidor.php',
        type: 'GET',
        data: {
            quest: 'nombre_usuario'
        },
        success: function (res) {
            var nombre = res;
            document.getElementById('nombre_del_usuario').innerHTML = nombre;
        }
    });

    $.ajax({
        url: './bd/servidor.php',
        type: 'GET',
        data: {
            quest: 'usuario'
        },
        success: function (idUsuario) {
            $.ajax({
                url: './bd/servidor.php',
                type: 'GET',
                data: {
                    quest: 'empresa_usuario',
                    id_usuario: idUsuario
                },
                success: function (resp) {
                    let lista = JSON.parse(resp);
                    lista.forEach(lista => {
                        if (lista.id_empresa == 1) {
                            document.getElementById("accordionSidebar").className = "navbar-nav bg-gradient-danger sidebar sidebar-dark accordion toggled";
                        } else {
                            document.getElementById("accordionSidebar").className = "navbar-nav bg-gradient-primary sidebar sidebar-dark accordion toggled";
                        }
                        4
                    });
                }
            });
        }
    });

    $.ajax({
        url: './bd/servidor.php',
        type: 'GET',
        data: {
            quest: 'ver_clientes_online'
        },
        success: function (res) {
            var template = '';
            let lista = JSON.parse(res);
            lista.forEach(lista => {
                template += `<option value="${lista.nombre}">`;
            });
            document.getElementById('browsers').innerHTML = template;
            $('#datatable_1').DataTable();
        }
    });
});

function guardar() {
    if (validar_datos() == "si") {
        clientes();
        insertar_pedido();
    }
}

function validar_datos() {
    var contador = 0;
    for (let i = 1; i < 19; i++) {
        if (document.getElementById(`cantidad${i}`).value > 0) {
            if (document.getElementById(`precio${i}`).value <= 0) {
                Swal.fire({
                    title: 'Espere!',
                    text: "El precio de una casilla que tiene cantidad, es 0 o menor que 0",
                    icon: 'warning'
                });
                return;
            } else {
                contador += 1;
            }
        } else if (document.getElementById(`cantidad${i}`).value < 0) {
            Swal.fire({
                title: 'Números negativos',
                text: "No puedes ingresar números negativos en cantidad o precio",
                icon: 'warning'
            });
            return;
        }
    }
    if (contador == 0) {
        Swal.fire({
            title: 'No hay ningún detalle',
            text: "Este pedido no cuenta con ningún producto a vender!",
            icon: 'warning'
        });
        return;
    }
    var nombre = document.getElementById('browser').value;
    var direccion = document.getElementById('direccion').value;
    var telefono = document.getElementById('telefono').value;
    var nombre_factura = document.getElementById('nombre_factura').value;
    var nit = document.getElementById('nit').value;
    var sticker = document.getElementById('sticker').value;
    var observacion = document.getElementById('observacion').value;
    var fecha_entrega = document.getElementById('fecha_entrega').value;
    var servicio = document.getElementById('servicio').value;
    if (nombre == '') {
        Swal.fire({
            title: 'Nombre',
            text: "El nombre no puede estar vacío.",
            icon: 'info'
        });
    }
    else if (direccion == '') {
        Swal.fire({
            title: 'Dirección',
            text: "La dirección no puede estar vacía.",
            icon: 'info'
        });
    }
    else if (telefono == '') {
        Swal.fire({
            title: 'Teléfono',
            text: "El número telefónico no puede estar vacío.",
            icon: 'info'
        });
    }
    else if (nombre_factura == '') {
        Swal.fire({
            title: 'Dirección',
            text: "La dirección no puede estar vacía.",
            icon: 'info'
        });
    }
    else if (nit == '') {
        Swal.fire({
            title: 'Nit',
            text: "El NIT no puede estar vacío.",
            icon: 'info'
        });
    }
    else if (sticker == '') {
        Swal.fire({
            title: 'Sticker',
            text: "El Sticker no puede estar vacío.",
            icon: 'info'
        });
    }
    else if (observacion == '') {
        Swal.fire({
            title: 'Observación',
            text: "La observación no puede estar vacía.",
            icon: 'info'
        });
    }
    else if (fecha_entrega == '') {
        Swal.fire({
            title: 'Fecha de estrenga',
            text: "La fecha de entrega no puede estar vacía.",
            icon: 'info'
        });
    }
    else if (servicio == '') {
        Swal.fire({
            title: 'Servicio',
            text: "El servicio no puede estar vacío.",
            icon: 'info'
        });
    } else {
        return 'si';
    }
}

function clientes() {
    var id_cliente = document.getElementById('id_cliente_online').value;
    var nombre = document.getElementById('browser').value;
    var direccion = document.getElementById('direccion').value;
    var telefono = document.getElementById('telefono').value;
    if (id_cliente == '') {
        $.ajax({
            url: './bd/servidor.php',
            type: 'POST',
            data: {
                quest: 'ingresar_cliente_online',
                nombre,
                direccion,
                telefono
            },
            success: function (res) {
            }
        });
    } else {
        $.ajax({
            url: './bd/servidor.php',
            type: 'POST',
            data: {
                quest: 'modificar_telefono_cliente',
                id_cliente,
                telefono
            },
            success: function (res) {
                console.log(res);
            }
        });
    }
    return true;
}

function insertar_pedido() {

    var nombre = document.getElementById('browser').value;
    var direccion = document.getElementById('direccion').value;
    var telefono = document.getElementById('telefono').value;
    var nombre_factura = document.getElementById('nombre_factura').value;
    var nit = document.getElementById('nit').value;
    var sticker = document.getElementById('sticker').value;
    var observacion = document.getElementById('observacion').value;
    var fecha_entrega = document.getElementById('fecha_entrega').value;
    var servicio = document.getElementById('servicio').value;
    var id_cliente = document.getElementById('id_cliente_online').value;

    $.ajax({
        url: './bd/servidor.php',
        type: 'GET',
        data: {
            quest: 'usuario'
        },
        success: function (idUsuario) {
            $.ajax({
                url: './bd/servidor.php',
                type: 'POST',
                data: {
                    quest: 'insertar_pedido_online',
                    nombre,
                    direccion,
                    telefono,
                    nombre_factura,
                    nit,
                    sticker,
                    servicio,
                    observacion,
                    fecha_entrega,
                    id_cliente,
                    id_cliente_sap: sessionStorage.getItem('opcion_online'),
                    idUsuario
                },
                success: function (res) {
                    console.log(res);
                    if (res != 'No') {
                        for (let i = 1; i < 19; i++) {
                            if (document.getElementById(`cantidad${i}`).value > 0) {
                                if (document.getElementById(`precio${i}`).value <= 0) {
                                    Swal.fire({
                                        title: 'Espere!',
                                        text: "El precio de una casilla que tiene cantidad, es 0 o menor que 0",
                                        icon: 'warning'
                                    });
                                } else {
                                    $.ajax({
                                        url: './bd/servidor.php',
                                        type: 'POST',
                                        data: {
                                            quest: 'insertar_detalle_pedido_online',
                                            id_producto: document.getElementById(`idProducto${i}`).value,
                                            precio: document.getElementById(`precio${i}`).value,
                                            cantidad: document.getElementById(`cantidad${i}`).value,
                                            id_pedido: res
                                        },
                                        success: function (response) {
                                            console.log(response);
                                            if (response != 'Successfuly') {
                                                Swal.fire({
                                                    title: 'Pedido no creado',
                                                    text: "El pedido que ingreso no ha sido creado correctamente!",
                                                    icon: 'error',
                                                    confirmButtonText: `Okay`
                                                });
                                            }
                                        }
                                    });
                                }
                            } else if (document.getElementById(`cantidad${i}`).value < 0) {
                                Swal.fire({
                                    title: 'Números negativos',
                                    text: "No puedes ingresar números negativos en cantidad o precio",
                                    icon: 'warning'
                                });
                            }
                        }
                        Swal.fire({
                            title: 'Pedido creado con éxito',
                            text: "El pedido que ingreso ha sido creado correctamente!",
                            icon: 'success',
                            confirmButtonText: `Okay`
                        }).then((result) => {
                            if (result.isConfirmed) {
                                console.log('reenviado');
                                document.location.href = "./buscar_pedido_online.html";
                            }
                        });
                    }
                }
            });
        }
    });
}

function test() {

}
function busqueda() {
    $.ajax({
        url: './bd/servidor.php',
        type: 'GET',
        data: {
            quest: 'buscar_clientes_online',
            nombre: document.getElementById('browser').value
        },
        success: function (res) {
            if (res != 'No') {
                let lista = JSON.parse(res);
                lista.forEach(lista => {
                    document.getElementById('direccion').value = lista.direccion;
                    document.getElementById('telefono').value = lista.telefono;
                    document.getElementById('id_cliente_online').value = lista.id;
                });
            } else {
                document.getElementById('direccion').value = '';
                document.getElementById('telefono').value = '';
                document.getElementById('id_cliente_online').value = '';
            }
        }
    });
}