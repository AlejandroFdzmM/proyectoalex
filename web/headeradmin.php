<?php
session_start(); // Inicia la sesión al principio del archivo

if (!isset($_SESSION['uid'])) {
    // Si no está autenticado como admin, redirige a la página de login
    header("Location:login.php");
    exit();

}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="estilos/estilos.css">
    <title>RPG</title>
</head>

<body>

    <div class="menu">

    <a href="admin.php">Inicio</a>
    <a href="adminlistado.php">Listado</a>
    <a href="adminenemigos.php">Crear enemigos</a>
    <a href="adminlistadoenemigos.php">Listado enemigos</a>
    <a href="adminobjetos.php">Crear objeto</a>
    <a href="adminobjetolista.php">Listar objetos</a>
    <a href="borrarsesion.php">Log out</a>

    </div>

    <main class="admin">

