<?php
session_start();

// ── CSRF: generar token si no existe en sesión ──────────────────────────────
if (empty($_SESSION['csrf_token_registro'])) {
    $_SESSION['csrf_token_registro'] = bin2hex(random_bytes(32));
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro 2FA</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Outfit:wght@300;400;500;600&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="Estilos/Formulario.css"/>
    <script src="css/jquery-1.9.1.min.js"></script>
</head>
<body>

<div class="shell">

    <!-- IZQUIERDO -->
    <aside class="aside">
        <div class="aside-bg-text">2FA</div>
        <div class="aside-content">
            <div class="chip">SISTEMA ACTIVO</div>
            <h1 class="aside-title">Autenticación<br>de Dos<br>Factores</h1>
            <p class="aside-sigla">2FA</p>
            <p class="aside-desc">
                Al Registrarte, el Sistema genera automáticamente un secreto único que vincula tu cuenta con Google Authenticator.
            </p>
            <div class="steps">
                <div class="step">
                    <div class="step-n">01</div>
                    <div class="step-info"><strong>Completa tus Datos Personales</strong></div>
                </div>
                <div class="step">
                    <div class="step-n">02</div>
                    <div class="step-info"><strong>Tu Contraseña se encripta</strong></div>
                </div>
                <div class="step">
                    <div class="step-n">03</div>
                    <div class="step-info"><strong>Se Genera tu secret_2fa automáticamente</strong></div>
                </div>
                <div class="step">
                    <div class="step-n">04</div>
                    <div class="step-info"><strong>Escaneas el QR con Google Authenticator</strong></div>
                </div>
            </div>
        </div>
        <div class="aside-footer">
            <span>PROTEGIDO CON 2FA</span>
        </div>
    </aside>

    <!-- DERECHO -->
    <main class="main">
        <div class="form-box">

            <div class="form-top">
                <p class="form-eyebrow">NUEVA CUENTA</p>
                <h2 class="form-title">Registro</h2>
            </div>

            <?php if (isset($_SESSION['error'])): ?>
            <div class="msg-error"><?= htmlspecialchars($_SESSION['error']) ?></div>
            <?php unset($_SESSION['error']); endif; ?>

            <form id="frm" method="post" action="ProcesarRegistro.php">

                <!-- ── CSRF: campo oculto con el token ─────────────────────── -->
                <input type="hidden" name="csrf_token_registro" value="<?= $_SESSION['csrf_token_registro'] ?>">

                <div class="row-2">
                    <div class="field">
                        <label for="nombre">Nombre</label>
                        <input type="text" id="nombre" name="nombre" placeholder="Juan" required>
                    </div>
                    <div class="field">
                        <label for="apellido">Apellido</label>
                        <input type="text" id="apellido" name="apellido" placeholder="Pérez" required>
                    </div>
                </div>

                <div class="field">
                    <label for="correo">Correo electrónico</label>
                    <input type="email" id="correo" name="correo" placeholder="juan@ejemplo.com" required>
                    <!-- ✅ Mensaje duplicado correo -->
                    <span id="msg-correo" style="font-size:0.8rem; margin-top:4px; display:block;"></span>
                    <input type="hidden" name="usuario" id="usuario">
                </div>

                <div class="field">
                    <label for="clave">Contraseña</label>
                    <input type="password" id="clave" name="clave" placeholder="Mínimo 8 caracteres" required>
                    <div class="strength-bar">
                        <div class="sb" id="b1"></div>
                        <div class="sb" id="b2"></div>
                        <div class="sb" id="b3"></div>
                        <div class="sb" id="b4"></div>
                    </div>
                    <span class="strength-label" id="slabel"></span>
                </div>

                <div class="field">
                    <label for="clave2">Confirmar Contraseña</label>
                    <input type="password" id="clave2" name="clave2" placeholder="Repite tu contraseña" required>
                    <span id="msg-clave" style="font-size:0.8rem; margin-top:4px; display:block;"></span>
                </div>

                <div class="field">
                    <label for="sexo">Sexo</label>
                    <select id="sexo" name="sexo" required>
                        <option value="" disabled selected>Selecciona...</option>
                        <option value="M">Masculino</option>
                        <option value="F">Femenino</option>
                    </select>
                </div>

                <input type="hidden" name="Accion" value="Guardar">

                <button type="submit" class="btn-reg">
                    <span>Crear cuenta y activar 2FA</span>
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                </button>

            </form>

            <p class="link-bottom">¿Ya tienes cuenta? <a href="login.php">Inicia sesión</a></p>

        </div>
    </main>

</div>

<script>
// ─── Fortaleza de contraseña ───────────────────────────────────────────────
const inp   = document.getElementById('clave');
const bars  = ['b1','b2','b3','b4'].map(id => document.getElementById(id));
const label = document.getElementById('slabel');
const lvls  = [
    {c:'#ef4444', t:'Muy débil'},
    {c:'#f97316', t:'Débil'},
    {c:'#eab308', t:'Aceptable'},
    {c:'#22c55e', t:'Fuerte'}
];

inp.addEventListener('input', function(){
    const v = this.value; let s = 0;
    if(v.length>=8) s++;
    if(/[A-Z]/.test(v)) s++;
    if(/[0-9]/.test(v)) s++;
    if(/[^A-Za-z0-9]/.test(v)) s++;
    bars.forEach((b,i)=>{
        b.style.background = i<s ? lvls[s-1].c : '';
        b.style.opacity    = i<s ? '1' : '0.12';
    });
    label.textContent = s ? lvls[s-1].t : '';
    label.style.color = s ? lvls[s-1].c : '';
});

// ─── Confirmar contraseña en tiempo real ──────────────────────────────────
document.getElementById('clave2').addEventListener('input', function(){
    const c1  = document.getElementById('clave').value;
    const c2  = this.value;
    const msg = document.getElementById('msg-clave');
    if(c2 === ''){
        msg.textContent = '';
    } else if(c1 === c2){
        msg.textContent = '✓ Las contraseñas coinciden';
        msg.style.color = '#22c55e';
        this.classList.remove('invalid');
    } else {
        msg.textContent = '⚠ Las contraseñas no coinciden';
        msg.style.color = '#ef4444';
        this.classList.add('invalid');
    }
});

// ─── Validar correo duplicado al salir del campo (FRONTEND) ────────────
document.getElementById('correo').addEventListener('blur', function(){
    const val = this.value.trim();
    const msg = document.getElementById('msg-correo');
    if(val === '') return;

    $.getJSON('verificar_duplicado.php', {campo: 'correo', valor: val}, function(resp){
        if(resp.duplicado){
            msg.textContent = '⚠ Este correo ya está registrado';
            msg.style.color = '#ef4444';
            document.getElementById('correo').classList.add('invalid');
        } else {
            msg.textContent = '✓ Correo disponible';
            msg.style.color = '#22c55e';
            document.getElementById('correo').classList.remove('invalid');
        }
    });
});

// ─── Validación al enviar ─────────────────────────────────────────────────
$('#frm').on('submit', function(e){
    // El usuario se toma del correo completo
    const correoVal = document.getElementById('correo').value;
    document.getElementById('usuario').value = correoVal;

    let ok = true;

    // Campos vacíos
    ['nombre','apellido','correo','clave','clave2','sexo'].forEach(id => {
        const el = document.getElementById(id);
        if(!el.value.trim()){ el.classList.add('invalid'); ok = false; }
        else el.classList.remove('invalid');
    });

    // Contraseñas coincidentes
    const c1  = document.getElementById('clave').value;
    const c2  = document.getElementById('clave2').value;
    const msg = document.getElementById('msg-clave');
    if(c1 !== c2){
        document.getElementById('clave2').classList.add('invalid');
        msg.textContent = '⚠ Las contraseñas no coinciden';
        msg.style.color = '#ef4444';
        ok = false;
    }

    // ✅ Bloquear si el correo ya está marcado como duplicado
    const msgCorreo = document.getElementById('msg-correo');
    if(msgCorreo.textContent.includes('ya está registrado')){
        ok = false;
    }

    if(!ok) e.preventDefault();
});
</script>
</body>
</html>