<?php
session_start();

if (!isset($_SESSION['uid']) || $_SESSION['uid'] == 1000) {
    header("Location: login.php");
    exit();
}

include "header.php";
include "conexion.php";

$uid = $_SESSION['uid'];

//menu para subastar,vender objetos o equiparlos
?>

<div>
<h1>Tabla objetos</h1>
<table>
    <tr>
        <td>Nombre</td>
        <td>Descripcion</td>
        <td>Ataque Fisico</td>
        <td>Ataque Magico</td>
        <td>Defensa Fisica</td>
        <td>Defensa Magica</td>
        <td>Equipar</td>
        <td>Precio</td>
        <td>Venta</td>
        <td>Subastar</td>
    </tr>
    <?php
    $sql = "SELECT inventario.id, equipo.nombre, equipo.descripcion, equipo.ataque_f, equipo.ataque_m, equipo.defensa_f, equipo.defensa_m, equipo.precio
            FROM inventario
            INNER JOIN equipo ON equipo.id = inventario.idequipo
            WHERE inventario.idjugador = ? and inventario.equipado = 0 ";
    $stmt = $connection->prepare($sql);
    $stmt->execute([$uid]);

    while ($objeto = $stmt->fetch(PDO::FETCH_ASSOC)) { ?>
        <tr>
            <td><?php echo htmlspecialchars($objeto['nombre']); ?></td>
            <td><?php echo htmlspecialchars($objeto['descripcion']); ?></td>
            <td><?php echo htmlspecialchars($objeto['ataque_f']); ?></td>
            <td><?php echo htmlspecialchars($objeto['ataque_m']); ?></td>
            <td><?php echo htmlspecialchars($objeto['defensa_f']); ?></td>
            <td><?php echo htmlspecialchars($objeto['defensa_m']); ?></td>
            <td><a href="equipar.php?codigo=<?php echo htmlspecialchars($objeto['id']); ?>"><img src="img/icons8-editar-50.png"></a></td>
            <td><?php echo htmlspecialchars($objeto['precio']); ?></td>
	    <td><a href="venta.php?codigo=<?php echo htmlspecialchars($objeto['id']); ?>"><img src="img/sell.png"></a></td>
	    <td>	<form action="subasta.php" method="POST">
			<input name="id" type="hidden" value="<?php echo $objeto['id']; ?> " >
		<a href="subasta.php"><button type="submit"><img src="img/carrito.png"></button></a>
		</form>
	    </td>
        </tr>
    <?php } ?>
</table>
</div>

<br>
<br>

<div>
<h1>Equipado</h1>
<table>
    <tr>
        <td>Nombre</td>
        <td>Descripcion</td>
        <td>Ataque Fisico</td>
        <td>Ataque Magico</td>
        <td>Defensa Fisica</td>
        <td>Defensa Magica</td>
    </tr>
    <?php
    $sql2 = "SELECT *
             FROM inventario
             INNER JOIN equipo ON inventario.idequipo = equipo.id
             WHERE inventario.idjugador = ? and inventario.equipado > 0";
    $stmt2 = $connection->prepare($sql2);
    $stmt2->execute([$uid]);

    while ($equipado = $stmt2->fetch(PDO::FETCH_ASSOC)) { ?>
        <tr>
            <td><?php echo htmlspecialchars($equipado['nombre']); ?></td>
            <td><?php echo htmlspecialchars($equipado['descripcion']); ?></td>
            <td><?php echo htmlspecialchars($equipado['ataque_f']); ?></td>
            <td><?php echo htmlspecialchars($equipado['ataque_m']); ?></td>
            <td><?php echo htmlspecialchars($equipado['defensa_f']); ?></td>
            <td><?php echo htmlspecialchars($equipado['defensa_m']); ?></td>
        </tr>
    <?php } ?>
</table>
</div>

<?php
$connection = null;
include "footeradmin.php";
?>
