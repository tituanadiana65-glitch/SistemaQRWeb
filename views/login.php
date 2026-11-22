<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Iniciar sesión - Sistema QR de Equipos</title>
    <link rel="stylesheet" href="lib/bootstrap.min.css">
    <link rel="stylesheet" href="css/estilos.css">
    <script src="lib/jquery.min.js"></script>
</head>
<body>
    <header class="encabezado">
        <h1>Sistema de Lectura y Procesamiento de Códigos QR</h1>
        <p>Configuración de equipos con cámara web USB</p>
    </header>

    <div class="container my-5 flex-grow-1" style="max-width: 400px;">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title text-center mb-3">Iniciar sesión</h4>
                <form id="fmLogin">
                    <div class="form-group">
                        <label for="usuario">Usuario</label>
                        <input id="usuario" name="usuario" class="form-control" autocomplete="username" required autofocus>
                    </div>
                    <div class="form-group">
                        <label for="clave">Contraseña</label>
                        <input id="clave" name="clave" type="password" class="form-control" autocomplete="current-password" required>
                    </div>
                    <div id="mensaje" class="text-danger small mb-2"></div>
                    <button type="submit" class="btn btn-primary btn-block">Ingresar</button>
                </form>
            </div>
        </div>
    </div>

    <footer>
        Universidad Técnica de Ambato · FISEI · Software · Cuarto A
    </footer>
    
    <script>
        var intentos = 0;

        // Al pulsar Ingresar (o Enter) se validan los datos en el servidor
        $('#fmLogin').on('submit', function (e) {
            e.preventDefault();
            $('#mensaje').text('');
            $.post('models/login.php', $(this).serialize(), function (r) {
                if (r.success) {
                    location.href = 'index.php';
                } else {
                    intentos++;
                    $('#clave').val('').focus();
                    $('#mensaje').text(r.error + ' (intento ' + intentos + ')');
                }
            }, 'json').fail(function () {
                $('#mensaje').text('No se pudo comunicar con el servidor. Revise que XAMPP esté encendido.');
            });
        });
    </script>
</body>
</html>