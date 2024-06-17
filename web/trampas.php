<?php
session_start();

if (!isset($_SESSION['uid']) || $_SESSION['uid'] == 1000) {
    header("Location: login.php");
    exit();
}


include "header.php";
include "conexion.php";



?>
<h1> No uses los botones de navegacion <h2>
<br>
<a href=personaje.php>Volver</a>




<?php
include "footer.php";
?>


