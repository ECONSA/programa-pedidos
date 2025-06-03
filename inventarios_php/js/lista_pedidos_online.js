/*
   lista_pedidos_online.js
   --------------------------------------------------
   - Muestra la lista de pedidos en DataTable, 
     con todos los productos agrupados con GROUP_CONCAT.
   - Solo muestra Teléfono #1 en la columna (split('/')).
   - Si “Código del Producto” está vacío, no pone botón.
   - Si no está vacío, pone “Ver más” + modal.
   - Exporta Excel con bulletifyAll.
*/

$(document).ready(function () {

    // 1) Verificar sesión (opcional)
    $.ajax({
        url: './bd/servidor.php',
        type: 'GET',
        data: { quest: 'usuario' },
        success: function (idUsuario) {
            // ...
        }
    });

    // 2) Nombre del usuario
    $.ajax({
        url: './bd/servidor.php',
        type: 'GET',
        data: { quest: 'nombre_usuario' },
        success: function (res) {
            document.getElementById('nombre_del_usuario').innerHTML = res;
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
                        let lista = JSON.parse(resp);
                        lista.forEach(item => {
                            if (item.id_empresa == 1) {
                                document.getElementById("accordionSidebar").className =
                                    "navbar-nav bg-gradient-danger sidebar sidebar-dark accordion toggled";
                            } else {
                                document.getElementById("accordionSidebar").className =
                                    "navbar-nav bg-gradient-primary sidebar sidebar-dark accordion toggled";
                            }
                        });
                    }
                }
            });
        }
    });

    // 4) Cargar la lista de pedidos => con rango amplio
    const fechaMinima = sessionStorage.getItem('fecha_minima') || '2000-01-01';
    const fechaMaxima = sessionStorage.getItem('fecha_maxima') || '2100-12-31';

    $.ajax({
        url: './bd/servidor.php',
        type: 'GET',
        data: {
            quest: 'lista_online',
            minimo: fechaMinima,
            maxima: fechaMaxima
        },
        success: function (res) {
            if (res === 'No') return;
            let dataSet = JSON.parse(res);

            $('#dataTable').DataTable({
                data: dataSet,
                columns: [
                    // Definición de columnas
                    { data: 'id_pedido' },
                    { data: 'nombre_pedido' },
                    { data: 'direccion_pedido' },
                    { data: 'departamento_pedido' },
                    { data: 'municipio_pedido' },
                    {
                        data: 'telefono_pedido',
                        render: function (data, type, row) {
                            if (!data) return '';
                            return data.split('/')[0];
                        }
                    },
                    { data: 'nombre_factura' },
                    { data: 'nit' },
                    { data: 'stickers' },
                    { data: 'servicio' },
                    { data: 'fecha_generado' },
                    { data: 'fecha_entrega' },
                    { data: 'cliente_sap' },
                    {
                        data: 'estado',
                        render: function (data, type, row) {
                            if (type === 'display') {
                                if (data === 'confirmado') {
                                    return `<span class="badge badge-success">${data}</span>`;
                                } else if (data === 'rechazado') {
                                    return `<span class="badge badge-danger">${data}</span>`;
                                } else {
                                    return `<span class="badge badge-primary">${data}</span>`;
                                }
                            }
                            return data;
                        }
                    },
                    { data: 'fidelizacion' },
                    { data: 'tipo_cliente' },
                    { data: 'como_se_entero' },
                    {
                        data: 'productos',
                        render: function (data, type, row) {
                            if (!data || data.trim() === '') return '';
                            let bullet = bulletifyAll(data);
                            if (type === 'display') {
                                let corto = shortText(bullet, 80);
                                return `
                                  <div style="white-space: pre-wrap;">${corto}</div>
                                  <button class="btn btn-info btn-sm" onclick="mostrarCompleto(\`${escapeBackticks(bullet)}\`)">
                                    Ver más
                                  </button>`;
                            }
                            return bullet;
                        }
                    },
                    {
                        data: 'precios',
                        render: function (data, type, row) {
                            if (!data || data.trim() === '') return '';
                            let bullet = bulletifyAll(data);
                            if (type === 'display') {
                                return `<div style="white-space: pre-wrap;">${bullet}</div>`;
                            }
                            return bullet;
                        }
                    },
                    {
                        data: 'cantidades',
                        render: function (data, type, row) {
                            if (!data || data.trim() === '') return '';
                            let bullet = bulletifyAll(data);
                            if (type === 'display') {
                                return `<div style="white-space: pre-wrap;">${bullet}</div>`;
                            }
                            return bullet;
                        }
                    },
                    { data: 'total_pedido' },
                    {
                        data: null,
                        render: function (data, type, row) {
                            return `
                            <button class="btn btn-primary" onclick="verDetalle(${row.id_pedido})">
                              <i class="fas fa-search"></i>
                            </button>`;
                        }
                    }
                ],
                order: [[10, "desc"]],
                lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]]
            });
        }
    });
});

