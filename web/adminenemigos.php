<?php

Include "headeradmin.php";
// Crea nuevos enemigos en un formulario
?>

    <form action="adminregistroenemigo.php" method="POST" enctype="multipart/form-data">
        <fieldset>
            <legend> Crear Enemigo </legend>

            <label for="nombre">Nombre</label>
            <input type="text" name="nombre" id="nombre" required maxlength="20"><br><br>
            <label for="ataque">Ataque</label>
            <input type="number" name="ataque" id="ataque" required min="1" max="10000"><br><br>
            <label for="defensa">Defensa</label>
            <input type="number" name="defensa" id="defensa" required min="1" max="10000"><br><br>
            <label for="ataquemagico">Ataque Magico</label>
            <input type="number" name="ataquemagico" id="ataquemagico" required min="1" max="10000"><br><br>
            <label for="defensamagica">Defensa</label>
            <input type="number" name="defensamagica" id="defensamagica" required min="1" max="10000"><br><br>
	    <label for="vida">Vida</label>
            <input type="number" name="vida" id="vida" required min="1" max="10000"><br><br>
            <label for="perfil">Imagen</label>
            <input type="file" name="perfil" required id="imagen"><br><br>


        </fieldset><br>
        <input type="submit" value="Crear" >
        <input type="reset" value="Reset" >

    </form>

<?php

Include "footeradmin.php"

?>
