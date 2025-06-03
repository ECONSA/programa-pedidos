function login(){
    var usuario = document.getElementById('usuario').value;
    var contrasena = document.getElementById('contrasena').value;

    $.ajax({
        url: './php/servidor.php',
        type: 'GET',
        data: {
            quest: 'login',
            usuario,
            contrasena
        },
        success: function (res) {
            if (res == 'No') {
                Swal.fire({
                    title: '¡Acceso Denegado!',
                    text: "Su usuario o contraseña son incorrectos o no existen!",
                    icon: 'warning'
                });
            }else{
                console.log(res);
                sessionStorage.setItem('usuario', res);
                window.location.href = './index.html';
            }
        }
    });
}