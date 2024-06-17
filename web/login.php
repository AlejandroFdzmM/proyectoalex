<?php

include "headerindex.php"
//Formulario login para entrar en la aplicacion
?>
<div>
<form action="comprobar.php" method="POST">

<fieldset>
    <legend>Login</legend>

	<label for="useraname">Usuario:</label>
        <input type="text" id="username" name="username" required><br><br>

        <label for="password">Contraseña:</label>
        <input type="password" id="password" name="password" required><br><br>

</fieldset>
<br>
<input type="submit" value="Acceder">
<input type="reset" value="Limpiar">

</form>
<br>
<br>

<a href="index.php"><button>Volver</button></a>
</div>

<?php

Include "footerindex.php"

?>
