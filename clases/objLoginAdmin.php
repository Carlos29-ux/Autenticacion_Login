<?php
// clases/objLoginAdmin.php
// Se incluye GestorHash para validar el hash en el login

final class ValidacionLogin {

    private $id;
    private $usuario;
    private $contrasena;
    private $hashGenerado;
    private $loginExitoso;
    private $ip;
    private $pdo;

    public function __construct($usuario, $contrasena, $ipRemoto, $pdo) {
        $this->usuario    = SanitizarEntrada::limpiarCadena($usuario);
        $this->contrasena = SanitizarEntrada::limpiarCadena($contrasena);
        $this->ip         = $ipRemoto;
        $this->pdo        = $pdo;
    }

    // ─── Busca el usuario en la BD ────────────────────────────────────────
    public function logger() {
        $usuariologueado = $this->pdo->log($this->usuario);

        if ($usuariologueado) {
            $this->id           = $usuariologueado->id;
            $this->hashGenerado = $usuariologueado->HashMagic;
            return true;
        } else {
            return false;
        }
    }

    // ─── Valida la contraseña usando GestorHash ───────────────────────────
    public function autenticar() {
        if (GestorHash::validarHash($this->contrasena, $this->hashGenerado)) {
            $this->loginExitoso = 1;

            // Verifica si el hash necesita actualizarse (mejora de seguridad)
            if (GestorHash::necesitaActualizacion($this->hashGenerado)) {
                $nuevoHash = GestorHash::generarHash($this->contrasena);
                $this->pdo->updateSeguro(
                    "usuarios",
                    ["HashMagic" => $nuevoHash],
                    ["id" => $this->id]
                );
            }
        } else {
            $this->loginExitoso = 0;
        }
    }

    // ─── Registra el intento de login en tabla "accesos" ─────────────────
    // ANTES escribía en "intentos_login", AHORA escribe en "accesos"
    public function registrarAcceso() {
        $data = array(
            "Usuario"  => $this->usuario,
            "ipRemoto" => $this->ip,
            "exitoso"  => $this->loginExitoso    // 1 = exitoso, 0 = fallido
        );
        $this->pdo->insertSeguro("accesos", $data);
    }

    // ─── Getters ──────────────────────────────────────────────────────────
    public function getIntentoLogin()  { return $this->loginExitoso; }
    public function getUsuario()       { return $this->usuario; }
    public function getContrasena()    { return $this->contrasena; }
    public function getHashGenerado()  { return $this->hashGenerado; }

} // fin ValidacionLogin
?>