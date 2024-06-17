<?php
include "headeradmin.php";
include "conexion.php";
include "conexionldap.php";

// Obtener el valor de búsqueda del formulario

$search_name = isset($_POST['search_name']) ? $_POST['search_name'] : '';

?>
<div>
<!-- Formulario de búsqueda -->
<form method="POST" action="">
    <label for="search_name">Buscar por nombre:</label>
    <input type="text" id="search_name" name="search_name" value="<?php echo htmlspecialchars($search_name); ?>">
    <button type="submit">Buscar</button>
</form>
<br>
<table>
    <tr>
        <td>Nombre</td>
        <td>Apellidos</td>
        <td>Correo electrónico</td>
        <td>Usuario</td>
        <td>Historial</td>
        <td>Transacciones</td>
        <td>Actualizar</td>
        <td>Borrar</td>
    </tr>
    <?php
    // Conexión al servidor LDAP
    $ldap_conn = ldap_connect($ldap_server) or die("No se pudo conectar al servidor LDAP.");
    ldap_set_option($ldap_conn, LDAP_OPT_PROTOCOL_VERSION, 3); // Establece la versión del protocolo LDAP

    // Autenticación en el servidor LDAP
    ldap_bind($ldap_conn, $ldap_user, $ldap_pass) or die("No se pudo autenticar en el servidor LDAP.");

    // Búsqueda en el directorio LDAP
    $search_base = "ou=users,dc=proyectoalex,dc=com"; // Server LDAP
    $search_filter = "(objectClass=inetOrgPerson)"; // Filtro para buscar todos los usuarios

    // Si se proporciona un nombre de búsqueda
    if (!empty($search_name)) {
        $search_filter = "(&(objectClass=inetOrgPerson)(|(givenName=*$search_name*)(sn=*$search_name*)))";
    }

    $result = ldap_search($ldap_conn, $search_base, $search_filter);
    $entries = ldap_get_entries($ldap_conn, $result);

    for ($i = 0; $i < $entries["count"]; $i++) {
        // Check if the entry has uid 1001 or greater
        if ($entries[$i]['uidnumber'][0] >= 1001) {
    ?>
            <tr>
                <td><?php echo $entries[$i]['givenname'][0]; ?></td>
                <td><?php echo $entries[$i]['sn'][0]; ?></td>
                <td><?php echo isset($entries[$i]['mail'][0]) ? $entries[$i]['mail'][0] : 'N/A'; ?></td>
                <td><?php echo $entries[$i]['uid'][0]; ?></td>
                <td>
                    <form action='adminhistorial.php' method='POST'>
                        <input type='hidden' name='codigo' value='<?php echo $entries[$i]['uidnumber'][0]; ?>'>
                        <button type='submit'>Historial</button>
                    </form>
                </td>
                <td>
                    <form action='transacciones.php' method='POST'>
                        <input type='hidden' name='codigo' value='<?php echo $entries[$i]['uidnumber'][0]; ?>'>
                        <button type='submit'>Transacciones</button>
                    </form>
                </td>
                <td>
                    <form action='actualizar.php' method='POST'>
                        <input type='hidden' name='codigo' value='<?php echo $entries[$i]['uid'][0]; ?>'>
                        <button type='submit'>Actualizar</button>
                    </form>
                </td>
                <td>
                    <form action='adminborrar.php' method='POST'>
                        <input type='hidden' name='codigo' value='<?php echo $entries[$i]['uidnumber'][0]; ?>'>
                        <button type='submit'>Borrar</button>
                    </form>
                </td>
            
            </tr>
    <?php
        }
    }

    ldap_unbind($ldap_conn);
    ?>
</table>
</div>
<?php
include "footeradmin.php";
?>
