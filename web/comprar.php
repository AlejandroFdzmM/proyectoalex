<?php

session_start();

if (!isset($_SESSION['uid']) || $_SESSION['uid'] == 1000) {
    header("Location: login.php");
    exit();
}

include "conexion.php";

$stat = $_POST['stat'];
$uid = $_SESSION['uid'];

$sql = "SELECT * FROM personaje WHERE id = ?";
$stmt = $connection->prepare($sql);
$stmt->execute([$uid]);
$result = $stmt->fetch();

//Tabla que recoge los valores de aumento de estadisticas y aumenta el coste de la subida

if (!$result) {
    exit("No se encontró ningún personaje con el ID proporcionado.");
}

switch ($stat) {
    case "ata":
        if ($result['oro'] >= $result['aumentoataf']) {
            $sql = "UPDATE personaje SET ataque = ataque + 1, oro = oro - aumentoataf, aumentoataf = aumentoataf + aumentoataf * 0.5 WHERE id = ?";
            $stmt = $connection->prepare($sql);
            $stmt->execute([$uid]);
        } else {
            header("Location: personaje.php");
        }
        break;

    case "def":
        if ($result['oro'] >= $result['aumentodeff']) {
            $sql = "UPDATE personaje SET defensa = defensa + 1, oro = oro - aumentodeff, aumentodeff = aumentodeff + aumentodeff * 0.5 WHERE id = ?";
            $stmt = $connection->prepare($sql);
            $stmt->execute([$uid]);
        } else {
            header("Location: personaje.php");
        }
        break;

    case "atam":
        if ($result['oro'] >= $result['aumentoam']) {
            $sql = "UPDATE personaje SET ataquemagico = ataquemagico + 1, oro = oro - aumentoam, aumentoam = aumentoam + aumentoam * 0.5 WHERE id = ?";
            $stmt = $connection->prepare($sql);
            $stmt->execute([$uid]);
        } else {
            header("Location: personaje.php");
        }
        break;

    case "defm":
        if ($result['oro'] >= $result['aumentodm']) {
            $sql = "UPDATE personaje SET defensamagica = defensamagica + 1, oro = oro - aumentodm, aumentodm = aumentodm + aumentodm * 0.5 WHERE id = ?";
            $stmt = $connection->prepare($sql);
            $stmt->execute([$uid]);
        } else {
            header("Location: personaje.php");
        }
        break;

    default:
        exit("Estadística no válida.");
}

header("Location: personaje.php");

?>

