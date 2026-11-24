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
