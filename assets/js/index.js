$(document).ready(function () {
    var usuario = sessionStorage.getItem('usuario');
    var lista = JSON.parse(sessionStorage.getItem('usuario'));

    if (!usuario) {
        window.location.href = './auth-login-basic.html';
    }

    lista.forEach(list => {
        document.getElementById('usuario').innerHTML = list.nombre;
    });

    $.ajax({
        url: './php/servidor.php',
        type: 'GET',
        data: {
            quest: 'recibos'
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
        }
    });
});

function detalle(id){
    sessionStorage.setItem('id_recibo', id);
    window.location.href = './detalle_recibo.html';
}

function filtrar(){

    var palabra = document.getElementById('buscador').value;
    console.log(palabra);

    $.ajax({
        url: './php/servidor.php',
        type: 'GET',
        data: {
            quest: 'recibos_filtro',
            palabra
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
                    <td>${list.monto}</td>
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
        }
    });
}