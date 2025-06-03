/*
  pedido_online_2.js
  --------------------------------------------
  - Lee 26 filas (1..21 fijas + 22..26 “en blanco”).
  - Teléfono #1 obligatorio, #2 opcional.
  - Suma total de TODAS las filas (1..26).
  - Envía al back-end con “insertar_detalle_pedido_online”.
*/

$(document).ready(function () {

    // 1) Ver sesión (opcional)
    $.ajax({
        url: './bd/servidor.php',
        type: 'GET',
        data: { quest: 'usuario' },
        success: function (idUsuario) {
            // ...
        }
    });

    // 2) Nombre usuario
    $.ajax({
        url: './bd/servidor.php',
        type: 'GET',
        data: { quest: 'nombre_usuario' },
        success: function (res) {
            let lbl = document.getElementById('nombre_del_usuario');
            if (lbl) lbl.innerHTML = res;
        }
    });

    // 3) Cambiar color sidebar
    $.ajax({
        url: './bd/servidor.php',
        type: 'GET',
        data: { quest: 'usuario' },
        success: function (idUsuario) {
            $.ajax({
                url: './bd/servidor.php',
                type: 'GET',
                data: { quest: 'empresa_usuario', id_usuario: idUsuario },
                success: function (resp) {
                    if (resp !== 'No') {
                        let arr = JSON.parse(resp);
                        arr.forEach(item => {
                            if (item.id_empresa == 1) {
                                document.getElementById('accordionSidebar').className =
                                    'navbar-nav bg-gradient-danger sidebar sidebar-dark accordion toggled';
                            } else {
                                document.getElementById('accordionSidebar').className =
                                    'navbar-nav bg-gradient-primary sidebar sidebar-dark accordion toggled';
                            }
                        });
                    }
                }
            });
        }
    });

    // 4) Llenar datalist con clientes_online (opcional)
    $.ajax({
        url: './bd/servidor.php',
        type: 'GET',
        data: { quest: 'ver_clientes_online' },
        success: function (res) {
            if (res !== 'No') {
                let data = JSON.parse(res);
                let template = '';
                data.forEach(item => {
                    template += `<option value="${item.nombre}">`;
                });
                let dl = document.getElementById('browsers');
                if (dl) dl.innerHTML = template;
            }
        }
    });

}); // fin document.ready


// ======================= GUARDAR =======================
function guardar() {

    // 1) Validaciones habituales
    if (!validarDatos()) return;

    // 2) Construir resumen (HTML)  -----------------------
    let total = 0;
    let rowsHTML = '';

    for (let i = 1; i <= 26; i++) {
        const inpCant   = document.getElementById('cantidad' + i);
        const inpPrecio = document.getElementById('precio' + i);
        if (!inpCant || !inpPrecio) continue;

        const cant  = parseFloat(inpCant.value)   || 0;
        const precio = parseFloat(inpPrecio.value) || 0;
        if (cant <= 0) continue;                      // solo líneas con cantidad>0

        // ── descripción del producto ───────────────────
        let descripcion = '';
        if (i <= 21) {
            // filas fijas: la descripción está en la 2.ª celda del <tr>
            descripcion = inpCant.closest('tr').children[1].textContent.trim();
        } else {
            // filas con <select>
            const sel = document.getElementById('idProducto' + i);
            if (sel && sel.selectedIndex > 0) {
                descripcion = sel.options[sel.selectedIndex].text;
            } else {
                descripcion = sel ? sel.value : '--';
            }
        }

        const subTotal = cant * precio;
        total += subTotal;

        rowsHTML += `
            <tr>
                <td>${descripcion}</td>
                <td class="text-center">${cant}</td>
                <td class="text-right">Q&nbsp;${precio.toFixed(2)}</td>
                <td class="text-right">Q&nbsp;${subTotal.toFixed(2)}</td>
            </tr>`;
    }

    // 3) Mostrar SweetAlert con el resumen  -------------
    const resumenHTML = `
        <div style="max-height:50vh;overflow:auto">
            <table class="table table-sm">
                <thead class="thead-dark">
                    <tr>
                        <th>Producto</th><th>Cant.</th>
                        <th class="text-right">Precio</th>
                        <th class="text-right">Subtotal</th>
                    </tr>
                </thead>
                <tbody>${rowsHTML}
                    <tr>
                        <td colspan="3" class="text-right"><strong>Total</strong></td>
                        <td class="text-right"><strong>Q&nbsp;${total.toFixed(2)}</strong></td>
                    </tr>
                </tbody>
            </table>
        </div>`;

    Swal.fire({
        title: '¿Guardar este pedido?',
        html: resumenHTML,
        icon: 'info',
        showCancelButton: true,
        confirmButtonText: 'Sí, guardar',
        cancelButtonText: 'Cancelar',
        width: '60%',
        focusConfirm: false
    }).then((result) => {

        // 4) Si confirmamos, grabar en BD  ----------------
        if (result.isConfirmed) {
            guardarClienteOnline();   // ya existentes en tu script
            guardarPedidoOnline();
        }
        // si se cancela, no hacemos nada
    });
}


