<h3>Gestión de equipos</h3>
<p class="text-muted">Seleccione una fila de la tabla y use los botones. El ID es el número que va dentro del código QR.</p>

<div id="toolbar" class="form-inline mb-2">
    <button class="btn btn-success btn-sm mr-1 mb-1" onclick="nuevoEquipo()">Nuevo equipo</button>
    <input id="txtBuscar" type="text" class="form-control form-control-sm mr-1 mb-1" placeholder="ID, nombre, tipo o ubicación" style="width:220px">
    <button class="btn btn-primary btn-sm mr-1 mb-1" onclick="buscarEquipos()">Buscar</button>
    <button class="btn btn-secondary btn-sm mr-3 mb-1" onclick="mostrarTodos()">Mostrar todos</button>
    <div class="form-check mb-1">
        <input id="chkBajas" type="checkbox" class="form-check-input">
        <label for="chkBajas" class="form-check-label">Incluir dados de baja</label>
    </div>
</div>

<div class="table-responsive">
    <table id="dg" class="table table-sm table-hover">
        <thead class="thead-dark">
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Tipo</th>
                <th>Ubicación</th>
                <th>Responsable</th>
                <th>IP</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>
</div>
<p id="totalEquipos" class="font-weight-bold"></p>

<!-- Ventana para registrar o editar un equipo -->
<div class="modal fade" id="dlg" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="dlgTitulo">Nuevo equipo</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body">
                <form id="fm">
                    <input type="hidden" name="id_equipo">
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label>Nombre *</label>
                            <input name="nombre" class="form-control" maxlength="100" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Tipo *</label>
                            <select name="tipo" class="form-control" required>
                                <option value="">-- Seleccione --</option>
                                <option>Computador de escritorio</option>
                                <option>Laptop</option>
                                <option>Impresora</option>
                                <option>Switch</option>
                                <option>Router</option>
                                <option>Access Point</option>
                                <option>Servidor</option>
                                <option>Proyector</option>
                                <option>Otro</option>
                            </select>
                        </div>
                        <div class="form-group col-md-4">
                            <label>Marca</label>
                            <input name="marca" class="form-control" maxlength="50">
                        </div>
                        <div class="form-group col-md-4">
                            <label>Modelo</label>
                            <input name="modelo" class="form-control" maxlength="50">
                        </div>
                        <div class="form-group col-md-4">
                            <label>N.º de serie</label>
                            <input name="numero_serie" class="form-control" maxlength="60">
                        </div>
                        <div class="form-group col-md-6">
                            <label>Ubicación</label>
                            <input name="ubicacion" class="form-control" maxlength="100">
                        </div>
                        <div class="form-group col-md-6">
                            <label>Responsable</label>
                            <input name="responsable" class="form-control" maxlength="100">
                        </div>
                        <div class="form-group col-md-4">
                            <label>Dirección IP</label>
                            <input name="direccion_ip" class="form-control" maxlength="15" placeholder="192.168.10.11">
                        </div>
                        <div class="form-group col-md-4">
                            <label>Dirección MAC</label>
                            <input name="direccion_mac" class="form-control" maxlength="17" placeholder="A4:BB:6D:12:34:01">
                        </div>
                        <div class="form-group col-md-4">
                            <label>Sistema operativo</label>
                            <input name="sistema_operativo" class="form-control" maxlength="60">
                        </div>
                        <div class="form-group col-12">
                            <label>Configuración</label>
                            <textarea name="configuracion" class="form-control" rows="4"></textarea>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary" onclick="guardarEquipo()">Guardar</button>
                <button class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
            </div>
        </div>
    </div>
</div>

<script>
    var url;
    var filas = [];         // equipos que se muestran en la tabla
    var seleccionado = null; // equipo de la fila seleccionada

    // CRUD: Leer. Pide los equipos al modelo y llena la tabla
    function cargarTabla() {
        var datos = {
            buscar: $('#txtBuscar').val().trim(),
            incluir_bajas: $('#chkBajas').is(':checked') ? 1 : 0
        };
        $.post('models/equipos_listar.php', datos, function (data) {
            if (data.error) { aviso(data.error, 'danger'); return; }
            filas = data;
            seleccionado = null;
            var html = '';
            $.each(data, function (i, e) {
                var estado = e.estado === 'ACTIVO'
                    ? '<span class="badge badge-success">Activo</span>'
                    : '<span class="badge badge-danger">Dado de baja</span>';
                html += '<tr data-i="' + i + '">' +
                    '<td>' + e.id_equipo + '</td><td>' + esc(e.nombre) + '</td><td>' + esc(e.tipo) + '</td>' +
                    '<td>' + esc(e.ubicacion) + '</td><td>' + esc(e.responsable) + '</td>' +
                    '<td>' + esc(e.direccion_ip) + '</td><td>' + estado + '</td></tr>';
            });
            $('#dg tbody').html(html);
            $('#totalEquipos').text('Equipos: ' + data.length);
        }, 'json');
    }

    // Seleccionar una fila
    $('#dg tbody').on('click', 'tr', function () {
        $('#dg tbody tr').removeClass('table-primary');
        $(this).addClass('table-primary');
        seleccionado = filas[$(this).data('i')];
    });

    function buscarEquipos() {
        cargarTabla();
    }

    function mostrarTodos() {
        $('#txtBuscar').val('');
        cargarTabla();
    }

    $('#txtBuscar').on('keypress', function (e) {
        if (e.which === 13) { buscarEquipos(); }
    });
    $('#chkBajas').on('change', cargarTabla);

    // CRUD: Crear. Abre la ventana con el formulario vacío
    function nuevoEquipo() {
        $('#dlgTitulo').text('Nuevo equipo');
        $('#fm')[0].reset();
        $('#fm [name=id_equipo]').val('');
        url = 'models/equipos_guardar.php';
        $('#dlg').modal('show');
    }

    // Revisa los datos antes de enviarlos (el servidor los vuelve a revisar)
    function validarFormulario() {
        var form = $('#fm')[0];
        if (!form.checkValidity()) {
            form.reportValidity();
            return false;
        }
        var ip = $('#fm [name=direccion_ip]').val().trim();
        var mac = $('#fm [name=direccion_mac]').val().trim();
        var regexIp = /^((25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)\.){3}(25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)$/;
        var regexMac = /^([0-9A-Fa-f]{2}[:-]){5}[0-9A-Fa-f]{2}$/;
        if (ip !== '' && !regexIp.test(ip)) {
            alert('La dirección IP no es válida (ejemplo: 192.168.10.11)');
            return false;
        }
        if (mac !== '' && !regexMac.test(mac)) {
            alert('La dirección MAC no es válida (ejemplo: A4:BB:6D:12:34:01)');
            return false;
        }
        return true;
    }

    function guardarEquipo() {
        if (!validarFormulario()) { return; }
        $.post(url, $('#fm').serialize(), function (r) {
            if (r.error) {
                alert(r.error);
            } else {
                document.activeElement.blur();
                $('#dlg').modal('hide');
                aviso(r.success, 'success');
                cargarTabla();
            }
        }, 'json');
    }

    cargarTabla();
</script>
