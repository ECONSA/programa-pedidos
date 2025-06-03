$(document).ready(function () {
    let list = JSON.parse(localStorage.getItem('usuario'));

    if (!list) {
        window.location.href = "../login.php";
    }
    $.ajax({
        url: './bd/servidor.php',
        type: 'GET',
        data: {
            quest: 'lista_diferencias_totales',
            id_inventario: sessionStorage.getItem('id_inventario')
        },
        success: function (res) {
            console.log(res);
            let list = JSON.parse(res);
            let template = '';
            list.forEach(list => {
                template += `
                <tr>
                        <td>${list.descripcion_sap}</td>
                        <td>${list.codigo_sap}</td>
                        <td>Q.${list.costo_diferencia}</td>
                </tr>
                `;
                document.getElementById('titulo').innerHTML = list.inventario;
            });
            $("#tabla").html(template);
        }
    });
});

function back(){
    window.location.href = "./reporteria.html";
}