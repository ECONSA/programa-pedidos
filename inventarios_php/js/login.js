function login() {
    var usuario = document.getElementById('usuario').value;
    var contrasena = document.getElementById('contrasena').value;
    $.ajax({
        url: './bd/servidor.php',
        type: 'GET',
        data: {
            quest: 'comprobar',
            usuario,
            contrasena
        },
        success: function (res) {
            if (res == 'No'){
                Swal.fire({
                    title: 'Usuario Inválido',
                    text: "El usuario o la contraseña no están correctos",
                    icon: 'error'
                });
            }
            else if (res) {
                localStorage.setItem('usuario', res);
                window.location.href = "./index.html";
            }
        }
    });
}
// <?php
// if (isset($_GET["error"])) {
//     echo '
//     <div>
//         <div class="alert alert-danger alert-dismissible fade show" role="alert">
//             <strong>Usuario Inválido</strong>
//             <button type="button" class="close" data-dismiss="alert" aria-label="Close">
//                 <span aria-hidden="true">&times;</span>
//             </button>
//         </div>
//     </div>';
// } ?>