// ======================= VALIDAR =======================
function validarDatos() {
    // Revisar las 26 filas
    let algunProducto = false;
    for (let i = 1; i <= 26; i++) {
        let c = document.getElementById('cantidad' + i);
        let p = document.getElementById('precio' + i);
        if (!c || !p) continue; // si no existe, salta
        let cant = parseFloat(c.value) || 0;
        let prec = parseFloat(p.value) || 0;
        if (cant > 0) {
            if (prec <= 0) {
                Swal.fire('Error', 'Cantidad>0 pero precio<=0. Corrige.', 'warning');
                return false;
            }
            algunProducto = true;
        }
        if (cant < 0) {
            Swal.fire('Error', 'No se permiten cantidades negativas.', 'warning');
            return false;
        }
    }
    if (!algunProducto) {
        Swal.fire('Sin productos', 'No ingresaste ningún producto con cantidad>0', 'warning');
        return false;
    }

    // Teléfono #1 obligatorio
    let tel1 = (document.getElementById('telefono1') || {}).value || '';
    if (!tel1.trim()) {
        Swal.fire('Teléfono', 'Teléfono #1 es obligatorio.', 'info');
        return false;
    }

    // Campos obligatorios
    let nombre = (document.getElementById('browser') || {}).value || '';
    let direccion = (document.getElementById('direccion') || {}).value || '';
    let departamento = (document.getElementById('departamento') || {}).value || '';
    let municipio = (document.getElementById('municipio') || {}).value || '';
    let nombre_factura = (document.getElementById('nombre_factura') || {}).value || '';
    let nit = (document.getElementById('nit') || {}).value || '';
    let sticker = (document.getElementById('sticker') || {}).value || '';
    let servicio = (document.getElementById('servicio') || {}).value || '';
    let fecha_entrega = (document.getElementById('fecha_entrega') || {}).value || '';

    if (!nombre.trim()) {
        Swal.fire('Falta Nombre', 'Ingresa el nombre del cliente', 'info');
        return false;
    }
    if (!direccion.trim()) {
        Swal.fire('Falta Dirección', 'La dirección es obligatoria', 'info');
        return false;
    }
    if (!departamento.trim()) {
        Swal.fire('Falta Departamento', 'Selecciona departamento', 'info');
        return false;
    }
    if (!municipio.trim()) {
        Swal.fire('Falta Municipio', 'Selecciona municipio', 'info');
        return false;
    }
    if (!nombre_factura.trim()) {
        Swal.fire('Falta Nombre Factura', 'Completa el nombre de factura', 'info');
        return false;
    }
    if (!nit.trim()) {
        Swal.fire('Falta NIT', 'Ingresa NIT', 'info');
        return false;
    }
    if (!sticker.trim()) {
        Swal.fire('Falta Sticker', 'Ingresa sticker', 'info');
        return false;
    }
    if (servicio === '0') {
        Swal.fire('Falta Servicio', 'Selecciona un servicio', 'info');
        return false;
    }
    if (!fecha_entrega.trim()) {
        Swal.fire('Falta Fecha de Entrega', 'Selecciona fecha', 'info');
        return false;
    }

    // Fidelización
    let radF = document.getElementById('radioFidelizado');
    let radN = document.getElementById('radioNoFidelizado');
    if (!radF.checked && !radN.checked) {
        Swal.fire('Falta Fidelización', 'Selecciona Fidelizado o NoFidelizado', 'info');
        return false;
    }

    return true;
}

