<?php
include "headeradmin.php";
include "conexion.php";

$codigo = $_POST['codigo'];

// Muestra el historial de combates de un personaje

?>

<div>

    <table>
        <tr>
            <td>id Combate</td>
            <td>Usuario</td>
            <td>Enemigo</td>
            <td>Estado</td>
            <td>Oro ganado</td>
            <td> id objeto </td>
            <td>Fecha</td>
        </tr>

        <?php
        $lista = "SELECT idcombate, personaje.nombre AS nombre, enemigos.nombre AS enemigo, estado, recompensa1, recompensa2, fecha
         FROM historialcombate INNER JOIN personaje ON historialcombate.idpersonaje = personaje.id
         INNER JOIN enemigos ON historialcombate.idenemigo = enemigos.id WHERE idpersonaje = ?";
        
        $stmt = $connection->prepare($lista);
        $stmt->execute([$codigo]);
        $lista_personas = $stmt->fetchAll();

        foreach ($lista_personas as $row) { ?>

            <tr>
                <td><?php echo $row['idcombate']; ?></td>
                <td><?php echo $row['nombre']; ?></td>
                <td><?php echo $row['enemigo']; ?></td>
                <td><?php echo $row['estado']; ?></td>
                <td><?php echo $row['recompensa1']; ?></td>
                <td><?php echo $row['recompensa2']; ?></td>
                <td><?php echo $row['fecha']; ?></td>
            </tr>

        <?php } ?>

    </table>
<?php

?>

    <a href="admin.php">Volver</a>

</div>

<?php
include "footeradmin.php";
?>
