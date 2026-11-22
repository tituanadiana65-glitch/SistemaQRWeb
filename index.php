<?php
// Punto de entrada del sistema (patrón MVC, igual que en clase)
require_once "controllers/controller.php";
require_once "models/enlaces.php";

$mvc = new PaginasController();
$mvc->getTemplate();
?>
