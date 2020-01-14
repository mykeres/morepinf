<article>
	<aside>
	</aside>
	<main>
		<h1>Subir imagen</h1>
		<p>Sube una imagen a tu galería</p>

		<form method="POST" enctype="multipart/form-data">
			<label for="imagen">Imagen para subir</label>
			<input type="file" name="imagen" id="imagen"/>
			<label for="nombre">Nombre</label>
			<input type="text" name="nombre" placeholder="nombre para la imagen" required>
			<button>Subir Imagen</button>
		</form>
	</main>
</article>
<script type="text/javascript" src="../static/js/formSubir.js"></script>
