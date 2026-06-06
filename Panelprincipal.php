<?php
session_start();
include("clases/mysql.inc.php");
$db = new mod_db();

include("clases/SanitizarEntrada.php");
include("clases/GestorHash.php");
include("clases/objLoginAdmin.php");
include("comunes/loginfunciones.php");

$tolog = false;

if (isset($_POST["tolog"]))
    $tolog = $_POST["tolog"];

if (isset($tolog) && ($tolog == "true") && ($_SERVER['REQUEST_METHOD'] === 'POST')) {

    // ── CSRF: validar token antes de procesar el login ──────────────────────
    if (
        empty($_POST['csrf_token_login']) ||
        empty($_SESSION['csrf_token_login']) ||
        !hash_equals($_SESSION['csrf_token_login'], $_POST['csrf_token_login'])
    ) {
        session_destroy();
        header("Location: login.php");
        exit();
    }
    unset($_SESSION['csrf_token_login']);
    // ────────────────────────────────────────────────────────────────────────

    $Usuario  = $_POST['usuario'];
    $ClaveKey = $_POST['contrasena'];
    $ipRemoto = $_SERVER['REMOTE_ADDR'];

    $Logearme = new ValidacionLogin($Usuario, $ClaveKey, $ipRemoto, $db);

    if ($Logearme->logger()) {
        $Logearme->autenticar();

        if ($Logearme->getIntentoLogin()) {
            $_SESSION['usuario_pendiente'] = $Logearme->getUsuario();
            $Logearme->registrarAcceso();   // ← escribe en tabla "accesos"
            $tolog = false;
            redireccionar("Autenticacion.php");
        } else {
            $Logearme->registrarAcceso();   // ← también registra los fallidos
            $_SESSION["emsg"] = 1;
            redireccionar("login.php");
        }
    } else {
        $_SESSION["emsg"] = 1;
        redireccionar("login.php");
    }

} else {
    redireccionar("login.php");
}
?>