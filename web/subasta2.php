<?php
session_start();
Include "conexion.php";


if (!isset($_SESSION['uid']) || $_SESSION['uid'] == 1000) {
    header("Location: login.php");
    exit();
}


//recoge datos para crear la subasta

$idobjeto = $_POST['idobjeto'];
$pminimo = $_POST['pminimo'];
$pmaximo = $_POST['pmaximo'];
$uid = $_SESSION['uid'];
$fmasdia = date('Y-m-d H:i:s', strtotime('+1 day'));

if ($pminimo >= $pmaximo) {
    $_SESSION['mensajesubasta']= 'Error en precios introducidos';
   header("Location:inventario.php");
   exit();
}



// Insertar datos en la subasta
$sql = "INSERT INTO subasta (id_vendedor, precio_min, precio_max, id_objeto, id_comprador,tiempo)
VALUES (?, ?, ?, ?, ?, ?)";
$stmt_personaje = $connection->prepare($sql);
$stmt_personaje->execute([$uid, $pminimo, $pmaximo, $idobjeto, 1000,$fmasdia]);

$sql2 = "UPDATE inventario SET idjugador = ? where id = ?  ";
$stmt2_personaje = $connection->prepare($sql2);
$stmt2_personaje->execute([ 1000, $idobjeto]);

header("Location:mercado.php");


?>
