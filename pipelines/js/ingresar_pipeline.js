
var idProductoNuevo = document.getElementById('id_producto_nuevo');
var prospecto = sessionStorage.getItem('prospecto');
var id_cliente = sessionStorage.getItem('id_cliente_unhesa');
var aux = 0;
var elementos = new Array();

$(document).ready(function () {
    validarUsuarioLogueado();
    obtenerNombreUsuario();
    validarEmpresaUsuario();
    inputs_prospecto();

    if (prospecto == 'true') {
        prospectos(id_cliente);
    } else {
        clientes_sap(id_cliente);
    }

    obtenerTipoPipeline();
    obtenerEstadoPipeline();
    obtenerProductosNuevos();
});

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

async function guardarProducto() {
    var nombreProductoNuevo = document.getElementById('browserProducto').value;
    var idProductoUnhesa = document.getElementById('idProducto').value;
    var nombreProductoUnhesa = document.getElementById('idProducto').textContent;
    var observaciones = document.getElementById('inputObservacion').value;

    var table = document.getElementById("dataTableProductos").getElementsByTagName('tbody')[0];
    var columnas = document.getElementById("dataTableProductos").getElementsByTagName('td');

    var row = table.insertRow(0);
    var cell1 = row.insertCell(0);
    var cell2 = row.insertCell(1);
    var cell3 = row.insertCell(2);
    var cell4 = row.insertCell(3);
    var cell5 = row.insertCell(4);

    if (nombreProductoNuevo != '' && idProductoNuevo.value == '') {
        await ingresar_producto_nuevo()
    }
    if (nombreProductoNuevo == '' && nombreProductoUnhesa != '' && idProductoUnhesa != '') {
        cell4.innerHTML = idProductoUnhesa
        cell5.innerHTML = "0"
        cell1.innerHTML = nombreProductoUnhesa
    } else {
        cell4.innerHTML = idProductoNuevo.value
        cell5.innerHTML = "1"
        cell1.innerHTML = nombreProductoNuevo
    }

    cell2.innerHTML = observaciones
    cell3.innerHTML = '<input type="button" class="btn btn-danger" value="Eliminar" onclick="advertenciaEliminarProducto(this, ' + aux + ')"/>';
    elementos.push({
        idelemento: aux,
        producto: cell1,
        observacionesProducto: cell2,
        eliminar: cell3,
        idProducto: cell4,
        productoNuevo: cell5
    });
    columnas[3].style.display = 'none';
    columnas[4].style.display = 'none';
    aux = aux + 1;
    $('#modalProductos').modal('hide');
    limpiar_inputs();
}

function advertenciaEliminarProducto(aux) {
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
            eliminarProducto(aux);
        }
    })
}

function eliminarProducto(btn, aux) {
    var index = elementos.findIndex((elemento) => elemento.idelemento == aux);
    elementos.splice(index, 1);
    var row = btn.parentNode.parentNode;
    row.parentNode.removeChild(row);
}

function obtenerTipoPipeline() {
    $.ajax({
        url: './db/servidor_pipeline.php',
        type: 'GET',
        data: {
            quest: 'tipo_pipeline'
        },
        success: function (res) {
            template = ``;
            let lista = JSON.parse(res);
            lista.forEach(lista => {
                template += `<option value="${lista.id}">${lista.nombre}</option>`;
            });
            document.getElementById('tipoPipeline').innerHTML = template;
        }
    });
}

