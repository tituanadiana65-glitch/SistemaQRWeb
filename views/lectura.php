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

<script>
    var lector = null;      // objeto de la librería html5-qrcode que maneja la cámara
    var camaras = [];
    var ultimoTexto = '';
    var ultimoTiempo = 0;

    // Pide al navegador la lista de cámaras conectadas
    function buscarCamaras() {
        Html5Qrcode.getCameras().then(function (lista) {
            camaras = lista;
            $('#cbxCamaras').empty();
            if (lista.length === 0) {
                $('#cbxCamaras').append('<option value="">-- Sin cámaras --</option>');
                mostrarEstado('No se encontró ninguna cámara. Revise que esté conectada.', 'estado-baja');
                return;
            }
            $.each(lista, function (i, c) {
                $('#cbxCamaras').append('<option value="' + i + '">' + esc(c.label || ('Cámara ' + (i + 1))) + '</option>');
            });
            // Si hay más de una, se elige la última (normalmente la USB externa)
            $('#cbxCamaras').val(lista.length - 1);
            mostrarEstado('Cámaras encontradas: ' + lista.length + '. Elija una y pulse Iniciar.', '');
        }).catch(function () {
            mostrarEstado('El navegador no dio permiso para usar la cámara.', 'estado-baja');
        });
    }

    // Enciende la cámara elegida y empieza a buscar códigos QR
    function iniciarCamara() {
        var indice = $('#cbxCamaras').val();
        if (indice === '' || indice === null) { alert('Primero busque y seleccione una cámara'); return; }
        lector = new Html5Qrcode('lectorCamara');
        var opciones = { fps: 5, qrbox: { width: 250, height: 250 } };
        lector.start(camaras[indice].id, opciones, qrEncontrado, function () { /* cuadro sin QR */ })
            .then(function () {
                $('#btnIniciar, #btnBuscarCamaras, #cbxCamaras').prop('disabled', true);
                $('#btnDetener').prop('disabled', false);
                ultimoTexto = '';
                mostrarEstado('Cámara activa: acerque el código QR del equipo', '');
            })
            .catch(function (error) {
                mostrarEstado('No se pudo abrir la cámara. Revise que ningún otro programa la use. ' + error, 'estado-baja');
            });
    }

    // Apaga la cámara
    function detenerCamara() {
        if (lector && lector.isScanning) {
            lector.stop().then(function () { lector.clear(); });
        }
        $('#btnIniciar, #btnBuscarCamaras, #cbxCamaras').prop('disabled', false);
        $('#btnDetener').prop('disabled', true);
        mostrarEstado('Cámara apagada', '');
    }

    // Si se sale de la página, la cámara se apaga
    $(window).on('beforeunload', function () {
        if (lector && lector.isScanning) { lector.stop(); }
    });

    function mostrarEstado(texto, clase) {
        $('#estado').removeClass('estado-activo estado-baja').addClass(clase).text(texto);
    }

    // La librería llama a esta función cada vez que encuentra un QR en la imagen de la cámara
    function qrEncontrado(texto) {
        texto = String(texto).trim();
        // El mismo QR frente a la cámara se lee muchas veces por segundo:
        // solo se registra de nuevo si pasaron más de 3 segundos
        var ahora = Date.now();
        if (texto === ultimoTexto && ahora - ultimoTiempo < 3000) { return; }
        ultimoTexto = texto;
        ultimoTiempo = ahora;
        procesarQR(texto);
    }

    // Revisa que el QR tenga un ID, registra la lectura y muestra el equipo
    function procesarQR(texto) {
        pitido();
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
            var hora = new Date().toLocaleTimeString();
            $('#listaLecturas').prepend('<li class="list-group-item py-1">' + hora + ' - ID ' + e.id_equipo + ' - ' + esc(e.nombre) + '</li>');
        }, 'json');
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

    // Sonido corto para avisar que se leyó un QR
    function pitido() {
        try {
            var audio = new (window.AudioContext || window.webkitAudioContext)();
            var oscilador = audio.createOscillator();
            oscilador.frequency.value = 880;
            oscilador.connect(audio.destination);
            oscilador.start();
            oscilador.stop(audio.currentTime + 0.15);
        } catch (e) { /* sin sonido */ }
    }
</script>
