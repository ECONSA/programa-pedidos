var index_productos = 0;
var total_pedido;
var productos = new Array();
const reader = new FileReader();

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
        success: function(idUsuario) {
            if(isNaN(idUsuario)) {
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
        success: function(res) {
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
        success: function(idUsuario) {
            $.ajax({
                url: './db/servidor_pedidos.php',
                type: 'GET',
                data: {
                    quest: 'empresa_usuario',
                    id_usuario: idUsuario
                },
                success: function(resp) {
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
                document.getElementById('idCliente').value = lista.id_cliente
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

async function validar_precios_sap_unhesa(){
    var cmb_producto = document.getElementById('idProducto')
    var id_producto = cmb_producto.value
    var cantidad = parseFloat(document.getElementById('cantidad').value)
    var precio = parseFloat(document.getElementById('precio').value)
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
        Swal.fire({
            title: 'Procesando',
            text: 'Por favor espere...',
            allowOutsideClick: false,
            allowEscapeKey: false,
            allowEnterKey: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
        const params = new URLSearchParams({
            quest: 'obtener_precio_sap_casillas',
            codigo: id_producto
        });
    
        await fetch(`./db/servidor_pedidos.php?${params}`, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json; charset=UTF-8'
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.length > 0) {
                console.log(precio);
                
                if ((parseFloat(data[0].CostoBodega) * cantidad) >= (precio * cantidad)) {
                    console.log((parseFloat(data[0].CostoBodega) * cantidad));
                    
                    Swal.fire({
                        title: 'Diferencia de montos',
                        text: `El monto total calculado es menor que el costo de producción, En SAP el monto por unidad es de: ${data[0].CostoBodega}`,
                        icon: 'warning',
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        allowEnterKey: false,
                        confirmButtonText: 'OK'
                    }).then(() => {
                        location.reload(); // Recarga la página al hacer clic en el botón OK
                    });
                }else{
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
                    Swal.close();
                }
            } else {
                console.log('Hola, no funciona');
            }
        }).then(() => {
        })
        .catch(error => {
            console.error('Error:', error);
        });
    }
}

function agregar_producto() {
    validar_precios_sap_unhesa();
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
    document.getElementById('cantidad').value = "1"
    document.getElementById('precio').value = "1"
    document.getElementById('observacionesProducto').value = ""
    $('#idProducto').val(null).trigger('change');
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

function ingresar_pedido() {
    if (validar_inputs_pedido()) {
        if (validar_productos()) {
            cargando();
            var id_cliente = document.getElementById('idCliente').value
            var tipo_entrega = document.getElementById('idTipoEntrega').value
            var direccion_entrega = document.getElementById('direccion').value
            var telefono_contacto = document.getElementById('telefono').value
            var fecha_despacho = document.getElementById('fecha').value
            var hora_entrega = document.getElementById('hora').value
            var observacion_pedido = document.getElementById('observaciones').value
            var observacion_adicional = document.getElementById('observacionesA').value
            // Primero ejecuta esta promesa para obtener el correlativo a ingresar
            return new Promise((resolve) => {
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
            }).then((resultado) => {
                // Despues ejecuta esta promesa que ingresa los datos del pedido a la base de datos
                return new Promise((resolve) => {
                    $.ajax({
                        url: './db/servidor_pedidos.php',
                        type: 'POST',
                        data: {
                            quest: 'ingresar_pedido_unhesa',
                            id_cliente,
                            tipo_entrega,
                            direccion_entrega,
                            telefono_contacto,
                            fecha_despacho,
                            hora_entrega,
                            observacion_pedido,
                            observacion_adicional,
                            correlativo: resultado,
                            total_pedido
                        },
                        success: function (res) {
                            if(res == "Successfully") {
                                resolve("Pedido Ingresado");
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error Al Ingresar Pedido',
                                    text: 'Por favor, intentalo de nuevo más tarde',
                                    showCancelButton: false,
                                    showConfirmButton: true,
                                    allowOutsideClick: false
                                });
                            }
                        }
                    });
                }).then(() => {
                    //Despues ejecuta esta promesa que ingresa el detalle del pedido a la base de datos
                    return new Promise((resolve) => {
                        productos.forEach((producto) => {
                            $.ajax({
                                url: './db/servidor_pedidos.php',
                                type: 'POST',
                                data: {
                                    quest: 'ingresar_detalle_pedido_unhesa',
                                    cantidad: producto.cantidad,
                                    precio: producto.precio,
                                    id_producto: producto.id_producto
                                },
                                success: function (res) {
                                    if(res == "Successfully") {
                                        resolve('Detalles Ingresados')
                                    } else {
                                        Swal.fire({
                                            icon: 'error',
                                            title: 'Error Al Ingresar Pedido',
                                            text: 'Por favor, intentalo de nuevo más tarde',
                                            showCancelButton: false,
                                            showConfirmButton: true,
                                            allowOutsideClick: false
                                        });
                                    }
                                }
                            });
                        })
                    }).then(() => {
                        //Por ultimo sube la foto ingresada a la base de datos
                        Swal.fire({
                            icon: 'success',
                            title: 'Pedido Ingresado',
                            text: 'El pedido ha sido ingresado exitosamente',
                            showCancelButton: false,
                            showConfirmButton: true,
                            allowOutsideClick: false
                        }).then(() => {
                            document.getElementById("submit").click();
                        });
                    })
                    
                })
            })
        } else {
            Swal.fire({
                icon: 'warning',
                title: 'Productos Vacios',
                text: 'Por Favor, asegurese de haber ingresado por lo menos un producto',
                confirmButtonText: "Ok",
                showCancelButton: false,
            })
        }
    } else {
        Swal.fire({
            icon: 'warning',
            title: 'Campos Vacios',
            text: 'Asegurese de haber llenado todos los campos',
            confirmButtonText: "Ok",
            showCancelButton: false,
        })
    }
}

function validar_inputs_pedido() {
    var tipo_entrega = document.getElementById('idTipoEntrega').value
    var direccion_entrega = document.getElementById('direccion').value
    var telefono_contacto = document.getElementById('telefono').value
    var fecha_despacho = document.getElementById('fecha').value
    var hora_entrega = document.getElementById('hora').value
    if (tipo_entrega == "" || direccion_entrega == "" || telefono_contacto == "" || fecha_despacho == "" || hora_entrega == "") {
        return false
    } else {
        return true
    }
}

function validar_productos() {
    if (productos.length <= 0) {
        return false
    } else {
        return true
    }
}

function calcular_total() {
    total_pedido = 0;
    productos.forEach(listado => {
        total_pedido += (listado.precio * listado.cantidad)
    })
    document.getElementById('monto_total_pedido').innerHTML = 'Monto Total Del Pedido: Q. ' + total_pedido.toFixed(2)
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
