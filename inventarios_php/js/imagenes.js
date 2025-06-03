$(document).ready(function () {
    let list = JSON.parse(localStorage.getItem('usuario'));

    if (!list) {
        window.location.href = "../login.php";
    }

    list.forEach(list => {
        document.getElementById('usuario').value = list.id;
    });

    $.ajax({
        url: './bd/servidor.php',
        type: 'GET',
        data: {
            quest: 'lista_img'
        },
        success: function (res) {
            console.log(res);
            let list = JSON.parse(res);
            let template = '';
            list.forEach(list => {
                template += `
                <tr>
                <td>${list.id}</td>
                <td>${list.fecha_guardado}</td>
                <td align = 'center'>
                <a href = 'javascript:editar(${list.id});'>
                <i class=\"fas fa-search\"></i>
                </td>
                </tr>
                `;
            });
            $("#tabla").html(template);
        }
    });


    $('#example').DataTable({
        dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ]
    });
});

function editar(id) {
    // redirecciona al detalle, para poder ver, y editar la imagen
    window.location = "./editar.html";
    sessionStorage.setItem('id_imagen', id);
}