$(document).ready(function () {
    $.ajax({
        url: './inventarios_php/bd/servidor.php',
        type: 'GET',
        data: {
            quest: 'usuario'
        },
        success: function (idUsuario) {
            if (isNaN(idUsuario)) {
                window.location.href = './login.php';
            }
        }
    })
    filtroActual();
    seguimientos();
})


function filtroActual() {
    document.getElementById('mes_seguimiento').value = moment().month()+1;
    document.getElementById('año_seguimiento').value = moment().year();
}

function seguimientos() {
    var vendedor = document.getElementById('idVendedor').value;
    $.ajax({
        url: '../inventarios_php/bd/servidor.php',
        type: 'GET',
        data: {
            quest: 'ver_seguimientos',
            vendedor
        },
        success: function (res) {
            let list = JSON.parse(res);
            var template = '';
            list.forEach(list => {
                template += `
                <tr>
                    <td>${list.id}</td>
                    <td>${list.cliente}</td>
                    <td>${list.estado}</td>
                    <td>${list.fecha}</td>
                    <td>${list.pedido}</td>
                    <td align = 'center'>
                        <a href = 'javascript:detalleSeguimiento(${list.id}, ${list.idCliente});'>
                        <i class=\"fas fa-search\"></i>
                    </td>
                </tr>
                `;
            });
            document.getElementById('seguimiento').innerHTML = template;
            $('#tablaSeguimiento').DataTable({
                order: [[0, 'desc']],
            });
        }
    });
}

function SeguimientoUNHESA() {
    var mes = document.getElementById('mes_seguimiento').value;
    var año = document.getElementById('año_seguimiento').value;
    var vendedor = document.getElementById('idVendedor').value;
    $.ajax({
        url: '../inventarios_php/bd/servidor.php',
        type: 'GET',
        data: {
            quest: 'ver_seguimientos_fecha',
            mes,
            año,
            vendedor
        },
        success: function (res) {
            try {
                if (res == 'no hay registros') {
                    let templates = '';

                    Swal.fire(
                        'Sin Seguimientos',
                        'Al parecer no hay seguimientos en este plazo de tiempo',
                        'info'
                    )
                    let template = '';
                    document.getElementById('seguimiento').innerHTML = template;
                    $('#dataTable').DataTable();
                } else {
                    let lista = JSON.parse(res);
                    let template = '';
                    lista.forEach(list => {
                        template +=

                            `<tr>
                    <td>${list.id}</td>
                    <td>${list.cliente}</td>
                    <td>${list.estado}</td>
                    <td>${list.fecha}</td>
                    <td>${list.pedido}</td>
                    <td align = 'center'>
                        <a href = 'javascript:detalleSeguimiento(${list.id}, ${list.idCliente});'>
                        <i class=\"fas fa-search\"></i>
                    </td>
                    </tr>`
                    });
                    document.getElementById('seguimiento').innerHTML = template;
                    $('#tablaSeguimiento').DataTable();
                }
            } catch (error) {

                $('#tablaSeguimiento').DataTable();
            }
            $('#tablaSeguimiento').DataTable();
        }

    });
}

function detalleSeguimiento(idSeguimiento, idCliente) {
    sessionStorage.setItem('idSeguimiento', idSeguimiento)
    sessionStorage.setItem('idCliente', idCliente)
    window.location = "./DetalleSeguimiento.php"
}
