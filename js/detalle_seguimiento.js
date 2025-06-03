var idSeguimiento = sessionStorage.getItem('idSeguimiento')
var fechaCreacion;

$(document).ready(function () {
    validarUsuarioLogueado();
    llenarInputs();
    historialSeguimiento();
});

function validarUsuarioLogueado() {
    $.ajax({
        url: '../inventarios_php/bd/servidor.php',
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
}

function validarInputIdPedido() {
    var tipoSeguimiento = document.getElementById('idStatus').value
    var inputIDPedido = document.getElementById('idPedidoUnhesa')
    var lblInputIDPedido = document.getElementById('lblIdPedidoUnhesa')
    var brInputIDPedido = document.getElementById('brIdPedidoUnhesa')
    var inputProximoContacto = document.getElementById('proximoContacto')

    if (tipoSeguimiento == "8") {
        inputIDPedido.hidden = false;
        lblInputIDPedido.hidden = false;
        brInputIDPedido.hidden = false;
        template = `ID del Pedido:`
        $("#lblIdPedidoUnhesa").html(template);
        inputProximoContacto.value = new Date().toISOString().split('T')[0].slice(0, 10);
    } else if(tipoSeguimiento == "7") {
        inputIDPedido.hidden = false;
        lblInputIDPedido.hidden = false;
        brInputIDPedido.hidden = false;
        ultimoIdPedido();
        inputProximoContacto.value = new Date().toISOString().split('T')[0].slice(0, 10);
    } else {
        inputIDPedido.hidden = true;
        lblInputIDPedido.hidden = true;
        brInputIDPedido.hidden = true;
    }
}

function limpiarInput() {
    document.getElementById('observaciones').value = "";
    document.getElementById('proximoContacto').value = "";
    document.getElementById('idPedidoUnhesa').value = "";
    validarInputIdPedido();
}

function llenarInputs() {
    $.ajax({
        url: '../inventarios_php/bd/servidor.php',
        type: 'GET',
        data: {
            quest: 'detalle_seguimiento',
            idSeguimiento
        },
        success: function(res) {
            console.log(res);
            try {
                let lista = JSON.parse(res);
                tiempoT = "";
                estado = "";
                lista.forEach(lista1 => {
                tiempoT += `Tiempo Transcurrido Desde Ultimo Seguimiento: <b>${lista1.tiempoTranscurrido} Días </b>`;
                document.getElementById('nombre_cliente').value = lista1.cliente;
                document.getElementById('observaciones').value = lista1.observaciones;
                document.getElementById('fechaCreacion').value = lista1.creacion;
                document.getElementById('idPedidoUnhesa').value = lista1.id_pedido;
                document.getElementById('proximoContacto').value = lista1.contacto;
                estado += `<option value=${lista1.id_estado}>${lista1.estado}</option>`
                document.getElementById('idStatus').innerHTML = estado
                idEstadoSeleccionado = lista1.id_estado
            });
            validarInputIdPedido()
            document.getElementById('tiempo_transcurrido').innerHTML = tiempoT;
            } catch (error) {
                console.log(error);
            }
            
        }
    });
}

function historialSeguimiento() {
    $.ajax({
        url: '../inventarios_php/bd/servidor.php',
        type: 'GET',
        data: {
            quest: 'historial_seguimiento',
            idSeguimiento
        },
        success: function(res) {
            try {
                let lista = JSON.parse(res);
                template = "";
                fecha_inicial = "";
                fecha_final = "";
                lista.forEach(lista => {
                    var fMinima = new Date(lista.fecha.replace(/-/g, "/"));
                    fecha_inicial = fMinima;
                    if (fecha_final == "") {
                        diferencia = fecha_inicial - fMinima;
                    } else {
                        diferencia = fMinima - fecha_final;
                    }
                    diasTranscurridos = Math.floor(diferencia / (1000 * 60 * 60 * 24));
                    template += `
                    <tr>
                        <td>${lista.id}</td>
                        <td>${lista.estado}</td>
                        <td>${lista.fecha}</td>
                        <td>${lista.observaciones}</td>
                        <td>${diasTranscurridos} días</td>
                    </tr>`;
                    fecha_final = fecha_inicial;
                });
                document.getElementById("tablaHistorial").innerHTML = template;
            } catch (error) {
                console.log(error);
            }
        }
    });
}

function editarSeguimiento() {
    document.getElementById('observaciones').disabled = false
    document.getElementById('proximoContacto').disabled = false
    document.getElementById('idStatus').disabled = false
    document.getElementById('idPedidoUnhesa').disabled = false
    document.getElementById('botonGuardar').hidden = false
    document.getElementById('botonEditar').hidden = true

    llenarComboboxEstado();
}

function llenarComboboxEstado() {
    $.ajax({
        url: '../inventarios_php/bd/servidor.php',
        type: 'GET',
        data: {
            quest: 'estado_seguimiento'
        },
        success: function (res) {
            template = ``;
            if (res != 'No') {
                let lista = JSON.parse(res);
                lista.forEach(lista => {
                    if (lista.id == idEstadoSeleccionado) {
                        template += `<option value="${lista.id}" selected>${lista.nombre}</option>`;
                    } else {
                        template += `<option value="${lista.id}">${lista.nombre}</option>`;
                    }
                });
                document.getElementById('idStatus').innerHTML = template;
            } else {
            }
        }
    });
}

function ultimoIdPedido() {
    var cliente = sessionStorage.getItem('idCliente')
    $.ajax({
        url: '../inventarios_php/bd/servidor.php',
        type: 'GET',
        data: {
            quest: 'ultimo_id_pedido',
            cliente,
        },
        success: function (res) {
            template = ``;
            if (res != 'No') {
                var lista = JSON.parse(res)
                template += `<b>ID del Pedido:</b> #${lista[0].pedido} - ${lista[0].cliente}`
            } else {
                template += `ID del Pedido:`
            }
            $("#lblIdPedidoUnhesa").html(template);
        }
    });
}

function validarIDPedido() {
    var idPedido = document.getElementById('idPedidoUnhesa').value
    var tipoSeguimiento = document.getElementById('idStatus').value
    if (tipoSeguimiento == "7" || tipoSeguimiento == "8") {
        if (idPedido != "") {
            $.ajax({
                url: '../inventarios_php/bd/servidor.php',
                type: 'GET',
                data: {
                    quest: 'validar_id_pedido',
                    idPedido
                },
                success: function (res) {
                    if (res != 'Si') {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'El pedido ingresado no existe',
                        })
                        return;
                    } else {
                        validarProximoContacto()
                    }
                }
            });
        } else {
            Swal.fire({
                icon: 'warning',
                title: 'ID Pedido Vacio',
                text: "A dejado el campo ID Pedido vacio",
            })
        }
    } else {
        validarProximoContacto()
    }

}


