<article>
	<div class="app-login">
	    <form method="POST">
		<label for="nombre">Nombre</label>
		<input type="text" name="nombre" id="nombre" placeholder="Tu nombre" required accesskey="n"/>
		<label for="email">email</label>
		<input type="email" name="email" id="email" placeholder="fulano@email" required accesskey="e" />
		<label for="password">Contraseña</label>
		<input type="password" name="password" id="password" placeholder="Tu contraseña" required accesskey="p"/>
		<button>Registrarse</button>
	    </form>
		{{#nombreInvalido}}
		<p>Nombre {{nombreInvalido}} ya escogido</p>
		{{/nombreInvalido}}
	</div> 
</article>
