<article>
	<aside></aside>
	<div class="main">
		<form method="POST">
			<table>
				<thead>
					<tr>
						<th>id</th>
						<th>nombre</th>
						<th>email</th>
					</tr>
				</thead>
				<tbody>
					{{#borrados}}
					<tr>
						<td><input type="checkbox"  name="seleccion[]" value="{{idusuario}}">{{idusuario}}</td>
						<td>{{nombre}}</td>
						<td>{{email}}</td>
					</tr>
					{{/borrados}}
				</tbody>
			</table>
			<button>Eliminar</button>
		</form>
	</div>
</article>
<script  src="static/js/admin.js"></script>
