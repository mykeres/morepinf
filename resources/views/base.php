<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>pinf</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link href="/static/css/index.css" type="text/css" rel="stylesheet">
	<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" type="text/css" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css?family=Roboto&display=swap" rel="stylesheet">
</head>
<body>
	<header>
		{{#link_return}}
		<div>
			<a href="{{link_return}}">
				<i class="fa fa-long-arrow-left" aria-hidden="true"></i>
				Volver
			</a>
		</div>
		{{/link_return}}
		<div style="display: flex;flex-grow: 1;">
			<label for="search">Busqueda</label>
			<form action="/search">
				<input type="text" name="search" id="search" placeholder="Buscar...">
				<button>Buscar</button>
			</form>
		</div>
		<div>
			{{#connected_user.nombre}}
				<span>{{connected_user.nombre}}</span>
				<a href="/logout">Logout</a>
			{{/connected_user.nombre}}
			{{^connected_user.nombre}}
				<a href="/login">Login</a>
			{{/connected_user.nombre}}
		</div>
	</header>
	{{__MAIN__}}
</body>
</html>
