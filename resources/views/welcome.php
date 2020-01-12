<article>
	<aside>
		
	</aside>
	<main>
		<h1>Bienvenido <span>{{user.nombre}}</span></h1>
		<p>Panel de usuario</p>
		{{^numeroImagenes}}
		<p>Parece que eres nuevo por aquí, así que debes:</p>
		{{/numeroImagenes}}
		<div><a href="/subir">Subir una nueva imagen.</a></div>
		{{#numeroImagenes}}
		<div><a href="/galeria/{{user.idusuario}}">Consulta tu galería de fotos ({{numeroImagenes}}).</a></div>
		{{/numeroImagenes}}

	</main>
</article>
