var id_usuario
var idPipeline = sessionStorage.getItem("id_pipeline_unhesa");
var prospecto = sessionStorage.getItem("detalle_prospecto");
var idProductoNuevo = document.getElementById('id_producto_nuevo');
var idEstado;
var elementos = new Array();

$(document).ready(function () {
    validarUsuarioLogueado();
    obtenerNombreUsuario();
    validarEmpresaUsuario();
    obtenerProductosNuevos();
    obtenerClientesNuevos();
    campos_prospectos();
    estado_pipeline();
    tipo_pipeline();
    detalle_pipeline();
    historial_pipeline();
})

function validarUsuarioLogueado() {
    $.ajax({
        url: './db/servidor_pipeline.php',
        type: 'GET',
        data: {
            quest: 'usuario'
        },
        success: function (idUsuario) {
            if (isNaN(idUsuario)) {
                window.location.href = '../login.php';
                id_usuario = idUsuario
            }
        }
    })
}

function obtenerNombreUsuario() {
    $.ajax({
        url: './db/servidor_pipeline.php',
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
        url: './db/servidor_pipeline.php',
        type: 'GET',
        data: {
            quest: 'usuario'
        },
        success: function (idUsuario) {
            $.ajax({
                url: './db/servidor_pipeline.php',
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

function campos_prospectos() {
    var template = '';
    if (prospecto == 'true') {
        template += `
        <label>Codigo Prospecto:</label>
        <input type="text" class="form-control form-control-user" id="codigo"
            name="codigo" value="" disabled required>
        <br>
        <label>Nombre Prospecto:</label>
        <input type="text" class="form-control form-control-user" id="prospecto"
            name="prospecto" value="" disabled required>
        <br>
        <button id="botonCambiarCliente" type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalClienteSap"
        >Cambiar A Cliente En SAP</button>
        `;
        document.getElementById('nombre_prospecto').innerHTML = template;
    } else {
        template += `
        <label>Codigo:</label>
        <input type="text" class="form-control form-control-user" id="codigo"
            name="codigo" value="" disabled required>
        <br>
        <label>Cliente:</label>
        <input type="text" class="form-control form-control-user" id="cliente"
            name="cliente" value="" disabled required>
        <br>
        <button id="botonCambiarCliente" type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalClienteNuevo">Cambiar A Cliente Nuevo</button>
        `;
        document.getElementById('nombre_prospecto').innerHTML = template;
    }
}

function productos() {
    var table = document.getElementById("dataTableProductos").getElementsByTagName('tbody')[0];
    $.ajax({
        url: './db/servidor_pipeline.php',
        type: 'GET',
        data: {
            quest: 'productos_detalle_pipeline',
            idPipeline
        },
        success: function (res) {
            template = ``;
            if (res != 'No') {
                let lista = JSON.parse(res);
                lista.forEach(lista => {
                    if (idEstado != "7") {
                        var row = table.insertRow(0);
                        var cell1 = row.insertCell(0);
                        var cell2 = row.insertCell(1);
                        var cell3 = row.insertCell(2);
                        cell1.innerHTML = lista.producto;
                        cell2.innerHTML = lista.observaciones;
                        cell3.innerHTML = '<input type="button" class="btn btn-danger" value="Eliminar" onclick="advertenciaEliminarProducto(' + lista.idProducto + ', ' + lista.productoNuevo + ')"/>';
                        elementos.push({
                            idelemento: lista.idProducto,
                            producto: cell1,
                            observacionesProducto: cell2,
                            eliminar: cell3
                        });
                    } else {
                        var row = table.insertRow(0);
                        var cell1 = row.insertCell(0);
                        var cell2 = row.insertCell(1);
                        cell1.innerHTML = lista.producto;
                        cell2.innerHTML = lista.observaciones;
                        elementos.push({
                            idelemento: lista.idProducto,
                            producto: cell1,
                            observacionesProducto: cell2,
                        });
                    }
                });
            } else {
            }
        }
    });
}

function advertenciaEliminarProducto(idProducto, productoNuevo) {
    Swal.fire({
        title: '¿Esta Seguro de Eliminar el Producto?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            eliminarProducto(idProducto, productoNuevo);
        }
    })
}

function eliminarProducto(idProducto, productoNuevo) {
    $.ajax({
        url: './db/servidor_pipeline.php',
        type: 'POST',
        data: {
            quest: 'eliminar_producto_detalle_pipeline',
            productoNuevo,
            idProducto,
            idPipeline
        },
        success: function (res) {
            if (res == 'Successfully') {
                Swal.fire({
                    icon: 'success',
                    title: 'Producto Eliminado',
                    showConfirmButton: false,
                    timer: 1500
                }).then(() => {
                    window.location.reload();
                })
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'No se ha podido eliminar el producto',
                    showConfirmButton: false,
                    timer: 1500
                })
            }
        }
    });
}

function limpiar_inputs() {
    document.getElementById('browserProducto').value = "";
    $("#idProducto").empty();
    document.getElementById('botonGuardarProducto').disabled = true;
}

function habilitarBotonGuardar() {
    document.getElementById('botonGuardarProducto').disabled = false;
}

function buscar_producto_nuevo() {
    document.getElementById('botonGuardarProducto').disabled = false;
    $.ajax({
        url: './db/servidor_pipeline.php',
        type: 'GET',
        data: {
            quest: 'buscar_producto_nuevo',
            nombre: document.getElementById('browserProducto').value
        },
        success: function (res) {
            if (res != 'No') {
                let lista = JSON.parse(res);
                lista.forEach(lista => {
                    idProductoNuevo.value = lista.id;
                });
            } else {
                idProductoNuevo.value = '';
            }
        }
    });
}

function ingresar_producto_nuevo() {
    return new Promise(resolve => {
        $.ajax({
            url: './db/servidor_pipeline.php',
            type: 'POST',
            data: {
                quest: 'ingresar_producto_nuevo',
                nombre_producto_nuevo: document.getElementById('browserProducto').value
            },
            success: function (res) {
                if (res == 'Successfully') {
                    $.ajax({
                        url: './db/servidor_pipeline.php',
                        type: 'GET',
                        data: {
                            quest: 'buscar_producto_nuevo',
                            nombre: document.getElementById('browserProducto').value
                        },
                        success: function (res) {
                            let lista = JSON.parse(res);
                            lista.forEach(lista => {
                                resolve(idProductoNuevo.value = lista.id);
                            });
                        }
                    })
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error Al Crear Producto Nuevo',
                        text: 'Por favor comunícate con el departamento de informática'
                    });
                }
            }
        })
    })
}

async function guardarProducto() {
    var productoNuevo = document.getElementById('browserProducto').value;
    var idProducto = document.getElementById('idProducto').value;
    var observaciones = document.getElementById('inputObservacion').value;

    if (productoNuevo != '' && idProductoNuevo.value == '') {
        await ingresar_producto_nuevo()
    }
    return new Promise(resolve => {
        cargando();
        setTimeout(() => {
            $.ajax({
                url: './db/servidor_pipeline.php',
                type: 'POST',
                data: {
                    quest: 'ingresar_producto_detalle_pipeline',
                    idPipeline,
                    idProductoNuevo: idProductoNuevo.value,
                    idProducto,
                    observaciones
                },
                success: function (res) {
                    if (res != 'Successfully') {
                        Swal.fire({
                            icon: 'error',
                            title: 'No se ha podido agregar el producto',
                            showConfirmButton: false,
                            timer: 1500
                        })
                    } else {
                        Swal.fire({
                            icon: 'success',
                            title: 'Producto Agregado',
                            showConfirmButton: false,
                            timer: 1500
                        }).then(() => {
                            resolve(window.location.reload());
                        })
                    }
                }
            });
        }, 3000);
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

function estado_pipeline() {
    $.ajax({
        url: './db/servidor_pipeline.php',
        type: 'GET',
        data: {
            quest: 'estado'
        },
        success: function (res) {
            template = ``;
            if (res != 'No') {
                let lista = JSON.parse(res);
                lista.forEach(lista => {
                    template += `<option value="${lista.id}">${lista.nombre}</option>`;
                });
                document.getElementById('estado_n').innerHTML = template;
            } else {
            }
        }
    });
}

function tipo_pipeline() {
    $.ajax({
        url: './db/servidor_pipeline.php',
        type: 'GET',
        data: {
            quest: 'tipo_pipeline'
        },
        success: function (res) {
            template = ``;
            if (res != 'No') {
                let lista = JSON.parse(res);
                lista.forEach(lista => {
                    template += `<option value="${lista.id}">${lista.nombre}</option>`;
                });
                document.getElementById('tipo_n').innerHTML = template;
            } else {
            }
        }
    });
}

function detalle_pipeline() {
    $.ajax({
        url: './db/servidor_pipeline.php',
        type: 'GET',
        data: {
            quest: 'detalle_pipeline',
            idPipeline,
            prospecto
        },
        success: function (res) {
            if (res == 'No') {
                Swal.fire({
                    icon: 'error',
                    title: 'Problemas con la base de datos',
                    text: 'Por favor, comuniquese con el departamento de informática'
                });
            } else {
                let lista = JSON.parse(res);
                ;
                titulo = "";
                tipo = "";
                tiempoT = "";
                estado = "";
                titulo += `Detalle Del Pipeline #${idPipeline}`;
                if (prospecto == 'true') {
                    document.getElementById("codigo").value = lista['prospecto'].codigo_prospecto;
                    document.getElementById('prospecto').value = lista['prospecto'].nombre_prospecto;
                } else {
                    document.getElementById("codigo").value = lista['general'].codigo_cliente;
                    document.getElementById("cliente").value = lista['general'].nombre_cliente;
                }

                tiempoT += `Tiempo transcurrido desde ultima actualización: <b>${lista['general'].tiempoTranscurrido} días </b>`;
                document.getElementById("tipo_moneda").value = lista['general'].tipo_moneda;
                document.getElementById("oportunidadAnio").value = lista['general'].oportunidad_anio;
                document.getElementById("potencial").value = lista['general'].potencial;
                document.getElementById("inicioOportunidad").value = lista['general'].inicioOportunidad;
                document.getElementById("inicioFacturacion").value = lista['general'].inicioFacturacion;
                tipo += `<option value="${lista['general'].tipo_pipeline}">${lista['general'].nombre_tipo}</option>`;
                estado += `<option value="${lista['general'].estado}">${lista['general'].nombre_estado}</option>`;
                document.getElementById("tituloDetalle").innerHTML = titulo;
                document.getElementById("tipo").innerHTML = tipo;
                document.getElementById("estado").innerHTML = estado;
                document.getElementById("tiempo_transcurrido").innerHTML = tiempoT;
                idEstado = lista['general'].estado;
                productos();
                validar_cierre();
            }
        }
    });
}

function historial_pipeline() {
    $.ajax({
        url: './db/servidor_pipeline.php',
        type: 'GET',
        data: {
            quest: 'historial_pipeline',
            idPipeline
        },
        success: function (res) {
            ;
            if (res == 'No') {
                Swal.fire({
                    icon: 'error',
                    title: 'Problemas con la base de datos',
                    text: 'Por favor, comuniquese con el departamento de informática'
                });
            } else {
                let lista = JSON.parse(res);
                template = "";
                fecha_inicial = "";
                fecha_final = "";
                lista.forEach(lista => {
                    var fMinima = new Date(lista.fecha.replace(/-/g, "/"));
                    fecha_inicial = fMinima;
                    if (fecha_final == "") {
                        diferencia = fecha_inicial - fMinima;
                    } else {
                        diferencia = fMinima - fecha_final;
                    }
                    diasTranscurridos = Math.floor(diferencia / (1000 * 60 * 60 * 24));
                    template += `
                    <tr>
                        <td>${lista.id}</td>
                        <td>${lista.estado}</td>
                        <td>${lista.fecha}</td>
                        <td>${diasTranscurridos} días</td>
                    </tr>`;
                    fecha_final = fecha_inicial;
                });
                document.getElementById("tablaHistorial").innerHTML = template;
            }
        }
    });
}

function validar_cierre() {
    template = ""
    if (idEstado == "7") {
        document.getElementById("botonCambiarCliente").disabled = true;
        document.getElementById("btn_agregar_producto").disabled = true;
        document.getElementById("btn_t").disabled = true;
        document.getElementById("btn_ef").disabled = true;
        document.getElementById("btn_e").disabled = true;
        template += `
            <th>Producto</th>
            <th>Observaciones</th>`;
        document.getElementById("cabeceraProductos").innerHTML = template;
        document.getElementById("footerProductos").innerHTML = template;
    } else {
        document.getElementById("botonCambiarCliente").disabled = false;
        document.getElementById("btn_agregar_producto").disabled = false;
        document.getElementById("btn_t").disabled = false;
        document.getElementById("btn_ef").disabled = false;
        document.getElementById("btn_e").disabled = false;
        template += `
            <th>Producto</th>
            <th>Observaciones</th>
            <th><i class="fas fa-trash-alt"></i></th>`;
        document.getElementById("cabeceraProductos").innerHTML = template
        document.getElementById("footerProductos").innerHTML = template;
    }
}

function cambiar_estado() {
    document.getElementById("estado_n").hidden = false;
    document.getElementById("estado").hidden = true;
    document.getElementById("btn_e").hidden = true;
    document.getElementById("btn_g").hidden = false;
}

function cambiar_facturacion() {
    document.getElementById("inicioFacturacion").disabled = false;
    document.getElementById("btn_ef").hidden = true;
    document.getElementById("btn_gf").hidden = false;
}

function cambiar_tipo() {
    document.getElementById("tipo_n").hidden = false;
    document.getElementById("tipo").hidden = true;
    document.getElementById("btn_t").hidden = true;
    document.getElementById("btn_gt").hidden = false;
}

function guardar_estado() {
    var id_estado = document.getElementById("estado_n").value;

    $.ajax({
        url: './db/servidor_pipeline.php',
        type: 'POST',
        data: {
            quest: 'guardar_estado',
            id: idPipeline,
            id_estado
        },
        success: function (res) {
            if (res == 'Successfully') {
                Swal.fire({
                    icon: 'success',
                    title: 'Estado Modificado Exitosamente',
                    text: 'El estado se modifico de manera exitosa',
                    showConfirmButton: false,
                    timer: 1000
                });
                setTimeout(function () {
                    window.location.reload();
                }, 1000)
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error Al Modificar Estado',
                    text: 'Por favor, inténtelo de nuevo'
                });
            }
        }
    });
}

function guardar_facturacion() {
    var fechaFacturacion = document.getElementById("inicioFacturacion").value;

    $.ajax({
        url: './db/servidor_pipeline.php',
        type: 'POST',
        data: {
            quest: 'modificar_facturacion_pipeline',
            id_pipeline: idPipeline,
            fechaFacturacion
        },
        success: function (res) {
            if (res == 'Successfully') {
                Swal.fire({
                    icon: 'success',
                    title: 'Inicio Facturación Editado Exitosamente',
                    text: 'El inicio de facturación se modifico de manera exitosa',
                    showConfirmButton: false,
                    timer: 1000
                });
                setTimeout(function () {
                    window.location.reload();
                }, 1000)
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error Al Modificar Inicio Facturación',
                    text: 'Por favor, inténtelo de nuevo'
                });
            }
        }
    });
}

