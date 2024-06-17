<?php
//conexion base de datos
       $database ="alejandro";
       $user = "nginx_user";
       $password = "secret";
       $host = "mysql";

       $connection = new PDO("mysql:host={$host};dbname={$database};charset=utf8", $user, $password);
?>