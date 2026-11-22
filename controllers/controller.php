<?php
require_once __DIR__ . "/../models/enlaces.php";

// Controlador: decide qué página (vista) se muestra según ?accion= de la URL
class PaginasController {

    public function enlacesPaginasController() {
        if (isset($_GET['accion'])) {
            $enlacesController = $_GET['accion'];
        } else {
            $enlacesController = "inicio";
        }

        $respuesta = EnlacesPaginas::enlacePaginaModel($enlacesController);
        include $respuesta;
    }

    public function getTemplate() {
        include "views/template.php";
    }
}
?>
