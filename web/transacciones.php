<?php
include "headeradmin.php";
include "conexion.php";

$codigo = $_POST['codigo'];
?>

<div>

    <table>
        <tr>
            <td>idTransacion</td>
            <td>item</td>
            <td>Vendedor</td>
            <td>Comprador</td>
            <td>precio</td>
            <td>Fecha</td>
        </tr>

        <?php
        //  Visualiza compras y ventas del usuario

        $lista = "SELECT t.idtransaccion, e.nombre AS equipovendido, v.nombre AS nombre_vendedor, c.nombre AS nombre_comprador, 
        t.fecha AS fecha_transaccion, t.precio AS precio_pagado FROM transacciones t 
        JOIN  personaje v ON t.id_vendedor = v.id
        JOIN personaje c ON t.id_comprador = c.id 
        JOIN equipo e ON e.id = t.itemid  where t.id_vendedor = ? or t.id_comprador = ?";

        $stmt = $connection->prepare($lista);
        $stmt->execute([$codigo, $codigo]);
        $lista_personas = $stmt->fetchAll();

        foreach ($lista_personas as $row) { ?>

            <tr>
                <td><?php echo $row['idtransaccion']; ?></td>
                <td><?php echo $row['equipovendido']; ?></td>
                <td><?php echo $row['nombre_vendedor']; ?></td>
                <td><?php echo $row['nombre_comprador']; ?></td>
                <td><?php echo $row['precio_pagado']; ?></td>
                <td><?php echo $row['fecha_transaccion']; ?></td>
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
