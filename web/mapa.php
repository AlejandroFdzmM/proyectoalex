<?php


session_start();

if (!isset($_SESSION['uid']) || $_SESSION['uid'] == 1000) {
    header("Location: login.php");
    exit();
}

include "header.php";
include "conexion.php";
    unset($_SESSION['nivel']);
    unset($_SESSION['nivele']);
    unset($_SESSION['atamf']);
    unset($_SESSION['deff']);
    unset($_SESSION['defmf']);
    unset($_SESSION['vida']);
    unset($_SESSION['vidae']);
    unset($_SESSION['idenemigo']);
    unset($_SESSION['ataquee']);
    unset($_SESSION['ataqueme']);
    unset($_SESSION['defe']);
    unset($_SESSION['defme']);
    unset($_SESSION['skill1']);
    unset($_SESSION['skill2']);
    unset($_SESSION['skill3']);
    unset($_SESSION['skill4']);
    unset($_SESSION['turno']);
    unset($_SESSION['vidamax']);
    unset($_SESSION['vidamaxe']);
    unset($_SESSION['idenemigo']);
    unset( $_SESSION['h1']);
    unset($_SESSION['h2']);
    unset($_SESSION['h3']);
    unset($_SESSION['h4']);
$idUsuario = $_SESSION['uid'];


// Consulta para obtener un enemigo aleatorio
$combatiente = "SELECT * FROM enemigos ORDER BY RAND() LIMIT 1";
$stmt_enemigo = $connection->query($combatiente);
$enemigo = $stmt_enemigo->fetch();

$idenemigo =$enemigo['id'];


// Consulta para obtener los detalles del personaje del usuario
$sql = "SELECT * FROM personaje  WHERE id = ?";
$stmt_personaje = $connection->prepare($sql);
$stmt_personaje->execute([$idUsuario]);
$personaje = $stmt_personaje->fetch();

$nivele = mt_rand(1, 3);

$vida = 20 + ($personaje['nivel'] - 1) * 20;

$vidae = $enemigo['Vida'] * $nivele;
$ataquee = $enemigo['ataque'];
$ataqueme = $enemigo['ataquemagico'];
$defe = $enemigo['defensa'];
$defme = $enemigo['defensamagica'];



$slot1 = "SELECT * FROM inventario
            INNER JOIN equipo ON inventario.idequipo = equipo.id
            WHERE idjugador = ? and posicion = 1 and equipado = 1";
$stmt_slot1 = $connection->prepare($slot1);
$stmt_slot1->execute([$idUsuario]);
$slot1 = $stmt_slot1->fetch();

if ($slot1 == false) {
    $defq1 = 0;
    $ataq1 = 0;
    $atamq1 = 0;
    $defmq1 = 0;
    $habilidad1 = "A. fisico";
} else {
    $defq1 = $slot1['defensa_f'];
    $ataq1 = $slot1['ataque_f'];
    $atamq1 = $slot1['ataque_m'];
    $defmq1 = $slot1['defensa_m'];
    $habilidad1 = $slot1['habilidad'];
}


$slot2 = "SELECT * FROM inventario
            INNER JOIN equipo on inventario.idequipo = equipo.id
            WHERE idjugador = ? and posicion = 2 and equipado = 1";
$stmt_slot2 = $connection->prepare($slot2);
$stmt_slot2->execute([$idUsuario]);
$slot2 = $stmt_slot2->fetch();

if ($slot2 == false) {
    $defq2 = 0;
    $ataq2 = 0;
    $atamq2 = 0;
    $defmq2 = 0;
    $habilidad2 = "A. magico";
} else {
    $defq2 = $slot2['defensa_f'];
    $ataq2 = $slot2['ataque_f'];
    $atamq2 = $slot2['ataque_m'];
    $defmq2 = $slot2['defensa_m'];
    $habilidad2 = $slot2['habilidad'];
}


$slot3 = "SELECT * FROM inventario
            INNER JOIN equipo on inventario.idequipo = equipo.id
            WHERE idjugador = ? and posicion = 3 and equipado = 1";
$stmt_slot3 = $connection->prepare($slot3);
$stmt_slot3->execute([$idUsuario]);
$slot3 = $stmt_slot3->fetch();

if ($slot3 == false) {
    
    $defq3 = 0;
    $ataq3 = 0;
    $atamq3 = 0;
    $defmq3 = 0;
    $habilidad3 = "A. fisico";
} else {
    
    $defq3 = $slot3['defensa_f'];
    $ataq3 = $slot3['ataque_f'];
    $atamq3 = $slot3['ataque_m'];
    $defmq3 = $slot3['defensa_m'];
    $habilidad3 = $slot3['habilidad'];
}

