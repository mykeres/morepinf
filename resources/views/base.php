<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>pinf</title>
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
		{{#login}}
		<div>
			<a href="">Logout</a>
		</div>
		{{/login}}
		<div>
			<label for="search">Busqueda</label>
			<input type="text" name="search" id="search" placeholder="Buscar...">
		</div>
	</header>
	{{__MAIN__}}
</body>
</html>
