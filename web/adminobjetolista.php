<?php
include "headeradmin.php";
include "conexion.php";

//muestra listado de todos los objetos creados
?>

<table>
    <tr>
        <td>id</td>
        <td>Nombre</td>
        <td>Descripcion</td>
        <td>Habilidad</td>
        <td>Ataque</td>
        <td>A. Magico</td>
        <td>Defensa</td>
        <td>D. Magica</td>
        <td>Posicion</td>
        <td>Skill</td>
        <td>Precio</td>
    </tr>

    <?php
    $lista = "SELECT * FROM equipo";
    $stmt = $connection->query($lista);

    while ($row = $stmt->fetch()) { ?>
    <tr>
        <td><?php echo $row['id']; ?></td>
        <td><?php echo $row['nombre']; ?></td>
        <td><?php echo $row['descripcion']; ?></td>
        <td><?php echo $row['habilidad']; ?></td>
        <td><?php echo $row['ataque_f']; ?></td>
        <td><?php echo $row['ataque_m']; ?></td>
        <td><?php echo $row['defensa_f']; ?></td>
        <td><?php echo $row['defensa_m']; ?></td>
        <td><?php echo $row['posicion']; ?></td>
        <td><?php echo $row['skill']; ?></td>
        <td><?php echo $row['precio']; ?></td>
        
    </tr>
    <?php } ?>
</table>



<?php
$connection = null;
include "footeradmin.php";
?>
