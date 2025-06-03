// recibos.js

$(document).ready(function () {

    // Inicializar Select2 para buscar clientes
    $('.idCliente').select2({
        placeholder: 'Codigo del cliente',
        ajax: {
            url: 'ajax.php',
            dataType: 'json',
            delay: 250,
            data: function(params) {
                // Select2 manda el término de búsqueda en params.term,
                // aquí lo enviamos como “q” para que ajax.php lo reciba correctamente
                return {
                    q: params.term
                };
            },
            processResults: function(data) {
                return {
                    results: data
                };
            },
            cache: true
        }
    });

    // 1) Verificar sesión de usuario
    $.ajax({
        url: './bd/servidor.php',
        type: 'GET',
        data: {
            quest: 'usuario'
        },
        success: function(idUsuario) {
            if (isNaN(idUsuario)) {
                window.location.href = '../login.php';
            }
        }
    });

    // 2) Obtener nombre de usuario y mostrarlo en el topbar
    $.ajax({
        url: './bd/servidor.php',
        type: 'GET',
        data: {
            quest: 'nombre_usuario'
        },
        success: function(res) {
            var nombre = res;
            console.log(nombre);
            document.getElementById('nombre_del_usuario').innerHTML = nombre;
        }
    });

    // 3) Color inicial del sidebar (por defecto rojo)
    document.getElementById("accordionSidebar").className = "navbar-nav bg-gradient-danger sidebar sidebar-dark accordion toggled";

    // 4) Ajustar color del sidebar según empresa del usuario
    $.ajax({
        url: './bd/servidor.php',
        type: 'GET',
        data: {
            quest: 'usuario'
        },
        success: function(idUsuario) {
            $.ajax({
                url: './bd/servidor.php',
                type: 'GET',
                data: {
                    quest: 'empresa_usuario',
                    id_usuario: idUsuario
                },
                success: function(resp) {
                    let lista = JSON.parse(resp);
                    lista.forEach(item => {
                        if (item.id_empresa == 1) {
                            // Empresa 1: degradado rojo
                            document.getElementById("accordionSidebar").className =
                                "navbar-nav bg-gradient-danger sidebar sidebar-dark accordion toggled";
                        } else {
                            // Otras empresas: degradado azul
                            document.getElementById("accordionSidebar").className =
                                "navbar-nav bg-gradient-primary sidebar sidebar-dark accordion toggled";
                        }
                    });
                }
            });
        }
    });

});

// Función para guardar el código de cliente y redirigir a la vista de recibo
function codigo_cliente() {
    var codigo = document.getElementById('idCliente').value;
    sessionStorage.setItem('id_cliente_unhesa', codigo);
    location.href = "./ver_recibo.html";
}

// Función de logout: limpia localStorage y vuelve al login
function logout() {
    localStorage.removeItem('usuario');
    window.location.href = "../login.php";
}
