<h3>Reporte de equipo por ID</h3>
<p class="text-muted">Escriba el ID del equipo (el número que va dentro del código QR) y pulse Generar reporte.
    El PDF se genera con JasperReports y tarda unos segundos.</p>

<div id="toolbar" class="form-inline mb-2">
    <input id="txtId" type="number" min="1" class="form-control form-control-sm mr-1 mb-1" placeholder="ID del equipo" style="width:130px"
           value="<?php echo (isset($_GET['id']) && (int) $_GET['id'] > 0) ? (int) $_GET['id'] : ''; ?>">
    <button id="btnGenerar" class="btn btn-primary btn-sm mr-1 mb-1" onclick="generarReporte()">Generar reporte</button>
    <button id="btnDescargar" class="btn btn-secondary btn-sm mb-1" onclick="descargarPdf()">Descargar PDF</button>
</div>

<div id="estado" class="estado-lectura mb-2" style="display:none;">Generando reporte…</div>
<iframe id="visor" class="border" style="width: 100%; height: 700px; display: none;"></iframe>

<script>
    // Revisa que el ID sea un número mayor que 0 (el servidor lo vuelve a revisar)
    function leerId() {
        var id = $('#txtId').val().trim();
        if (!/^[0-9]+$/.test(id) || parseInt(id, 10) <= 0) {
            alert('Ingrese un ID de equipo válido');
            return 0;
        }
        return parseInt(id, 10);
    }

    // Comprueba que el equipo exista antes de pedir el PDF
    function comprobarEquipo(id, siExiste) {
        $('#avisos').empty();
        $.post('models/equipos_buscar.php', { id_equipo: id }, function (e) {
            if (e.error) { aviso(e.error, 'danger'); return; }
            siExiste();
        }, 'json');
    }

    // Mientras se genera el PDF se muestra el aviso y se desactivan los botones
    function cargando(activo) {
        $('#btnGenerar, #btnDescargar').prop('disabled', activo);
        $('#estado').toggle(activo);
    }

    // Muestra el PDF dentro de la página
    function generarReporte() {
        var id = leerId();
        if (!id) { return; }
        comprobarEquipo(id, function () {
            cargando(true);
            $('#visor').show().attr('src', 'models/reporte_pdf.php?id=' + id);
        });
    }

    // Cuando el PDF termina de cargar se vuelven a activar los botones
    $('#visor').on('load', function () {
        if ($(this).attr('src')) { cargando(false); }
    });

    // Descarga el PDF como archivo reporte_equipo_N.pdf (la página no cambia)
    function descargarPdf() {
        var id = leerId();
        if (!id) { return; }
        comprobarEquipo(id, function () {
            aviso('El PDF se descargará en unos segundos', 'info');
            location.href = 'models/reporte_pdf.php?id=' + id + '&descargar=1';
        });
    }

    $('#txtId').on('keypress', function (e) {
        if (e.which === 13) { generarReporte(); }
    });

    // Si se llegó con ?id= (botones "Ver reporte" de la lectura y del historial), se genera solo
    if ($('#txtId').val() !== '') {
        generarReporte();
    }
</script>
