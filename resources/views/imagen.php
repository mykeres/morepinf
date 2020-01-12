<article>
	<aside>
		
	</aside>
	<main>
		<h1>{{image.nombre}}</h1>
		<p>Foto de la galeria</p>

		<div>
			<img src="/ver/{{user.idusuario}}/{{image.idimagen}}"/>
		</div>
		{{#tags}}
			<a href="/tag/{{idetiqueta}}">{{nombre}}</a>
		{{/tags}}
		{{#can_edit}}
		<form method="POST">
			<label for="nombre">nombre</label>
			<input type="text" name="nombre" id="nombre" required placeholder="nombre etiqueta">
			<label for="tipo">tipo</label>
			<select name="tipo" id="tipo">
				<option value="tema">tema</option>
				<option value="evento">evento</option>
				<option value="lugar">lugar</option>
				<option value="persona">persona</option>
				<option value="grupo">grupo</option>
			</select>
			<br/>
			<input type="submit" value="añadir">
		</form>
		{{/can_edit}}
		
		<h3>Compartir:</h3>
		<textarea readonly>http://{{domain}}/imagen/{{user.idusuario}}/{{image.idimagen}}</textarea>
		<form method="POST">
			{{#tagsNotInImage}}
			<div>{{tagsNotInImage}}</div>
			{{#tagsNotInImage.tipo}}
			<input type="submit" value="{{nombre}}">
			{{/tagsNotInImage.tipo}}
			{{/tagsNotInImage}}
		</form>
	</main>
</article>
