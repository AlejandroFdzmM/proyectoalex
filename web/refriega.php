<?php
session_start();

if (!isset($_SESSION['uid']) || $_SESSION['uid'] == 1000) {
    header("Location: login.php");
    exit();
}



$id = $_SESSION['uid'];

include "conexion.php";

//Recoge variables del combate
$ataf = isset($_SESSION['ataf']) ? (int)$_SESSION['ataf'] : 0;
$nivel = isset($_SESSION['nivel']) ? (int)$_SESSION['nivel'] : 0;
$nivele = isset($_SESSION['nivele']) ? (int)$_SESSION['nivele'] : 0;
$atamf = isset($_SESSION['atamf']) ? (int)$_SESSION['atamf'] : 0;
$deff = isset($_SESSION['deff']) ? (int)$_SESSION['deff'] : 0;
$defmf = isset($_SESSION['defmf']) ? (int)$_SESSION['defmf'] : 0;
$vida = isset($_SESSION['vida']) ? (int)$_SESSION['vida'] : 0;
$vidae = isset($_SESSION['vidae']) ? (int)$_SESSION['vidae'] : 10000;
$idenemigo = isset($_SESSION['idenemigo']) ? (int)$_SESSION['idenemigo'] : 1;
$ataquee = isset($_SESSION['ataquee']) ? (int)$_SESSION['ataquee'] :10000;
$ataqueme = isset($_SESSION['ataqueme']) ? (int)$_SESSION['ataqueme'] : 10000;
$defe = isset($_SESSION['defe']) ? (int)$_SESSION['defe'] : 10000;
$defme = isset($_SESSION['defme']) ? (int)$_SESSION['defme'] : 10000;
$skill1 = isset($_SESSION['skill1']) ? $_SESSION['skill1'] : '0';
$skill2 = isset($_SESSION['skill2']) ? $_SESSION['skill2'] : '0';
$skill3 = isset($_SESSION['skill3']) ? $_SESSION['skill3'] : '0';
$skill4 = isset($_SESSION['skill4']) ? $_SESSION['skill4'] : '0';
$vidamax = isset($_SESSION['vidamax']) ? $_SESSION['vidamax'] : $vida;
$vidamaxe = isset($_SESSION['vidamaxe']) ? $_SESSION['vidamaxe'] : $vidae;
$selectedSkill = isset($_POST['skillselect']) ? (int)$_POST['skillselect'] : 0;
$turno = isset($_SESSION['turno']) ? (int)$_SESSION['turno'] : 0;
$turno = 1 + $turno;
$_SESSION['turno']=$turno;
$date = date("Y-m-d H:i:s");

$selectedSkill = (int)$selectedSkill;

    if ($nivel >= ($nivele + 3)) {
        $vidae = $vidae - 1.5 * $selectedSkill;
    } elseif ($nivele >= ($nivel + 3)) {
        $vidae = $vidae - $selectedSkill * 0.75;
    } else {
        $vidae = $vidae - $selectedSkill;
    }

