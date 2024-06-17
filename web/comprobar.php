<?php
session_start();
include "conexion.php";

function authenticateUser($username, $password) {
    $ldapServer = 'ldap://ldap-service'; // ldap server
    $ldapAdminDN = 'cn=admin,dc=proyectoalex,dc=com'; // ldap admin cn
    $ldapAdminPassword = 'secret'; // ldap admin password
    $baseDN = "ou=users,dc=proyectoalex,dc=com"; // UO donde buscar

    // Conexion server ldap
    $ldapConnection = ldap_connect($ldapServer);
    if (!$ldapConnection) {
        die("Could not connect to LDAP server");
    }

    //Protocolo ldap a 3
    ldap_set_option($ldapConnection, LDAP_OPT_PROTOCOL_VERSION, 3);

    // Conexion con las credenciales
    $bind = ldap_bind($ldapConnection, $ldapAdminDN, $ldapAdminPassword);
    if (!$bind) {
        die("Could not bind to LDAP server: " . ldap_error($ldapConnection));
    }

    // Busqueda por usuario
    $searchFilter = "(uid=$username)";
    $attributes = ["uid", "uidNumber"];
    $searchResult = ldap_search($ldapConnection, $baseDN, $searchFilter, $attributes);

    if (!$searchResult) {
        ldap_close($ldapConnection);
        return false;
    }

    $entries = ldap_get_entries($ldapConnection, $searchResult);

    if ($entries["count"] == 0) {
        ldap_close($ldapConnection);
        return false; //Usuario no encontrado
    }

    $userDN = $entries[0]["dn"];
    $uidNumber = $entries[0]["uidnumber"][0];

    // Autenticacion de usuario
    $bindUser = @ldap_bind($ldapConnection, $userDN, $password);
    if (!$bindUser) {
        return false; // Credenciales no validas
    }

    ldap_close($ldapConnection);

    return $uidNumber; // Devuel la uidNumber del usuario
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $uidNumber = authenticateUser($username, $password);

    if ($uidNumber !== false) {
        // Comineza la sesion con las credenciales
        $_SESSION['uid'] = $uidNumber;
        $_SESSION['username'] = $username;

        // Redireccion basada en el uidNumber
        if ($uidNumber == 1000) {
            header("Location: admin.php");
        } else {
            header("Location: personaje.php");
        }
        exit();
    } else {
        header("Location: NoRegistrado.php");
    }
}
?>