function validarProximoContacto() {
    var proximoContacto = document.getElementById('proximoContacto').value;
    var mesCreacion = moment(fechaCreacion).format('MM');
    var mesProximoContacto = moment(proximoContacto).format('MM');
    var anoCreacion = moment(fechaCreacion).format('YYYY');
    var anoProximoContacto = moment(proximoContacto).format('YYYY');
    if (proximoContacto != "") {
        if (mesProximoContacto != mesCreacion || anoProximoContacto != anoCreacion) {
            Swal.fire({
                icon: 'warning',
                title: 'Proximo Contacto Invalido',
                text: 'El mes y año del proximo contacto deben ser igual a la fecha de creación',
            })
            return;
        } else {
            validarProximoC()
        }
    } else {
        validarProximoC()
    }

}

function validarProximoC() {
    var proximoContacto = document.getElementById('proximoContacto').value
    if (proximoContacto == "") {
        Swal.fire({
            icon: 'warning',
            title: 'Proximo Contacto Vacio',
            text: "A dejado el campo de proximo contacto vacio, ¿Desea Continuar?",
            confirmButtonText: "Continuar",
            showDenyButton: true,
            denyButtonText: "Cancelar",
            showCloseButton: false
        }).then((result) => {
            if (result.isConfirmed) {
                validarObservacion();
            }
        })
    } else {
        validarObservacion();
    }
}

function validarObservacion() {
    var obs = document.getElementById('observaciones').value
    if (obs == "") {
        Swal.fire({
            icon: 'warning',
            title: 'Observacion Vacia',
            text: "A dejado el campo de observación vacio, ¿Desea Continuar?",
            confirmButtonText: "Continuar",
            showDenyButton: true,
            denyButtonText: "Cancelar",
            showCloseButton: false
        }).then((result) => {
            if (result.isConfirmed) {
                guardarSeguimiento();
            }
        })
    } else {
        guardarSeguimiento();
    }
}

function guardarSeguimiento() {
    var idEstado = document.getElementById('idStatus').value
    var Obs = document.getElementById('observaciones').value
    var idPedido = document.getElementById('idPedidoUnhesa').value
    var proximoContacto = document.getElementById('proximoContacto').value

    $.ajax({
        url: '../inventarios_php/bd/servidor.php',
        type: 'POST',
        data: {
            quest: 'editar_seguimiento',
            idEstado,
            Obs,
            idPedido,
            proximoContacto,
            idSeguimiento
        },
        success: function (res) {
            template = ``;
            if (res == 'Succesfully') {
                ingresar_historial_seguimiento();
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'A ocurrido un error al editar el seguimiento',
                })
            }
        }
    });
}

function bloquearInputs() {
    document.getElementById('observaciones').disabled = true
    document.getElementById('proximoContacto').disabled = true
    document.getElementById('idStatus').disabled = true
    document.getElementById('idPedidoUnhesa').disabled = true
    document.getElementById('botonGuardar').hidden = true
    document.getElementById('botonEditar').hidden = false
}

function ingresar_historial_seguimiento() {
    return new Promise(resolve => {
        cargando();
        setTimeout(() => {
            var idEstado = document.getElementById('idStatus').value
            var Obs = document.getElementById('observaciones').value
            $.ajax({
                url: '../inventarios_php/bd/servidor.php',
                type: 'POST',
                data: {
                    quest: 'guardar_historial_seguimiento',
                    idSeguimiento,
                    id_estado: idEstado,
                    Obs
                },
                success: function (res) {
                    if (res == 'Succesfully') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Seguimiento Editado',
                            text: 'El seguimiento a sido editado exitosamente',
                        }).then(() => {
                            resolve(llenarInputs(), historialSeguimiento(), bloquearInputs())
                        })
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'A ocurrido un error al guardar el historial del seguimiento',
                        })
                    }
                }
            });
        }, 3000)

    })
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

