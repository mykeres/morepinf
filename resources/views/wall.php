<form method="POST" enctype="multipart/form-data">
	<input name="imagen" type="file" />
	<input type="submit" value="Subir" />
</form>
<?php
/*
	//llamar al modelo que selecciona imagenes para mostrarlas
	include 'User.php';
	//$imagenes = User->getImagenes();
	foreach($imagenes as $imagen){
		echo "<img src='".$imagen."/>";
		// $etiquetas = User->getTagsFromImage($imagen);
		foreach ($etiquetas as $etiqueta) {
			echo "<span>".$etiqueta."</span>";
		}
		
	}
*/
?>
</br>



<?php //crear un formulario dinamico con las etiquetas clasificadas por tipo
	// $eventos = User->getTagsFromClass('evento') ?? [];
/*	$eventos = [];
	$lugares = [];
	$personas = [];
	$temas = [];
	if(isset($_GET['eventos'])){
		$eventos = $_GET['eventos'];
	}
	if(isset($_GET['lugares'])){
		$lugares = $_GET['lugares'];
	}
	if(isset($_GET['personas'])){
		$personas = $_GET['personas'];
	}
	if (isset($_GET['temas'])){
		$temas = $_GET['temas'];
	}

?>

<form method="GET">
	<?php foreach ($eventos as $evento){
		echo "<input value='".$evento."' type='text' name='eventos[]'><br/>";
	}
	foreach ($lugares as $lugar){
		echo "<input value='".$lugar."' type='text' name='lugares[]'><br/>";
	}
	foreach ($personas as $persona){
		echo "<input value='".$persona."' type='text' name='personas[]'><br/>";
	}
	foreach ($temas as $tema){
		echo "<input value='".$tema."' type='text' name='temas[]'><br/>";
	}
	?>
</form>
*/