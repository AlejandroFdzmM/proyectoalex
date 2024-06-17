<?php
session_start();
include "header.php";
include "conexion.php";
//Muestra las recompensas obtenidas
$oro = isset($_SESSION['ganado']) ? (int)$_SESSION['ganado'] : 0;
$objeto = isset($_SESSION['objeto']) ? (int)$_SESSION['objeto'] : 0;
$mensaje1 = '';
$mensaje2 = '';

if ($oro > 0){
    $mensaje1 = "Ha conseguido $oro de oro";
}

if ($objeto > 0){
    $sql = "SELECT nombre FROM equipo WHERE id = ?";
    $stmt_sql = $connection->prepare($sql);
    $stmt_sql->execute([$objeto]);
    $nombreObjeto = $stmt_sql->fetchColumn();
    $mensaje2 = "Ha obtenido el objeto $nombreObjeto";
}

unset($_SESSION['objeto']);
unset($_SESSION['ganado']);

?>

<div>
</div>

<div class="enmedio">
    <h1>Victoria</h1>
    <h2>
        <?php echo $mensaje1; ?><br>
        <?php echo $mensaje2; ?>
        
    </h2>
    <a href="personaje.php">Volver</a>
</div>

<div>
</div>

<?php
include "footer.php";
?>



