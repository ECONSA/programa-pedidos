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
    var id_inventario = document.getElementById('producto').value;
    // console.log(id_inventario);
    $.ajax({
        url: './bd/servidor.php',
        type: 'GET',
        data: {
            quest: 'dashboard_econsa',
            id_inventario
        },
        success: function (res) {
            console.log(res);
            var contados;
            var no_contados;
            var total;
            let list = JSON.parse(res);
            list.forEach(list => {
                contados = list.contados;
                no_contados = list.no_contados;
                total = list.total;
            });
            document.getElementById('contados_econsa').innerHTML = contados;
            document.getElementById('no_contados_econsa').innerHTML = no_contados;
            var porcentaje_contados_econsa = ((100 * contados) / (total));
            var porcentaje_no_contados_econsa = ((100 * no_contados) / (total));
            if (!porcentaje_contados_econsa) {
                porcentaje_contados_econsa = 0;
            }
            if (!porcentaje_no_contados_econsa) {
                porcentaje_no_contados_econsa = 0;
            }
            document.getElementById('%_contados_econsa').innerHTML = `${Math.round(porcentaje_contados_econsa)}%`;
            document.getElementById('%_no_contados_econsa').innerHTML = `${Math.round(porcentaje_no_contados_econsa)}%`;
            // Set new default font family and font color to mimic Bootstrap's default styling
            Chart.defaults.global.defaultFontFamily = 'Nunito', '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
            Chart.defaults.global.defaultFontColor = '#858796';

            // Pie Chart Example
            var ctx = document.getElementById("pieEconsa");
            var myPieChart = new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ["Contados", "No Contados"],
                    datasets: [{
                        data: [contados, no_contados],
                        backgroundColor: ['#4e73df', '#1cc88a', '#36b9cc'],
                        hoverBackgroundColor: ['#2e59d9', '#17a673', '#2c9faf'],
                        hoverBorderColor: "rgba(234, 236, 244, 1)",
                    }],
                },
                options: {
                    maintainAspectRatio: false,
                    tooltips: {
                        backgroundColor: "rgb(255,255,255)",
                        bodyFontColor: "#858796",
                        borderColor: '#dddfeb',
                        borderWidth: 1,
                        xPadding: 15,
                        yPadding: 15,
                        displayColors: false,
                        caretPadding: 10,
                    },
                    legend: {
                        display: false
                    },
                    cutoutPercentage: 80,
                },
            });
        }
    });
    $.ajax({
        url: './bd/servidor.php',
        type: 'GET',
        data: {
            quest: 'dashboard_unhesa',
            id_inventario
        },
        success: function (res) {
            console.log(res);
            var contados;
            var no_contados;
            var total;
            let list = JSON.parse(res);
            list.forEach(list => {
                contados = list.contados;
                no_contados = list.no_contados;
                total = list.total;
            });
            document.getElementById('contados_unhesa').innerHTML = contados;
            document.getElementById('no_contados_unhesa').innerHTML = no_contados;
            var porcentaje_contados_unhesa = ((100 * contados) / (total));
            var porcentaje_no_contados_unhesa = ((100 * no_contados) / (total));
            if (!porcentaje_contados_unhesa) {
                porcentaje_contados_unhesa = 0;
            }
            if (!porcentaje_no_contados_unhesa) {
                porcentaje_no_contados_unhesa = 0;
            }
            document.getElementById('%_contados_unhesa').innerHTML = `${Math.round(porcentaje_contados_unhesa)}%`;
            document.getElementById('%_no_contados_unhesa').innerHTML = `${Math.round(porcentaje_no_contados_unhesa)}%`;
            // Set new default font family and font color to mimic Bootstrap's default styling
            Chart.defaults.global.defaultFontFamily = 'Nunito', '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
            Chart.defaults.global.defaultFontColor = '#858796';

            // Pie Chart Example
            var ctx = document.getElementById("pieUnhesa");
            var myPieChart = new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ["Contados", "No Contados"],
                    datasets: [{
                        data: [contados, no_contados],
                        backgroundColor: ['#4e73df', '#1cc88a', '#36b9cc'],
                        hoverBackgroundColor: ['#2e59d9', '#17a673', '#2c9faf'],
                        hoverBorderColor: "rgba(234, 236, 244, 1)",
                    }],
                },
                options: {
                    maintainAspectRatio: false,
                    tooltips: {
                        backgroundColor: "rgb(255,255,255)",
                        bodyFontColor: "#858796",
                        borderColor: '#dddfeb',
                        borderWidth: 1,
                        xPadding: 15,
                        yPadding: 15,
                        displayColors: false,
                        caretPadding: 10,
                    },
                    legend: {
                        display: false
                    },
                    cutoutPercentage: 80,
                },
            });
        }
    });
    $.ajax({
        url: './bd/servidor.php',
        type: 'GET',
        data: {
            quest: 'dashboard_proquima',
            id_inventario
        },
        success: function (res) {
            console.log(res);
            var contados;
            var no_contados;
            var total;
            let list = JSON.parse(res);
            list.forEach(list => {
                contados = list.contados;
                no_contados = list.no_contados;
                total = list.total;
            });
            var porcentaje_contados_proquima = ((100 * contados) / (total));
            if(!porcentaje_contados_proquima){
                porcentaje_contados_proquima = 0;
            }
            var porcentaje_no_contados_proquima = ((100 * no_contados) / (total));
            if (!porcentaje_no_contados_proquima) {
                porcentaje_no_contados_proquima = 0;
            }
            document.getElementById('%_contados_proquima').innerHTML = `${Math.round(porcentaje_contados_proquima)}%`;
            document.getElementById('%_no_contados_proquima').innerHTML = `${Math.round(porcentaje_no_contados_proquima)}%`;

            document.getElementById('contados_proquima').innerHTML = contados;
            document.getElementById('no_contados_proquima').innerHTML = no_contados;
            // Set new default font family and font color to mimic Bootstrap's default styling
            Chart.defaults.global.defaultFontFamily = 'Nunito', '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
            Chart.defaults.global.defaultFontColor = '#858796';

            // Pie Chart Example
            var ctx = document.getElementById("pieProquima");
            var myPieChart = new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ["Contados", "No Contados"],
                    datasets: [{
                        data: [contados, no_contados],
                        backgroundColor: ['#4e73df', '#1cc88a', '#36b9cc'],
                        hoverBackgroundColor: ['#2e59d9', '#17a673', '#2c9faf'],
                        hoverBorderColor: "rgba(234, 236, 244, 1)",
                    }],
                },
                options: {
                    maintainAspectRatio: false,
                    tooltips: {
                        backgroundColor: "rgb(255,255,255)",
                        bodyFontColor: "#858796",
                        borderColor: '#dddfeb',
                        borderWidth: 1,
                        xPadding: 15,
                        yPadding: 15,
                        displayColors: false,
                        caretPadding: 10,
                    },
                    legend: {
                        display: false
                    },
                    cutoutPercentage: 80,
                },
            });
        }
    });
}

function buscarID() {
    var id = document.getElementById('id_conteo_busqueda').value;
    var id_inventario = document.getElementById('producto').value;
    if (id) {
        if (id_inventario) {
            $.ajax({
                url: './bd/servidor.php',
                type: 'GET',
                data: {
                    quest: 'traer_conteo_total',
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
function logout(){
    localStorage.removeItem('usuario');
    window.location.href = "./login.html";
}