function obtenerEstadoPipeline() {
    $.ajax({
        url: './db/servidor_pipeline.php',
        type: 'GET',
        data: {
            quest: 'estado'
        },
        success: function (res) {
            template = ``;
            let lista = JSON.parse(res);
            lista.forEach(lista => {
                template += `<option value="${lista.id}">${lista.nombre}</option>`;
            });
            document.getElementById('estado').innerHTML = template;
        }
    });
}

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
            } else {
                document.getElementById('idCliente').value = id_cliente;
                document.getElementById('idVendedor').value = idUsuario;
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


function inputs_prospecto() {
    var template = '';
    if (prospecto == 'true') {
        template += `
        <label>Codigo:</label>
        <input type="text" class="form-control form-control-user" id="codigo"
            name="codigo" value="" disabled required>
        <br>
        <label>Cliente:</label>
        <input type="text" class="form-control form-control-user" id="cliente"
            name="cliente" value="" disabled required>
        <br>
        <label>Nombre Prospecto:</label>
        <input type="text" class="form-control form-control-user" id="prospecto"
            name="prospecto" value="" disabled required>
        <br>
        `
        document.getElementById('container_prospecto').innerHTML = template
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
        `
        document.getElementById('container_prospecto').innerHTML = template
    }
}

function prospectos(cliente) {
    $.ajax({
        url: './db/servidor_pipeline.php',
        type: 'GET',
        data: {
            quest: 'cliente_prospecto',
            id_prospecto: cliente
        },
        success: function (res) {
            let lista = JSON.parse(res);
            lista.forEach(lista => {
                document.getElementById('codigo').value = lista.id;
                document.getElementById('cliente').value = 'Clientes en prospectos';
                document.getElementById('prospecto').value = lista.nombre;
                sessionStorage.setItem('id_prospecto', lista.id)
            });
        }
    });
}

function limpiar_inputs() {
    document.getElementById('browserProducto').value = "";
    $("#idProducto").empty();
    document.getElementById('botonGuardarProducto').disabled = true;
    document.getElementById('inputObservacion').value = "";
}

function clientes_sap(cliente) {
    $.ajax({
        url: './db/servidor_pipeline.php',
        type: 'GET',
        data: {
            quest: 'cliente_unhesa',
            id_cliente: cliente
        },
        success: function (res) {
            let lista = JSON.parse(res);
            lista.forEach(lista => {
                document.getElementById('codigo').value = lista.codigo;
                document.getElementById('cliente').value = lista.nombre;
                sessionStorage.setItem('id_cliente_unhesa', cliente);
            });
        }
    });
}

async function ingresar_pipeline() {
    if (validaciones()) {

        if (prospecto == 'true') {
            var codigo_cliente = 2414;
            var codigo_prospecto = document.getElementById('idCliente').value;
        } else {
            var codigo_cliente = document.getElementById('idCliente').value;
            var codigo_prospecto = 'NULL';
        }

        var id_vendedor = document.getElementById('idVendedor').value;;
        var nombre_cliente = document.getElementById('cliente').value;
        var tipo_moneda = document.getElementById('tipo_moneda').value;
        var tipo_pipeline = document.getElementById('tipoPipeline').value;
        var precio = document.getElementById('precio').value;
        var oportunidad_anio = document.getElementById('oportunidadAnio').value;
        var potencial = document.getElementById('potencial').value;
        var estado = document.getElementById('estado').value;
        var inicio_oportunidad = document.getElementById('inicioOportunidad').value;
        var inicio_facturacion = document.getElementById('inicioFacturacion').value;

        $.ajax({
            url: './db/servidor_pipeline.php',
            type: 'POST',
            data: {
                quest: 'ingresar_pipeline_unhesa',
                id_vendedor,
                codigo_cliente,
                nombre_cliente,
                tipo_moneda,
                tipo_pipeline,
                precio,
                oportunidad_anio,
                potencial,
                estado,
                inicio_oportunidad,
                inicio_facturacion,
                codigo_prospecto
            },
            success: function (res) {
                console.log(res);
                if (res == "Successfuly") {
                    ingresar_productos_detalle();
                } else {
                    Swal.fire({
                        title: 'Ha Ocurrido Un Error',
                        text: 'No se ha podido ingresar el pipeline, por favor, comuniquese con el departamento de informatica',
                        icon: 'success',
                        confirmButtonText: 'Ok'
                    });
                }
            }
        });
    } else {
        Swal.fire(
            'Espere',
            'Ha dejado algún campo en blanco, por favor revise que todos los campos estén llenos correctamente',
            'warning'
        );
    }
}

function ingresar_productos_detalle() {
    var cont = 0;
    elementos.every(function (valor, index, array) {
        array.forEach(function (valor2) {
            return new Promise(resolve => {
                cargando();
                setTimeout(() => {
                    if (valor2.productoNuevo.innerHTML == '0') {
                        $.ajax({
                            url: './db/servidor_pipeline.php',
                            type: 'POST',
                            data: {
                                quest: 'ingresar_producto_detalle_pipeline_nuevo',
                                idProductoNuevo: '',
                                idProducto: valor2.idProducto.innerHTML,
                                observaciones: valor2.observacionesProducto.innerHTML
                            },
                            success: function (res) {
                                console.log(res);
                                if (res != 'Successfully') {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'No se ha podido agregar el producto',
                                        showConfirmButton: false,
                                        timer: 1500
                                    })
                                    return;
                                } else {
                                    cont++
                                    if(cont == elementos.length) {
                                        resolve(ingresar_historial_pipeline());
                                    }
                                }
                            }
                        });
                    } else {
                        $.ajax({
                            url: './db/servidor_pipeline.php',
                            type: 'POST',
                            data: {
                                quest: 'ingresar_producto_detalle_pipeline_nuevo',
                                idProductoNuevo: valor2.idProducto.innerHTML,
                                idProducto: '',
                                observaciones: valor2.observacionesProducto.innerHTML
                            },
                            success: function (res) {
                                console.log(res);
                                if (res != 'Successfully') {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'No se ha podido agregar el producto',
                                        showConfirmButton: false,
                                        timer: 1500
                                    })
                                    return;
                                } else {
                                    cont++
                                    if(cont == elementos.length) {
                                        resolve(ingresar_historial_pipeline());
                                    }
                                }
                            }
                        });
                    }
                }, 2000);
            })
            
        })
    })
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

function ingresar_historial_pipeline() {
    var fMaxima = new Date();
    var añoAnterior = (fMaxima.getFullYear() - 1);
    var fMinima = new Date(añoAnterior, 0);
    var fechaMaxima = fMaxima.getFullYear() + "-12-" + fMaxima.getDate();
    var fechaMinima = fMinima.getFullYear() + "-1-" + fMinima.getDate();
    var estado = document.getElementById('estado').value;
    return new Promise(resolve => {
        setTimeout(() => {
            $.ajax({
                url: './db/servidor_pipeline.php',
                type: 'POST',
                data: {
                    quest: 'ingresar_historial_pipeline',
                    estado: estado
                },
                success: function (res) {
                    console.log(res);
                    if (res == "Successfuly") {
                        Swal.fire({
                            title: 'Ingresado',
                            text: 'El pipeline ha sido ingresado',
                            icon: 'success',
                            confirmButtonText: 'Ok'
                        }).then((result) => {
                            sessionStorage.setItem("maximaFecha", fechaMaxima);
                            sessionStorage.setItem("minimaFecha", fechaMinima);
                            if (result.isConfirmed) {
                                resolve(window.location.href = './listado_pipeline.html')
                            }
                        });
                    } else {
                        Swal.fire({
                            title: 'Ha Ocurrido Un Error',
                            text: 'No se ha podido ingresar el historial del pipeline, por favor, comuniquese con el departamento de informatica',
                            icon: 'error',
                            confirmButtonText: 'Ok'
                        });
                    }
                }
            });
        }, 2000)

    })
}

function validaciones() {
    var id_vendedor = document.getElementById('idVendedor').value;;
    var codigo_cliente = document.getElementById('idCliente').value;
    var nombre_cliente = document.getElementById('cliente').value;
    var tipo_moneda = document.getElementById('tipo_moneda').value;
    var tipo_pipeline = document.getElementById('tipoPipeline').value;
    var precio = document.getElementById('precio').value;
    var oportunidad_anio = document.getElementById('oportunidadAnio').value;
    var potencial = document.getElementById('potencial').value;
    var estado = document.getElementById('estado').value;
    var inicio_oportunidad = document.getElementById('inicioOportunidad').value;
    var productos = elementos.length;

    if (id_vendedor == "" || codigo_cliente == "" || nombre_cliente == "" || tipo_moneda == "" || tipo_pipeline == "" || precio == "" || oportunidad_anio == "" || potencial == "" || estado == "" ||
        inicio_oportunidad == "" || productos <= 0) {
        return false;
    } else {
        return true;
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