<h3>Historial de lecturas</h3>
<p class="text-muted">Cada vez que se lee la etiqueta QR de un equipo (con la cámara web o desde una imagen) se guarda una lectura.
    Puede filtrar por ID de equipo y por fechas.</p>

<div id="toolbar" class="form-inline mb-2">
    <input id="txtId" type="number" min="1" class="form-control form-control-sm mr-1 mb-1" placeholder="ID del equipo" style="width:130px">
    <label for="txtDesde" class="mr-1 mb-1">Desde</label>
    <input id="txtDesde" type="date" class="form-control form-control-sm mr-1 mb-1">
    <label for="txtHasta" class="mr-1 mb-1">Hasta</label>
    <input id="txtHasta" type="date" class="form-control form-control-sm mr-1 mb-1">
    <button class="btn btn-primary btn-sm mr-1 mb-1" onclick="buscarLecturas()">Buscar</button>
    <button class="btn btn-secondary btn-sm mr-3 mb-1" onclick="mostrarTodas()">Mostrar todas</button>
    <button class="btn btn-info btn-sm mb-1" onclick="verReporte()">Ver reporte del equipo</button>
</div>

<div class="table-responsive">
    <table id="dg" class="table table-sm table-hover">
        <thead class="thead-dark">
            <tr>
                <th>N.º lectura</th>
                <th>Fecha y hora</th>
                <th>ID equipo</th>
                <th>Nombre</th>
                <th>Tipo</th>
                <th>Ubicación</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>
</div>
<p id="totalLecturas" class="font-weight-bold"></p>

<script>
    var filas = [];          // lecturas que se muestran en la tabla
    var seleccionado = null; // lectura de la fila seleccionada

    // Pide las lecturas al modelo (con los filtros) y llena la tabla
    function cargarTabla() {
        var datos = {
            id_equipo: $('#txtId').val().trim(),
            desde: $('#txtDesde').val(),
            hasta: $('#txtHasta').val()
        };
        $.post('models/lecturas_listar.php', datos, function (data) {
            if (data.error) { aviso(data.error, 'danger'); return; }
            filas = data;
            seleccionado = null;
            var html = '';
            $.each(data, function (i, l) {
                var estado = l.estado === 'ACTIVO'
                    ? '<span class="badge badge-success">Activo</span>'
                    : '<span class="badge badge-danger">Dado de baja</span>';
                html += '<tr data-i="' + i + '">' +
                    '<td>' + l.id_lectura + '</td><td>' + esc(l.fecha_lectura) + '</td>' +
                    '<td>' + l.id_equipo + '</td><td>' + esc(l.nombre) + '</td><td>' + esc(l.tipo) + '</td>' +
                    '<td>' + esc(l.ubicacion) + '</td><td>' + estado + '</td></tr>';
            });
            if (data.length === 0) {
                html = '<tr><td colspan="7" class="text-center text-muted">No hay lecturas registradas con esos filtros</td></tr>';
            }
            $('#dg tbody').html(html);
            $('#totalLecturas').text('Lecturas: ' + data.length);
        }, 'json');
    }

    // Seleccionar una fila
    $('#dg tbody').on('click', 'tr[data-i]', function () {
        $('#dg tbody tr').removeClass('table-primary');
        $(this).addClass('table-primary');
        seleccionado = filas[$(this).data('i')];
    });

    // Revisa los filtros antes de buscar (el servidor los vuelve a revisar)
    function buscarLecturas() {
        var id = $('#txtId').val().trim();
        var desde = $('#txtDesde').val();
        var hasta = $('#txtHasta').val();
        if (id !== '' && (!/^[0-9]+$/.test(id) || parseInt(id, 10) <= 0)) {
            alert('Ingrese un ID de equipo válido');
            return;
        }
        if (desde !== '' && hasta !== '' && desde > hasta) {
            alert('La fecha Desde no puede ser mayor que la fecha Hasta');
            return;
        }
        cargarTabla();
    }

    function mostrarTodas() {
        $('#txtId, #txtDesde, #txtHasta').val('');
        cargarTabla();
    }

    $('#txtId').on('keypress', function (e) {
        if (e.which === 13) { buscarLecturas(); }
    });

    // Abre el reporte del equipo de la lectura seleccionada (HU-10)
    function verReporte() {
        if (!seleccionado) { alert('Seleccione una lectura de la tabla'); return; }
        location.href = 'index.php?accion=reporte&id=' + seleccionado.id_equipo;
    }

    cargarTabla();
</script>
