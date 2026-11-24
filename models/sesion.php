<?php
// Todos los modelos incluyen este archivo: si no hay sesión, no responden datos
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION["usuario"])) {
    http_response_code(401);
    header("Content-Type: application/json; charset=utf-8");
    echo json_encode(["error" => "La sesión terminó. Vuelva a iniciar sesión."]);
    exit;
}
?>