// ==================================================
// bulletifyAll => separa en '---', añade “• “
// ==================================================
function bulletifyAll(str) {
    if (!str) return '';
    let parts = str.split('---');
    let lines = parts.map(item => '• ' + item.trim());
    return lines.join('\n');
}

// ==================================================
// shortText(str, maxLen)
// ==================================================
function shortText(str, maxLen) {
    if (!str) return '';
    if (str.length <= maxLen) return str;
    return str.substring(0, maxLen) + '...';
}

// ==================================================
// escapeBackticks => para no romper template
// ==================================================
function escapeBackticks(text) {
    return text.replace(/`/g, '\\`');
}

// ==================================================
// mostrarCompleto => abre modal
// ==================================================
function mostrarCompleto(text) {
    document.getElementById('verMasModalBody').textContent = text;
    $('#verMasModal').modal('show');
}

// ==================================================
// verDetalle => redirigir
// ==================================================
function verDetalle(idPedido) {
    sessionStorage.setItem('id_pedido_online', idPedido);
    location.href = "./detalle_online.html";
}

// ==================================================
// exportDataTableToExcel => bullets
// ==================================================
function exportDataTableToExcel() {
    const table = $('#dataTable').DataTable();
    const data = table.rows({ search: 'applied', page: 'current' }).data();

    let xlsHeader = `
    <tr>
      <th>ID Pedido</th>
      <th>Nombre</th>
      <th>Dirección</th>
      <th>Departamento</th>
      <th>Municipio</th>
      <th>Teléfono</th>
      <th>Nombre Factura</th>
      <th>NIT</th>
      <th>Sticker</th>
      <th>Servicio</th>
      <th>Fecha Generado</th>
      <th>Fecha Entrega</th>
      <th>Cliente SAP</th>
      <th>Estado</th>
      <th>Fidelización</th>
      <th>Tipo de cliente</th>
      <th>¿Cómo se enteró?</th>
      <th>Código del Producto</th>
      <th>Precio</th>
      <th>Cantidad</th>
      <th>Total</th>
    </tr>`;

    let xlsRows = '';
    data.each((row) => {
        // Teléfono => solo #1
        let primerTel = '';
        if (row.telefono_pedido) {
            primerTel = row.telefono_pedido.split('/')[0];
        }
        // bulletify
        let pds = bulletifyAll(row.productos || '');
        let prs = bulletifyAll(row.precios || '');
        let cts = bulletifyAll(row.cantidades || '');

        xlsRows += `
      <tr>
        <td>${row.id_pedido || ''}</td>
        <td>${row.nombre_pedido || ''}</td>
        <td>${row.direccion_pedido || ''}</td>
        <td>${row.departamento_pedido || ''}</td>
        <td>${row.municipio_pedido || ''}</td>
        <td>${primerTel}</td>
        <td>${row.nombre_factura || ''}</td>
        <td>${row.nit || ''}</td>
        <td>${row.stickers || ''}</td>
        <td>${row.servicio || ''}</td>
        <td>${row.fecha_generado || ''}</td>
        <td>${row.fecha_entrega || ''}</td>
        <td>${row.cliente_sap || ''}</td>
        <td>${row.estado || ''}</td>
        <td>${row.fidelizacion || ''}</td>
        <td>${row.tipo_cliente || ''}</td>
        <td>${row.como_se_entero || ''}</td>
        <td>${pds}</td>
        <td>${prs}</td>
        <td>${cts}</td>
        <td>${row.total_pedido || ''}</td>
      </tr>`;
    });

    const tableHTML = `
    <html>
      <head>
        <meta charset="UTF-8">
        <style>
          table {
            border-collapse: collapse;
            font-family: Arial, sans-serif;
            font-size: 14px;
            width: 100%;
          }
          thead tr {
            background-color: #D9EDF7;
            font-weight: bold;
            text-align: center;
          }
          th, td {
            border: 1px solid #999;
            padding: 8px;
            text-align: left;
            vertical-align: top;
            white-space: pre-wrap; /* saltos de línea */
          }
        </style>
      </head>
      <body>
        <table>
          <thead>${xlsHeader}</thead>
          <tbody>${xlsRows}</tbody>
        </table>
      </body>
    </html>`;

    const blob = new Blob([tableHTML], { type: 'application/vnd.ms-excel;charset=utf-8' });
    const link = document.createElement('a');
    link.href = window.URL.createObjectURL(blob);
    link.style.display = 'none';
    link.download = 'pedidos_online.xls';
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}
