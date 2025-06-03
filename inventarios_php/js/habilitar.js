$(document).ready(function () {
    let list = JSON.parse(localStorage.getItem('usuario'));

    if (!list) {
        window.location.href = "../login.php";
    }
    listado();
});

function listado() {
    $.ajax({
        url: './bd/servidor.php',
        type: 'GET',
        data: {
            quest: 'lista_inventario_activos'
        },
        success: function (res) {
            // console.log(res);
            if (res == 'No') {
                Swal.fire({
                    title: 'No hay Inventarios',
                    text: "Por el momento no hay inventarios con fechas próximas a aperturar.",
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
                $("#producto").html(template);
            }
        }
    });
}

function buscar() {
    var id = document.getElementById('id_conteo_busqueda').value;
    var id_inventario = document.getElementById('producto').value;
    if (id) {
        if (id_inventario) {
            $.ajax({
                url: './bd/servidor.php',
                type: 'GET',
                data: {
                    quest: 'traer_conteo',
                    id,
                    id_inventario
                },
                success: function (res) {
                    console.log(res);
                    if (res == 'No') {
                        Swal.fire({
                            title: 'Espere!',
                            text: "Este ID no corresponde al Inventario Ingresado O no tiene el estado de 'Contado'",
                            icon: 'error'
                        });
                    }
                    else if (res) {
                        Swal.fire({
                            title: 'Se encontró',
                            text: "Vea el registro",
                            icon: 'success'
                        });
                        let list = JSON.parse(res);
                        list.forEach(list => {
                            document.getElementById('id_conteo').value = list.id;
                            document.getElementById('id_descripcion').value = list.descripcion_sap;
                            document.getElementById('codigo_sap').value = list.codigo_sap;
                            document.getElementById('unidad_medidas').value = list.unidad_medida;
                            document.getElementById('bodega').value = `${list.codigo_bodega} - ${list.nombre_bodega}`;
                            document.getElementById('existencia').value = list.existencia_sap;
                            document.getElementById('costo').value = list.costo_promedio;
                            document.getElementById('conteo').value = list.conteo_fisico;
                            document.getElementById('cantidad_diferencia').value = list.cantidad_diferencia;
                            document.getElementById('costo_diferencia').value = list.costo_diferencia;
                            document.getElementById('empresa').value = list.empresa;
                            document.getElementById('usuario').value = list.usuario;
                            document.getElementById('estado').value = list.estado;
                            document.getElementById('inventario').value = list.inventario;
                            document.getElementById('riesgo').value = list.riesgo;
                        });
                    }
                }
            });
        } else {
            Swal.fire({
                title: 'No hay Inventario',
                text: "Parece que no ha seleccionado un Inventario!",
                icon: 'warning'
            });
        }
    } else {
        Swal.fire({
            title: 'No hay ID',
            text: "Por favor, ingrese el ID a buscar",
            icon: 'warning'
        });
    }
}

function reconteo() {
    var id = document.getElementById('id_conteo').value;
    if (id != "") {
        $.ajax({
            url: './bd/servidor.php',
            type: 'POST',
            data: {
                quest: 'recontar',
                id
            },
            success: function (res) {
                Swal.fire({
                    title: 'Se ha habilitado para recontar',
                    text: "La operación ha sido realizada con éxito",
                    icon: 'success',
                    confirmButtonText: `OK`,
                }).then((result) => {
                    if (result.isConfirmed) {
                        location.reload();
                    }
                });
            }
        });
    } else {
        Swal.fire({
            title: 'Espere!',
            text: "No ha ingresado ningún ID aún",
            icon: 'warning'
        });
    }

}
function logout() {
    localStorage.removeItem('usuario');
    window.location.href = "./login.html";
}