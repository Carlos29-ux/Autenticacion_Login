<?php
// clases/GestorHash.php
// Clase dedicada a generar y validar hashes de contraseñas
// Se usa en: Registrese.php (generar) y objLoginAdmin.php (validar)

class GestorHash {

    private static $costo = 13;

    // ─── Genera el hash de una contraseña en texto plano ──────────────────
    // Usado en: Registrese.php -> encriptarClave()
    public static function generarHash($contrasenaPlana) {
        $options = ['cost' => self::$costo];
        return password_hash($contrasenaPlana, PASSWORD_BCRYPT, $options);
    }

    // ─── Valida una contraseña contra su hash guardado en BD ──────────────
    // Usado en: objLoginAdmin.php -> autenticar()
    public static function validarHash($contrasenaPlana, $hashGuardado) {
        return password_verify($contrasenaPlana, $hashGuardado);
    }

    // ─── Verifica si el hash necesita ser regenerado ───────────────────────
    // Usado en: objLoginAdmin.php -> autenticar() como mejora de seguridad
    public static function necesitaActualizacion($hashGuardado) {
        $options = ['cost' => self::$costo];
        return password_needs_rehash($hashGuardado, PASSWORD_BCRYPT, $options);
    }

} // GestorHash
?>