<?php

session_start();

if (!isset($_SESSION['uid']) || $_SESSION['uid'] == 1000) {
    header("Location: login.php");
    exit();
}
if (!isset($_SESSION['ataf'])) {
        header("Location: personaje.php");
        exit();

}



include "header.php";
include "conexion.php";

$idUsuario = $_SESSION['uid'];

//Recoge datos necesarios para la logica del combate
$ataf = isset($_SESSION['ataf']) ? (int)$_SESSION['ataf'] : 0;
$nivel = isset($_SESSION['nivel']) ? (int)$_SESSION['nivel'] : 0;
$nivele = isset($_SESSION['nivele']) ? (int)$_SESSION['nivele'] : 0;
$atamf = isset($_SESSION['atamf']) ? (int)$_SESSION['atamf'] : 0;
$deff = isset($_SESSION['deff']) ? (int)$_SESSION['deff'] : 0;
$defmf = isset($_SESSION['defmf']) ? (int)$_SESSION['defmf'] : 0;
$vida = isset($_SESSION['vida']) ? (int)$_SESSION['vida'] : 0;
$vidae = isset($_SESSION['vidae']) ? (int)$_SESSION['vidae'] : 0;
$idenemigo = isset($_SESSION['idenemigo']) ? (int)$_SESSION['idenemigo'] : 0;
$ataquee = isset($_SESSION['ataquee']) ? (int)$_SESSION['ataquee'] : 0;
$ataqueme = isset($_SESSION['ataqueme']) ? (int)$_SESSION['ataqueme'] : 0;
$defe = isset($_SESSION['defe']) ? (int)$_SESSION['defe'] : 0;
$defme = isset($_SESSION['defme']) ? (int)$_SESSION['defme'] : 0;
$skill1 = isset($_SESSION['skill1']) ? $_SESSION['skill1'] : '';
$skill2 = isset($_SESSION['skill2']) ? $_SESSION['skill2'] : '';
$skill3 = isset($_SESSION['skill3']) ? $_SESSION['skill3'] : '';
$skill4 = isset($_SESSION['skill4']) ? $_SESSION['skill4'] : '';
$vidamax = isset($_SESSION['vidamax']) ? $_SESSION['vidamax'] : $vida;
$vidamaxe = isset($_SESSION['vidamaxe']) ? $_SESSION['vidamaxe'] : $vidae;
$idenemigo = isset($_SESSION['idenemigo']) ? $_SESSION['idenemigo'] : 1;
$h1 = isset($_SESSION['h1']) ? $_SESSION['h1'] : 'A fisico';
$h2 = isset($_SESSION['h2']) ? $_SESSION['h2'] : 'A magico';
$h3 = isset($_SESSION['h3']) ? $_SESSION['h3'] : 'A fisico';
$h4 = isset($_SESSION['h4']) ? $_SESSION['h4'] : 'A magico';

$sql = "SELECT * from enemigos where id = ?  ";
$stmt = $connection->prepare($sql);
$stmt->execute([$idenemigo]);
$perfile = $stmt->fetch();

$sql2 = "SELECT * from personaje where id = ?  ";
$stmt2 = $connection->prepare($sql2);
$stmt2->execute([$idUsuario]);
$perfil = $stmt2->fetch();

//Muestra habilidades equipadas segun el objeto
?>
<div>
<table>
        <tr>
            <td colspan="2"><?php echo $perfil['nombre']; ?></td>
        </tr>
        <tr>
            <td colspan="2"><img src='<?php echo $perfil['perfil']; ?>' alt='Perfil Image'></td>
        </tr>
        <tr >
            <td colspan="2">Vida</td>
        </tr>
        <tr>
            <td colspan="2"><?php echo $vida ?>/ <?php echo $vidamax ?></td>
        </tr>
        <tr>
            <td>
                <form action="refriega.php" method="post">
		    <input type="hidden" name="skillselect" value="<?php echo $skill1; ?>">
                    <button type="submit"><?php echo $h1; ?></button>
                </form>
            </td>
            <td>
            <form action="refriega.php" method="post">
            	    <input type="hidden" name="skillselect" value="<?php echo $skill2; ?>">

                    <button type="submit"><?php echo $h2; ?></button>
		</form>
            </td>
        </tr>
        <tr>
            <td>
            <form action="refriega.php" method="post">
                    <input type="hidden" name="skillselect" value="<?php echo $skill3; ?>">

                    <button type="submit"><?php echo $h3; ?></button>
	   </form>
            </td>
            <td>
            <form action="refriega.php" method="post">
                    <input type="hidden" name="skillselect" value="<?php echo $skill4; ?>">

                    <button type="submit"><?php echo $h4; ?></button>
	   </form>
            </td>
        </tr>
    </table>


</div>
<div class="enmedio">

</div>

<div>
        <table>
           
            <tr>

                <td ><?php echo $perfile['nombre'];?></td>

            </tr>
            <tr>
            <td ><img  src='<?php echo $perfile['ImagenEnemigo'] ?>' alt='Imagen Enemigo'></td>
            </tr>
            <tr>
                <td> Vida enemigo</td>
            </tr>
            <tr>
		<td><?php echo $vidae ?>/ <?php echo $vidamaxe ?>  </td>
	    </tr>
        </table>

</div>
