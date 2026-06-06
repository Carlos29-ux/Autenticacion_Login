<?php
// verificar_duplicado.php
// Recibe via GET: campo=correo|usuario  y  valor=el_valor
// Responde JSON: {"duplicado": true/false}

include("clases/mysql.inc.php");
include("clases/SanitizarEntrada.php");

header('Content-Type: application/json');

$campo = isset($_GET['campo']) ? $_GET['campo'] : '';
$valor = isset($_GET['valor']) ? SanitizarEntrada::limpiarCadena($_GET['valor']) : '';

if(empty($campo) || empty($valor)){
    echo json_encode(['duplicado' => false]);
    exit;
}

$pdo  = new mod_db();
$conn = $pdo->getConexion();

// Solo permitimos consultar estos dos campos por seguridad
$mapa = [
    'correo'  => 'Correo',
    'usuario' => 'Usuario'
];

if(!isset($mapa[$campo])){
    echo json_encode(['duplicado' => false]);
    exit;
}

$columna = $mapa[$campo];
$sql     = "SELECT COUNT(*) as total FROM usuarios WHERE $columna = :valor";
$stmt    = $conn->prepare($sql);
$stmt->bindParam(':valor', $valor, PDO::PARAM_STR);
$stmt->execute();
$fila = $stmt->fetch(PDO::FETCH_OBJ);

echo json_encode(['duplicado' => ($fila->total > 0)]);