<?php

include "conexion.php";
//Registro de un nuevo enemigo
$errores = 0;
$nombreCompleto = "";
//recogidad de datos del formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $ataque = $_POST['ataque'];
    $defensa = $_POST['defensa'];
    $vida = $_POST['vida'];
    $defensamagica = $_POST['defensamagica'];
    $ataquemagico = $_POST['ataquemagico'];

    $directoriosubida = "imagenes/";
    $max_file_size = 5120000; // Tamaño máximo en bytes
    $extensionesValidas = array("jpg", "png", "gif");

    if (isset($_FILES['perfil'])) {
        $nombreArchivo = $_FILES['perfil']['name'];
        $filesize = $_FILES['perfil']['size'];
        $directorioTemp = $_FILES['perfil']['tmp_name'];
        $tipoArchivo = $_FILES['perfil']['type'];
        $arrayArchivo = pathinfo($nombreArchivo);
        $extension = strtolower($arrayArchivo['extension']);

        if (!in_array($extension, $extensionesValidas)) {
            echo "La extensión no es válida";
            $errores = 1;
        }

        if ($filesize > $max_file_size) {
            echo "La imagen debe tener un tamaño menor";
            $errores = 1;
        }

        if ($errores == 0) {
            $nombreCompleto = $directoriosubida . $nombreArchivo;
            move_uploaded_file($directorioTemp, $nombreCompleto);
        }
    }

    if ($errores == 0) {
        $stmt = $connection->prepare("INSERT INTO enemigos (nombre, ataque, defensa, vida, ataquemagico, defensamagica, ImagenEnemigo) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$nombre, $ataque, $defensa, $vida, $ataquemagico, $defensamagica, $nombreCompleto]);

        header("Location:adminlistadoenemigos.php");
        exit();
    } else {
        header("Location:Errorextensionadmin.php");
        exit();
    }

   
}

?>
