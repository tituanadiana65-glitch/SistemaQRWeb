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

<script>
    // Se ejecuta cuando el usuario elige un archivo
    $('#archivoImagen').on('change', function () {
        var archivo = this.files[0];
        if (!archivo) { return; }
        $('.custom-file-label').text(archivo.name);
        $('#datosEquipo').empty();
        $('#btnReporte').hide();
        $('#vistaPrevia').attr('src', URL.createObjectURL(archivo)).show();
        $('#textoVistaPrevia').hide();
        leerImagen(archivo);
    });

    // La librería html5-qrcode busca el QR en la imagen y devuelve su texto
    function leerImagen(archivo) {
        mostrarEstado('Leyendo el código QR...', '');
        var lector = new Html5Qrcode('lectorOculto');
        lector.scanFile(archivo, false)
            .then(function (texto) {
                procesarQR(texto);
            })
            .catch(function () {
                mostrarEstado('La imagen no tiene un código QR legible.', 'estado-baja');
            });
    }

    // El texto del QR debe ser un número: el ID del equipo
    function procesarQR(texto) {
        texto = String(texto).trim();
        if (!/^[0-9]+$/.test(texto) || parseInt(texto, 10) <= 0) {
            mostrarEstado('El código QR no contiene un ID válido de equipo.', 'estado-baja');
            return;
        }
        $.post('models/lecturas_registrar.php', { id_equipo: texto }, function (r) {
            if (r.error) {
                mostrarEstado(r.error, 'estado-baja');
                return;
            }
            var e = r.equipo;
            if (e.estado === 'ACTIVO') {
                mostrarEstado('Equipo identificado: ID ' + e.id_equipo + ' - ' + e.nombre, 'estado-activo');
            } else {
                mostrarEstado('ATENCIÓN: el equipo con ID ' + e.id_equipo + ' está DADO DE BAJA', 'estado-baja');
            }
            mostrarDatos(e);
            $('#btnReporte').attr('href', 'index.php?accion=reporte&id=' + e.id_equipo).show();
        }, 'json');
    }

    function mostrarEstado(texto, clase) {
        $('#estado').removeClass('estado-activo estado-baja').addClass(clase).text(texto);
    }

    function mostrarDatos(e) {
        var html = '<table class="table table-sm">' +
            '<tr><th>ID</th><td>' + e.id_equipo + '</td></tr>' +
            '<tr><th>Nombre</th><td>' + esc(e.nombre) + '</td></tr>' +
            '<tr><th>Tipo</th><td>' + esc(e.tipo) + '</td></tr>' +
            '<tr><th>Ubicación</th><td>' + esc(e.ubicacion) + '</td></tr>' +
            '<tr><th>Responsable</th><td>' + esc(e.responsable) + '</td></tr>' +
            '<tr><th>IP / MAC</th><td>' + esc(e.direccion_ip) + ' / ' + esc(e.direccion_mac) + '</td></tr>' +
            '<tr><th>Sistema operativo</th><td>' + esc(e.sistema_operativo) + '</td></tr>' +
            '<tr><th>Veces leído</th><td>' + e.veces_leido + '</td></tr>' +
            '</table><strong>Configuración</strong>' +
            '<pre class="border p-2 bg-light">' + esc(e.configuracion) + '</pre>';
        $('#datosEquipo').html(html);
    }
</script>
