var res = "";
$(document).ready(function () {
    let list = JSON.parse(localStorage.getItem('usuario'));

    if (!list) {
        window.location.href = "../login.php";
    }

    list.forEach(list => {
        document.getElementById('bienvenida').innerHTML = `Bienvenido <br> ${list.nombre}`;
        id_usuario = list.id;
        fecha = list.fecha;
    });


    listado();
});

function ShowSelected() {
    var cod = document.getElementById("producto").value;
    $.ajax({
        url: './bd/servidor.php',
        type: 'GET',
        data: {
            quest: 'id_usuario_inventario',
            id_inventario: cod
        },
        success: function (res) {
            let list = JSON.parse(res);
            list.forEach(list => {
                $.ajax({
                    url: './bd/servidor.php',
                    type: 'GET',
                    data: {
                        quest: 'id_walter'
                    },
                    success: function (res) {
                        let lista = JSON.parse(res);
                        lista.forEach(lista => {
                            if (id_usuario == list.id_usuario || id_usuario == lista.id_usuario) {
                                console.log('Si puede');
                                habilitar();
                            } else {
                                console.log('No puede');
                                Swal.fire({
                                    title: 'No Autorizado',
                                    text: "Usted no es la persona autorizada para activar este inventario",
                                    icon: 'warning'
                                });
                            }
                        });
                    }
                });
            });
        }
    });
}

function habilitar() {
    var cod = document.getElementById("producto").value;
    var fecha;

    let list = JSON.parse(localStorage.getItem('usuario'));

    if (!list) {
        window.location.href = "./login.html";
    }

    list.forEach(list => {
        fecha = `${list.fecha}`;
    });

    $.ajax({
        url: './bd/servidor.php',
        type: 'POST',
        data: {
            quest: 'habilitar_inventario',
            cod
        },
        success: function (res) {
            console.log(res);
            if (res == 'NO') {
                Swal.fire({
                    title: '¡Hoy no!',
                    text: "Hoy no es el día que se registró para aperturar este inventario",
                    icon: 'warning'
                });
            } else if (res == 'Successfuly') {
                Swal.fire({
                    title: '¡Activado!',
                    text: "Este inventario ha sido activado exitosamente!",
                    icon: 'success'
                });
                listado();
            }
        }
    });
}

function secondary() {
    window.location.href = "./php/habilitar.php";
}

function listado() {
    $.ajax({
        url: './bd/servidor.php',
        type: 'GET',
        data: {
            quest: 'id_walter'
        },
        success: function (res) {
            let lista = JSON.parse(res);
            lista.forEach(lista => {
                if (lista.id_usuario == id_usuario) {
                    $.ajax({
                        url: './bd/servidor.php',
                        type: 'GET',
                        data: {
                            quest: 'lista_inventario'
                        },
                        success: function (res) {
                            if (res == 'No') {
                                $("#producto").html(template);
                            }
                            else if (res) {
                                let list = JSON.parse(res);
                                let template = '';
                                list.forEach(list => {
                                    template += `
                                        <option value="${list.id}">${list.nombre} / ${list.fecha}</option>
                                    `;
                                });
                                $("#producto").html(template);
                            }
                        }
                    });
                } else {
                    $.ajax({
                        url: './bd/servidor.php',
                        type: 'GET',
                        data: {
                            quest: 'lista_inventario_individual',
                            id_usuario
                        },
                        success: function (res) {
                            if (res == 'No') {
                                $("#producto").html(template);
                            }
                            else if (res) {
                                let list = JSON.parse(res);
                                let template = '';
                                list.forEach(list => {
                                    template += `
                                        <option value="${list.id}">${list.nombre} / ${list.fecha}</option>
                                    `;
                                });
                                $("#producto").html(template);
                            }
                        }
                    });
                }
            });
        }
    });
}
function logout() {
    localStorage.removeItem('usuario');
    window.location.href = "./login.html";
}