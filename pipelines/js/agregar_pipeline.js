$(document).ready(function () {
    validarUsuarioLogueado();
    obtenerNombreUsuario();
    validarEmpresaUsuario();
    obtenerClientesProspecto();
})

function validarUsuarioLogueado() {
    $.ajax({
        url: './db/servidor_pipeline.php',
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
        url: './db/servidor_pipeline.php',
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
        url: './db/servidor_pipeline.php',
        type: 'GET',
        data: {
            quest: 'usuario'
        },
        success: function(idUsuario) {
            $.ajax({
                url: './db/servidor_pipeline.php',
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

function obtenerClientesProspecto() {
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
            document.getElementById('browsers').innerHTML = template;
        }
    });
}

function limpiar_inputs() {
    document.getElementById('browser').value = "";
    $("#id_cliente").empty();
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

function verificar() {
    if (document.getElementById('id_cliente').value == "") {
        Swal.fire({
            icon: 'error',
            title: 'Cliente Inválido',
            text: 'Porfavor seleccione un cliente válido'
        });
    } else {
        sessionStorage.setItem('prospecto', false);
        sessionStorage.setItem('id_cliente_unhesa', document.getElementById('id_cliente').value);
        window.location.href = "./ingresar_pipeline.html";
    }
}

function verificar_prospecto() {
    if (document.getElementById('browser').value == "") {
        Swal.fire({
            icon: 'error',
            title: 'Cliente Inválido',
            text: 'Porfavor ingrese un cliente válido'
        });
    } else {
        sessionStorage.setItem('prospecto', true);
        var idCliente = document.getElementById('id_cliente_prospecto').value;
        console.log(idCliente);
        if(idCliente != "") {
            sessionStorage.setItem('id_cliente_unhesa', document.getElementById('id_cliente_prospecto').value);
            window.location.href = "./ingresar_pipeline.html";
        } else {
            $.ajax({
                url: './db/servidor_pipeline.php',
                type: 'POST',
                data: {
                    quest: 'ingresar_prospecto',
                    nombre_prospecto: document.getElementById('browser').value
                },
                success: function(res) {
                    if(res == 'Successfully'){
                        $.ajax({
                            url: './db/servidor_pipeline.php',
                            type: 'GET',
                            data: {
                                quest: 'obtener_id_prospecto',
                                nombre_prospecto: document.getElementById('browser').value
                            },
                            success: function(res) {
                                let lista = JSON.parse(res);
                                lista.forEach(lista => {
                                    sessionStorage.setItem('id_cliente_unhesa', lista.id);
                                });
                                window.location.href = "./ingresar_pipeline.html";
                            }
                        })
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error Al Crear Prospecto',
                            text: 'Por favor comunícate con el departamento de informática'
                        });
                    } 
                }
            })
        }
    }
}