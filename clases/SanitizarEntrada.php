<?php
class SanitizarEntrada {

    // ─── Método 1 ──────────────────────────────────────────────────────────
    // Nombre y Apellido: elimina etiquetas HTML, espacios y caracteres peligrosos
    // pero respeta tildes y apóstrofes válidos
    // Usado en: Registrese.php (campos Nombre y Apellido)
    public static function limpiarCadena($cadena) {
        $cadena = trim($cadena);
        $cadena = strip_tags($cadena);
        $cadena = htmlspecialchars($cadena, ENT_QUOTES, 'UTF-8');
        return $cadena;
    }

    // ─── Método 2 ──────────────────────────────────────────────────────────
    // Usuario: solo permite letras, números y guiones bajos, valida longitud
    // Usado en: Registrese.php (campo Usuario)
    public static function sanitizarUsuario($usuario) {
        $usuario = trim($usuario);
        $usuario = strip_tags($usuario);
        $usuario = htmlspecialchars($usuario, ENT_QUOTES, 'UTF-8');

        if (preg_match('/^[a-zA-Z0-9_]{4,}$/', $usuario)) {
            return $usuario;
        }
        return null;
    }

    // ─── Método 3 ──────────────────────────────────────────────────────────
    // Correo: sanitiza y valida con filter_var tal como indica la rúbrica
    // Usado en: Registrese.php (campo Correo), ProcesarRegistro.php
    public static function sanitizarCorreo($correo) {
        // Primero sanitiza eliminando caracteres no permitidos
        $correo = filter_var(trim($correo), FILTER_SANITIZE_EMAIL);
        // Luego valida que tenga formato válido
        if (filter_var($correo, FILTER_VALIDATE_EMAIL)) {
            return $correo;
        }
        return null;
    }

    // ─── Método 4 ──────────────────────────────────────────────────────────
    // Sexo: valida que sea M, F u Otro tal como indica la rúbrica
    // Usado en: Registrese.php (campo Sexo)
    public static function sanitizarSexo($sexo) {
        $sexo = trim(strtoupper($sexo));
        $valoresPermitidos = ['M', 'F', 'OTRO'];
        if (in_array($sexo, $valoresPermitidos)) {
            return $sexo;
        }
        return null;
    }

    // ─── Método 5 ──────────────────────────────────────────────────────────
    // Secreto 2FA: valida que sea cadena alfanumérica base32 válida
    // Usado en: Registrese.php (campo secret_2fa antes de guardar)
    public static function sanitizarSecreto2FA($secret) {
        $secret = trim($secret);
        // Verifica que sea una cadena base32 válida (A-Z y 2-7)
        if (preg_match('/^[A-Z2-7=]+$/', $secret)) {
            return $secret;
        }
        return null;
    }

} // SanitizarEntrada
?>