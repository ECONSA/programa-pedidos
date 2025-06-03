$(document).ready(function () {

    $.ajax({
        url: './bd/servidor.php',
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
                    quest: 'recibos',
                    id_usuario: idUsuario
                },
                success: function (res) {
                    let list = JSON.parse(res);
                    let template = '';
                    list.forEach(list => {
                        template += `
                        <tr>
                        <td>${list.id}</td>
                        <td>${list.id_usuario}</td>
                        <td>${list.id_cliente}</td>
                        <td>${list.fecha}
                        </td>
                        <td>${parseFloat(list.monto).toFixed(2)}</td>
                        <td>${list.recibo}</td>
                        <td>`
                        if (list.estado == 'rechazado') {
                            template += `<div class="badge badge-danger badge-pill">${list.estado}</div>`
                        } else if (list.estado == 'ingresado a sap') {
                            template += `<div class="badge badge-success badge-pill">${list.estado}</div>`
                        } else if (list.estado == 'pendiente de deposito') {
                            template += `<div class="badge badge-warning badge-pill">${list.estado}</div>`
                        } else {
                            console.log(list.estado);
                            template += `<div class="badge badge-primary badge-pill">${list.estado}</div>`
                        }
                        template += `
                        </td>
                        <td>
                        <button type="button" class="btn btn-primary btn-block" onclick="detalle('${list.id}')">Ver</button>
                        </td>
                        </tr>
                        `;
                    });
                    $("#tabla").html(template);
                    $('#tablaRecibos').DataTable();
                }
            });
            sessionStorage.setItem('id_de_usuario', idUsuario);
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
});

function detalle(id) {
    sessionStorage.setItem('id_recibo', id);
    window.location.href = './recibo_detalle.html';
}

function filtrar() {

    var palabra = document.getElementById('buscador').value;
    var id_usuario = sessionStorage.getItem('id_de_usuario');

    $.ajax({
        url: './bd/servidor.php',
        type: 'GET',
        data: {
            quest: 'recibos_filtro',
            palabra,
            id_usuario
        },
        success: function (res) {
            if (res == 'No') {
                return;
            }
            let list = JSON.parse(res);
            let template = '';
            list.forEach(list => {
                template += `
                <tr>
                    <td>${list.id}</td>
                    <td>${list.id_usuario}</td>
                    <td>${list.id_cliente}</td>
                    <td>${list.fecha}</td>
                    <td>${parseFloat(list.monto).toFixed(2)}</td>
                    <td>${list.recibo}</td>
                    <td>`
                if (list.estado == 'rechazado') {
                    template += `<div class="badge badge-danger badge-pill">${list.estado}</div>`
                } else if (list.estado == 'ingresado a sap') {
                    template += `<div class="badge badge-success badge-pill">${list.estado}</div>`
                } else {
                    template += `<div class="badge badge-primary b4446adge-pill">${list.estado}</div>`
                }
                template += `
                    </td>
                    <td>
                        <button type="button" class="btn btn-primary btn-block" onclick="detalle('${list.id}')">Ver</button>
                    </td>
                </tr>
                `;
            });
            $("#tabla").html(template);
            $('#tablaRecibos').DataTable();
        }
    });
}