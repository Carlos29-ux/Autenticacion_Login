<?php
include("../comunes/bloque_Seguridad.php");
$menu08 = " id=\"current\"";
$Usuario = $_SESSION['Usuario'];
$inicial = strtoupper(substr($Usuario, 0, 1));
$meses = ["","Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre"];
$dia = date("j"); $mes = date("n"); $anio = date("Y"); $hora = date("H:i");
$fechaHoy = $dia . " de " . $meses[$mes] . " de " . $anio;
$esNavidad = ($mes == 12);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Control | UTP</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../Estilos/Dashboard.css">
</head>
<body>

<nav class="navbar">
    <div class="navbar-logo">
        <span class="navbar-brand">UTP</span>
    </div>
    <ul class="navbar-nav">
        <li><a href="#" class="active">Panel</a></li>
        <li><a href="#">Académico</a></li>
        <li><a href="#">Menú</a></li>
    </ul>
    <div class="navbar-right">
        <div class="user-badge">
            <div class="user-avatar"><?php echo $inicial; ?></div>
            <span class="user-name"><?php echo strtoupper($Usuario); ?></span>
        </div>
        <a href="../salir.php" class="btn-salir" onclick="return confirm('¿Desea salir de verdad?');">Salir →</a>
    </div>
</nav>

<div class="main-wrap">

    <!-- Bienvenida -->
    <div class="welcome-bar">
        <div class="welcome-info">
            <p class="welcome-sub">PANEL DE CONTROL</p>
            <h1 class="welcome-user"><?php echo strtoupper($Usuario); ?></h1>
            <p class="welcome-date">
                <?php if($esNavidad): ?>
                    🎄 Feliz Navidad y Próspero Año Nuevo — <?php echo $fechaHoy; ?>
                <?php else: ?>
                    Bendiciones en este día &mdash; <?php echo $fechaHoy; ?>
                <?php endif; ?>
            </p>
        </div>
        <div class="welcome-chip"><span class="live-dot"></span> SESIÓN ACTIVA</div>
    </div>

    <?php if(isset($_GET['id_mess'])): ?>
    <div class="alert-msg">⚠ <?php echo htmlspecialchars(Mensajes($_GET['id_mess'])); ?></div>
    <?php endif; ?>

    <!-- Fila 1: 3 módulos -->
    <div class="section-header">
        <span class="section-title">Módulos principales</span>
        <div class="section-line"></div>
    </div>

    <div class="modules-grid" style="margin-bottom:16px;">

        <a href="#" class="mod-card" style="--ca:#3b82f6;">
            <div class="card-img-wrap">
                <img src="../img/icons/programming.png" alt="Módulo 1" class="card-img card-img--icon">
                <span class="card-tag">MÓDULO 01</span>
            </div>
            <div class="card-body">
                <span class="card-title">Etiqueta 1</span>
                <span class="card-cta">Ir al módulo →</span>
            </div>
        </a>

        <a href="#" class="mod-card" style="--ca:#10b981;">
            <div class="card-img-wrap">
                <img src="../img/icons/literature.png" alt="Módulo 2" class="card-img card-img--icon">
                <span class="card-tag">MÓDULO 02</span>
            </div>
            <div class="card-body">
                <span class="card-title">Etiqueta 2</span>
                <span class="card-cta">Ir al módulo →</span>
            </div>
        </a>

        <a href="#" class="mod-card" style="--ca:#f59e0b;">
            <div class="card-img-wrap">
                <img src="../img/icons/creative-writing.png" alt="Módulo 3" class="card-img card-img--icon">
                <span class="card-tag">MÓDULO 03</span>
            </div>
            <div class="card-body">
                <span class="card-title">Etiqueta 3</span>
                <span class="card-cta">Ir al módulo →</span>
            </div>
        </a>

    </div>

    <!-- Fila 2: 4 módulos -->
    <div class="section-header">
        <span class="section-title">Módulos adicionales</span>
        <div class="section-line"></div>
    </div>

    <div class="modules-grid grid-4">

        <a href="#" class="mod-card mod-card--sm" style="--ca:#8b5cf6;">
            <div class="card-img-wrap">
                <img src="../img/icons/drawing.png" alt="Módulo 4" class="card-img card-img--icon">
                <span class="card-tag">MÓDULO 04</span>
            </div>
            <div class="card-body">
                <span class="card-title">Etiqueta 4</span>
                <span class="card-cta">Ir al módulo →</span>
            </div>
        </a>

        <a href="#" class="mod-card mod-card--sm" style="--ca:#ec4899;">
            <div class="card-img-wrap">
                <img src="../img/icons/game-development.png" alt="Módulo 5" class="card-img card-img--icon">
                <span class="card-tag">MÓDULO 05</span>
            </div>
            <div class="card-body">
                <span class="card-title">Etiqueta 5</span>
                <span class="card-cta">Ir al módulo →</span>
            </div>
        </a>

        <a href="#" class="mod-card mod-card--sm" style="--ca:#06b6d4;">
            <div class="card-img-wrap">
                <img src="../img/icons/painting.png" alt="Módulo 6" class="card-img card-img--icon">
                <span class="card-tag">MÓDULO 06</span>
            </div>
            <div class="card-body">
                <span class="card-title">Etiqueta 6</span>
                <span class="card-cta">Ir al módulo →</span>
            </div>
        </a>

        <a href="#" class="mod-card mod-card--sm" style="--ca:#64748b;">
            <div class="card-img-wrap">
                <img src="../img/icons/ui-design.png" alt="Módulo 7" class="card-img card-img--icon">
                <span class="card-tag">MÓDULO 07</span>
            </div>
            <div class="card-body">
                <span class="card-title">Configuración</span>
                <span class="card-cta">Ir al módulo →</span>
            </div>
        </a>

    </div>

    <div class="dash-footer">
        <span>© Universidad Tecnológica de Panamá | Design by for <span style="color:var(--accent)">UTP.</span></span>
        <span>UTP <?php echo $anio; ?> | UTP- Desarrollo de Software VII</span>
    </div>

</div>
</body>
</html>