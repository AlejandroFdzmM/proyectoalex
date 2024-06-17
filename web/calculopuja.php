<?php
session_start();

if (!isset($_SESSION['uid']) || $_SESSION['uid'] == 1000) {
    header("Location: login.php");
    exit();
}

include "conexion.php";

$uid = $_SESSION['uid'];
$puja = (int)$_POST['precio'];
$id = (int)$_POST['id'];
$pmax = (int)$_POST['pmax'];

$mensaje = '';

// Obtener la subasta y validar la puja
$sql1 = "SELECT precio_min, id_comprador, precio_max, id_objeto, id_vendedor FROM subasta WHERE id_subasta = ?";
$stmt1 = $connection->prepare($sql1);
$stmt1->execute([$id]);
$subasta = $stmt1->fetch(PDO::FETCH_ASSOC);





if (!$subasta) {
    header("Location: mercado.php");
    exit();
}

$preciomin = (int)$subasta['precio_min'];
$comprador = (int)$subasta['id_comprador'];
$preciomax = (int)$subasta['precio_max'];
$id_objeto = (int)$subasta['id_objeto'];
$id_vendedor = (int)$subasta['id_vendedor'];

if ($puja < $preciomin){
    header("Location: mercado.php");
    exit();
}

// Obtener el oro del usuario
$sql2 = "SELECT oro FROM personaje WHERE id = ?";
$stmt2 = $connection->prepare($sql2);
$stmt2->execute([$uid]);
$oro = (int)$stmt2->fetchColumn();

if ($oro < $puja) {
    
    header("Location: mercado.php?error=1");
    exit();
}

if ($comprador == 1000) {
    // Primer postor
    $sql3 = "UPDATE subasta SET precio_min = ?, id_comprador = ? WHERE id_subasta = ?";
    $stmt3 = $connection->prepare($sql3);
    $stmt3->execute([$puja, $uid, $id]);

    $sql4 = "UPDATE personaje SET oro = oro - ? WHERE id = ?";
    $stmt4 = $connection->prepare($sql4);
    $stmt4->execute([$puja, $uid]);

    header("Location: mercado.php");
    exit();
}

// Puja subsiguiente
if ($puja < $preciomax) {
    // Devolver oro al anterior postor
    $sql5 = "UPDATE personaje SET oro = oro + ? WHERE id = ?";
    $stmt5 = $connection->prepare($sql5);
    $stmt5->execute([$preciomin, $comprador]);

    // Actualizar la subasta con la nueva puja
    $sql6 = "UPDATE subasta SET precio_min = ?, id_comprador = ? WHERE id_subasta = ?";
    $stmt6 = $connection->prepare($sql6);
    $stmt6->execute([$puja, $uid, $id]);

    // Restar oro del nuevo postor
    $sql7 = "UPDATE personaje SET oro = oro - ? WHERE id = ?";
    $stmt7 = $connection->prepare($sql7);
    $stmt7->execute([$puja, $uid]);
    
    header("Location: mercado.php");
    exit();
}

// Comprobar si la puja alcanza o supera el precio máximo para finalizar la subasta
if ($puja >= $preciomax) {
    // Actualizar inventario
    $sql8 = "UPDATE inventario SET idjugador = ? WHERE id = ?";
    $stmt8 = $connection->prepare($sql8);
    $stmt8->execute([$uid, $id_objeto]);

    // Actualizar el oro del vendedor
    $sql9 = "UPDATE personaje SET oro = oro + ? WHERE id = ?";
    $stmt9 = $connection->prepare($sql9);
    $stmt9->execute([$puja - 3, $id_vendedor]);

    // Insertar en transacciones
    $sql10 = "INSERT INTO transacciones (itemid, id_vendedor, fecha, precio, id_comprador) VALUES (?, ?, ?, ?, ?)";
    $stmt10 = $connection->prepare($sql10);
    $stmt10->execute([$id_objeto, $id_vendedor, date('Y-m-d H:i:s'), $puja - 3, $uid]);

    // Eliminar la subasta
    $sql11 = "DELETE FROM subasta WHERE id_subasta = ?";
    $stmt11 = $connection->prepare($sql11);
    $stmt11->execute([$id]);

    header("Location: mercado.php");
    exit();
}


header("Location:mercado.php");
exit();
?>

