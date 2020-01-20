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
		{{^hide_search}}
		<div class="search">
			<form action="/search">
				<input type="text" name="search" id="search" placeholder="Buscar fotos..." accesskey="b">
			</form>
		</div>
		{{/hide_search}}
		<div class="user">
			{{#connected_user.nombre}}
				<a href="/subir" accesskey="s">💎 Subir</a>
				<span class="connected-user">{{connected_user.nombre}}</span>
				<a href="/logout" accesskey="o">Logout</a>
			{{/connected_user.nombre}}
			{{^connected_user.nombre}}
				<a href="/login" class="login" accesskey="l">Login</a>
			{{/connected_user.nombre}}
		</div>
	</header>
	{{__MAIN__}}
	<footer>
	</footer>
</body>
</html>
