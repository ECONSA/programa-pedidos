$(document).ready(function() {

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
            quest: 'nombre_usuario'
        },
        success: function(res) {
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

    $.ajax({
        url: './bd/servidor.php',
        type: 'GET',
        data: {
            quest: 'ver_pedido_online',
            id_pedido: sessionStorage.getItem('id_pedido_online')
        },
        success: function (res) {
            var idPedido = sessionStorage.getItem('id_pedido_online')
            let list = JSON.parse(res);
            list.forEach(list => {
                document.getElementById('bienvenida').innerHTML = `Pedido Online #${idPedido}`
                document.getElementById('nombre').value = list.nombre;
                document.getElementById('direccion').value = list.direccion;
                document.getElementById('departamento').value = list.departamento;
                document.getElementById('municipio').value = list.municipio;
                document.getElementById('telefono').value = list.telefono;
                document.getElementById('nombre_factura').value = list.nombre_factura;
                document.getElementById('nit').value = list.nit;
                document.getElementById('sticker').value = list.stickers;
                document.getElementById('servicio').value= list.servicio;
                document.getElementById('observacion').value = list.observaciones;
                document.getElementById('fecha_entrega').value = list.fecha_entrega;
                if (list.estado == 'confirmado') {
                    document.getElementById('estado').innerHTML = `
                    <h3>Estado</h3>
                    <button class="btn btn-outline-success btn-block">${list.estado}</button>`;
                }else if(list.estado == 'rechazado'){
                    document.getElementById('estado').innerHTML = `
                    <h3>Estado</h3>
                    <button class="btn -outline--danger btn-block">${list.estado}</button>`;
                }else{
                    document.getElementById('estado').innerHTML = `
                    <h3>Estado</h3>
                    <button class="btn btn-outline-primary btn-block">${list.estado}</button>`;
                    document.getElementById('eliminar').innerHTML = `
                    <h3>Eliminar Pedido</h3>
                    <button class="btn btn-danger btn-block" onclick="eliminar()">Eliminar</button>`;
                    document.getElementById('editar').innerHTML = `
                    <h3>Editar Pedido</h3>
                    <button class="btn btn-info btn-block" onclick="editar()">Editar</button>`;
                }
                
            });
        }
    });

    $.ajax({
        url: './bd/servidor.php',
        type: 'GET',
        data: {
            quest: 'ver_detalle_online',
            id_pedido: sessionStorage.getItem('id_pedido_online')
        },
        success: function (res) {
            let list = JSON.parse(res);
            var template = '';
            list.forEach(list => {
                template += `
                <tr>
                    <td>${list.producto}</td>
                    <td>${parseFloat(list.precio).toFixed(2)}</td>
                    <td>${parseFloat(list.cantidad).toFixed(2)}</td>
                    <td>${parseFloat(list.total).toFixed(2)}</td>
                </tr>
                `;
            });
            document.getElementById('tabla').innerHTML = template;
        }
    });

});

function eliminar() {
    Swal.fire({
        title: '¿Esta Seguro De Eliminar Este Pedido?',
        text: "Esto eliminara el pedido y sus detalles, la información ya no se podra recuperar",
        icon: 'warning',
        showCancelButton: true,
        cancelButtonColor: '#3085d6',
        confirmButtonColor: '#d33',
        confirmButtonText: 'Eliminar'
      }).then((result) => {
        if (result.isConfirmed) {
            $("#eliminarPedidoModal").modal();
        }
      })
}

function eliminar_pedido() {
    var idPedidoInput = document.getElementById("idPedido").value;
    var idPedido = sessionStorage.getItem('id_pedido_online');
    if(idPedidoInput != "") {
        if(idPedido == idPedidoInput) {
            $.ajax({
                url: './bd/servidor.php',
                type: 'POST',
                data: {
                    quest: 'eliminar_detalle_pedido',
                    idPedido
                },
                success: function(res) {
                    if(res == 'Successfuly') {
                        $.ajax({
                            url: './bd/servidor.php',
                            type: 'POST',
                            data: {
                                quest: 'eliminar_pedido',
                                idPedido
                            },
                            success: function(res) {
                                if(res == 'Successfuly') {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Pedido Eliminado Exitosamente',
                                        text: 'Se ha eliminado el pedido de manera exitosa.',
                                        showConfirmButton: false,
                                    });
                                    setTimeout(() => {
                                        window.location.href = './lista_pedidos_online.html';
                                    }, 1500);
                                    
                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Error Al Eliminar Pedido',
                                        text: 'Ha ocurrido un error al eliminar el pedido, por favor, comunicate con el departamento de informatica.',
                                      });
                                }
                            }
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error Al Eliminar Detalle Del Pedido',
                            text: 'Ha ocurrido un error al eliminar el detalle del pedido, por favor, comunicate con el departamento de informatica.',
                          });
                    }
                }
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'ID Incorrecto',
                text: 'El ID que ingreso no coincide con el ID del pedido a eliminar',
              });
        }
    } else {
        Swal.fire({
            icon: 'warning',
            title: 'Ingrese El Id Del Pedido',
            text: 'Por favor, ingrese el id del pedido para confirmar la eliminación',
          });
    }
    
}

function editar(){
    window.location.href = 'editar_pedido_online.html';
}