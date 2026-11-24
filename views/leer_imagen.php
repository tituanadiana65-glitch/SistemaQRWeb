<script src="lib/html5-qrcode.min.js"></script>

<h3>Leer código QR desde una imagen</h3>
<p class="text-muted">Elija una foto o la imagen PNG de una etiqueta. El sistema lee el ID del QR,
    registra la lectura y muestra los datos del equipo.</p>

<div class="row">
    <div class="col-md-5">
        <div class="custom-file mb-2">
            <input type="file" class="custom-file-input" id="archivoImagen" accept="image/png, image/jpeg">
            <label class="custom-file-label" for="archivoImagen">Seleccionar imagen...</label>
        </div>
        <div class="border text-center bg-light p-2" style="min-height: 300px;">
            <img id="vistaPrevia" style="max-width: 100%; max-height: 280px; display: none;">
            <span id="textoVistaPrevia" class="text-muted" style="display: block; margin-top: 130px;">Vista previa</span>
        </div>
    </div>
    <div class="col-md-7">
        <div id="estado" class="estado-lectura mb-2">Seleccione una imagen con un código QR</div>
        <div id="datosEquipo"></div>
        <a id="btnReporte" class="btn btn-secondary" href="#" style="display:none;">Ver reporte del equipo</a>
    </div>
</div>

<!-- La librería necesita un elemento propio para analizar la imagen -->
<div id="lectorOculto" style="display:none;"></div>