function guardar_tipo() {
    var id_tipo = document.getElementById("tipo_n").value;

    $.ajax({
        url: './db/servidor_pipeline.php',
        type: 'POST',
        data: {
            quest: 'modificar_tipo_pipeline',
            id_pipeline: idPipeline,
            id_tipo
        },
        success: function (res) {
            if (res == 'Successfully') {
                Swal.fire({
                    icon: 'success',
                    title: 'Tipo Modificado Exitosamente',
                    text: 'El tipo se modifico de manera exitosa',
                    showConfirmButton: false,
                    timer: 1000
                });
                setTimeout(function () {
                    window.location.reload();
                }, 1000)
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error Al Modificar Tipo',
                    text: 'Por favor, inténtelo de nuevo'
                });
            }
        }
    });
}

function busqueda_producto_nuevo() {
    $.ajax({
        url: './db/servidor_pipeline.php',
        type: 'GET',
        data: {
            quest: 'buscar_producto_nuevo',
            nombre: document.getElementById('browser').value
        },
        success: function (res) {
            if (res != 'No') {
                let lista = JSON.parse(res);
                lista.forEach(lista => {
                    document.getElementById('id_producto_nuevo').value = lista.id;
                });
            } else {
                document.getElementById('id_producto_nuevo').value = '';
            }
        }
    });
}

