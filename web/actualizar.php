<?php
include "headeradmin.php";
include "conexionldap.php"; // Incluye el archivo de conexión LDAP
include "conexion.php";

$codigo = $_POST['codigo'];

// Verificación de entrada
if (empty($codigo)) {
    die("Código de usuario no proporcionado.");
}

// Conexión al servidor LDAP
$ldap_conn = ldap_connect($ldap_server);
if (!$ldap_conn) {
    die("No se pudo conectar al servidor LDAP.");
}

ldap_set_option($ldap_conn, LDAP_OPT_PROTOCOL_VERSION, 3); // Establece la versión del protocolo LDAP

// Autenticación en el servidor LDAP
if (!ldap_bind($ldap_conn, $ldap_user, $ldap_pass)) {
    die("No se pudo autenticar en el servidor LDAP.");
}

// Base de búsqueda y filtro
$search_base = "ou=users,dc=proyectoalex,dc=com"; // Cambia esto por la base de búsqueda de tu LDAP
$search_filter = "(uid={$codigo})"; // Filtro para buscar el usuario por uid


// Búsqueda en el directorio LDAP
$result = ldap_search($ldap_conn, $search_base, $search_filter);

if (!$result) {
    die("Error en la búsqueda LDAP.");
}

$entries = ldap_get_entries($ldap_conn, $result);
$sql = "SELECT * from personaje where nombre = ? ";
$stmt = $connection->prepare($sql);
$stmt->execute([$codigo]);
$personaje = $stmt->fetch();


if ($entries["count"] == 0) {
    die("Usuario no encontrado.");
} elseif ($entries["count"] > 1) {
    die("Se encontraron múltiples usuarios con el mismo UID.");
} else {
    $entry = $entries[0];
    $nombre = isset($entry['givenname'][0]) ? $entry['givenname'][0] : '';
    $apellidos = isset($entry['sn'][0]) ? $entry['sn'][0] : '';
    $nacimiento = isset($entry['description'][0]) ? $entry['description'][0] : ''; // Description tiene la fecha de nacimiento
    $direccion = isset($entry['postaladdress'][0]) ? $entry['postaladdress'][0] : '';
    $usuario = isset($entry['uid'][0]) ? $entry['uid'][0] : '';
    $contrasena = isset($entry['userpassword'][0]) ? $entry['userpassword'][0] : '';
    $mail = isset($entry['mail'][0]) ? $entry['mail'][0] : '';
    $imagen = isset($entry['jpegphoto'][0]) ? $entry['jpegphoto'][0] : ''; // Imagen de perfil en formato binario
}

ldap_unbind($ldap_conn); // Cierra la conexión LDAP
?>


<form action="adminactualizar2.php" method="POST" enctype="multipart/form-data"> 
    <fieldset>
        <legend>Actualizacion</legend>

        <input type="hidden" name="code" value="<?php echo htmlspecialchars($codigo); ?>">
        <label for="nombre">Nombre</label><br>
        <input type="text" id="nombre" name="nombre" maxlength="20" placeholder="Tu Nombre" required value="<?php echo htmlspecialchars($nombre); ?>"><br>
        <label for="apellidos">Apellidos</label><br>
        <input type="text" name="apellidos" id="apellidos" maxlength="30" placeholder="Tus apellidos" required value="<?php echo htmlspecialchars($apellidos); ?>"><br>
        <label for="direccion">Direccion</label><br>
        <input type="text" id="direccion" name="direccion" maxlength="50" placeholder="Tu direccion" required value="<?php echo htmlspecialchars($direccion); ?>"><br>
        <label for="usuario">Usuario</label><br>
        <input type="text" name="usuario" id="usuario" maxlength="20" placeholder="Nombre usuario" required value="<?php echo htmlspecialchars($usuario); ?>"><br>
        <label for="contrasena">Contraseña</label><br>
        <input type="text" name="contrasena" id="contrasena" maxlength="20" placeholder="Contraseña" required value="<?php echo htmlspecialchars($contrasena); ?>"><br>
        <label for="correo">Correo Electronico</label><br>
        <input type="email" id="correo" name="correo" maxlength="30" placeholder="Tu e-mail" required value="<?php echo htmlspecialchars($mail); ?>"><br>
        <label for="oro">Oro</label><br>
        <input type="number" id="oro" name="oro" required value="<?php echo $personaje['oro']; ?>"><br>
        <label for="ataque">Ataque</label><br>
        <input type="number" id="ataque" name="ataque" required value="<?php echo $personaje['ataque']; ?>"><br>
        <label for="defensa">Defensa</label><br>
        <input type="number" id="defensa" name="defensa"  required value="<?php echo $personaje['defensa']; ?>"><br>
        <label for="ataquemagico">A Magico</label><br>
        <input type="number" id="ataquemagico" name="ataquemagico" required value="<?php echo $personaje['ataquemagico']; ?>"><br>
        <label for="defensamagica">D magica</label><br>
        <input type="number" id="defensamagica" name="defensamagica" required value="<?php echo $personaje['defensamagica']; ?>"><br>
        <label for="perfil">Foto Perfil</label>
        <br>
        <img src="<?php echo $personaje['perfil']; ?>">
        <input type="hidden" name="perfil" value="<?php echo $personaje['perfil'] ?>"><br>
        <input type="file" id="imagen" name="imagen" accept="image/*" ><br><br>
    </fieldset>
    <input type="submit" value="Actualizar"> <input type="reset" value="Resetear">
</form>

<?php
include "footeradmin.php";
?>
