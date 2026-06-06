<?php
session_start();

ini_set('display_errors', 1);
ini_set('log_errors', 1);

include("clases/mysql.inc.php");
include("clases/SanitizarEntrada.php");
include("clases/Registrese.php");
include("comunes/loginfunciones.php");

require 'vendor/autoload.php';

use Sonata\GoogleAuthenticator\GoogleAuthenticator;
use Sonata\GoogleAuthenticator\GoogleQrUrl;

// ── CSRF: validar token antes de procesar el registro ───────────────────────
if (
    empty($_POST['csrf_token_registro']) ||
    empty($_SESSION['csrf_token_registro']) ||
    !hash_equals($_SESSION['csrf_token_registro'], $_POST['csrf_token_registro'])
) {
    session_destroy();
    header("Location: FormRegistro.php");
    exit();
}
unset($_SESSION['csrf_token_registro']);
// ────────────────────────────────────────────────────────────────────────────

$pdo        = new mod_db();
$conn       = $pdo->getConexion();
$arrMensaje = array();
$qr_url     = null;   // ← inicializar siempre para evitar Undefined variable

try {
    $MyRegistro = new RegistroUsuario($_POST, $pdo, $arrMensaje);

    if (count($arrMensaje) == 0) {
        $Accion = $_POST['Accion'];

        if ($Accion == "Guardar") {

            // VALIDACIÓN BACKEND: verificar correo duplicado
            $correo  = SanitizarEntrada::sanitizarCorreo($_POST['correo']);
            $usuario = $correo; // El usuario es el correo completo

            if (!$correo) {
                $_SESSION['error'] = 'El correo electrónico no es válido.';
                header('Location: FormRegistro.php');
                exit;
            }

            if (!$usuario) {
                $_SESSION['error'] = 'El correo electrónico no es válido.';
                header('Location: FormRegistro.php');
                exit;
            }

            $stmtCorreo = $conn->prepare("SELECT COUNT(*) as total FROM usuarios WHERE Correo = :correo");
            $stmtCorreo->bindParam(':correo', $correo, PDO::PARAM_STR);
            $stmtCorreo->execute();
            $filaCorreo = $stmtCorreo->fetch(PDO::FETCH_OBJ);

            if ($filaCorreo->total > 0) {
                $_SESSION['error'] = 'El correo electrónico ya está registrado.';
                header('Location: FormRegistro.php');
                exit;
            }

            // VALIDACIÓN BACKEND: verificar usuario duplicado (mismo correo)
            $stmtUsuario = $conn->prepare("SELECT COUNT(*) as total FROM usuarios WHERE Usuario = :usuario");
            $stmtUsuario->bindParam(':usuario', $usuario, PDO::PARAM_STR);
            $stmtUsuario->execute();
            $filaUsuario = $stmtUsuario->fetch(PDO::FETCH_OBJ);

            if ($filaUsuario->total > 0) {
                $_SESSION['error'] = 'El usuario ya está en uso.';
                header('Location: FormRegistro.php');
                exit;
            }

            // VALIDACIÓN BACKEND: contraseñas coincidentes
            if ($_POST['clave'] !== $_POST['clave2']) {
                $_SESSION['error'] = 'Las contraseñas no coinciden.';
                header('Location: FormRegistro.php');
                exit;
            }

            $MyRegistro->Guardar_RegistroUsuario();

            // Generar el secreto 2FA
            $g      = new GoogleAuthenticator();
            $secret = $g->generateSecret();

            // Guardar el secreto en la BD
            $MyRegistro->GuardarMySecreto($secret);

            // Generar el QR
            $nombre_usuario    = $MyRegistro->getUsuario();
            $nombre_aplicacion = 'MiSistemaLogin';
            $otpauth           = 'otpauth://totp/' . urlencode($nombre_aplicacion) . ':' . urlencode($nombre_usuario) . '?secret=' . $secret . '&issuer=' . urlencode($nombre_aplicacion);
            $qr_url            = 'https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=' . urlencode($otpauth);
        }
    } else {
        // Redirigir con el primer mensaje de error encontrado
        $_SESSION['error'] = reset($arrMensaje);
        header('Location: FormRegistro.php');
        exit;
    }
} catch (Exception $e) {
    $_SESSION['error'] = "Ha ocurrido un error: " . $e->getMessage();
    header('Location: FormRegistro.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Activar 2FA</title>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Outfit:wght@300;400;500;600&family=JetBrains+Mono:wght@500&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --bg:       #080b12;
            --surface:  #0e1420;
            --border:   #1e2a40;
            --accent:   #4f8ef7;
            --accent2:  #a78bfa;
            --green:    #22d3a5;
            --text:     #e2e8f0;
            --muted:    #64748b;
        }

        body {
            background: var(--bg);
            color: var(--text);
            font-family: 'Outfit', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow-x: hidden;
            padding-bottom: 50px;
        }

        /* fondo animado */
        body::before {
            content: '';
            position: fixed; inset: 0;
            background:
                radial-gradient(ellipse 60% 50% at 20% 20%, rgba(79,142,247,.10) 0%, transparent 70%),
                radial-gradient(ellipse 50% 60% at 80% 80%, rgba(167,139,250,.08) 0%, transparent 70%);
            pointer-events: none;
        }

        .card {
            position: relative;
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 20px;
            padding: 48px 44px 40px;
            padding-bottom: 60px;
            width: min(480px, 94vw);
            text-align: center;
            box-shadow: 0 24px 80px rgba(0,0,0,.5);
            animation: fadeUp .6s cubic-bezier(.22,1,.36,1) both;
        }

        @keyframes fadeUp {
            from { opacity:0; transform: translateY(28px); }
            to   { opacity:1; transform: translateY(0); }
        }

        /* badge superior */
        .badge {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            background: rgba(34,211,165,.10);
            border: 1px solid rgba(34,211,165,.25);
            color: var(--green);
            font-size: .72rem;
            font-weight: 600;
            letter-spacing: .12em;
            text-transform: uppercase;
            padding: 5px 14px;
            border-radius: 99px;
            margin-bottom: 22px;
        }
        .badge::before {
            content: '';
            width: 7px; height: 7px;
            background: var(--green);
            border-radius: 50%;
            box-shadow: 0 0 8px var(--green);
            animation: pulse 1.6s ease-in-out infinite;
        }
        @keyframes pulse {
            0%,100% { opacity:1; transform:scale(1); }
            50%      { opacity:.4; transform:scale(.7); }
        }

        h1 {
            font-family: 'Bebas Neue', sans-serif;
            font-size: 2.6rem;
            letter-spacing: .06em;
            line-height: 1;
            background: linear-gradient(135deg, var(--accent), var(--accent2));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 8px;
        }

        .subtitle {
            color: var(--muted);
            font-size: .88rem;
            margin-bottom: 32px;
            line-height: 1.5;
        }

        /* marco del QR */
        .qr-wrap {
            position: relative;
            display: inline-block;
            margin-bottom: 28px;
        }
        .qr-wrap::before, .qr-wrap::after {
            content: '';
            position: absolute;
            width: 22px; height: 22px;
            border-color: var(--accent);
            border-style: solid;
        }
        .qr-wrap::before { top:-8px; left:-8px; border-width:3px 0 0 3px; border-radius:4px 0 0 0; }
        .qr-wrap::after  { bottom:-8px; right:-8px; border-width:0 3px 3px 0; border-radius:0 0 4px 0; }

        .qr-wrap img {
            display: block;
            width: 200px; height: 200px;
            border-radius: 10px;
            border: 3px solid var(--border);
            padding: 6px;
            background: #fff;
        }

        /* pasos */
        .steps {
            display: flex;
            flex-direction: column;
            gap: 10px;
            margin-bottom: 30px;
            text-align: left;
        }
        .step {
            display: flex;
            align-items: center;
            gap: 12px;
            background: rgba(255,255,255,.03);
            border: 1px solid var(--border);
            border-radius: 10px;
            padding: 11px 14px;
            font-size: .84rem;
            color: var(--text);
        }
        .step-n {
            font-family: 'JetBrains Mono', monospace;
            font-size: .7rem;
            color: var(--accent);
            background: rgba(79,142,247,.12);
            border: 1px solid rgba(79,142,247,.25);
            border-radius: 6px;
            padding: 3px 8px;
            flex-shrink: 0;
        }

        /* botón */
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 9px;
            background: linear-gradient(135deg, var(--accent), var(--accent2));
            color: #fff;
            font-family: 'Outfit', sans-serif;
            font-weight: 600;
            font-size: .9rem;
            padding: 13px 32px;
            border-radius: 10px;
            text-decoration: none;
            letter-spacing: .03em;
            transition: opacity .2s, transform .2s;
            box-shadow: 0 4px 24px rgba(79,142,247,.30);
        }
        .btn:hover { opacity:.88; transform: translateY(-2px); }
        .btn svg { flex-shrink:0; }

        footer, .footer, #footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            text-align: center;
            padding: 10px;
            font-size: .75rem;
            color: var(--muted);
            background: transparent;
        }

        #footer span {
            display: inline;
        }
    </style>
