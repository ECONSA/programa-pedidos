$(document).ready(function () {
    let list = JSON.parse(localStorage.getItem('usuario'));

    if (!list) {
        window.location.href = "../login.php";
    }
    listado();
});

function diferencias() {
    $.ajax({
        url: './bd/servidor.php',
        type: 'GET',
        data: {
            quest: 'diferencia_inventario',
            id_inventario: document.getElementById('inventario').value
        },
        success: function (res) {
            let list = JSON.parse(res);
            list.forEach(list => {
                document.getElementById('diferencia').innerHTML = list.Diferencia_Inventario;
            });
        }
    });
}

function listado() {
    console.log('Hola');
    $.ajax({
        url: './bd/servidor.php',
        type: 'GET',
        data: {
            quest: 'categoria'
        },
        success: function (res) {
            if (res == 'No') {
                Swal.fire({
                    title: 'No hay Categorias',
                    text: "Por el momento no hay categorias",
                    icon: 'error'
                });
            }
            else if (res) {
                let list = JSON.parse(res);
                let template = '';
                template += `
                        <option>-- Seleccione una categoría --</option>
                    `;
                list.forEach(list => {
                    template += `
                        <option value="${list.id}">${list.nombre}</option>
                    `;
                });
                $("#Estado").html(template);
            }
        }
    });
}

function buscar() {
    console.log(document.getElementById('inventario').value);
    recontar();
    extraordinarios();
    diferencias();
    cantidad_diferencia();
    chart();
}

function extraordinarios(){
    $.ajax({
        url: './bd/servidor.php',
        type: 'GET',
        data: {
            quest: 'extraordinario_inventario',
            id_inventario: document.getElementById('inventario').value
        },
        success: function (res) {
            console.log(res);
            let list = JSON.parse(res);
            list.forEach(list => {
                document.getElementById('extraordinarias_inventario').innerHTML = list.extraordinario;
            })
        }
    });
}

function recontar(){
    $.ajax({
        url: './bd/servidor.php',
        type: 'GET',
        data: {
            quest: 'reconteos_inventario',
            id_inventario: document.getElementById('inventario').value
        },
        success: function (res) {
            console.log(res);
            let list = JSON.parse(res);
            list.forEach(list => {
                document.getElementById('reconteo_inventario').innerHTML = list.reconteo;
            })
        }
    });
}

function diferencias_totales(){
    sessionStorage.setItem('id_inventario', document.getElementById('inventario').value)
    console.log('sesión: '+sessionStorage.getItem('id_inventario'));
    if(sessionStorage.getItem('id_inventario') != ''){
        window.location.href = "./lista_conteos.html";
    }else{
        Swal.fire({
            title: 'No ha seleccionado un inventario',
            text: "Seleccione un inventario para continuar.",
            icon: 'warning'
        });
    }
}
7
function cantidad_diferencia(){
    $.ajax({
        url: './bd/servidor.php',
        type: 'GET',
        data: {
            quest: 'conteos_diferencia',
            id_inventario: document.getElementById('inventario').value
        },
        success: function (res) {
            let list = JSON.parse(res);
            list.forEach(list => {
                document.getElementById('diferencias_totales').innerHTML = list.diferencia;
            })
        }
    });
}

function inventarios() {
    $.ajax({
        url: './bd/servidor.php',
        type: 'GET',
        data: {
            quest: 'inventario',
            idCategoria: document.getElementById('Estado').value
        },
        success: function (res) {
            if (res == 'No') {
                Swal.fire({
                    title: 'No hay Categorias',
                    text: "Por el momento no hay categorias",
                    icon: 'error'
                });
            }
            else if (res) {
                let list = JSON.parse(res);
                let template = '';
                list.forEach(list => {
                    template += `
                        <option value="${list.id}">${list.nombre} / ${list.fecha}</option>
                    `;
                });
                $("#inventario").html(template);
            }
        }
    });
}