var idCita

$(document).ready(function () {
    $.ajax({
        url: '../inventarios_php/bd/servidor.php',
        type: 'GET',
        data: {
            quest: 'usuario'
        },
        success: function (idUsuario) {
            if (isNaN(idUsuario)) {
                window.location.href = './login.php';
            } else {
                crearCitasSeguimiento(idUsuario);
                detalleCita();
            }
        }
    })

    $('#calendario').evoCalendar({
        settingName: "calendarioGeneral",
        'theme': "Orange Coral",
        'language': 'es'
    })    
})

function crearCitasSeguimiento(idUsuario) {
    $.ajax({
        url: '../inventarios_php/bd/servidor.php',
        type: 'GET',
        data: {
            quest: 'generar_citas',
            idUsuario
        },
        success: function(res) {
            var tipoEvento;
            try {
                let lista = JSON.parse(res);
                lista.forEach(lista => {
                    if (lista.estado == 'Visita') {
                        tipoEvento = 'holiday'
                    } else if (lista.estado == 'Venta Exitosa') {
                        tipoEvento = 'birthday'
                    } else {
                        tipoEvento = 'event'
                    }
                    $('#calendario').evoCalendar('addCalendarEvent', {
                        id: lista.id,
                        name: lista.cliente,
                        description: lista.estado,
                        date: lista.fecha,
                        type: tipoEvento
                    });
                });
            } catch (error) {}

        }

    });
}

function detalleCita() {
    $('#calendario').on('selectEvent', function(event, activeEvent) {
        idCita = activeEvent.id;
        $.ajax({
            url: '../inventarios_php/bd/servidor.php',
            type: 'GET',
            data: {
                quest: 'obtener_cita',
                idCita
            },
            success: function(res) {
                try {
                    let lista = JSON.parse(res);
                    lista.forEach(lista => {
                        document.getElementById('cliente').textContent = lista.cliente;
                        document.getElementById('estado').textContent = lista.estado;
                        document.getElementById('vendedor').textContent = lista.vendedor;
                        document.getElementById('descripcion').textContent = lista.descripcion;
                        document.getElementById('fechaC').textContent = lista.creacion;
                        document.getElementById('fechaP').textContent = lista.fecha;
                    });
                } catch (error) {
                    console.log(error);
                }
            }
        });
        $('#modalDetalleCita').modal();
    });
}

function botonEditar() {
    sessionStorage.setItem('idSeguimiento', idCita);
    window.location = "./DetalleSeguimiento.php"
}

function clickBotonDefault() {
    $('#calendario').evoCalendar('setTheme', 'Orange Coral');
}

function clickBotonMidnight() {
    $('#calendario').evoCalendar('setTheme', 'Midnight Blue');
}

function clickBotonRoyal() {
    $('#calendario').evoCalendar('setTheme', 'Royal Navy');
}

function clickBotonCoral() {
    $('#calendario').evoCalendar('setTheme', 'Default');
}