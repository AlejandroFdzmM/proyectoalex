<?php
include "headeradmin.php";
include "conexion.php";
//Muestra una lista de todos los enemigos

?>

<table>
    <tr>
        <td>id</td>
        <td>Nombre</td>
        <td>Ataque</td>
	<td>A, Magico</td>
        <td>Defensa</td>
	<td>D. Magica</td>
        <td>Vida</td>
        <td>Imagen</td>
    </tr>

    <?php
    $lista = "SELECT * FROM enemigos";
    $stmt = $connection->query($lista);

    while ($row = $stmt->fetch()) { ?>
    <tr>
        <td><?php echo $row['id']; ?></td>
        <td><?php echo $row['nombre']; ?></td>
        <td><?php echo $row['ataque']; ?></td>
	<td><?php echo $row['ataquemagico']; ?></td>
        <td><?php echo $row['defensa']; ?></td>
	<td><?php echo $row['defensamagica']; ?></td>
        <td><?php echo $row['Vida']; ?></td>
	<td><img width="100px" src='<?php echo $row['ImagenEnemigo']; ?>'></td>
    </tr>
    <?php } ?>
</table>

<?php
$connection = null;
include "footeradmin.php";
?>
