<script src="lib/html5-qrcode.min.js"></script>

<h3>Lectura de códigos QR con cámara web</h3>
<p class="text-muted">Conecte la cámara web USB, elíjala y pulse Iniciar. Acerque la etiqueta QR del equipo a la cámara.
    La primera vez el navegador pedirá permiso para usar la cámara.</p>

<div class="row">
    <div class="col-md-6">
        <div class="form-inline mb-2">
            <select id="cbxCamaras" class="form-control form-control-sm mr-1 mb-1" style="min-width: 220px;">
                <option value="">-- Sin cámaras --</option>
            </select>
            <button id="btnBuscarCamaras" class="btn btn-outline-primary btn-sm mr-1 mb-1" onclick="buscarCamaras()">Buscar cámaras</button>
            <button id="btnIniciar" class="btn btn-success btn-sm mr-1 mb-1" onclick="iniciarCamara()">Iniciar</button>
            <button id="btnDetener" class="btn btn-danger btn-sm mb-1" onclick="detenerCamara()" disabled>Detener</button>
        </div>
        <div id="lectorCamara" class="border bg-light" style="width: 100%; min-height: 300px;"></div>
        <h6 class="mt-3">Lecturas de esta sesión</h6>
        <ul id="listaLecturas" class="list-group small"></ul>
    </div>
    <div class="col-md-6">
        <div id="estado" class="estado-lectura mb-2">Cámara apagada</div>
        <div id="datosEquipo"></div>
    </div>
</div>
