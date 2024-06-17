<?php
include "conexion.php";
include "conexionldap.php";

// Obtener el UID del usuario a borrar
$codigo = $_POST['codigo'];

if (empty($codigo)) {
    die(header("Location:admin.php"));
}

// Conexión al servidor LDAP
$ldap_conn = ldap_connect($ldap_server);
if (!$ldap_conn) {
    die(header("Location:admin.php"));
}

ldap_set_option($ldap_conn, LDAP_OPT_PROTOCOL_VERSION, 3);

// Autenticación en el servidor LDAP
if (!ldap_bind($ldap_conn, $ldap_user, $ldap_pass)) {
    die(header("Location:admin.php"));
}

// Buscar el DN del usuario en LDAP
$search_base = "ou=users,dc=proyectoalex,dc=com";
$search_filter = "(uidnumber={$codigo})";
$result = ldap_search($ldap_conn, $search_base, $search_filter);

if (!$result) {
    die(header("Location:admin.php"));
}

$entries = ldap_get_entries($ldap_conn, $result);
if ($entries["count"] == 0) {
    die(header("Location:admin.php"));
} elseif ($entries["count"] > 1) {
    die(header("Location:admin.php"));
} else {
    $dn = $entries[0]['dn'];
}

// Eliminar el usuario de LDAP
if (!ldap_delete($ldap_conn, $dn)) {
    die(header("Location:admin.php"));
}

ldap_unbind($ldap_conn);

// Eliminar el usuario de la base de datos
try {
    $sql1 = "DELETE FROM inventario WHERE idjugador=?";
    $stmt1 = $connection->prepare($sql1);
    $stmt1->execute([$codigo]);

    $sql2 = "DELETE FROM transacciones WHERE id_vendedor=?";
    $stmt2 = $connection->prepare($sql2);
    $stmt2->execute([$codigo]);

    $sql2 = "DELETE FROM transacciones WHERE id_comprador=?";
    $stmt2 = $connection->prepare($sql2);
    $stmt2->execute([$codigo]);

    $sql3 = "DELETE FROM subasta WHERE id_comprador=?";
    $stmt3 = $connection->prepare($sql3);
    $stmt3->execute([$codigo]);

    $sql4 = "DELETE FROM subasta WHERE id_vendedor=?";
    $stmt4 = $connection->prepare($sql4);
    $stmt4->execute([$codigo]);
    

    $sql = "DELETE FROM personaje WHERE id=?";
    $stmt = $connection->prepare($sql);
    $stmt->execute([$codigo]);

    if ($stmt->rowCount() == 0) {
        die(header("Location:admin.php"));
    }

    header("location:admin.php");
} catch (Exception $e) {
    die(header("Location:admin.php"));
}


$connection = null;
?>

