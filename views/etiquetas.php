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

<script>
    var equipos = [];
    var equipoActual = null;

    // Llena la lista con los equipos activos
    $.post('models/equipos_listar.php', { buscar: '', incluir_bajas: 0 }, function (data) {
        if (data.error) { aviso(data.error, 'danger'); return; }
        equipos = data;
        $.each(data, function (i, e) {
            $('#cbxEquipos').append('<option value="' + i + '">' + e.id_equipo + ' - ' + esc(e.nombre) + '</option>');
        });
    }, 'json');

    // Dibuja la etiqueta: arriba el QR (que guarda el ID) y abajo "ID n" y el nombre
    function generarEtiqueta() {
        var indice = $('#cbxEquipos').val();
        if (indice === '') { alert('Seleccione un equipo'); return; }
        equipoActual = equipos[indice];

        // 1. La librería qrcode.js genera el QR con el texto del ID
        $('#qrTemporal').empty();
        new QRCode(document.getElementById('qrTemporal'), {
            text: String(equipoActual.id_equipo),
            width: 280,
            height: 280,
            correctLevel: QRCode.CorrectLevel.M
        });
        var qr = $('#qrTemporal canvas')[0];

        // 2. Se arma la etiqueta en un canvas de 300 x 370 píxeles
        var canvas = document.getElementById('etiqueta');
        var g = canvas.getContext('2d');
        g.fillStyle = '#ffffff';
        g.fillRect(0, 0, 300, 370);
        g.drawImage(qr, 10, 10);
        g.fillStyle = '#000000';
        g.textAlign = 'center';
        g.font = 'bold 22px Arial';
        g.fillText('ID ' + equipoActual.id_equipo, 150, 320);
        var nombre = equipoActual.nombre.length > 34 ? equipoActual.nombre.substring(0, 33) + '...' : equipoActual.nombre;
        g.font = '14px Arial';
        g.fillText(nombre, 150, 348);
        g.strokeStyle = '#c0c0c0';
        g.strokeRect(0.5, 0.5, 299, 369);

        $('#textoVistaPrevia').hide();
        $(canvas).show();
    }
</script>