//Si la vida del enemigo baja a 0 se eliminan las variables de sesion
if ($vidae <= 0) {

    $reward = mt_rand(1, 3);
    unset($_SESSION['nivel']);
    unset($_SESSION['nivele']);
    unset($_SESSION['ataf']);
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
//Recompensas obtenidas si derrotas al opente
    if ($reward == 1) {
        $ganado = mt_rand(1, 3) * $nivele;
        $sql = "UPDATE personaje SET oro = oro + ? WHERE id = ?";
        $stmt_sql = $connection->prepare($sql);
        $stmt_sql->execute([$ganado, $id]);

        $registro = "INSERT INTO historialcombate (idenemigo, idpersonaje, estado, recompensa1, recompensa2, fecha) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt_registro = $connection->prepare($registro);
        $stmt_registro->execute([$idenemigo, $id, 'vic', $ganado, 0, $date]);
	$_SESSION['ganado'] = $ganado;
        header("Location: victoria.php");
        exit();
    } elseif ($reward == 2) {
        $ganado_query = "SELECT id FROM equipo ORDER BY RAND() LIMIT 1";
        $stmt_ganado = $connection->prepare($ganado_query);
        $stmt_ganado->execute();
        $ganado = $stmt_ganado->fetchColumn();

        $obtencion = "INSERT INTO inventario (idjugador, idequipo) VALUES (?, ?)";
        $stmt_obtencion = $connection->prepare($obtencion);
        $stmt_obtencion->execute([$id, $ganado]);
	
        $registro = "INSERT INTO historialcombate (idenemigo, idpersonaje, estado, recompensa1, recompensa2, fecha) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt_registro = $connection->prepare($registro);
        $stmt_registro->execute([$idenemigo, $id, 'vic', 0, $ganado, $date]);
	
	$_SESSION['objeto']= $ganado;

        header("Location: victoria.php");
        exit();
    } else {
        $ganado1_query = "SELECT id FROM equipo ORDER BY RAND() LIMIT 1";
        $stmt_ganado1 = $connection->prepare($ganado1_query);
        $stmt_ganado1->execute();
        $ganado1 = $stmt_ganado1->fetchColumn();

        $obtencion = "INSERT INTO inventario (idjugador, idequipo) VALUES (?, ?)";
        $stmt_obtencion = $connection->prepare($obtencion);
        $stmt_obtencion->execute([$id, $ganado1]);

        $ganado = mt_rand(1, 3) * $nivele;
        $sql = "UPDATE personaje SET oro = oro + ? WHERE id = ?";
        $stmt_sql = $connection->prepare($sql);
        $stmt_sql->execute([$ganado, $id]);

        $registro = "INSERT INTO historialcombate (idenemigo, idpersonaje, estado, recompensa1, recompensa2, fecha) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt_registro = $connection->prepare($registro);
        $stmt_registro->execute([$idenemigo, $id, 'vic', $ganado, $ganado1, $date]);
	
	$_SESSION['oro']=$ganado1;
	$_SESSION['objeto']=$ganado;
        header("Location: victoria.php");
        exit();
    }
}

//ataques enemigos segun el turno
if ($turno == 1) {
    if ($nivele >= ($nivel + 3)) {
        $vida -= 1.5 * $ataquee / $deff + 1;
    } else {
        $vida -= $ataquee / $deff + 1;
    }
} elseif ($turno == 2) {
    if ($nivele >= ($nivel + 3)) {
        $vida -= 1.5 * $ataqueme / $defmf + 3;
    } else {
        $vida -= $ataqueme / $defmf + 3;
    }
} elseif ($turno == 3) {
    if ($nivele >= ($nivel + 3)) {
        $vida -= 1.5 * $ataquee / $deff + 1;
    } else {
        $vida -= $ataquee / $deff + 3;
    }
} elseif ($turno == 4) {
    // El enemigo no ataca este turno
} else {
    $vida = 0;// te matan si no matas al enemigo en el turno 5
}

// Si tu vida baja a 0 se borra la sesion y se dirige a la pantalla de derrota
if ($vida <= 0) {

    unset($_SESSION['nivel']);
    unset($_SESSION['nivele']);
    unset($_SESSION['ataf']);
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

    $registro = "INSERT INTO historialcombate (idenemigo, idpersonaje, estado, recompensa1, recompensa2, fecha) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt_registro = $connection->prepare($registro);
    $stmt_registro->execute([$idenemigo, $id, 'derr', 0, 0, $date]);

    header("Location: derrota.php");
    exit();
}

	$_SESSION['vida']= $vida;
	$_SESSION['vidae']= $vidae;
	$_SESSION['vidamax']=$vidamax;
	$_SESSION['vidamaxe']=$vidamaxe;


header("Location:combate1.php")
?>
