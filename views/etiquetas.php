<script src="lib/qrcode.min.js"></script>

<h3>Etiquetas QR</h3>
<p class="text-muted">Elija un equipo y genere su etiqueta. El código QR guarda solo el ID del equipo:
    así la etiqueta nunca se desactualiza aunque cambie su configuración.</p>

<div class="row">
    <div class="col-md-5">
        <div class="form-group">
            <label for="cbxEquipos">Equipo</label>
            <select id="cbxEquipos" class="form-control">
                <option value="">-- Seleccione un equipo --</option>
            </select>
        </div>
        <button class="btn btn-primary mb-1" onclick="generarEtiqueta()">Generar etiqueta</button>
        <button id="btnDescargar" class="btn btn-success mb-1" onclick="descargarPNG()" disabled>Descargar PNG</button>
        <button id="btnImprimir" class="btn btn-secondary mb-1" onclick="imprimirEtiqueta()" disabled>Imprimir</button>
    </div>
    <div class="col-md-7 text-center">
        <div id="zonaImpresion" class="border d-inline-block p-2 bg-light" style="min-width: 304px; min-height: 374px;">
            <span id="textoVistaPrevia" class="text-muted" style="display: block; margin-top: 170px;">Vista previa</span>
            <canvas id="etiqueta" width="300" height="370" style="display:none;"></canvas>
        </div>
    </div>
</div>

<!-- Aquí la librería dibuja el QR; luego se copia a la etiqueta -->
<div id="qrTemporal" style="display:none;"></div>
