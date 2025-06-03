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

    var id = sessionStorage.getItem('id_imagen');
    document.getElementById('titulo').innerHTML = 'Imagen #' + id;

    let template = `<img src="./bd/servidor.php?quest=mostrar_img&id_imagen=${id}" alt="">`;
    console.log(template);
    $("#imagen").html(template);

    $.ajax({
        url: './bd/servidor.php',
        type: 'GET',
        data: {
            quest: 'datos_imagen',
            id_imagen: id
        },
        success: function (res) {
            console.log(res);
            let list = JSON.parse(res);
            let template = '';
            list.forEach(list => {
                document.getElementById('descripcion').value = list.descripcion;
                document.getElementById('usuario').value = list.usuario;
                document.getElementById('id_imagen').value = id;
                document.getElementById('fecha_guardado').value = list.fecha_guardado;
            });
            $("#tabla").html(template);
        }
    });


    // $.ajax({
    //     url: './bd/servidor.php',
    //     type: 'GET',
    //     data: {
    //         quest: 'mostrar_img',
    //         id_imagen: id
    //     },
    //     success: function (res) {
    //         console.log(res);
    //         // let list = JSON.parse(res);
    //         let template = `<img src="${res}" alt="">`;
    //         // list.forEach(list => {
    //         //     template += `
    //         //     <tr>
    //         //     <td>${list.id}</td>
    //         //     <td>${list.fecha_guardado}</td>
    //         //     <td align = 'center'>
    //         //     <a href = 'javascript:editar(${list.id});'>
    //         //     <i class=\"fas fa-search\"></i>
    //         //     </td>
    //         //     </tr>
    //         //     `;
    //         // });
    //         $("#imagen").html(template);
    //     }
    // });
});