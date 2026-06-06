<?php
session_start();

// Si no hay usuario pendiente de verificar, redirigir al login
if (!isset($_SESSION['usuario_pendiente'])) {
    header("location: login.php");
    exit();
}

include("clases/mysql.inc.php");
require 'vendor/autoload.php';

use Sonata\GoogleAuthenticator\GoogleAuthenticator;

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // ── CSRF: validar token antes de procesar el código 2FA ─────────────────
    if (
        empty($_POST['csrf_token_2fa']) ||
        empty($_SESSION['csrf_token_2fa']) ||
        !hash_equals($_SESSION['csrf_token_2fa'], $_POST['csrf_token_2fa'])
    ) {
        session_destroy();
        header("Location: login.php");
        exit();
    }
    // Invalidar el token después de validarlo (uso único)
    unset($_SESSION['csrf_token_2fa']);
    // ────────────────────────────────────────────────────────────────────────

    $codigo  = trim($_POST['codigo_2fa']);
    $usuario = $_SESSION['usuario_pendiente'];

    $db     = new mod_db();
    $secret = $db->getSecret2FA($usuario);

    $g = new GoogleAuthenticator();

    $ipRemoto = $_SERVER['REMOTE_ADDR'];

    if ($g->checkCode($secret, $codigo)) {
        // Registrar verificación exitosa
        $db->registrarVerificacion2FA($usuario, $ipRemoto, 1);

        // Código correcto — autenticación completa
        $_SESSION['autenticado'] = "SI";
        $_SESSION['Usuario']     = $usuario;
        unset($_SESSION['usuario_pendiente']);
        header("location: formularios/PanelControl.php");
        exit();
    } else {
        // Registrar intento fallido
        $db->registrarVerificacion2FA($usuario, $ipRemoto, 0);

        $error = "Código incorrecto. Intenta de nuevo.";
    }
}

// ── CSRF: generar token para el formulario 2FA ──────────────────────────────
if (empty($_SESSION['csrf_token_2fa'])) {
    $_SESSION['csrf_token_2fa'] = bin2hex(random_bytes(32));
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verificación 2FA</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&family=Orbitron:wght@700;900&family=Share+Tech+Mono&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="Estilos/Autenticacion.css"/>
</head>
<body>

<div class="shell">

    <div class="lado-izq">
        <div class="izq-inner">
            <div class="chip">SISTEMA ACTIVO</div>
            <h1 class="aside-title">Verificación<br>de Dos<br>Factores</h1>
            <p class="aside-sigla">2FA</p>
            <p class="aside-desc">
                Ingresa el código de 6 dígitos que genera Google Authenticator en tu celular.
            </p>
            <div class="info-box">
                <div class="info-item">
                    <span class="info-num">01</span>
                    <span>Abre Google Authenticator</span>
                </div>
                <div class="info-item">
                    <span class="info-num">02</span>
                    <span>Busca la cuenta MiSistemaLogin</span>
                </div>
                <div class="info-item">
                    <span class="info-num">03</span>
                    <span>Ingresa el código de 6 dígitos</span>
                </div>
            </div>
        </div>
        <div class="aside-footer">PROTEGIDO CON 2FA</div>
    </div>

    <div class="lado-der">
        <div class="form-box">

            <div class="form-top">
                <p class="form-eyebrow">SEGUNDO FACTOR</p>
                <h2 class="form-title">Código 2FA</h2>
                <p class="form-sub">El código cambia cada 30 segundos</p>
            </div>

            <div class="timer-bar">
                <div class="timer-bar-inner" id="timerBar"></div>
            </div>

            <?php if ($error): ?>
                <div class="msg-error"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <form method="post" action="Autenticacion.php">

                <!-- ── CSRF: campo oculto con el token ─────────────────────── -->
                <input type="hidden" name="csrf_token_2fa" value="<?= $_SESSION['csrf_token_2fa'] ?>">

                <div class="field">
                    <label for="codigo_2fa">Código de verificación</label>
                    <input
                        type="text"
                        id="codigo_2fa"
                        name="codigo_2fa"
                        placeholder="000000"
                        maxlength="6"
                        autocomplete="off"
                        required
                        autofocus
                    >
                </div>

                <button type="submit" class="btn-reg">
                    <span>Verificar código</span>
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                </button>
            </form>

            <p class="link-bottom"><a href="login.php">← Volver al inicio de sesión</a></p>

        </div>
    </div>

</div>

<script>
// Sincroniza la barra con el ciclo real de 30 segundos de TOTP
(function() {
    const bar = document.getElementById('timerBar');
    function updateBar() {
        const s = Math.floor(Date.now() / 1000);
        const elapsed = s % 30;
        const pct = ((30 - elapsed) / 30) * 100;
        bar.style.animation = 'none';
        bar.style.transform = 'scaleX(' + (pct / 100) + ')';
        bar.style.transformOrigin = 'left';
    }
    updateBar();
    setInterval(updateBar, 1000);
})();

// Solo deja ingresar dígitos en el campo
const inp = document.getElementById('codigo_2fa');
inp.addEventListener('input', function() {
    this.value = this.value.replace(/\D/g, '').slice(0, 6);
});
</script>

</body>
</html>