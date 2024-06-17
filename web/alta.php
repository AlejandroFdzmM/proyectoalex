<?php


include "conexion.php";

// Crea un nuevo usuario ldap
function createLDAPUser($username, $password, $email, $nombre, $apellidos, $edad, $direccion) {
    // Prametros conexion server ldap
    $ldapServer = 'ldap://ldap-service'; // ldap name
    $ldapAdminDN = 'cn=admin,dc=proyectoalex,dc=com'; // ldap admin cn
    $ldapAdminPassword = 'secret'; // Contraseña admin ldap
    $baseDN = "ou=users,dc=proyectoalex,dc=com"; // Uo donde buscar al usuario

    // Conectar al servidor ldap
    $ldapConnection = ldap_connect($ldapServer);
    if (!$ldapConnection) {
        die("Could not connect to LDAP server");
    }

    // Poner el protocolo ldap version 3
    ldap_set_option($ldapConnection, LDAP_OPT_PROTOCOL_VERSION, 3);

    // Aplicar credenciales
    $bind = ldap_bind($ldapConnection, $ldapAdminDN, $ldapAdminPassword);
    if (!$bind) {
        die("Could not bind to LDAP server: " . ldap_error($ldapConnection));
    }

    // Tomar el uidNumber mayor
    $highestUidNumber = getHighestUidNumber($ldapConnection, $baseDN);
    $nextUidNumber = $highestUidNumber + 1;

    // Atributos para registro en ldap
    $userAttributes = [
        'cn' => $username,
        'sn' => $apellidos,
        'givenName' => $nombre,
        'userPassword' => $password, // contraseña
        'mail' => $email,
        'description' => "Birthdate: $edad", // Cumpleaños como campo descripcion
        'postalAddress' => $direccion,
        'uid' => $username,
        'uidNumber' => $nextUidNumber,
        'gidNumber' => 1000, // uidnumber
        'homeDirectory' => "/home/$username",
        'objectClass' => ['inetOrgPerson', 'posixAccount']
    ];

    // Añade al nuevo usuario al directorio ldap
    $userDN = "uid=$username,$baseDN"; 
    $addUser = ldap_add($ldapConnection, $userDN, $userAttributes);

    // Comprueba si la creacion fue exitosa
    if (!$addUser) {
        die(header("Location: Usuarioyacreado.php"));
    }

    ldap_close($ldapConnection);
    return $nextUidNumber; // Devuelve el nuevo uidNumber
}

function getHighestUidNumber($ldapConnection, $baseDN) {
    $searchFilter = "(uidNumber=*)";
    $attributes = ["uidNumber"];
    $searchResult = ldap_search($ldapConnection, $baseDN, $searchFilter, $attributes);

    if (!$searchResult) {
        die("Error searching LDAP: " . ldap_error($ldapConnection));
    }

    $entries = ldap_get_entries($ldapConnection, $searchResult);

    if ($entries["count"] == 0) {
        return 1000; // Si el usuario no exister crea el usuario con uidNUmber 1000
    }

    $maxUidNumber = 0;
    for ($i = 0; $i < $entries["count"]; $i++) {
        $uidNumber = (int) $entries[$i]["uidnumber"][0];
        if ($uidNumber > $maxUidNumber) {
            $maxUidNumber = $uidNumber;
        }
    }

    return $maxUidNumber;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $apellidos = $_POST['apellidos'];
    $edad = $_POST['edad'];
    $direccion = $_POST['direccion'];
    $usuario = $_POST['usuario'];
    $contrasena = $_POST['contrasena'];
    $mail = $_POST['correo'];
    $raza = $_POST['raza'];
    $oro = 0;
    

    // Comprueba que el usuario fue creado en la base de datos
    $lista = "SELECT nombre FROM personaje";
    $lista_personas = $connection->query($lista);

    $usuarioExiste = false;
    while ($row = $lista_personas->fetch(PDO::FETCH_ASSOC)) {
        $usuariocreado = $row['nombre'];
        if ($usuariocreado == $usuario) {
            $usuarioExiste = true;
            break;
        }
    }

    if ($usuarioExiste) {
        header("Location: Usuarioyacreado.php");
        exit();
    }

    // Procesado de la base de datos
    $nombreCompleto = "";
    $directoriosubida = "imagenes/";
    $max_file_size = "5120000";
    $extensionesValidas = array("jpg", "png", "gif", "jpeg");
    $errores = 0;
    $errorMsg = '';

    if (isset($_FILES['perfil'])) {
        $nombreArchivo = $_FILES['perfil']['name'];
        $filesize = $_FILES['perfil']['size'];
        $directorioTemp = $_FILES['perfil']['tmp_name'];
        $arrayArchivo = pathinfo($nombreArchivo);
        $extension = $arrayArchivo['extension'];

        if (!in_array($extension, $extensionesValidas)) {
            header("Location: Usuarioyacreado.php");
            
        }

        if ($filesize > $max_file_size) {
            header("Location: Usuarioyacreado.php");
            
        }

        if ($errores == 0) {
            $nombreCompleto = $directoriosubida . $nombreArchivo;
            move_uploaded_file($directorioTemp, $nombreCompleto);
        }
    }

    if ($errores == 0) {
        // Estadisticas basadas en las razas elegidas
        $ataque = $defensa = $defensamagica = $ataquemagico = 0;
        switch ($raza) {
            case "orco":
                $ataque = 20;
                $defensa = 5;
                $defensamagica = 5;
                $ataquemagico = 5;
                break;
            case "elfo":
                $ataque = 12;
                $defensa = 8;
                $defensamagica = 10;
                $ataquemagico = 10;
                break;
            case "enano":
                $ataque = 5;
                $defensa = 15;
                $defensamagica = 15;
                $ataquemagico = 5;
                break;
            case "humano":
                $ataque = 10;
                $defensa = 10;
                $defensamagica = 10;
                $ataquemagico = 10;
                break;
        }

        // Introduce al usuario en el ldap con el nuevo uid
        $newUidNumber = createLDAPUser($usuario, $contrasena, $mail, $nombre, $apellidos, $edad, $direccion);

        if ($newUidNumber) {
            // Introduce al usuario en msql
            $sql_personaje = "INSERT INTO personaje (id, nombre, raza, oro, ataque, defensa, defensamagica, ataquemagico, perfil) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt_personaje = $connection->prepare($sql_personaje);
            $stmt_personaje->execute([$newUidNumber, $usuario, $raza, $oro, $ataque, $defensa, $defensamagica, $ataquemagico, $nombreCompleto]);

            if ($stmt_personaje) {
                header("Location: usuariocreado.php");
                exit();
            } else {
                header("Location: Usuarioyacreado.php");
                exit();
            }
        } else {
            header("Location: alta.php");
        }
    }

    if (!empty($errorMsg)) {
        header("Location: Usuarioyacreado.php");
        
        
    }

    $connection = null;
}


?>
