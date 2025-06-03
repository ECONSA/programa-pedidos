$(document).ready(function () {

    $.ajax({
        url: './bd/recibo.php',
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

    var id_recibo = sessionStorage.getItem('id_recibo');

    $.ajax({
        url: './bd/recibo.php',
        type: 'GET',
        data: {
            quest: 'datos_recibo',
            id_recibo
        },
        success: function (res) {
            let list = JSON.parse(res);
            list.forEach(list => {
                document.getElementById('recibo_id').innerHTML = `Recibo de Caja #${list.id_recibo}`;
                document.getElementById('número_de_recibo').innerHTML = list.recibo;
                document.getElementById('cliente_name').innerHTML = list.cliente;
                document.getElementById('codigo').innerHTML = list.codigo_cliente;
                document.getElementById('fecha_recibo').innerHTML = list.fecha;
                $.ajax({
                    url: './bd/recibo.php',
                    type: 'GET',
                    data: {
                        quest: 'detalle_recibo',
                        id_recibo
                    },
                    success: function (res) {
                        var total = 0;
                        let lista = JSON.parse(res);
                        let template = '';
                        lista.forEach(lista => {
                            template += `
                            <tr class="border-bottom">
                                <td>
                                    <div class="font-weight-bold">${lista.factura}</div>
                                </td>
                                <td class="text-right font-weight-bold">Q.${parseFloat(lista.saldo_a_cobrar, 2)}</td>
                                <td class="text-right font-weight-bold">Q.${parseFloat(lista.saldo, 2)}</td>
                                <td class="text-right font-weight-bold">Q.${parseFloat(lista.abono, 2)}</td>
                            </tr>
                            `;
                            total += parseFloat(lista.abono);
                        });
                        template += `
                            <tr>
                                <td class="text-right pb-0" colspan="3">
                                    <div
                                        class="text-uppercase small font-weight-700 text-muted">
                                        <strong>Total:</strong></div>
                                </td>
                                <td class="text-right pb-0">
                                    <div class="h5 mb-0 font-weight-700 text-green"><strong>Q.${parseFloat(total, 2)}</strong></div>
                                </td>
                            </tr>
                        `;
                        document.getElementById('tabla').innerHTML = template;
                    }
                });
            });
        }
    });
});

function logout() {
    localStorage.removeItem('usuario');
    window.location.href = "./login.php";
}
88
function Div2IMG(divID) {
    html2canvas([document.getElementById(divID)], {
        onrendered: function (canvas) {
            var recibo_id = document.getElementById('recibo_id').textContent;
            var img = canvas.toDataURL('image/jpeg'); //o por 'image/jpeg' 
            //display 64bit imag
            // document.write('<img src="' + img + '"/>');
            var link = document.createElement('a');
            link.download = `${recibo_id}.png`;
            link.href = img;

            link.click();
        }
    });
}

function email() {
    cargando();
    var email = document.getElementById('email').value;
    var contenido = document.getElementById('detalles').innerHTML;
    var titulo = document.getElementById('recibo_id').innerHTML;
    var codigo_cliente = document.getElementById('codigo').innerHTML;
    var fecha_creacion = document.getElementById('fecha_recibo').innerHTML;
    var nombre_cliente = document.getElementById('cliente').innerHTML;

    html2canvas([document.getElementById('recibo')], {
        onrendered: function (canvas) {
            var img = canvas.toDataURL('image/png');
            contentHtml = `
                <img src="https://www.grupoeconsa.com/sites/default/files/unhesa.png" alt="Unhesa">
                <br>
                <br>
                <strong><center>UNIÓN HERMANOS S.A.</center></strong>
                <center>
                    <p>10 Av. 25-63 Zona 13 Interior 18-19 Zona 13<br>
                    Colonia Santa Fé. Ciudad Guatemala<br>
                    Tels.: 2310-5454 • 2310-5400<br>
                    NIT.:2303818-7<br>
                    servicioalcliente@grupoeconsa.com</p>
                </center>
                <br>
                <center>
                    <h2><strong>${titulo}</strong></h2><br>
                    <strong>Código Cliente: </strong>${codigo_cliente}<br>
                    <strong>Fecha de creación: </strong>${fecha_creacion}<br>
                    <strong>Recibimos de: </strong>${nombre_cliente}<br>
                </center>
                <br>
                <center>
                    ${contenido}
                </center>
                <br>
                <center>
                    <p>Toda cancelación de factura debe ser amparada por este recibo de caja debidamente firmado por nuestros representantes o por la caja de la empresa para ser valido, 
                    <strong>LOS CHEQUES SE RECIBEN BAJO RESERVA DE COBRO Y TODO CHEQUE RECHAZADO TIENE UN RECARGO DE Q.125.00 </strong></p>
                </center>
            `;

            $.ajax({
                url: 'http://localhost:3000/send-email',
                type: 'POST',
                data: {
                    conten: contentHtml,
                    email
                },
                success: function (res) {
                    console.log(res);
                    if (res == 'recibido') {
                        Swal.fire({
                            title: '¡Correo Enviado!',
                            text: "El correo ha sido enviado con éxito",
                            icon: 'success'
                        });
                    }else{
                        Swal.fire({
                            title: '¡Servidor caído!',
                            text: "El correo no ha sido enviado, por favor comuniquese con el departamento de informática",
                            icon: 'warning'
                        });
                    }
                }
            });
        }
    });
}


function whats() {
    cargando();
    if (document.getElementById('WhatsApp').value.length != 8) {
        Swal.fire({
            title: '¡Número Inválido!',
            text: "El número que usted ingreso, no es un número telefónico!",
            icon: 'warning'
        });
        return;
    }
    else {
        var id_recibo = sessionStorage.id_recibo;

        $.ajax({
            url: './bd/recibo.php',
            type: 'GET',
            data: {
                quest: 'datos_recibo',
                id_recibo
            },
            success: function (res) {
                console.log(res);
                let list = JSON.parse(res);
                list.forEach(list => {
                    $.ajax({
                        url: './bd/recibo.php',
                        type: 'GET',
                        data: {
                            quest: 'detalle_recibo',
                            id_recibo
                        },
                        success: function (res) {
                            let lista = JSON.parse(res);
                            let template = `
*UNIÓN HERMANOS S.A*.
10 Av. 25-63 Zona 13 Interior 18-19 Zona 13
Colonia Santa Fé. Ciudad Guatemala
Tels.: 2310-5454 • 2310-5400
NIT.:2303818-7
servicioalcliente@grupoeconsa.com

*Recibo de Caja #12*
                        `;
                            lista.forEach(lista => {
                                template += `
------------------------------------------
Factura No.: ${lista.factura}
Saldo a Cobrar: Q.${parseFloat(lista.saldo_a_cobrar, 2)}
Saldo Restante: Q.${parseFloat(lista.saldo, 2)}
Saldo Abonado: Q.${parseFloat(lista.abono, 2)}
------------------------------------------
                            `;
                            });
                            template += `
*Total:* Q.${parseFloat(list.monto_depositado, 2)}
                        `;
                            $.ajax({
                                url: 'http://localhost:3001/whatsapp',
                                type: 'POST',
                                data: {
                                    text: template,
                                    number: document.getElementById('WhatsApp').value
                                },
                                success: function (res) {
                                    console.log(res);
                                    if (res == "El mensaje fue enviado") {
                                        Swal.fire({
                                            title: '¡WhatsApp Enviado!',
                                            text: "El mensaje ha sido enviado con éxito",
                                            icon: 'success'
                                        });
                                    } else {
                                        Swal.fire({
                                            title: '¡Servidor caído!',
                                            text: "El correo no ha sido enviado, por favor comuniquese con el departamento de informática",
                                            icon: 'warning'
                                        });
                                    }
                                }
                            });
                        }
                    });
                });
            }
        });
    }
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