// ======================= GUARDAR CLIENTE =======================
function guardarClienteOnline() {
    let idcli = (document.getElementById('id_cliente_online') || {}).value || '';
    let nombre = (document.getElementById('browser') || {}).value || '';
    let direccion = (document.getElementById('direccion') || {}).value || '';
    let tel1 = (document.getElementById('telefono1') || {}).value || '';

    if (!idcli) {
        // Insertar
        $.ajax({
            url: './bd/servidor.php',
            type: 'POST',
            data: {
                quest: 'ingresar_cliente_online',
                nombre,
                direccion,
                telefono: tel1
            },
            success: function (resp) {
                console.log('ingresar_cliente_online =>', resp);
            }
        });
    } else {
        // Actualizar
        $.ajax({
            url: './bd/servidor.php',
            type: 'POST',
            data: {
                quest: 'modificar_telefono_cliente',
                id_cliente: idcli,
                telefono: tel1
            },
            success: function (resp) {
                console.log('modificar_telefono_cliente =>', resp);
            }
        });
    }
}

// ======================= GUARDAR PEDIDO =======================
function guardarPedidoOnline() {
    let nombre = (document.getElementById('browser') || {}).value || '';
    let direccion = (document.getElementById('direccion') || {}).value || '';
    let departamento = (document.getElementById('departamento') || {}).value || '';
    let municipio = (document.getElementById('municipio') || {}).value || '';
    // Teléfonos => unimos con '/'
    let t1 = (document.getElementById('telefono1') || {}).value || '';
    let t2 = (document.getElementById('telefono2') || {}).value || '';
    let telefono = t2.trim() ? (t1 + '/' + t2) : t1;

    let nombre_factura = (document.getElementById('nombre_factura') || {}).value || '';
    let nit = (document.getElementById('nit') || {}).value || '';
    let sticker = (document.getElementById('sticker') || {}).value || '';
    let servicio = (document.getElementById('servicio') || {}).value || '';
    let observacion = (document.getElementById('observacion') || {}).value || '';
    let fecha_entrega = (document.getElementById('fecha_entrega') || {}).value || '';
    let id_cliente_online = (document.getElementById('id_cliente_online') || {}).value || '';

    // Fidelización
    let fid = '';
    if ((document.getElementById('radioFidelizado') || {}).checked) {
        fid = 'Fidelizado';
    } else if ((document.getElementById('radioNoFidelizado') || {}).checked) {
        fid = 'NoFidelizado';
    }

    // Tipo de cliente
    let arrTipo = [];
    if ((document.getElementById('checkCasa') || {}).checked) arrTipo.push('Desde casa');
    if ((document.getElementById('checkEscuelas') || {}).checked) arrTipo.push('En Escuelas');
    if ((document.getElementById('checkCalle') || {}).checked) arrTipo.push('Venta en la calle');
    if ((document.getElementById('checkTienda') || {}).checked) arrTipo.push('Tienda');
    if ((document.getElementById('checkOtras') || {}).checked) {
        let espe = (document.getElementById('txtTipoClienteOtras') || {}).value || '';
        arrTipo.push('Otras - ' + espe);
    }
    let tipo_cliente = arrTipo.join(', ');

    // Cómo se enteró
    let arrComo = [];
    if ((document.getElementById('checkRedes') || {}).checked) arrComo.push('Redes Sociales');
    if ((document.getElementById('checkPublicidad') || {}).checked) arrComo.push('Publicidad en calle');
    if ((document.getElementById('checkReferido') || {}).checked) arrComo.push('Referido por alguien');
    if ((document.getElementById('checkOtras2') || {}).checked) {
        let esp2 = (document.getElementById('txtComoSeEnteroOtras') || {}).value || '';
        arrComo.push('Otras - ' + esp2);
    }
    let como_se_entero = arrComo.join(', ');

    // idUsuario
    $.ajax({
        url: './bd/servidor.php',
        type: 'GET',
        data: { quest: 'usuario' },
        success: function (idUsuario) {
            // Insertar pedido
            $.ajax({
                url: './bd/servidor.php',
                type: 'POST',
                data: {
                    quest: 'insertar_pedido_online',
                    nombre,
                    direccion,
                    departamento,
                    municipio,
                    telefono,
                    nombre_factura,
                    nit,
                    sticker,
                    servicio,
                    observacion,
                    fecha_entrega,
                    id_cliente_sap: sessionStorage.getItem('opcion_online'),
                    id_cliente: id_cliente_online,
                    idUsuario,

                    // extras
                    fidelizacion: fid,
                    tipo_cliente,
                    como_se_entero
                },
                success: function (res) {
                    console.log('insertar_pedido_online =>', res);
                    if (res !== 'No') {
                        // Insertar DETALLE (26 filas)
                        for (let i = 1; i <= 26; i++) {
                            let inpID = document.getElementById('idProducto' + i);
                            let inpP = document.getElementById('precio' + i);
                            let inpC = document.getElementById('cantidad' + i);
                            if (!inpID || !inpP || !inpC) continue;

                            let cant = parseFloat(inpC.value) || 0;
                            let prec = parseFloat(inpP.value) || 0;
                            if (cant > 0 && prec > 0) {
                                let id_prod = inpID.value;
                                insertarDetallePedido(id_prod, prec, cant);
                            }
                        }
                        // Mensaje final
                        Swal.fire({
                            title: 'Pedido creado',
                            text: 'El pedido se creó correctamente',
                            icon: 'success',
                            showConfirmButton: false,
                            timer: 1500
                        }).then(() => {
                            location.href = './buscar_pedido_online.html';
                        });
                    } else {
                        Swal.fire('Error', 'No se pudo crear el pedido.', 'error');
                    }
                }
            });
        }
    });
}

