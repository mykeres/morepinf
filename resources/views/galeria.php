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

		<div class="image-thumb-container">
			{{#images}}
			<div class="image-thumb">
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
	</main>
</article>
