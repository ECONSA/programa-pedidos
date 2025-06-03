const ids_detalles = [];

$(document).ready(function () {

    obtener_listado_productos();
    datos_usuario();
    listado_clientes();
    datos_pedido();
});

function datos_pedido() {
    $.ajax({
        url: './bd/servidor.php',
        type: 'GET',
        data: {
            quest: 'datos_pedido_online',
            id_pedido_online: sessionStorage.getItem("id_pedido_online")
        },
        success: function (res) {
            let lista = JSON.parse(res);
            lista.forEach(lista => {
                document.getElementById("browser").value = lista.nombre;
                document.getElementById("id_cliente_online").value = lista.id_cliente_online;
                document.getElementById("direccion").value = lista.direccion;
                document.getElementById("departamento").value = lista.departamento;
                document.getElementById("municipio").value = lista.municipio;
                document.getElementById("telefono").value = lista.telefono;
                document.getElementById("nombre_factura").value = lista.nombre_factura;
                document.getElementById("nit").value = lista.nit;
                document.getElementById("sticker").value = lista.stickers;
                document.getElementById("servicio").value = lista.servicio;
                document.getElementById("observacion").value = lista.observaciones;
                document.getElementById("fecha_entrega").value = lista.fecha_entrega;
            });
        }
    });
    $.ajax({
        url: './bd/servidor.php',
        type: 'GET',
        data: {
            quest: 'datos_detalle_pedido_online',
            id_pedido_online: sessionStorage.getItem("id_pedido_online")
        },
        success: function (res) {
            let template = ``;
            let lista = JSON.parse(res);
            lista.forEach(lista => {
                ids_detalles.push(lista.id);
                template += `
                    <tr>
                        <td>${lista.producto}</td>
                        <td><input class="form-control" value="${lista.precio}" id="precio${lista.id}" type="number"></td>
                        <td><input class="form-control" value="${lista.cantidad}" id="cantidad${lista.id}" type="number"></td>
                        <td><button type="button" class="btn btn-outline-danger" onclick="borrar(${lista.id})">Borrar</button></td>
                    </tr>
                `;
            });
            document.getElementById("cuerpo_detalle").innerHTML = template;
        }
    });
}

function listado_productos_pedido() {
    $.ajax({
        url: './bd/servidor.php',
        type: 'GET',
        data: {
            quest: 'datos_detalle_pedido_online',
            id_pedido_online: sessionStorage.getItem("id_pedido_online")
        },
        success: function (res) {
            let template = ``;
            let lista = JSON.parse(res);
            lista.forEach(lista => {
                ids_detalles.push(lista.id);
                template += `
                    <tr>
                        <td>${lista.producto}</td>
                        <td><input class="form-control" value="${lista.precio}" id="precio${lista.id}" type="number"></td>
                        <td><input class="form-control" value="${lista.cantidad}" id="cantidad${lista.id}" type="number"></td>
                        <td><button type="button" class="btn btn-outline-danger" onclick="borrar(${lista.id})">Borrar</button></td>
                    </tr>
                `;
            });
            document.getElementById("cuerpo_detalle").innerHTML = template;
        }
    });
}

function borrar(id) {
    Swal.fire({
        icon: 'warning',
        title: '¿Esta seguro de eliminar el producto?',
        confirmButtonText: "Eliminar",
        cancelButtonText: "Cancelar",
        showCancelButton: true,
        cancelButtonColor: "#FF3333"
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: './bd/servidor.php',
                type: 'POST',
                data: {
                    quest: 'eliminar_detalle_online',
                    id_detalle: id
                },
                success: function (res) {
                    if (res == 'Successfully') {
                        Swal.fire({
                            title: 'Eliminado!',
                            text: "El pedido ha sido modificado con éxito!",
                            icon: 'success',
                            timer: 1300,
                            showConfirmButton: false
                        }).then(() => {
                            listado_productos_pedido();
                        })
                    }
                }
            });
        }
    });
}

function datos_usuario() {
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
                    });
                }
            });
        }
    });
}

