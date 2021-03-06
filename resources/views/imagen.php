<article>
	<aside>
		
	</aside>
	<div class="main">
		<h1>{{image.nombre}}</h1>
		{{#can_edit}}
		<form method="POST" name="update-name">
			<input type="hidden" name="command" value="renombrar">
			<input type="text" name="nombreImagen" placeholder="nuevo nombre de imagen" required>
			<button>Confirmar</button> 
		</form>
		{{/can_edit}}
		<p>Foto de la galeria</p>
		<div class="image-view">
			<img src="/ver/{{user.idusuario}}/{{image.idimagen}}" alt="{{image.nombre}}"/>
		</div>

		{{#can_edit}}
			{{#tiene_tags}}
			<div class="image-form" >
				<form method="POST" name="delete-tags">
					<input type="hidden" name="command" value="borrar">
					<span id="checkbox-tags">
					{{#tags}}	
						<input type="checkbox" name="seleccion[]" value="{{idetiqueta}}">
						<a href="/tag/{{idetiqueta}}">{{tipo}}:{{nombre}}</a>
					{{/tags}}
					</span>
					<br/>
					<button id="delete-tags" disabled="disabled">Borrar</button>
				</form>
			</div>
			{{/tiene_tags}}

			<div class="image-form" >
				<form method="post" name="add-tag">
					<input type="hidden" name="command" value="etiqueta">
					<div class="form-linea">
						<label class="alineado" for="tipo" >tipo</label>
						<select name="tipo" id="tipo">
							<option value="tema">tema</option>
							<option value="evento">evento</option>
							<option value="lugar">lugar</option>
							<option value="persona">persona</option>
							<option value="grupo">grupo</option>
						</select>
					</div>
					<div class="form-linea">
						<label class="alineado" for="nombre">nombre</label>
						<input type="text" name="nombre" id="nombre" required placeholder="nombre etiqueta">
						<button id="add-tag">Añadir Tag</button>
					</div>
				</form>
			</div>
		{{/can_edit}}

		<div class="image-form">
			<h3>Compartir:</h3>
			<textarea readonly>http://{{domain}}/imagen/{{user.idusuario}}/{{image.idimagen}}</textarea>
		</div>
</div>
</article>
<script type="text/javascript" src="static/js/formImagen.js"></script>
