<?php

Include "headeradmin.php";
//cabecera y menu del apartado de administracion
?>



<div>

    <form action="adminregistroobjeto.php" method="POST" enctype="multipart/form-data">
        <fieldset>
            <legend> Crear Enemigo </legend>

            <label for="nombre">Nombre</label>
            <input type="text" name="nombre" id="nombre" required maxlength="20"><br><br>
            <label for="descripcion">Descripcion</label>
            <input type="text" name="descripcion" id="descripcion" required><br><br>
            <label for="">Nombre habilidad</label>
            <input type="text" name="habilidad" id="habilidad" required maxlength="20"><br><br>
            <label for="ataquefisico">Ataque fisico</label>
            <input type="number" name="ataquefisico" id="ataquefisico" required min="1" max="10000"><br><br>
            <label for="defensa">Defensa</label>
            <input type="number" name="defensa" id="defensa" required min="1" max="10000"><br><br>
            <label for="ataquemagico">Ataque magico</label>
            <input type="number" name="ataquemagico" id="ataquemagico" required min="1" max="10000"><br><br>
            <label for="defensamagica">Defensa magica</label>
            <input type="number" name="defensamagica" id="defensamagica" required min="1" max="10000"><br><br>
            <label for="">Skill</label>
            <input type="text" name="skill" id="skill" required maxlength="20"><br><br>
            <label for="posicion">Posicion</label>
            <input type="number" name="posicion" id="posicion" required min="1" max="4"><br>
	    1: casco 2: peto 3: pantalones 4: arma
	    <br><br>
            <label for="precio">Precio</label>
            <input type="number" name="precio" id="precio" required min="1" max="10000"><br>


        </fieldset><br>
        <input type="submit" value="Crear" >
        <input type="reset" value="Reset" >

    </form>
</div>
<div>
<table>
    <tr>
        <th colspan="2">Variables usables en SKILL</th>
    </tr>
    <tr>
        <td>"$ataquee"</td>
        <td>Ataque enemigo</td>
    </tr>
    <tr>
        <td>"$ataqueme"</td>
        <td>A magigo enemigo</td>
    </tr>
    <tr>
        <td>"$defe"</td>
        <td>Defensa enemigo</td>
    </tr>
    <tr>
        <td>"$defme"</td>
        <td>D. mágica enemigo</td>
    </tr>
    <tr>
        <td>"$ataf"</td>
        <td>Ataque</td>
    </tr>
    <tr>
        <td>"$atamf"</td>
        <td>A magigo</td>
    </tr>
    <tr>
        <td>"$deff"</td>
        <td>Defensa</td>
    </tr>
    <tr>
        <td>"$defmf"</td>
        <td>D. mágica</td>
    </tr>
</table>

<div>


<?php

Include "footeradmin.php"

?>