function listado_clientes() {
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
        }
    });
}

function guardar() {
    if (validar_datos() == "si") {
        return new Promise(resolve => {
            cargando();
            resolve(clientes());
        }).then(() => {
            insertar_pedido();
        })
    }
}

function validar_datos() {
    var contador = 0;
    for (let i = 1; i <= 21; i++) {
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
    var departamento = document.getElementById('departamento').value;
    var municipio = document.getElementById('municipio').value;
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
    }
    else if (departamento == '') {
        Swal.fire({
            title: 'Departamento',
            text: "El departamento no puede estar vacío.",
            icon: 'info'
        });
    }
    else if (municipio == '') {
        Swal.fire({
            title: 'Municipio',
            text: "El municipio no puede estar vacío.",
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
    var departamento = document.getElementById('departamento').value;
    var municipio = document.getElementById('municipio').value;
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
                    departamento,
                    municipio,
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
                    if (res != 'No') {
                        for (let i = 1; i <= 21; i++) {
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
                                        },
                                        success: function (response) {
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
                            allowOutsideClick: false,
                            showConfirmButton: false,
                            timer: 1500
                        }).then(() => {
                            document.location.href = "./buscar_pedido_online.html";
                        });
                    }
                }
            });
        }
    });
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

function obtener_listado_productos() {
    $('.idProducto').select2({
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

var index_productos = 0;
var total_pedido;
var productos = new Array();

function agregar_producto() {
    var cmb_producto = document.getElementById('idProducto')
    var id_producto = cmb_producto.value
    var cantidad = document.getElementById('cantidad').value
    var precio = document.getElementById('precio').value
    var observaciones = document.getElementById('observacionesProducto').value
    if (id_producto == "" || cantidad == "" || precio == "") {
        Swal.fire({
            icon: 'warning',
            title: 'Campos Vacios',
            text: 'Por favor, asegurese de haber llenado todos los campos con asterisco (*)',
            confirmButtonText: "Ok",
            showCancelButton: false
        });
    } else {
        var nombre_producto = cmb_producto.options[cmb_producto.selectedIndex].text
        productos.push({
            "id_json": index_productos,
            "id_producto": id_producto,
            "nombre_producto": nombre_producto,
            "cantidad": cantidad,
            "precio": precio,
            "observaciones": observaciones
        })
        index_productos++
        actualizar_tabla_productos();
        limpiar_inputs_producto();
    }
}

function actualizar_tabla_productos() {
    template = ``;
    productos.forEach(lista => {
        template += `
            <tr>
                <td>${lista.nombre_producto}</td>
                <td>${lista.cantidad}</td>
                <td>${lista.precio}</td>
                <td>${lista.cantidad * lista.precio}</td>
                <td>${lista.observaciones}</td>
                <td><button type="button" class="btn btn-danger" onclick="eliminar_producto(${lista.id_json})"><i class=\"fas fa-trash\"></i></button></td>
            </tr>
        `;
    });
    document.getElementById('cuerpo_tabla_productos').innerHTML = template;
    calcular_total();
}

function limpiar_inputs_producto() {
    document.getElementById('cantidad').value = "1";
    document.getElementById('precio').value = "1";
    document.getElementById('observacionesProducto').value = "";
    $('#idProducto').val(null).trigger('change');
}

function calcular_total() {
    total_pedido = 0;
    productos.forEach(listado => {
        total_pedido += (listado.precio * listado.cantidad)
    })
}

function eliminar_producto(id) {
    Swal.fire({
        icon: 'warning',
        title: '¿Esta seguro de eliminar el producto?',
        confirmButtonText: "Eliminar",
        cancelButtonText: "Cancelar",
        showCancelButton: true,
        cancelButtonColor: "#FF3333"
    }).then((result) => {
        if (result.isConfirmed) {
            delete productos[id];
            actualizar_tabla_productos();
        }
    });
}

function guardar_cambios() {
    var id_pedido_online = sessionStorage.getItem("id_pedido_online");
    var nombre_cliente = document.getElementById("browser").value;
    var direccion = document.getElementById("direccion").value;
    var departamento = document.getElementById("departamento").value;
    var municipio = document.getElementById("municipio").value;
    var telefono = document.getElementById("telefono").value;
    var nombre_factura = document.getElementById("nombre_factura").value;
    var nit = document.getElementById("nit").value;
    var stickers = document.getElementById("sticker").value;
    var servicio = document.getElementById("servicio").value;
    var observaciones = document.getElementById("observacion").value;
    var fecha_entrega = document.getElementById("fecha_entrega").value;
    var id_cliente = document.getElementById("id_cliente_online").value;
    try {
        $.ajax({
            url: './bd/servidor.php',
            type: 'POST',
            data: {
                quest: 'actualizar_pedido_online',
                nombre_cliente,
                direccion,
                departamento,
                municipio,
                telefono,
                nombre_factura,
                nit,
                stickers,
                servicio,
                observaciones,
                fecha_entrega,
                id_cliente,
                id_pedido_online
            },
            success: function (res) {
                console.log(res);
                if (res == 'Succesfully') {
                    if(productos.length >= 1) {
                        editar_detalle();
                    } else {
                        Swal.fire({
                            title: 'Modificado!',
                            text: "El pedido ha sido modificado con éxito!",
                            icon: 'success',
                            timer: 1300,
                            showConfirmButton: false
                        }).then((result) => {
                            window.location.href = 'detalle_online.html';
                        });
                    }
                } else {
                    Swal.fire({
                        title: 'Error!',
                        text: "Algo salió mal, comuniquese con sistemas! linea 597",
                        icon: 'error'
                    });
                }
            }
        });
    } catch (error) {
        console.log('¡Oh no!, ocurrió un error, QUÉ MAL! ' + error);
    }
}

function editar_detalle() {
    try {
        ids_detalles.forEach(element => {
            $.ajax({
                url: './bd/servidor.php',
                type: 'POST',
                data: {
                    quest: 'actualizar_detalle_pedido_online',
                    cantidad: document.getElementById(`cantidad${element}`).value,
                    precio: document.getElementById(`precio${element}`).value,
                    id_detalle: element
                },
                success: function (res) {
                    console.log(res);
                    if (res == 'Succesfully') {
                    } else {
                        Swal.fire({
                            title: 'Error!',
                            text: "Algo salió mal, comuniquese con sistemas! linea 543",
                            icon: 'error'
                        });
                    }
                }
            });
        });
        agregar_detalle();
    } catch (error) {
        Swal.fire({
            title: 'Error!',
            text: "Algo salió mal, comuniquese con sistemas!  linea 554",
            icon: 'error'
        });
    }

}

function agregar_detalle() {
    if (productos.length != 0) {
        productos.forEach(lista => {
            $.ajax({
                url: './bd/servidor.php',
                type: 'POST',
                data: {
                    quest: 'agregar_detalle_pedido_online',
                    cantidad: lista.cantidad,
                    precio: lista.precio,
                    id_producto: lista.id_producto,
                    id_pedido_online: sessionStorage.getItem("id_pedido_online")
                },
                success: function (res) {
                    if (res == 'Successfuly') {
                        Swal.fire({
                            title: 'Modificado!',
                            text: "El pedido ha sido modificado con éxito!",
                            icon: 'success',
                            timer: 1300,
                            showConfirmButton: false
                        }).then((result) => {
                            window.location.href = 'detalle_online.html';
                        });
                    } else {
                        Swal.fire({
                            title: 'Error!',
                            text: "Algo salió mal, comuniquese con sistemas! linea 626",
                            icon: 'error'
                        });
                    }
                }
            });
        });
    } else {
        Swal.fire({
            title: 'Modificado!',
            text: "El pedido ha sido modificado con éxito!",
            icon: 'success',
            timer: 1300,
            showConfirmButton: false
        }).then((result) => {
            window.location.href = 'detalle_online.html';
        });
    }
}