$(document).ready(function () {

    $.ajax({
        url: './inventarios_php/bd/servidor.php',
        type: 'GET',
        data: {
            quest: 'usuario'
        },
        success: function(idUsuario) {
            if(isNaN(idUsuario)) {
                window.location.href = './login.php';
            }
        }
    })

    $.ajax({
        url: '../inventarios_php/bd/servidor.php',
        type: 'GET',
        data: {
            quest: 'mostrar_img_pedido'
        },
        success: function (res) {
            if (res != '') {
                document.getElementById('img').src = `../inventarios_php/bd/servidor.php?quest=mostrar_img_pedido`;
            }else{
                document.getElementById('img').src = `./img/Econsa 404.jpg`;
            }
        }
    });
});