function obtenerProductosNuevos() {
    $.ajax({
        url: './db/servidor_pipeline.php',
        type: 'GET',
        data: {
            quest: 'ver_productos_nuevos'
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

function obtenerClientesNuevos() {
    $.ajax({
        url: './db/servidor_pipeline.php',
        type: 'GET',
        data: {
            quest: 'ver_clientes_prospecto'
        },
        success: function (res) {
            var template = '';
            let lista = JSON.parse(res);
            lista.forEach(lista => {
                template += `<option value="${lista.nombre}">`;
            });
            document.getElementById('browserClientes').innerHTML = template;
        }
    });
}

function busqueda() {
    $.ajax({
        url: './db/servidor_pipeline.php',
        type: 'GET',
        data: {
            quest: 'buscar_clientes_prospecto',
            nombre: document.getElementById('browser').value
        },
        success: function (res) {
            if (res != 'No') {
                let lista = JSON.parse(res);
                lista.forEach(lista => {
                    document.getElementById('id_cliente_prospecto').value = lista.id;
                });
            } else {
                document.getElementById('id_cliente_prospecto').value = '';
            }
        }
    });
}

function modificar_cliente_sap() {
    if (document.getElementById('id_cliente').value == "") {
        Swal.fire({
            icon: 'error',
            title: 'Cliente Inválido',
            text: 'Porfavor seleccione un cliente válido'
        });
    } else {
        $.ajax({
            url: './db/servidor_pipeline.php',
            type: 'POST',
            data: {
                quest: 'modificar_cliente_sap',
                cliente_unhesa: document.getElementById('id_cliente').value,
                id_pipeline: idPipeline
            },
            success: function (res) {
                ;
                if (res == 'Successfully') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Cliente Modificado Exitosamente',
                        text: 'El cliente se modifico de manera exitosa',
                        showConfirmButton: false,
                        timer: 1000
                    });
                    sessionStorage.setItem('detalle_prospecto', false);
                    setTimeout(function () {
                        window.location.reload();
                    }, 1000)
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error Al Modificar Cliente',
                        text: 'Por favor, inténtelo de nuevo más tarde'
                    });
                }
            }
        });

    }
}

