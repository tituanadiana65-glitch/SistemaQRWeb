<?php
// Cierra la sesión y vuelve a la pantalla de inicio de sesión
session_start();
$_SESSION = [];
session_destroy();
header("Location: ../index.php");
exit;
?>