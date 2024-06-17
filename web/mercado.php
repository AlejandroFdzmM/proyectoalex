<?php
session_start();

if (!isset($_SESSION['uid']) || $_SESSION['uid'] == 1000) {
    header("Location: login.php");
    exit();
}

include "header.php";
include "conexion.php";

$uid = $_SESSION['uid'];



// Obtener los parámetros de búsqueda y ordenación de la solicitud
$search = isset($_GET['search']) ? $_GET['search'] : '';
$orden = isset($_GET['orden']) ? $_GET['orden'] : 'precio_min';

// Validar el campo de ordenación
$valid_orden = ['nombre', 'precio_min', 'precio_max'];
if (!in_array($orden, $valid_orden)) {
    $orden = 'precio_min';
}

$sql = "SELECT subasta.id_subasta, equipo.nombre AS nombre_equipo, personaje.nombre AS nombre_vendedor,
        subasta.precio_min, subasta.precio_max, subasta.tiempo AS tiempo, subasta.id_comprador AS idcomprador,
        subasta.id_objeto AS idobjeto, personaje.id AS idvendedor, equipo.id AS idequipon
        FROM subasta
        JOIN inventario ON subasta.id_objeto = inventario.id
        JOIN equipo ON inventario.idequipo = equipo.id
        JOIN personaje ON subasta.id_vendedor = personaje.id
        WHERE equipo.nombre LIKE ?
        ORDER BY $orden";

$stmt = $connection->prepare($sql);
$stmt->execute(['%' . $search . '%']);
$dia = date('Y-m-d H:i:s');

// Procesar subastas finalizadas
while ($objeto = $stmt->fetch(PDO::FETCH_ASSOC)) {
    if ($objeto['tiempo'] <= $dia) {
        if ($objeto['idcomprador'] == 1000) {
            $sql1 = "UPDATE inventario SET idjugador = ? WHERE id = ?";
            $stmt1 = $connection->prepare($sql1);
            $stmt1->execute([$objeto['idvendedor'], $objeto['idobjeto']]);

            $sql2 = "DELETE FROM subasta WHERE id_subasta = ?";
            $stmt2 = $connection->prepare($sql2);
            $stmt2->execute([$objeto['id_subasta']]);
        } else {
            $sql2 = "UPDATE inventario SET idjugador = ? WHERE id = ?";
            $stmt2 = $connection->prepare($sql2);
            $stmt2->execute([$objeto['idcomprador'], $objeto['idobjeto']]);

            $sql3 = "UPDATE personaje SET oro = oro + ? WHERE id = ?";
            $stmt3 = $connection->prepare($sql3);
            $stmt3->execute([$objeto['precio_min'] - 3, $objeto['idvendedor']]);

            $sql4 = "INSERT INTO transacciones (itemid, id_vendedor, fecha, precio, id_comprador) VALUES (?, ?, ?, ?, ?)";
            $stmt4 = $connection->prepare($sql4);
            $stmt4->execute([$objeto['idequipon'], $objeto['idvendedor'], $dia, $objeto['precio_min'] - 3, $objeto['idcomprador']]);

            $sql5 = "DELETE FROM subasta WHERE id_subasta = ?";
            $stmt5 = $connection->prepare($sql5);
            $stmt5->execute([$objeto['id_subasta']]);
        }
    }
}

//lista las subastas actuales
?>

<div>


    <h1>Tabla objetos</h1>
    <form method="GET" action="">
        <input type="text" name="search" placeholder="Buscar objeto" value="<?php echo htmlspecialchars($search); ?>">
        <select name="orden">
            <option value="equipo.nombre" <?php if ($orden == 'equipo.nombre') echo 'selected'; ?>>Nombre</option>
            <option value="subasta.precio_min" <?php if ($orden == 'subasta.precio_min') echo 'selected'; ?>>Precio Mínimo</option>
            <option value="subasta.precio_max" <?php if ($orden == 'subasta.precio_max') echo 'selected'; ?>>Precio Máximo</option>
        </select>
        <button type="submit">Buscar y Ordenar</button>
    </form>
    </form>
    <br>
    <table>
        <tr>
            <th>ID</th>
            <th>Objeto</th>
            <th>Vendedor</th>
            <th>Precio Mínimo</th>
            <th>Pujar</th>
            <th>Precio Máximo</th>
            <th>Compra</th>
            <th>Tiempo</th>
        </tr>
        <?php
        $stmt->execute(['%' . $search . '%']);
        while ($objeto = $stmt->fetch(PDO::FETCH_ASSOC)) { ?>
            <tr>
                <td><?php echo htmlspecialchars($objeto['id_subasta']); ?></td>
                <td><?php echo htmlspecialchars($objeto['nombre_equipo']); ?></td>
                <td><?php echo htmlspecialchars($objeto['nombre_vendedor']); ?></td>
                <td><?php echo htmlspecialchars($objeto['precio_min']); ?></td>
                <td>
                    <form method="post" action="calculopuja.php" enctype="multipart/form-data">
                        <input type="number" name="precio" min="<?php echo $objeto['precio_min'] + 1; ?>">
                        <input type="hidden" name="id" value="<?php echo $objeto['id_subasta']; ?>">
                        <input type="hidden" name="pmax" value="<?php echo htmlspecialchars($objeto['precio_max']); ?>">
                        <input type="submit" value="Pujar">
                    </form>
                </td>
                <td><?php echo htmlspecialchars($objeto['precio_max']); ?></td>
                <td>
                    <form method="post" action="calculocompra.php" enctype="multipart/form-data">
                        <input type="hidden" name="id" value="<?php echo $objeto['id_subasta']; ?>">
                        <input type="submit" value="Comprar">
                    </form>
                </td>
                <td><?php echo htmlspecialchars($objeto['tiempo']); ?></td>
            </tr>
        <?php } ?>
    </table>
</div>

<?php
include "footer.php";
?>
