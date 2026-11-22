<div class="text-center mb-4">
    <h2>Sistema de Lectura y Procesamiento de Códigos QR</h2>
    <p class="text-muted">Cada equipo tiene una etiqueta con un código QR que guarda su ID.
        Al leerla con la cámara web se muestran sus datos y su configuración.</p>
</div>

<div class="row">
    <div class="col-md-4 mb-3">
        <div class="card h-100">
            <div class="card-body">
                <h5 class="card-title">Equipos</h5>
                <p class="card-text">Registrar, editar, buscar y dar de baja los equipos.</p>
                <a href="index.php?accion=equipos" class="btn btn-primary btn-sm">Gestión de equipos</a>
                <a href="index.php?accion=etiquetas" class="btn btn-outline-primary btn-sm">Etiquetas QR</a>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="card h-100">
            <div class="card-body">
                <h5 class="card-title">Lectura QR</h5>
                <p class="card-text">Leer la etiqueta de un equipo con la cámara web o desde una imagen.</p>
                <a href="index.php?accion=lectura" class="btn btn-success btn-sm">Con cámara web</a>
                <a href="index.php?accion=leer_imagen" class="btn btn-outline-success btn-sm">Desde imagen</a>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="card h-100">
            <div class="card-body">
                <h5 class="card-title">Historial y reportes</h5>
                <p class="card-text">Ver las lecturas, los cambios de configuración y el reporte en PDF.</p>
                <a href="index.php?accion=historial" class="btn btn-secondary btn-sm">Historial</a>
                <a href="index.php?accion=reporte" class="btn btn-outline-secondary btn-sm">Reporte por ID</a>
            </div>
        </div>
    </div>
</div>
