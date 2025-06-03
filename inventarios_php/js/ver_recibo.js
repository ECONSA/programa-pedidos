var cod_cliente;
var id_vendedor;
$(document).ready(function () {

    $.ajax({
        url: './bd/servidor.php',
        type: 'GET',
        data: {
            quest: 'usuario'
        },
        success: function (idUsuario) {
            if (isNaN(idUsuario)) {
                window.location.href = '../login.php';
            } else {
                id_vendedor = idUsuario;
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
                    });
                }
            });
        }
    });

    var codigo = sessionStorage.getItem('id_cliente_unhesa');

    $.ajax({
        url: './bd/servidor.php',
        type: 'GET',
        data: {
            quest: 'nombre_cliente',
            codigo
        },
        success: function (res) {
            let list = JSON.parse(res);
            list.forEach(list => {
                document.getElementById('nombre_cliente').innerHTML = list.nombre;
                document.getElementById('codigo_cliente').innerHTML = list.codigo;
                cod_cliente = list.codigo;
                var codigo_cliente = list.codigo;
                $.ajax({
                    url: './bd/servidor.php',
                    type: 'GET',
                    data: {
                        quest: 'facturas_cliente',
                        codigo: codigo_cliente
                    },
                    success: function (resp) {
                        let lista = JSON.parse(resp);
                        let template = '';
                        var saldo_total = 0;
                        lista.forEach(lista => {
                            saldo_total += parseFloat(lista.saldo);
                            template += `
                                <tr>
                                    <td>${lista.factura}</td>
                                    <td>${lista.fecha_factura}</td>
                                    <td>Q.${parseFloat(lista.monto).toFixed(2)}</td>
                                    <td>Q.${parseFloat(lista.saldo).toFixed(2)}</td>
                                    <td>${lista.antiguedad}</td>
                                    <td><input type="checkbox" name="" value="${lista.saldo}" id="check_${lista.factura}" onchange="check(this, '${lista.factura}')"></td>
                                    <td>            
                                        <div class="input-group mb-3">
                                            <input type="number" min="0" style="width:100px;" class="form-control abono" placeholder="Abono" id="${lista.factura}" onchange="calcularTotalAbono()">
                                        </div>
                                    </td>
                                </tr>
                            `;
                            $("#bodi").html(template);
                        });
                        document.getElementById('saldo_cliente').innerHTML = saldo_total.toFixed(2);
                    }
                });
            });
        }
    });
});

function calcularTotalAbono() {
    let totalAbono = 0;

    $('.abono').each(function () {
        let valor = parseFloat($(this).val());
        if (!isNaN(valor)) {
            totalAbono += valor;
        }
    });

    $('#monto_total_recibo').text('Monto Total Del Recibo: Q. ' + totalAbono.toFixed(2));
}


function check(checkboxElem, factura) {
    if (checkboxElem.checked) {
        document.getElementById(factura).value = checkboxElem.value;
    } else {
        document.getElementById(factura).value = 0;
    }
    calcularTotalAbono();
}


function guardar() {
    var banco = document.getElementById('banco').value;
    var numero_de_boleta = document.getElementById('numero_boleta').value;
    var fecha = document.getElementById('fecha').value;
    var codigo = sessionStorage.getItem('id_cliente_unhesa');
    var recibo_fisico = document.getElementById('recibo_fisico').value;
    var estado = 'recibido en contabilidad';
    var saldo_a_cobrar;
    if (numero_de_boleta == '') {
        estado = 'pendiente de deposito';
    }
    // Primero obtiene los valores ingresados en los abonos de la tabla de facturas y los suma en una variable
    return new Promise((resolve) => {
        cargando();
        $.ajax({
            url: './bd/servidor.php',
            type: 'GET',
            data: {
                quest: 'facturas_cliente',
                codigo: cod_cliente
            },
            success: function (resp) {
                let list = JSON.parse(resp);
                saldo_a_cobrar = 0;
                list.forEach(list => {
                    var saldo = document.getElementById(list.factura).value;
                    if (saldo != '') {
                        saldo_a_cobrar += parseFloat(saldo);
                    }
                });
                if (saldo_a_cobrar == 0) {
                    Swal.fire({
                        title: 'Epere!',
                        text: "No se han seleccionado las facturas a cobrar!",
                        icon: 'warning'
                    });
                    return;
                } else {
                    resolve('Facturas Llenas')
                }
            }
        })
    }).then(() => {
        // Despues ingresa la información del recibo en la base de datos
        return new Promise((resolve) => {
            $.ajax({
                url: './bd/servidor.php',
                type: 'POST',
                data: {
                    quest: 'ingresar_recibo',
                    id_usuario: id_vendedor,
                    id_cliente: codigo,
                    monto: saldo_a_cobrar,
                    recibo: recibo_fisico,
                    banco: banco,
                    numero_de_boleta: numero_de_boleta,
                    fecha: fecha,
                    estado
                },
                success: function (respuesta) {
                    if (respuesta == 'Successfuly') {
                        resolve('Successfuly');
                    } else {
                        Swal.fire({
                            title: 'Error Al Ingresar Recibo',
                            text: 'Ha ocurrido un error al ingresar recibo, por favor intentalo de nuevo',
                            icon: 'error',
                            showCancelButton: false,
                        })
                    }
                }
            });
        }).then((res) => {
            // Despues ingresa los dellates del recibo en la base de datos
            return new Promise((resolve) => {
                if (res == 'Successfuly') {
                    $.ajax({
                        url: './bd/servidor.php',
                        type: 'GET',
                        data: {
                            quest: 'facturas_cliente',
                            codigo: cod_cliente
                        },
                        success: function (result) {
                            let list = JSON.parse(result);
                            list.forEach(list => {
                                var saldo = document.getElementById(list.factura).value;
                                if (saldo != '') {
                                    var factura = list.factura;
                                    var saldo_a_cobrar = list.saldo;
                                    var abono = 0;
                                    abono = parseFloat(saldo);
                                    $.ajax({
                                        url: './bd/servidor.php',
                                        type: 'POST',
                                        data: {
                                            quest: 'ingresar_detalle_recibo',
                                            factura: factura,
                                            saldo_a_cobrar: saldo_a_cobrar,
                                            abono: abono,
                                            saldo: parseFloat(parseFloat(saldo_a_cobrar) - parseFloat(abono))
                                        },
                                        success: function (resp) {
                                            if (resp != "") {
                                                sessionStorage.setItem('id_recibo', resp)
                                                resolve('Detalle Ingresado');
                                            }
                                        }
                                    });
                                }
                            });
                        }
                    });
                } else {
                    Swal.fire({
                        title: 'Error Al Ingresar Recibo',
                        text: 'Ha ocurrido un error al ingresar recibo, por favor intentalo de nuevo',
                        icon: 'error',
                        showCancelButton: false,
                    })
                }
            }).then(() => {
                //Por ultimo muestra el mensaje de ingreso exitoso e ingresa la imagen seleccionada a la base de datos
                Swal.fire({
                    title: 'Recibo Ingresado',
                    text: 'El recibo fue ingresado de manera exitosa',
                    icon: 'success',
                    showCancelButton: false,
                    allowOutsideClick: false
                }).then(() => {
                    document.getElementById("submit").click();
                })
            })
        })
    })

}

function logout() {
    localStorage.removeItem('usuario');
    window.location.href = "../login.php";
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