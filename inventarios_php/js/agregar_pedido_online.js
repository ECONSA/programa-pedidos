$(document).ready(function () {
    $.ajax({
        url: './bd/servidor.php',
        type: 'GET',
        data: {
            quest: 'usuario'
        },
        success: function (idUsuario) {
            if (isNaN(idUsuario)) {
                window.location.href = '../login.php';
            } else {
                filtrarOpciones(idUsuario);
            }
        }
    });

    $.ajax({
        url: './bd/servidor.php',
        type: 'GET',
        data: {
            quest: 'nombre_usuario'
        },
        success: function (res) {
            var nombre = res;
            console.log(nombre);
            document.getElementById('nombre_del_usuario').innerHTML = nombre;
        }
    });
});

// function filtrarOpciones(idUsuario) {
//     const opcionesPorUsuario = {
//         46: [
//             { value: "2502", text: "C02503-CLIENTES VARIOS FRI-OSO CREMOSOS ONLINE 3" },
//             { value: "2503", text: "C02502-CLIENTES VARIOS CAEX VENTAS ONLINE 3" },
//             { value: "2501", text: "C02501-CLIENTES VARIOS VENTA CHAT CENTER CAPITAL ONLINE 3" }
//         ]
//     };

//     const selectElement = document.getElementById("opcion");

//     // Si el usuario tiene opciones predefinidas, las usamos
//     if (opcionesPorUsuario[idUsuario]) {
//         selectElement.innerHTML = ""; // Limpiar opciones actuales
//         const defaultOption = document.createElement("option");
//         defaultOption.value = "0";
//         defaultOption.textContent = "Elija un cliente";
//         defaultOption.selected = true;
//         selectElement.appendChild(defaultOption);

//         opcionesPorUsuario[idUsuario].forEach(opcion => {
//             const optionElement = document.createElement("option");
//             optionElement.value = opcion.value;
//             optionElement.textContent = opcion.text;
//             selectElement.appendChild(optionElement);
//         });

//     }
// }

function filtrarOpciones(idUsuario) {
    const opcionesPorUsuario = {
        46: [
            { value: "2502", text: "C02503-CLIENTES VARIOS FRI-OSO CREMOSOS ONLINE 3" },
            { value: "2503", text: "C02502-CLIENTES VARIOS CAEX VENTAS ONLINE 3" },
            { value: "2501", text: "C02501-CLIENTES VARIOS VENTA CHAT CENTER CAPITAL ONLINE 3" }
        ]
    };

    const selectElement = document.getElementById("opcion");
    const sidebar = document.getElementById("accordionSidebar");

    // Set sidebar class directly
    sidebar.className = "navbar-nav bg-gradient-danger sidebar sidebar-dark accordion toggled";

    if (opcionesPorUsuario[idUsuario]) {
        selectElement.innerHTML = "";
        const defaultOption = document.createElement("option");
        defaultOption.value = "0";
        defaultOption.textContent = "Elija un cliente";
        defaultOption.selected = true;
        selectElement.appendChild(defaultOption);

        opcionesPorUsuario[idUsuario].forEach(opcion => {
            const optionElement = document.createElement("option");
            optionElement.value = opcion.value;
            optionElement.textContent = opcion.text;
            selectElement.appendChild(optionElement);
        });
    }
}

function seleccion() {
    var opcion = document.getElementById('opcion').value;
    if (opcion == 0) {
        Swal.fire({
            title: 'Espere!',
            text: "Por favor Seleccione un cliente",
            icon: 'warning'
        });
    } else {
        sessionStorage.setItem('opcion_online', opcion);
        window.location.href = './pedido_online.html';
    }
}

function verifica_codigo(pform) {
    if (pform.idCliente.value == "") {
        Swal.fire({
            icon: 'error',
            title: 'Cliente Inválido',
            text: 'Por favor seleccione un cliente válido'
        });
        return false;
        pform.idCliente.focus();
    }
}


// $(document).ready(function() {

//     $.ajax({
//         url: './bd/servidor.php',
//         type: 'GET',
//         data: {
//             quest: 'usuario'
//         },
//         success: function(idUsuario) {
//             if(isNaN(idUsuario)) {
//                 window.location.href = '../login.php';
//             }
//         }
//     })

//     $.ajax({
//         url: './bd/servidor.php',
//         type: 'GET',
//         data: {
//             quest: 'nombre_usuario'
//         },
//         success: function(res) {
//             var nombre = res;
//             console.log(nombre);
//             document.getElementById('nombre_del_usuario').innerHTML = nombre;
//         }
//     });

//     $.ajax({
//         url: './bd/servidor.php',
//         type: 'GET',
//         data: {
//             quest: 'usuario'
//         },
//         success: function(idUsuario) {
//             $.ajax({
//                 url: './bd/servidor.php',
//                 type: 'GET',
//                 data: {
//                     quest: 'empresa_usuario',
//                     id_usuario: idUsuario
//                 },
//                 success: function(resp) {
//                     let lista = JSON.parse(resp);
//                     lista.forEach(lista => {
//                         if (lista.id_empresa == 1) {
//                             document.getElementById("accordionSidebar").className = "navbar-nav bg-gradient-danger sidebar sidebar-dark accordion toggled";
//                         } else {
//                             document.getElementById("accordionSidebar").className = "navbar-nav bg-gradient-primary sidebar sidebar-dark accordion toggled";
//                         }
//                         4
//                     });
//                 }
//             });
//         }
//     });
// });

// function seleccion() {
//     var opcion = document.getElementById('opcion').value;
//     // alert(opcion);
//     if (opcion == 0) {
//         Swal.fire({
//             title: 'Espere!',
//             text: "Por favor Seleccione un cliente",
//             icon: 'warning'
//         });
//     } else {
//         sessionStorage.setItem('opcion_online', opcion);
//         window.location.href = './pedido_online.html';
//     }
// }

// function verifica_codigo(pform) {
//     if (pform.idCliente.value == "") {
//         Swal.fire({
//             icon: 'error',
//             title: 'Cliente Inválido',
//             text: 'Porfavor seleccione un cliente válido'
//         });
//         return false;
//         pform.idCliente.focus();
//     }
// }