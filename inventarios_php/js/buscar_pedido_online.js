$(document).ready(function () {

    n = new Date();
    //Año
    y = n.getFullYear();
    //Mes
    m = n.getMonth() + 1;
    //Día
    d = n.getDate();

    //Lo ordenas a gusto.
    document.getElementById("maximaFecha").innerHTML = d + "/" + m + "/" + y;

    setFechaMinima();

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
    var fecha = new Date();
    document.getElementById('maximaFecha').valueAsDate = new Date(fecha.getTime() + 86400000);
});

function setFechaMinima() {
    const now = new Date();
    const year = now.getFullYear();
    const month = String(now.getMonth() + 1).padStart(2, '0');
    const firstDay = `${year}-${month}-01`;

    document.getElementById('minimaFecha').value = firstDay;
}

window.onload = setFechaMinima;

function buscar() {
    if (document.getElementById('maximaFecha').value == '') {
        Swal.fire({
            title: 'Espere!',
            text: "Elija una fecha máxima para el rango de fechas por favor.",
            icon: 'warning'
        });
    } else {
        sessionStorage.setItem('fecha_minima', document.getElementById('minimaFecha').value);
        sessionStorage.setItem('fecha_maxima', document.getElementById('maximaFecha').value);

        console.log(sessionStorage.getItem('fecha_minima'));
        console.log(sessionStorage.getItem('fecha_maxima'));
        window.location.href = './lista_pedidos_online.html';
    }
}
