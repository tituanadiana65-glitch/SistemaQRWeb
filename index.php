<?php
// Punto de entrada del sistema (patrón MVC, igual que en clase)
session_start();

// Si no hay una sesión iniciada, se muestra la pantalla de inicio de sesión
if (!isset($_SESSION['usuario'])) {
    include "views/login.php";
    exit;
}
require_once "controllers/controller.php";
require_once "models/enlaces.php";

$mvc = new PaginasController();
$mvc->getTemplate();
?>
