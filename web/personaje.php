<?php
session_start();

if (!isset($_SESSION['uid']) || $_SESSION['uid'] == 1000) {
    header("Location: login.php");
    exit();
}


include "header.php";
include "conexion.php";


//recoge variables de session y estadisticas del personaje vinculado a ellas
$username = $_SESSION['username'];
$uid = $_SESSION['uid'];





$idUsuario = $_SESSION['uid'];

$sql = "SELECT * FROM personaje WHERE id = ?";
$stmt = $connection->prepare($sql);
$stmt->execute([$uid]);
$personaje = $stmt->fetch();

?>

<div>
    <h1>Personaje</h1>

    <table>
        <tr>
            <td>
                <img  src='<?php echo $personaje['perfil']; ?>' alt='Perfil Image'>
            </td>
        </tr>
        <tr>
            <td> Nombre: <?php echo $personaje['nombre']; ?> </td>
        </tr>
        <tr>
            <td>
                Raza: <?php echo $personaje['raza']; ?>
            </td>
        </tr>
        <tr>
            <td>
                Oro: <?php echo $personaje['oro']; ?>
            </td>
        </tr>
    </table>
</div>

<div class="enmedio">
    <a href="mapa.php"><button type="submit">Combatir</button></a>
    <br><br><br>
    <a href="inventario.php"> <button>Inventario</button> </a>
    <br><br><br>
    <div><a href="mercado.php"><button>Subasta</button></a></div>
    <br><br><br>
    <div><a href="borrarsesion.php"><button>Log out</button></a></div>

</div>

<div>
    
    <table>
        <tr>
            <td colspan="4">

               <h1> Mejora stats </h1>

            </td>
        </tr>

        <tr>
            <td colspan="4">
                <img src="img/vendedor.jpg">
            </td>
        </tr>

        <tr>
            <td>Stats</td>
            <td>Valor</td>
            <td>Precio</td>
	    <td>Compra</td>

        </tr>
        <tr>
           <td>Ataque</td>
           <td> <?php echo $personaje['ataque']; ?> </td>
           <td> <?php echo $personaje['aumentoataf']; ?> </td>
	   <td>
                <form method="post" action="comprar.php"> 
                    <input type="hidden" name="stat" value="ata">
                    <input type="submit" value="+">  
                </form>
            </td>
        </tr>

        <tr>
           <td>defensa</td>
           <td> <?php echo $personaje['defensa']; ?></td>
	   <td> <?php echo $personaje['aumentodeff']; ?> </td>
           <td>
                <form method="post" action="comprar.php"> 
                    <input type="hidden" name="stat" value="def">
                    <input type="submit" value="+">  
                </form>
            </td>
        </tr>

        <tr>
           <td>Ataque magico</td>
           <td> <?php echo $personaje['ataquemagico']; ?></td>
           <td> <?php echo $personaje['aumentoam']; ?> </td>
           <td>
                <form method="post" action="comprar.php"> 
                    <input type="hidden" name="stat" value="atam">
                    <input type="submit" value="+">  
                </form>
            </td>
        </tr>

        <tr>
           <td>Defensa magica</td>
           <td> <?php echo $personaje['defensamagica']; ?></td>
           <td> <?php echo $personaje['aumentodm']; ?> </td>
           <td>
                <form method="post" action="comprar.php"> 
                    <input type="hidden" name="stat" value="defm">
                    <input type="submit" value="+">  
                </form>
            </td>
        </tr>

    </table>

</div>









<?php

Include "footer.php"

?>

