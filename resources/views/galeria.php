<article>
	<aside>
		
	</aside>
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
			<div style="display:flex;flex-direction:row;">
				<select id="form-command" name="command">
					<option value="">--Elige una opción</option>
					<option value="borrar">Borrar</option>
					<option value="etiqueta">Etiqueta</option>
				</select>
				<select id="form-tags" name="idetiqueta" class="hidden">
					{{#tags}}
					<option value="{{idetiqueta}}">{{nombre}} - {{tipo}}</option>
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
							<img src="/ver/{{idusuario}}/{{idimagen}}"/>
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
	<script>
		document.getElementById('form-command').addEventListener('change',(e) => {
			if (e.target.value != '') {
				document.getElementById('form-button').classList.remove('hidden');
			} else {
				document.getElementById('form-button').classList.add('hidden');
			}

			if (e.target.value == 'etiqueta') {
				document.getElementById('form-tags').classList.remove('hidden');
			} else {
				document.getElementById('form-tags').classList.add('hidden');
			}
		});
	</script>
</article>
