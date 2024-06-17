<?php
Include "headerindex.php"

//registro del usuario
?>
    <form action="alta.php" method="POST" enctype="multipart/form-data">
        <fieldset>
            <legend>Registro</legend>
            <label for="nombre">Nombre</label><br>
            <input type="text" id="nombre" name="nombre" maxlength="20"  placeholder="Tu Nombre" required><br>
            <label for="apellidos">Apellidos</label><br>
            <input type="text" name="apellidos" id="apellidos" maxlength="30" placeholder="Tus apellidos" required><br>
            <label for="edad">Año de nacimiento</label><br>
            <input type="date" id="edad" name="edad"  placeholder="Año de nacimiento" required ><br>
            <label for="direccion">Direccion</label><br>
            <input type="text" id="direccion" name="direccion" maxlength="50" placeholder="Tu direccion" required><br>
            <label for="usuario">Usuario</label><br>
            <input type="text" name="usuario" id="usuario" maxlength="20" placeholder="Nombre usuario" require><br>
            <label for="contrasena">Contraseña</label><br>
            <input type="password" name="contrasena" id="contrasena" maxlength="20" placeholder="Contraseña" required><br>
            <label for="correo">Correo Electronico</label><br>
            <input type="mail" id="correo" name="correo" maxlength="30" placeholder="Tu e-mail" required><br>
            <label for="raza">Raza:</label><br>
            <select name="raza" id="raza">
            <option value="orco">Orco</option>
            <option value="elfo">Elfo</option>
            <option value="enano">Enano</option>
            <option value="humano">Humano</option>
            </select>
            <br>
            <label for="perfil">Foto Perfil</label><br>
            <input type="file" name="perfil" id="perfil" required><br>
        </fieldset>
	<br>
        <input type="submit" value="Enviar"> <input type="reset" value="Resetear" >
	<br><br>
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	<a href="index.php">Volver</a>
    </form>

<?php

Include "footerindex.php"

?>
