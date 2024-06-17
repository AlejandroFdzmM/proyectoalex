<?php
session_start();

if (!isset($_SESSION['uid']) || $_SESSION['uid'] == 1000) {
    header("Location: login.php");
    exit();
}

include "conexion.php";

$uid = $_SESSION['uid'];
$id = $_POST['id'];

// Obtener la subasta y validar la compra
$sql1 = "SELECT subasta.id_subasta, subasta.precio_max, subasta.id_comprador, subasta.id_objeto, subasta.id_vendedor, equipo.id AS idequipon
         FROM subasta
         JOIN inventario ON subasta.id_objeto = inventario.id
         JOIN equipo ON inventario.idequipo = equipo.id
         WHERE id_subasta = ?";
$stmt1 = $connection->prepare($sql1);
$stmt1->execute([$id]);
$subasta = $stmt1->fetch(PDO::FETCH_ASSOC);

if (!$subasta) {
    header("Location: mercado.php");
    exit();
}

$preciomax = $subasta['precio_max'];
$comprador = $subasta['id_comprador'];
$id_objeto = $subasta['id_objeto'];
$id_vendedor = $subasta['id_vendedor'];
$idequipon = $subasta['idequipon'];

// Obtener el oro del usuario
$sql2 = "SELECT oro FROM personaje WHERE id = ?";
$stmt2 = $connection->prepare($sql2);
$stmt2->execute([$uid]);
$oro = $stmt2->fetchColumn();

if ($oro < $preciomax) {
    header("Location: personaje.php");
    exit();
}

try {
    $connection->beginTransaction();

    // Actualizar inventario
    $sql3 = "UPDATE inventario SET idjugador = ? WHERE id = ?";
    $stmt3 = $connection->prepare($sql3);
    $stmt3->execute([$uid, $id_objeto]);

    // Actualizar el oro del vendedor
    $sql4 = "UPDATE personaje SET oro = oro + ? WHERE id = ?";
    $stmt4 = $connection->prepare($sql4);
    $stmt4->execute([$preciomax - 3, $id_vendedor]);

    // Actualizar el oro del comprador
    $sql5 = "UPDATE personaje SET oro = oro - ? WHERE id = ?";
    $stmt5 = $connection->prepare($sql5);
    $stmt5->execute([$preciomax, $uid]);

    // Insertar en transacciones
    $sql6 = "INSERT INTO transacciones (itemid, id_vendedor, fecha, precio, id_comprador) VALUES (?, ?, ?, ?, ?)";
    $stmt6 = $connection->prepare($sql6);
    $stmt6->execute([$idequipon, $id_vendedor, date('Y-m-d H:i:s'), $preciomax - 3, $uid]);

    // Eliminar la subasta
    $sql7 = "DELETE FROM subasta WHERE id_subasta = ?";
    $stmt7 = $connection->prepare($sql7);
    $stmt7->execute([$id]);

    $connection->commit();
} catch (Exception $e) {
    $connection->rollBack();
    // Manejo de errores, redirigir o mostrar mensaje de error
    header("Location: mercado.php?error=1");
    exit();
}

header("Location: mercado.php?success=1");
exit();
?>

