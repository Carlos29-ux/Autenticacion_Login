<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/Gestorhash.php';

use Sonata\GoogleAuthenticator\GoogleAuthenticator;

class RegistroUsuario {

    private $id;
    private $Nombre;
    private $Apellido;
    private $Usuario;
    private $Correo;
    private $Sexo;
    private $contrasena;
    private $hastGenerado;
    private $pdo;
    private $tabla;
    private $FechaSistema;

    // ─── Constructor: solo inicializa variables base ───────────────────────
    public function __construct($datos, $pdo, &$arrMensaje) {
        $this->pdo          = $pdo;
        $this->tabla        = "usuarios";
        $this->FechaSistema = date("Y-m-d H:i:s");
        $this->cargarDatos($datos, $arrMensaje);
    }

    // ─── Carga y sanitiza cada campo usando SanitizarEntrada ──────────────
    private function cargarDatos($datos, &$arrMensaje) {

        // Nombre — usa limpiarCadena()
        if (isset($datos["nombre"])) {
            $this->Nombre = SanitizarEntrada::limpiarCadena($datos["nombre"]);
        } else {
            $arrMensaje[1] = "No trajo datos la Columna Nombre";
        }

        // Apellido — usa limpiarCadena()
        if (isset($datos["apellido"])) {
            $this->Apellido = SanitizarEntrada::limpiarCadena($datos["apellido"]);
        } else {
            $arrMensaje[2] = "No trajo datos la Columna Apellido";
        }

        // Usuario — usa el correo completo
        $usuarioFuente = isset($datos["correo"]) ? $datos["correo"] : (isset($datos["usuario"]) ? $datos["usuario"] : null);
        if ($usuarioFuente !== null) {
            $usuarioLimpio = SanitizarEntrada::sanitizarCorreo($usuarioFuente);
            if ($usuarioLimpio) {
                $this->Usuario = $usuarioLimpio;
            } else {
                $arrMensaje[3] = "El correo ingresado no es válido";
            }
        } else {
            $arrMensaje[3] = "No trajo datos la Columna Usuario";
        }

        // Correo — usa sanitizarCorreo()
        if (isset($datos["correo"])) {
            $correoLimpio = SanitizarEntrada::sanitizarCorreo($datos["correo"]);
            if ($correoLimpio) {
                $this->Correo = $correoLimpio;
            } else {
                $arrMensaje[4] = "El correo ingresado no es válido";
            }
        } else {
            $arrMensaje[4] = "No trajo datos la Columna Correo";
        }

        // Contraseña — valida longitud antes de hashear
        if (isset($datos["clave"])) {
            $clave = trim($datos["clave"]);
            if (strlen($clave) >= 8) {
                $this->contrasena = $clave;
            } else {
                $arrMensaje[5] = "La contraseña debe tener mínimo 8 caracteres";
            }
        } else {
            $arrMensaje[5] = "No trajo datos la Columna Clave";
        }

        // Sexo — usa sanitizarSexo()
        if (isset($datos["sexo"])) {
            $sexoLimpio = SanitizarEntrada::sanitizarSexo($datos["sexo"]);
            if ($sexoLimpio) {
                $this->Sexo = $sexoLimpio;
            } else {
                $arrMensaje[6] = "El sexo ingresado no es válido";
            }
        } else {
            $arrMensaje[6] = "No trajo datos la Columna Sexo";
        }
    }

    // ─── Encripta la contraseña usando GestorHash ─────────────────────────
    // ✅ Antes usaba password_hash() directamente, ahora usa GestorHash
    private function encriptarClave() {
        $this->hastGenerado = GestorHash::generarHash($this->contrasena);
    }

    // ─── Prepara el arreglo de datos para insertar ────────────────────────
    private function prepararDatos() {
        return array(
            "Nombre"       => $this->Nombre,
            "Apellido"     => $this->Apellido,
            "Usuario"      => $this->Usuario,
            "Correo"       => $this->Correo,
            "HashMagic"    => $this->hastGenerado,
            "FechaSistema" => $this->FechaSistema
        );
    }

    // ─── Solo inserta el registro en la BD ────────────────────────────────
    private function insertarEnBD($data) {
        $this->pdo->insertSeguro($this->tabla, $data);
        $this->id = $this->pdo->insert_id();
    }

    // ─── Coordina el guardado completo del usuario ────────────────────────
    public function Guardar_RegistroUsuario() {
        $this->encriptarClave();
        $data = $this->prepararDatos();
        $this->insertarEnBD($data);
    }

    // ─── Guarda el secreto 2FA validado ───────────────────────────────────
    public function GuardarMySecreto($secreto) {
        $secretoLimpio = SanitizarEntrada::sanitizarSecreto2FA($secreto);
        if (!$secretoLimpio) return false;

        $datoSecreto = array("secret_2fa" => $secretoLimpio);
        $condicion   = array("id" => $this->id);

        if ($this->pdo->updateSeguro($this->tabla, $datoSecreto, $condicion)) {
            return true;
        }
        return false;
    }

    // ─── Getters ──────────────────────────────────────────────────────────
    public function getUsuario() { return $this->Usuario; }
    public function getCorreo()  { return $this->Correo; }
    public function getId()      { return $this->id; }
}
?>