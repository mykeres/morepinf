<article>
	<aside>
	</aside>
	<div class="main">
		{{#imagen_subida}}
		<h1>🎉 Imagen subida</h1>
		<p>La imagen ya se encuentra en tu galería</p>
		<div><a href="{{link_galeria}}">📂 Ver galería</a></div>		
		<div><a href="/subir">📸 Subir otra imagen</a></div>
		{{/imagen_subida}}
		{{^imagen_subida}}
		<h1>Subir imagen</h1>
		<p>Sube una imagen a tu galería</p>

		<form method="POST" enctype="multipart/form-data" style="padding-top:20px;">
			<div class="form-linea">
				<label for="imagen" class="alineado">Imagen</label>
				<input type="file" name="imagen" id="imagen"/>
			</div>
			<div class="form-linea">
				<label for="nombre" class="alineado">Nombre</label>
				<input type="text" name="nombre" placeholder="nombre para la imagen" required>
				<button>📸 Subir Imagen</button>
			</div>
		</form>
		{{/imagen_subida}}
	</div>
</article>
<script type="text/javascript" src="/static/js/formSubir.js"></script>
