<form method="POST">
    <label for="nombre">nombre</br>
    </label>
    <input type="text" name="nombre" id="nombre" placeholder="Tu nombre" required/></br>
    <label for="email">email</br>
    </label>
    <input type="email" name="email" id="email" placeholder="fulano@email" required/></br>
    <label for="password">contraseña</br>
    </label>
    <input type="password" name="password" id="password" placeholder="Tu contraseña" required/></br>
    <input type="submit" value="registrarse">
</form> 
{{#nombreInvalido}}
<p>Nombre {{nombreInvalido}} ya escogido</p>
{{/nombreInvalido}}