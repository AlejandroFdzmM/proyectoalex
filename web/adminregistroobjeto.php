<?php

include "conexion.php";

$errores = 0;

//Recogida de datos del creacion de nuevos objetos
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $ataque = $_POST['ataquefisico'];
    $defensa = $_POST['defensa'];
    $defensamagica = $_POST['defensamagica'];
    $ataquemagico = $_POST['ataquemagico'];
    $habilidad = $_POST['habilidad'];
    $posicion = $_POST['posicion'];
    $precio = $_POST['precio'];
    $skill = $_POST['skill'];
    
    

    if ($errores == 0) {
        $stmt = $connection->prepare("INSERT INTO equipo (nombre, descripcion, habilidad, ataque_f, defensa_f, ataque_m, defensa_m, posicion, precio, skill) 
                                      VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$nombre, $descripcion, $habilidad, $ataque, $defensa, $ataquemagico, $defensamagica, $posicion, $precio, $skill]);

        header("Location:adminobjetolista.php");
        exit();
    } else {
        header("Location:adminobjetolista.php");
        exit();

    }
}

?>
