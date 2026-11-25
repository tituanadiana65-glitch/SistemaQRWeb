<h3>Historial de configuración</h3>
<p class="text-muted">Cada vez que se edita un equipo (en Gestión de equipos o desde la lectura con cámara web) se guardan
    sus datos anteriores y los campos que cambiaron. Puede filtrar por ID de equipo y por fechas.</p>

<div id="toolbar" class="form-inline mb-2">
    <input id="txtId" type="number" min="1" class="form-control form-control-sm mr-1 mb-1" placeholder="ID del equipo" style="width:130px">
    <label for="txtDesde" class="mr-1 mb-1">Desde</label>
    <input id="txtDesde" type="date" class="form-control form-control-sm mr-1 mb-1">
    <label for="txtHasta" class="mr-1 mb-1">Hasta</label>
    <input id="txtHasta" type="date" class="form-control form-control-sm mr-1 mb-1">
    <button class="btn btn-primary btn-sm mr-1 mb-1" onclick="buscarCambios()">Buscar</button>
    <button class="btn btn-secondary btn-sm mb-1" onclick="mostrarTodos()">Mostrar todos</button>
</div>

<div class="table-responsive">
    <table id="dg" class="table table-sm table-hover">
        <thead class="thead-dark">
            <tr>
                <th>Fecha</th>
                <th>ID</th>
                <th>Equipo</th>
                <th>Campos cambiados</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>
</div>
<p id="totalCambios" class="font-weight-bold"></p>

<script>
    var filas = []; // cambios que se muestran en la tabla

    // Pide los cambios al modelo (con los filtros) y llena la tabla
    function cargarTabla() {
        var datos = {
            id_equipo: $('#txtId').val().trim(),
            desde: $('#txtDesde').val(),
            hasta: $('#txtHasta').val()
        };
        $.post('models/historial_config_listar.php', datos, function (data) {
            if (data.error) { aviso(data.error, 'danger'); return; }
            filas = data;
            var html = '';
            $.each(data, function (i, h) {
                html += '<tr data-i="' + i + '">' +
                    '<td>' + esc(h.fecha_cambio) + '</td><td>' + h.id_equipo + '</td>' +
                    '<td>' + esc(h.nombre) + '</td><td>' + esc(h.campos_cambiados) + '</td></tr>';
            });
            if (data.length === 0) {
                html = '<tr><td colspan="4" class="text-center text-muted">No hay cambios de configuración con esos filtros</td></tr>';
            }
            $('#dg tbody').html(html);
            $('#totalCambios').text('Cambios: ' + data.length);
        }, 'json');
    }

    // Revisa los filtros antes de buscar (el servidor los vuelve a revisar)
    function buscarCambios() {
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

    function mostrarTodos() {
        $('#txtId, #txtDesde, #txtHasta').val('');
        cargarTabla();
    }

    $('#txtId').on('keypress', function (e) {
        if (e.which === 13) { buscarCambios(); }
    });

    cargarTabla();
</script>