function modificar_tipo_pipeline() {
    if (document.getElementById('id_tipo_pipeline').value == "") {
        Swal.fire({
            icon: 'error',
            title: 'Tipo Pipeline Invalido',
            text: 'Porfavor seleccione un tipo de pipeline válido'
        });
    } else {
        $.ajax({
            url: './db/servidor_pipeline.php',
            type: 'POST',
            data: {
                quest: 'modificar_tipo_pipeline',
                tipo_pipeline: document.getElementById('id_tipo_pipeline').value,
                id_pipeline: idPipeline
            },
            success: function (res) {
                ;
                if (res == 'Successfully') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Tipo Pipeline Modificado Exitosamente',
                        text: 'El tipo de pipeline se modifico de manera exitosa',
                        showConfirmButton: false,
                        timer: 1000
                    });
                    setTimeout(function () {
                        window.location.reload();
                    }, 1000)
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error Al Modificar Tipo Pipeline',
                        text: 'Por favor, inténtelo de nuevo más tarde'
                    });
                }
            }
        });

    }
}

function modificar_cliente_nuevo() {
    if (document.getElementById('browser').value == "") {
        Swal.fire({
            icon: 'error',
            title: 'Nombre Inválido',
            text: 'Porfavor ingrese un nombre válido'
        });
    } else {
        var idCliente = document.getElementById('id_cliente_prospecto').value;
        if (idCliente != "") {
            $.ajax({
                url: './db/servidor_pipeline.php',
                type: 'POST',
                data: {
                    quest: 'modificar_cliente_nuevo',
                    id_prospecto: idCliente,
                    id_pipeline: idPipeline
                },
                success: function (res) {
                    ;
                    if (res == 'Successfully') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Cliente Modificado Exitosamente',
                            text: 'El cliente se modifico de manera exitosa',
                            showConfirmButton: false,
                            timer: 1000
                        });
                        sessionStorage.setItem('detalle_prospecto', true);
                        setTimeout(function () {
                            window.location.reload();
                        }, 1000)
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error Al Modificar Cliente',
                            text: 'Por favor, inténtelo de nuevo más tarde'
                        });
                    }
                }
            });
        } else {
            $.ajax({
                url: './db/servidor_pipeline.php',
                type: 'POST',
                data: {
                    quest: 'ingresar_prospecto',
                    nombre_prospecto: document.getElementById('browser').value
                },
                success: function (res) {
                    if (res == 'Successfully') {
                        $.ajax({
                            url: './db/servidor_pipeline.php',
                            type: 'GET',
                            data: {
                                quest: 'obtener_id_prospecto',
                                nombre_prospecto: document.getElementById('browser').value
                            },
                            success: function (res) {
                                let lista = JSON.parse(res);
                                lista.forEach(lista => {
                                    $.ajax({
                                        url: './db/servidor_pipeline.php',
                                        type: 'POST',
                                        data: {
                                            quest: 'modificar_cliente_nuevo',
                                            id_prospecto: lista.id,
                                            id_pipeline: idPipeline
                                        },
                                        success: function (res) {
                                            ;
                                            if (res == 'Successfully') {
                                                Swal.fire({
                                                    icon: 'success',
                                                    title: 'Cliente Modificado Exitosamente',
                                                    text: 'El cliente se modifico de manera exitosa',
                                                    showConfirmButton: false,
                                                    timer: 1000
                                                });
                                                sessionStorage.setItem('detalle_prospecto', true);
                                                setTimeout(function () {
                                                    window.location.reload();
                                                }, 1000)
                                            } else {
                                                Swal.fire({
                                                    icon: 'error',
                                                    title: 'Error Al Modificar Cliente',
                                                    text: 'Por favor, inténtelo de nuevo más tarde'
                                                });
                                            }
                                        }
                                    });
                                });
                            }
                        })
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error Al Crear Prospecto',
                            text: 'Por favor, comunícate con el departamento de informática'
                        });
                    }
                }
            })
        }
    }
}
