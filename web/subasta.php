<?php
session_start();
include "conexion.php";
include "header.php";

if (!isset($_SESSION['uid']) || $_SESSION['uid'] == 1000) {
    header("Location: login.php");
    exit();
}


$idobjeto = $_POST['id'];

$uid = $_SESSION['uid'];
$mensajesubasta = isset($_SESSION['mensajesubasta']) ? $_SESSION['mensajesubasta'] : '';;
unset($_SESSION['mensajesubasta']);


//Formulario para subastar un objeto de tu inventario

?>

<div>
  <h1> Aviso: </h1>
  <p>La subasta tine una comison de 3 oros<br>
  No se admiten precios de puja menores al precio de venta</p>
<form action="subasta2.php" method="post" enctype="multipart/form-data">
<fieldset>
  <legend>Formulario Subasta</legend>
    <input type="hidden" name="idobjeto" value="<?php echo $idobjeto ?>" >
    <label for="pminimo">Precio de puja minimo</label></br>
    <input type="number" name="pminimo" id="pminimo" min="4" max="10000" required></br>
    <label for="pmaximo">Precio de venta directa</label></br>
    <input type="number" name="pmaximo" id="pmaximo" min="5" max="20000" required></br></br>
    <input type="submit" value="Subastar"> <input type="reset" value="Resetear" ></br>

</fieldset>
</form>
</div>

<?php
Include "footer.php";

?>
