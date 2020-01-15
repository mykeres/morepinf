<article>

	<main>
		{{#tag.nombre}}
		<h1>Tag <span>{{tag.nombre}}</span></h1>
		<p>Imágenes etiquetadas</p>
		{{/tag.nombre}}
		{{^tag.nombre}}
		<h1>Tu galería</h1>
		<p>Galería de fotos</p>
		{{/tag.nombre}}
		<form method="post">
			{{#can_edit}}
			<div class="form-options">
				<select id="form-command" name="command">
					<option value="">--Elige una opción</option>
					<option value="borrar">Borrar Imagen</option>
					<option value="etiqueta">Añadir Etiqueta</option>
				</select>
				<select id="form-tags" name="idetiqueta" class="hidden">
					{{#tags}}
					<option value="{{idetiqueta}}">{{tipo}} - {{nombre}} </option>
					{{/tags}}
				</select>
				<button id="form-button" class="hidden">Aplicar</button>
			</div>
			{{/can_edit}}

			<div class="image-thumb-container">
				{{#images}}
				<div class="image-thumb">
					<input type="checkbox" name="seleccion[]" value="{{idimagen}}">
					<div>
						<a href="/imagen/{{idusuario}}/{{idimagen}}">
							<img src="/ver/{{idusuario}}/{{idimagen}}" alt="{{nombre}}"/>
						</a>
					</div>
					<div
						class="image-thumb-name"
						title="{{nombre}}"
					>
						<a href="/imagen/{{idusuario}}/{{idimagen}}">
							{{nombre}}
						</a>
					</div>
				</div>
				{{/images}}
			</div>
		</form>
	</main>
	<aside>
		{{#tagnube}}
			<h3>{{nombre}}</h3>
			{{#valores}}
			<a href="/tag/{{idetiqueta}}">{{nombre}}</a>
			{{/valores}}
		{{/tagnube}}


		<!--<h3>Personas:</h3>
		{{#persona}}		
			<a href="/tag/{{idetiqueta}}">{{nombre}}</a>
		{{/persona}}
		<h3>Temas:</h3>
		{{#tema}}		
			<a href="/tag/{{idetiqueta}}">{{nombre}}</a>
		{{/tema}}
		<h3>Lugares:</h3>
		{{#lugar}}		
			<a href="/tag/{{idetiqueta}}">{{nombre}}</a>
		{{/lugar}}
		<h3>Grupos:</h3>
		{{#grupo}}		
			<a href="/tag/{{idetiqueta}}">{{nombre}}</a>
		{{/grupo}}
		<h3>Eventos:</h3>
		{{#evento}}		
			<a href="/tag/{{idetiqueta}}">{{nombre}}</a>
		{{/evento}}-->

	</aside>
	<script type="text/javascript" src="static/js/form.js"></script>
</article>
