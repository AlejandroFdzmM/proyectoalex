<?php
//destruir sesion
session_start();

session_destroy();
header('Location: index.php');
exit();
?>
