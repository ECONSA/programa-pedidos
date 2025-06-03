$(document).ready(function () {

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
            quest: 'diferencia_anual'
        },
        success: function (res) {
            let list = JSON.parse(res);
            list.forEach(list => {
                document.getElementById('diferencia').innerHTML = 'Q.'+list.diferencia;
            });
        }
    });
    // $.ajax({
    //     url: './bd/servidor.php',
    //     type: 'GET',
    //     data: {
    //         quest: 'cantidad_diferencia_inventarios_generales'
    //     },
    //     success: function (res) {
    //         let list = JSON.parse(res);
    //         list.forEach(list => {
    //             document.getElementById('diferencia_general').innerHTML = list.cantidad_diferencia;
    //         });
    //     }
    // });
    // $.ajax({
    //     url: './bd/servidor.php',
    //     type: 'GET',
    //     data: {
    //         quest: 'diferencias_positivas'
    //     },
    //     success: function (res) {
    //         let list = JSON.parse(res);
    //         list.forEach(list => {
    //             document.getElementById('diferencia_positiva').innerHTML = list.cantidad_diferencia;
    //         });
    //     }
    // });
    // $.ajax({
    //     url: './bd/servidor.php',
    //     type: 'GET',
    //     data: {
    //         quest: 'diferencias_negativas'
    //     },
    //     success: function (res) {
    //         let list = JSON.parse(res);
    //         list.forEach(list => {
    //             document.getElementById('diferencia_negativa').innerHTML = list.cantidad_diferencia;
    //         });
    //     }
    // });
    $.ajax({
        url: './bd/servidor.php',
        type: 'GET',
        data: {
            quest: 'suma_diferencias_positivas'
        },
        success: function (res) {
            let list = JSON.parse(res);
            list.forEach(list => {
                document.getElementById('suma_diferencia_positiva').innerHTML = list.costo_diferencia;
            });
        }
    });
    $.ajax({
        url: './bd/servidor.php',
        type: 'GET',
        data: {
            quest: 'suma_diferencias_negativas'
        },
        success: function (res) {
            let list = JSON.parse(res);
            list.forEach(list => {
                document.getElementById('suma_diferencia_negativas').innerHTML = list.costo_diferencia;
            });
        }
    });
    $.ajax({
        url: './bd/servidor.php',
        type: 'GET',
        data: {
            quest: 'diferencia_general_inventario'
        },
        success: function (res) {
            let list = JSON.parse(res);
            list.forEach(list => {
                document.getElementById('suma_diferencia').innerHTML = list.diferencia;
            });
        }
    });
    $.ajax({
        url: './bd/servidor.php',
        type: 'GET',
        data: {
            quest: 'reconteos_generales'
        },
        success: function (res) {
            let list = JSON.parse(res);
            list.forEach(list => {
                document.getElementById('reconteo_general').innerHTML = list.reconteo;
            });
        }
    });
    $.ajax({
        url: './bd/servidor.php',
        type: 'GET',
        data: {
            quest: 'extraordinario'
        },
        success: function (res) {
            let list = JSON.parse(res);
            list.forEach(list => {
                document.getElementById('extraordinarias').innerHTML = list.extraordinario;
            });
        }
    });
});