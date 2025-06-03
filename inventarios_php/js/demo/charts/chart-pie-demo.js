$.ajax({
    url: './bd/servidor.php',
    type: 'GET',
    data: {
        quest: 'total_conteos_generales'
    },
    success: function (res) {
        let list = JSON.parse(res);
        list.forEach(list => {
            $.ajax({
                url: './bd/servidor.php',
                type: 'GET',
                data: {
                    quest: 'total_conteos_generales_positivos'
                },
                success: function (res) {
                    let lista = JSON.parse(res);
                    lista.forEach(lista => {
                        $.ajax({
                            url: './bd/servidor.php',
                            type: 'GET',
                            data: {
                                quest: 'total_conteos_generales_negativos'
                            },
                            success: function (res) {
                                let listo = JSON.parse(res);
                                listo.forEach(listo => {
                                    var total = (list.conteos - lista.conteos - listo.conteos);
                                    var porcenaje_sin_diferencia = (100 * total)/list.conteos;
                                    var porcenaje_con_diferencia_posi = (100 * lista.conteos)/list.conteos;
                                    var porcenaje_con_diferencia_neg = (100 * listo.conteos)/list.conteos;
                                    console.log();
                                    // document.getElementById('sin_diferencias').innerHTML = Number(porcenaje_sin_diferencia.toFixed(2))+'%';
                                    // document.getElementById('con_diferencias_positivas').innerHTML = Number(porcenaje_con_diferencia_posi.toFixed(2))+'%';
                                    // document.getElementById('con_diferencias_negtivas').innerHTML = Number(porcenaje_con_diferencia_neg.toFixed(2))+'%';
                                    // Set new default font family and font color to mimic Bootstrap's default styling
                                    Chart.defaults.global.defaultFontFamily = 'Nunito', '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
                                    Chart.defaults.global.defaultFontColor = '#858796';

                                    // Pie Chart Example
                                    var ctx = document.getElementById("myPieChart");
                                    var myPieChart = new Chart(ctx, {
                                        type: 'doughnut',
                                        data: {
                                            labels: ["Sin Diferencia", "Diferencia Positiva", "Diferencia Negativa"],
                                            datasets: [{
                                                data: [total, lista.conteos, listo.conteos],
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
                                });
                            }
                        });
                    });
                }
            });
        });
    }
});