var vendedorLogueado

$(document).ready(function () {
    validarUsuarioLogueado()
    obtenerNombreUsuario()
    validarEmpresaUsuario()
    obtener_datos_cliente()
    obtener_tipos_entrega()
    obtener_listado_productos()
})

function validarUsuarioLogueado() {
    $.ajax({
        url: './db/servidor_pedidos.php',
        type: 'GET',
        data: {
            quest: 'usuario'
        },
        success: function (idUsuario) {
            vendedorLogueado = idUsuario
            if (isNaN(idUsuario)) {
                window.location.href = '../login.php';
            }
        }
    })
}

function obtenerNombreUsuario() {
    $.ajax({
        url: './db/servidor_pedidos.php',
        type: 'GET',
        data: {
            quest: 'nombre_usuario'
        },
        success: function (res) {
            var nombre = res;
            document.getElementById('nombre_del_usuario').innerHTML = nombre;
        }
    });
}

function validarEmpresaUsuario() {
    $.ajax({
        url: './db/servidor_pedidos.php',
        type: 'GET',
        data: {
            quest: 'usuario'
        },
        success: function (idUsuario) {
            $.ajax({
                url: './db/servidor_pedidos.php',
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
}

function obtener_datos_cliente() {
    var id_cliente = localStorage.getItem('id_cliente_unhesa');
    $.ajax({
        url: './db/servidor_pedidos.php',
        type: 'GET',
        data: {
            quest: 'cliente_unhesa',
            id_cliente
        },
        success: function (res) {
            let lista = JSON.parse(res);
            lista.forEach(lista => {
                document.getElementById('codigo').value = lista.codigo
                document.getElementById('cliente').value = lista.nombre
                document.getElementById('direccion').value = lista.direccion
            });
        }
    });
}

function obtener_tipos_entrega() {
    $.ajax({
        url: './db/servidor_pedidos.php',
        type: 'GET',
        data: {
            quest: 'tipo_entrega',
        },
        success: function (res) {
            let listado = ``
            let lista = JSON.parse(res);
            lista.forEach(lista => {
                listado += `<option value="${lista.id_tipo}">${lista.nombre}</option>`
            });
            document.getElementById('idTipoEntrega').innerHTML = listado;
        }
    });
}

function obtener_listado_productos() {
    $('.idCliente').select2({
        placeholder: 'Codigo del Producto',
        ajax: {
            url: '../Pedidos/productos.php',
            dataType: 'json',
            delay: 250,
            processResults: function (data) {
                return {
                    results: data
                };
            },
            cache: true
        }
    });
}

function setearDatos(casilla, idCampo) {
    var id = document.getElementById(`casilla${casilla}`).value;
    document.getElementById(`cantidad${idCampo}`).disabled = false;
    document.getElementById(`precio${idCampo}`).disabled = false;
    $.ajax({
        url: './db/servidor_pedidos.php',
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
    for (let index = 1; index < 23; index++) {
        if (document.getElementById(`cantidad${index}`).value == "") {
            document.getElementById(`cantidad${index}`).value = "0";
        }
    }

    for (let index = 25; index < 30; index++) {
        if (document.getElementById(`cantidad${index}`).value == "") {
            document.getElementById(`cantidad${index}`).value = "0";
        }
    }

    for (let index = 1; index < 23; index++) {
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
    for (let index = 1; index < 23; index++) {
        document.getElementById(`total${index}`).innerHTML = (document.getElementById(`cantidad${index}`).value * document.getElementById(`precio${index}`).value);
    }
    for (let index = 25; index < 30; index++) {
        document.getElementById(`total${index}`).innerHTML = (document.getElementById(`cantidad${index}`).value * document.getElementById(`precio${index}`).value);
    }

    var cantidadTotal = 0;

    for (let index = 1; index < 23; index++) {
        cantidadTotal = cantidadTotal + parseInt(document.getElementById(`cantidad${index}`).value);
    }

    for (let index = 25; index < 30; index++) {
        cantidadTotal = cantidadTotal + parseInt(document.getElementById(`cantidad${index}`).value)
    }

    document.getElementById('cantidad').innerHTML = cantidadTotal

    var sumaTotales = 0.0;

    for (let index = 1; index < 23; index++) {
        sumaTotales = sumaTotales + parseFloat(document.getElementById(`total${index}`).innerHTML)
    }

    for (let index = 25; index < 30; index++) {
        sumaTotales = sumaTotales + parseFloat(document.getElementById(`total${index}`).innerHTML)
    }

    document.getElementById('total').innerHTML = sumaTotales
}

function calcularTotal() {
    asignarValor();
    var cantidadTotal = 0;

    for (let index = 1; index < 23; index++) {
        cantidadTotal = cantidadTotal + parseInt(document.getElementById(`cantidad${index}`).value)
    }

    for (let index = 25; index < 30; index++) {
        cantidadTotal = cantidadTotal + parseInt(document.getElementById(`cantidad${index}`).value)
    }

    document.getElementById('cantidad').innerHTML = cantidadTotal

    var sumaTotales = 0.0;

    for (let index = 1; index < 23; index++) {
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

function agregarPedido() {
    var fecha_despacho = document.getElementById('fecha').value;
    var direccion = document.getElementById('direccion').value;
    var observacion = document.getElementById('observaciones').value;
    var telefono = document.getElementById('telefono').value;
    var hora = document.getElementById('hora').value;
    var observacion_A = document.getElementById('observacionesA').value;
    var total = document.getElementById('total').innerHTML;
    var idVendedor = vendedorLogueado;
    var idTipoEntrega = document.getElementById('idTipoEntrega').value;
    var idCliente = localStorage.getItem('id_cliente_unhesa');

    if (validar_inputs()) {
        if(validar_totales()) {
            return new Promise((resolve) => {
                cargando();
                $.ajax({
                    url: './db/servidor_pedidos.php',
                    type: 'GET',
                    data: {
                        quest: 'obtener_correlativo',
                    },
                    success: function (res) {
                        let lista = JSON.parse(res);
                        lista.forEach(lista => {
                            resolve(lista.correlativo)
                        });
                    }
                });
            }).then((cor) => {
                return new Promise((resolve, reject) => {
                    $.ajax({
                        url: './db/servidor_pedidos.php',
                        type: 'POST',
                        data: {
                            quest: 'agregar_pedido_frioso',
                            correlativo: cor,
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
                }).then(res => {
                    if (res == 'Successfully') {
                        return new Promise((resolve) => {
                            try {
                                for (let i = 1; i <= 22; i++) {
                                    if (document.getElementById(`cantidad${i}`).value != 0 && document.getElementById(`precio${i}`).value != 0) {
                                        var cantidad = document.getElementById(`cantidad${i}`).value;
                                        var precio = document.getElementById(`precio${i}`).value;
                                        var codigo = document.getElementById(`codigox${i}`).value;
                                        $.ajax({
                                            url: './db/servidor_pedidos.php',
                                            type: 'POST',
                                            data: {
                                                quest: 'agregar_detalle_pedido_frioso',
                                                cantidad: cantidad,
                                                precio: precio,
                                                id_producto: codigo
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
                                            url: './db/servidor_pedidos.php',
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
                            } catch (error) {
                                console.log(error);
                            } finally {
                                resolve('Success')
                            }
                            
                        }).then((res) => {
                            if(res == 'Success') {
                                Swal.fire({
                                    title: 'Ingresado Correctamente!',
                                    text: 'Su pedido fue creado con éxito!',
                                    icon: 'success',
                                }).then(() => {
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
                    } else {
                        Swal.fire({
                            title: 'Error Al Ingresar Pedido',
                            text: 'Ha ocurrido un error al ingresar pedido, por favor intentalo de nuevo',
                            icon: 'error',
                        })
                    }
                })
            })
        } else {
            Swal.fire({
                icon: 'warning',
                title: 'Total Igual A 0',
                text: 'El total de productos y precio del pedido es 0',
            })
        }
    } else {
        Swal.fire({
            icon: 'warning',
            title: 'Campos Vacios',
            text: 'A dejado algun campo vacio',
        })
    }
}

function validar_inputs() {
    var fecha_despacho = document.getElementById('fecha').value;
    var direccion = document.getElementById('direccion').value;
    var hora = document.getElementById('hora').value;
    var total = document.getElementById('total').innerHTML;
    var idVendedor = vendedorLogueado;
    var idTipoEntrega = document.getElementById('idTipoEntrega').value;
    var idCliente = localStorage.getItem('id_cliente_unhesa');
    if ((fecha_despacho && direccion && hora && total && idTipoEntrega && idCliente && idVendedor) != "") {
        return true 
    } else {
        return false
    }
}

function validar_totales() {
    var cantidad_productos = document.getElementById('cantidad').innerHTML
    var precio_productos = document.getElementById('total').innerHTML
    if(cantidad_productos != "0" && precio_productos != "0") {
        return true
    } else {
        return false
    }
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
