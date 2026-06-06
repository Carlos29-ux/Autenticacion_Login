<?php
// CSRF token ya se genera en login.php antes de incluir este archivo.
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="Description" content="Ejemplo de Login" />
    <title>Inicio de Sesión | MiSistemaLogin</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Orbitron:wght@700;900&family=Share+Tech+Mono&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="Estilos/Login.css">

    <script src="jquery/jquery-latest.js" type="text/javascript"></script>
    <script src="jquery/jquery.validate.js" type="text/javascript"></script>
    <script>
    $(document).ready(function(){
        $("#deteccionUser").validate({
            rules: {
                usuario:   "required",
                contrasena: "required"
            }
        });
    });
    </script>
</head>
<body>

<div class="shell">

    <!-- ── PANEL IZQUIERDO ── -->
    <div class="lado-izq">
        <div class="izq-inner">
            <div class="chip"></span>SISTEMA ACTIVO</div>

            <p class="aside-logo">IMPLEMENTACIÓN DE 2FA</p>

            <h1 class="aside-title">Laboratorio de<br>Autenticación</h1>

            <p class="aside-desc">Accede con tus Credenciales. La Sesión está protegida con Cifrado y Verificación de Dos Factores.</p>

            <div class="feature-list">
                <div class="feature-item"><span class="feature-icon">🔒</span><span>Conexión Cifrada</span></div>
                <div class="feature-item"><span class="feature-icon">🛡️</span><span>Protección con 2FA</span></div>
                <div class="feature-item"><span class="feature-icon">⚡</span><span>Sesión Segura y Monitorizada</span></div>
            </div>
        </div>

        <div class="aside-footer">LABORATORIO NO.6</div>
    </div>

    <!-- ── PANEL DERECHO ── -->
    <div class="lado-der">
        <div class="form-box">

            <p class="form-eyebrow">INICIO DE SESIÓN</p>
            <h2 class="form-title">Bienvenido</h2>
            <p class="form-sub">Ingresa tus Credenciales para Continuar</p>
            <div class="divider"></div>

            <?php if (isset($_SESSION["emsg"]) && $_SESSION["emsg"] == 1): ?>
                <div class="msg-error">Usuario o Contraseña Incorrectos, vuelve a intentarlo.</div>
                <?php unset($_SESSION["emsg"]); ?>
            <?php endif; ?>

            <form id="deteccionUser" name="deteccionUser" method="post" action="Panelprincipal.php">
                <input type="hidden" name="tolog" id="tolog" value="true">

                <!-- ── CSRF: campo oculto con el token ─────────────────────────────── -->
                <input type="hidden" name="csrf_token_login" value="<?= $_SESSION['csrf_token_login'] ?>">

                <div class="field">
                    <label for="usuario">Correo</label>
                    <input type="email" id="usuario" name="usuario" placeholder="tu@correo.com" autocomplete="username">
                </div>

                <div class="field">
                    <label for="contrasena">Contraseña</label>
                    <input type="password" id="contrasena" name="contrasena" placeholder="••••••••" autocomplete="current-password">
                </div>

                <button type="submit" class="btn-login">
                    <span>Iniciar sesión</span>
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <path d="M5 12h14M12 5l7 7-7 7"/>
                    </svg>
                </button>
            </form>

            <p class="link-registro">¿No tienes cuenta? <a href="FormRegistro.php">Regístrate aquí</a></p>

        </div>
    </div>

</div>

</body>
</html>