// ======================= INSERTAR DETALLE =======================
function insertarDetallePedido(id_producto, precio, cantidad) {
    $.ajax({
        url: './bd/servidor.php',
        type: 'POST',
        data: {
            quest: 'insertar_detalle_pedido_online',
            id_producto,
            precio,
            cantidad
        },
        success: function (resp) {
            console.log('detalle =>', resp);
        }
    });
}

// ======================= CALCULAR TOTAL =======================
function calcular_total_pedido() {
    let total = 0;
    for (let i = 1; i <= 26; i++) {
        let inpP = document.getElementById('precio' + i);
        let inpC = document.getElementById('cantidad' + i);
        if (!inpP || !inpC) continue;
        let p = parseFloat(inpP.value) || 0;
        let c = parseFloat(inpC.value) || 0;
        total += (p * c);
    }
    let lbl = document.getElementById('monto_total_pedido');
    if (lbl) lbl.innerText = 'Monto Total Del Pedido: Q. ' + total.toFixed(2);
}

// ======================= BÚSQUEDA CLIENTE =======================
function busqueda() {
    let nombre = (document.getElementById('browser') || {}).value || '';
    $.ajax({
        url: './bd/servidor.php',
        type: 'GET',
        data: { quest: 'buscar_clientes_online', nombre },
        success: function (res) {
            if (res !== 'No') {
                let arr = JSON.parse(res);
                arr.forEach(item => {
                    (document.getElementById('direccion') || {}).value = item.direccion;
                    (document.getElementById('telefono1') || {}).value = item.telefono;
                    (document.getElementById('id_cliente_online') || {}).value = item.id;
                });
            } else {
                (document.getElementById('direccion') || {}).value = '';
                (document.getElementById('telefono1') || {}).value = '';
                (document.getElementById('id_cliente_online') || {}).value = '';
            }
        }
    });
}
