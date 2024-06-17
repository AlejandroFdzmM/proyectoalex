<?php
include "conexion.php";
include "conexionldap.php";

// Obtener los datos del formulario
$codigo = $_POST['code'];
$nombre = $_POST['nombre'];
$apellidos = $_POST['apellidos'];
$direccion = $_POST['direccion'];
$nusuario = $_POST['usuario'];
$contrasena = $_POST['contrasena'];
$correo = $_POST['correo'];
$antiguo = $_POST['perfil'];
$oro = $_POST['oro'];
$ataque = $_POST['ataque'];
$defensa = $_POST['defensa'];
$ataquemagico = $_POST['ataquemagico'];
$defensamagica = $_POST['defensamagica'];

$directoriosubida = "imagenes/";
$max_file_size = 5120000;
$extensionesValidas = array("jpg", "png", "gif", "jpeg");

$errores = 0;
$nombreArchivo = "";

if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] !== UPLOAD_ERR_NO_FILE) {
    $nombreArchivo = $_FILES['imagen']['name'];
    $filesize = $_FILES['imagen']['size'];
    $directorioTemp = $_FILES['imagen']['tmp_name'];
    $tipoArchivo = $_FILES['imagen']['type'];
    $arrayArchivo = pathinfo($nombreArchivo);
    $extension = strtolower($arrayArchivo['extension']);

    if (!in_array($extension, $extensionesValidas)) {
        $errores = 1;
    }

    if ($filesize > $max_file_size) {
        $errores = 1;
    }

    if ($errores == 0) {
        $nombreCompleto = $directoriosubida . $nombreArchivo;
        move_uploaded_file($directorioTemp, $nombreCompleto);
    }
}

// Actualizar datos en la base de datos
$personaje_sql = "UPDATE personaje
                  SET oro=?, ataque=?, defensa=?, ataquemagico=?, defensamagica=?, perfil=?
                  WHERE nombre=?";
$update_nombre_sql = "UPDATE personaje SET nombre=? WHERE nombre=?";

try {
    $connection->beginTransaction();

    if ($nombreArchivo != "") {
        $stmt = $connection->prepare($personaje_sql);
        $stmt->execute([$oro, $ataque, $defensa, $ataquemagico, $defensamagica, $nombreCompleto, $codigo]);
    } else {
        $stmt = $connection->prepare($personaje_sql);
        $stmt->execute([$oro, $ataque, $defensa, $ataquemagico, $defensamagica, $antiguo, $codigo]);
    }

    // Actualizar el nombre del personaje si se ha modificado el UID
    if ($codigo !== $nusuario) {
        $stmt = $connection->prepare($update_nombre_sql);
        $stmt->execute([$nusuario, $codigo]);
    }

    $connection->commit();
} catch (Exception $e) {
    $connection->rollBack();
    die("Error en la consulta SQL: " . $e->getMessage());
}

// Conexión al servidor LDAP
$ldap_conn = ldap_connect($ldap_server);
if (!$ldap_conn) {
    die("No se pudo conectar al servidor LDAP.");
}

ldap_set_option($ldap_conn, LDAP_OPT_PROTOCOL_VERSION, 3);

// Autenticación en el servidor LDAP
if (!ldap_bind($ldap_conn, $ldap_user, $ldap_pass)) {
    die("No se pudo autenticar en el servidor LDAP.");
}

// Datos a actualizar en LDAP
$ldap_data = [
    "givenName" => $nombre,
    "sn" => $apellidos,
    "postalAddress" => $direccion,
    "userPassword" => $contrasena,
    "mail" => $correo,
];

// Si se subió una nueva imagen
if ($nombreArchivo != "") {
    $ldap_data["jpegPhoto"] = file_get_contents($nombreCompleto);
}

// Modificar la entrada en LDAP
$dn = "uid={$codigo},ou=users,dc=proyectoalex,dc=com";

if (!ldap_modify($ldap_conn, $dn, $ldap_data)) {
    die("Error al actualizar el usuario en el LDAP: " . ldap_error($ldap_conn));
}

// Si se ha cambiado el UID, también se debe renombrar la entrada en LDAP
if ($codigo !== $nusuario) {
    $new_dn = "uid={$nusuario},ou=users,dc=proyectoalex,dc=com";
    if (!ldap_rename($ldap_conn, $dn, "uid={$nusuario}", "ou=users,dc=proyectoalex,dc=com", true)) {
        die("Error al renombrar el DN en LDAP: " . ldap_error($ldap_conn));
    }
}

ldap_unbind($ldap_conn);

header("location:admin.php");
$connection = null;
?>

