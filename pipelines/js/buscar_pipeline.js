$(document).ready(function () {
    validarUsuarioLogueado();
    obtenerNombreUsuario();
    validarEmpresaUsuario();
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

function fechas() {
    var maximaFecha = document.getElementById("maximaFecha").value;
    var minimaFecha = document.getElementById("minimaFecha").value;
    if (maximaFecha == "" || minimaFecha == "") {
        minimaFecha = "";
        maximaFecha = "";
    }
    sessionStorage.setItem("id_cliente_unhesa", document.getElementById('id_cliente').value);
    sessionStorage.setItem("maximaFecha", maximaFecha);
    sessionStorage.setItem("minimaFecha", minimaFecha);
    sessionStorage.setItem("prospecto", "");
    sessionStorage.setItem("id_prospecto", "");
    window.location.href = "./listado_pipeline.html";
}