</head>
<body>

<div class="card">

    <?php if ($qr_url): ?>

        <div class="badge">Registro completado</div>

        <h1>Activa tu 2FA</h1>
        <p class="subtitle">Escanea el código QR con <strong>Google Authenticator</strong><br>para proteger tu cuenta.</p>

        <div class="qr-wrap">
            <img src="<?= $qr_url ?>" alt="Código QR 2FA">
        </div>

        <div class="steps">
            <div class="step"><span class="step-n">01</span> Abre Google Authenticator en tu celular</div>
            <div class="step"><span class="step-n">02</span> Toca el botón <strong>+</strong> y elige "Escanear QR"</div>
            <div class="step"><span class="step-n">03</span> Apunta la cámara a este código</div>
            <div class="step"><span class="step-n">04</span> Inicia sesión con el código de 6 dígitos</div>
        </div>

        <a href="login.php" class="btn">
            Ir a iniciar sesión
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
        </a>

    <?php else: ?>

        <div class="badge" style="--green:#ef4444; border-color:rgba(239,68,68,.25); background:rgba(239,68,68,.10);">Error en el registro</div>
        <h1>Algo salió mal</h1>
        <p class="subtitle">No se pudo completar el registro.<br>Por favor intenta de nuevo.</p>
        <a href="FormRegistro.php" class="btn">Volver al formulario</a>

    <?php endif; ?>

</div>

<?php include("comunes/footer.php"); ?>
</body>
</html>