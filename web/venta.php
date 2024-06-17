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

// Check if the item exists and belongs to the current user
$sql = "SELECT idequipo FROM inventario WHERE idjugador = ? AND id = ?";
$stmt = $connection->prepare($sql);
$stmt->execute([$uid, $objeto]);
$item = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$item) {
    header("Location: inventario.php");
    exit();
}

$idobjeto = $item['idequipo'];

// Obtine el precio del item
$sql2 = "SELECT precio FROM equipo WHERE id = ?";
$stmt2 = $connection->prepare($sql2);
$stmt2->execute([$idobjeto]);
$equipo = $stmt2->fetch(PDO::FETCH_ASSOC);

if (!$equipo) {
    echo "Error: Equipo not found.";
    exit();
}

$precio = $equipo['precio'];

// DA el oro al usuario
$venta = "UPDATE personaje SET oro = oro + ? WHERE id = ?";
$stmt3 = $connection->prepare($venta);
$stmt3->execute([$precio, $uid]);

// Elimina el objeto del inventario
$borrado = "DELETE FROM inventario WHERE idjugador = ? AND id = ?";
$stmt4 = $connection->prepare($borrado);
$stmt4->execute([$uid, $objeto]);

header("Location: inventario.php");
exit();

$connection = null;
?>

