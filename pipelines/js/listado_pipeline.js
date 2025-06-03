var id_usuario

$(document).ready(function () {
    validarUsuarioLogueado();
    obtenerNombreUsuario();
    validarEmpresaUsuario();
    obtenerPipelines();
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

function obtenerPipelines() {
    var maximaFecha = sessionStorage.getItem("maximaFecha");
    var minimaFecha = sessionStorage.getItem("minimaFecha");
    var id_cliente = sessionStorage.getItem("id_cliente_unhesa");
    var id_prospecto = sessionStorage.getItem("id_prospecto");
    var prospecto = sessionStorage.getItem("prospecto");

    $.ajax({
        url: './db/servidor_pipeline.php',
        type: 'GET',
        data: {
            quest: 'lista_pipeline',
            maximaFecha,
            minimaFecha,
            id_cliente,
            id_prospecto,
            prospecto,
            idUsuario: id_usuario
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
                template = "";
                lista.forEach(lista => {
                    template += `
                    <tr>
                        <td>${lista.idPipeline}</td>
                        <td>${lista.cliente}</td>
                        <td>${lista.vendedor}</td>
                        <td>${lista.tipo}</td>
                        <td>${lista.producto}</td>
                        <td>${lista.moneda}.${lista.precio}</td>
                        <td>${lista.oportunidad}</td>
                        <td>${lista.potencial}</td>
                        <td>
                            <div class="progress">
                                <div class="progress-bar bg-danger" role="progressbar" style="width: ${lista.porcentaje}%" aria-valuenow="${lista.porcentaje}" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <br>
                            <div class="text-center">
                                <h4 class="small">
                                    <a font-weight-bold text-dark">${lista.porcentaje}%</a>
                                </h4>
                            </div>
                        </td>
                        <td>${lista.iOportunidad}</td>
                        <td>${lista.iFacturacion}</td>
                        <td>${lista.estado}</td>
                        <td>${lista.ultimaActualizacion}</td>
                        <td>${lista.tiempoTranscurrido} días</td>
                        <td align = 'center'>
                        <a href = 'javascript:detalle_pipeline(${lista.idPipeline}, ${lista.prospecto}, ${lista.producto_nuevo}, ${lista.id_cliente}, ${lista.id_producto});'>
                        <i class="fas fa-search"></i>
                        </td>
                    </tr>`;
                });
                document.getElementById("cuerpoTabla").innerHTML = template;
                $('#tablaGeneral').DataTable({
                    "order": [[ 0, "desc" ]]
                });
            }

        }
    });
}

function detalle_pipeline(idPipeline, idProspecto, idProductoNuevo, idCliente, idProducto) {
    if(idProspecto == undefined || idProspecto == 'undefined') {
        sessionStorage.setItem('detalle_prospecto', false);
    } else {
        if(idCliente == 2414 || idCliente == '2414') {
            sessionStorage.setItem('detalle_prospecto', true);
        } else {
            sessionStorage.setItem('detalle_prospecto', false);
        }
    }
    if(idProductoNuevo == undefined || idProductoNuevo == 'undefined') {
        sessionStorage.setItem('detalle_producto_nuevo', false)
    } else {
        if(idProducto == 1379 || idProducto == '1379') {
            sessionStorage.setItem('detalle_producto_nuevo', true)
        } else {
            sessionStorage.setItem('detalle_producto_nuevo', false)
        }
    }
    sessionStorage.setItem("id_pipeline_unhesa", idPipeline);
    window.location.href = "./detalle_pipeline.html";
}