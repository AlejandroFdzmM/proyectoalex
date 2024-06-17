<?php
session_start();

if (!isset($_SESSION['uid']) || $_SESSION['uid'] == 1000) {
    header("Location: login.php");
    exit();
}

include "conexion.php";

$uid = $_SESSION['uid'];
$objeto = isset($_GET['codigo']) ? $_GET['codigo'] : '';

if (empty($objeto)) {
    header("Location: inventario.php");
    exit();
}

// Comprueba los item equipados
$sql = "SELECT idequipo FROM inventario WHERE idjugador = ? AND id = ?";
$stmt = $connection->prepare($sql);
$stmt->execute([$uid, $objeto]);
$item = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$item) {
    header("Location: inventario.php");
    exit();
}

$idobjeto = $item['idequipo'];

// Obtiene posicion del item
$sql2 = "SELECT posicion FROM equipo WHERE id = ?";
$stmt2 = $connection->prepare($sql2);
$stmt2->execute([$idobjeto]);
$equipo = $stmt2->fetch(PDO::FETCH_ASSOC);

if (!$equipo) {
    echo "Error: Equipo not found.";
    exit();
}

$posicion = $equipo['posicion'];

// Pone desequipados todos los objetos con la posicion del objeto seleccionado
$cambio = "UPDATE inventario 
INNER JOIN equipo ON inventario.idequipo = equipo.id
SET inventario.equipado = 0 
WHERE inventario.idjugador = ? AND equipo.posicion = ?";
$stmt3 = $connection->prepare($cambio);
$stmt3->execute([$uid, $posicion]);

// Pone al objeto equipado con valor a 1
$equipo = "UPDATE inventario SET equipado = 1 WHERE idjugador = ? AND id = ?";
$stmt4 = $connection->prepare($equipo);
$stmt4->execute([$uid, $objeto]);

header("Location: inventario.php");
exit();

$connection = null;
?>

