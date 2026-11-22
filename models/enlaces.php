<?php
// Modelo de enlaces: lista de páginas que se pueden abrir desde el menú
class EnlacesPaginas {

    static function enlacePaginaModel($enlacesModel) {
        $paginas = ["inicio", "equipos", "etiquetas", "lectura", "leer_imagen",
                    "historial", "historial_config", "reporte"];

        // Si la página no está en la lista, se muestra el inicio
        if (!in_array($enlacesModel, $paginas, true)) {
            return "views/inicio.php";
        }

        // Si la página está en la lista pero todavía no se ha programado
        $modulo = "views/" . $enlacesModel . ".php";
        if (!file_exists($modulo)) {
            return "views/pendiente.php";
        }
        return $modulo;
    }
}
?>