$slot4 = "SELECT * FROM inventario
            INNER JOIN equipo on inventario.idequipo = equipo.id
            WHERE idjugador = ? and posicion = 4 and equipado = 1";
$stmt_slot4 = $connection->prepare($slot4);
$stmt_slot4->execute([$idUsuario]);
$slot4 = $stmt_slot4->fetch();

if ($slot4 == false) {
    
    $defq4 = 0;
    $ataq4 = 0;
    $atamq4 = 0;
    $defmq4 = 0;
    $habilidad4 = "A. magico";
} else {
    
    $defq4 = $slot4['defensa_f'];
    $ataq4 = $slot4['ataque_f'];
    $atamq4 = $slot4['ataque_m'];
    $defmq4 = $slot4['defensa_m'];
    $habilidad4 = $slot4['habilidad'];
}


$ataf = $personaje['ataque'] + $ataq1 + $ataq2 + $ataq3 + $ataq4;
$atamf = $personaje['ataquemagico'] + $atamq1 + $atamq2 + $atamq3 + $atamq4;
$deff = $personaje['defensa'] + $defq1 + $defq2 + $defq3 + $defq4;
$defmf = $personaje['defensamagica'] + $defmq1 + $defmq2 + $defmq3 + $defmq4;

function evaluateExpression($expression, $variables) {
    // Elimina variables no validas
    $sanitizedExpression = preg_replace('/[^0-9\+\-\*\/\(\)\.\s\$a-zA-Z]/', '', $expression);

    // Reemplaza variables por el valor actual
    foreach ($variables as $var => $value) {
        $sanitizedExpression = str_replace('$' . $var, $value, $sanitizedExpression);
    }

    // Evalua la expresion y devuelve el numero
    try {
        $result = eval('return ' . $sanitizedExpression . ';');
        return $result;
    } catch (Throwable $e) {
        return 0; // o da erro
    }
}

$variables = [
    'ataf' => $ataf,
    'atamf' => $atamf,
    'deff' => $deff,
    'defmf' => $defmf,
    'defe' => $defe,
    'defme' => $defme,
    // Variables aplicadas
];





if ($slot1 == false) {
    $skillq1 = ($ataf * 1 * ($ataf/$defe) + 1);
} else {
    $skillq1 = evaluateExpression($slot1['skill'], $variables);
}

if ($slot2 == false) {
    $skillq2 = $atamf * 1 * ($atamf/$defme) + 1;
} else {
    $skillq2 =  evaluateExpression($slot2['skill'], $variables);
}

if ($slot3 == false) {
    $skillq3 = $ataf * 1 * ($ataf/$defe) + 1;
} else {
    $skillq3 =  evaluateExpression($slot3['skill'], $variables);
}

if ($slot4 == false) {
    $skillq4 = ($atamf * 1 * ($atamf/$defme) + 1);
} else {
    $skillq4 =  evaluateExpression($slot4['skill'], $variables);
}


//añade variables a la session para la logica del combate
$nivel = $personaje['nivel'];
$nivele = $nivele;

$_SESSION['ataf']=$ataf;
$_SESSION['nivel']=$nivel;
$_SESSION['nivele']=$nivele;
$_SESSION['atamf']=$atamf;
$_SESSION['deff']=$deff;
$_SESSION['defmf']=$defmf;
$_SESSION['vida']=$vida;
$_SESSION['vidae']=$vidae;
$_SESSION['idenemigo']=$idenemigo;
$_SESSION['ataquee']=$ataquee;
$_SESSION['ataqueme']=$ataqueme;
$_SESSION['defe']=$defe;
$_SESSION['defme']=$defme;
$_SESSION['skill1']=$skillq1;
$_SESSION['skill2']=$skillq2;
$_SESSION['skill3']=$skillq3;
$_SESSION['skill4']=$skillq4;
$_SESSION['turno']=0;
$_SESSION['idenemigo']=$idenemigo;
$_SESSION['h1']=$habilidad1;
$_SESSION['h2']=$habilidad2;
$_SESSION['h3']=$habilidad3;
$_SESSION['h4']=$habilidad4;
?>

<div class="mapa">
	<br><br><br><br><br><br><br><br><br><br><br>
       <a href=combate1.php> <button> Zona1 </button> </a>
	<br><br><br><br><br><br><br><br>&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;
      <a href=combate1.php>  <button> Zona2 </button> </a>
</div>
