<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sistema QR de Equipos</title>
    <link rel="stylesheet" href="lib/bootstrap.min.css">
    <link rel="stylesheet" href="css/estilos.css">
    <script src="lib/jquery.min.js"></script>
    <script src="lib/bootstrap.bundle.min.js"></script>
    <script src="js/app.js"></script>
</head>
<body>
    <header class="encabezado">
        <h1>Sistema de Lectura y Procesamiento de Códigos QR</h1>
        <p>Configuración de equipos con cámara web USB</p>
    </header>

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <a class="navbar-brand" href="index.php">Sistema QR</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#menu">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="menu">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item"><a class="nav-link" href="index.php?accion=inicio">Inicio</a></li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown">Equipos</a>
                    <div class="dropdown-menu">
                        <a class="dropdown-item" href="index.php?accion=equipos">Gestión de equipos</a>
                        <a class="dropdown-item" href="index.php?accion=etiquetas">Etiquetas QR</a>
                        <a class="dropdown-item" href="index.php?accion=historial_config">Historial de configuración</a>
                    </div>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown">Lectura QR</a>
                    <div class="dropdown-menu">
                        <a class="dropdown-item" href="index.php?accion=lectura">Lectura con cámara web</a>
                        <a class="dropdown-item" href="index.php?accion=leer_imagen">Leer QR desde imagen</a>
                        <a class="dropdown-item" href="index.php?accion=historial">Historial de lecturas</a>
                    </div>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown">Reportes</a>
                    <div class="dropdown-menu">
                        <a class="dropdown-item" href="index.php?accion=reporte">Reporte de equipo por ID</a>
                    </div>
                </li>
            </ul>
        </div>
    </nav>

    <section class="contenido container my-4">
        <div id="avisos"></div>
        <?php
            $mvc = new PaginasController();
            $mvc->enlacesPaginasController();
        ?>
    </section>

    <footer>
        Universidad Técnica de Ambato · FISEI · Software · Cuarto A
    </footer>
</body>
</html>
