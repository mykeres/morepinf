<div class="app-login">
    <form method="POST">
        <label for="nombre">nombre</label>
        <input type="text" name="nombre" id="nombre" placeholder="Tu nombre" required/>
        <label for="email">email</label>
        <input type="email" name="email" id="email" placeholder="fulano@email" required/>
        <label for="password">contraseña</label>
        <input type="password" name="password" id="password" placeholder="Tu contraseña" required/>
        <button>Registrarse</button>
    </form>
</div> 
{{#nombreInvalido}}
<p>Nombre {{nombreInvalido}} ya escogido</p>
{{/nombreInvalido}}