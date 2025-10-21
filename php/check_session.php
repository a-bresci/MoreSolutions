<?php
// php/check_session.php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    // si es petición XHR devolvemos JSON, si no redirigimos
    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH'])) {
        http_response_code(401);
        echo json_encode(['success'=>false,'message'=>'No autorizado']);
    } else {
        header("Location: /MoreSolutions/login.php");
    }
    exit;
}
?>
