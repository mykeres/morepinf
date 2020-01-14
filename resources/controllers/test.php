<?php
	class _controller_test extends _controller{
		function main(...$params){
			
		}
		function images(){
			$UserTable = new UserTable();
			$user = $UserTable->getByName('user');

			$ImageTable = new ImageTable();
			$images = $ImageTable->getImagesFromUser($user,['count'=>30]);
print_r($images[0]);
			$numeroImagenesUser = $ImageTable->countImagesFromUser($user);
			echo 'numero de imagenes de usuario: '. $numeroImagenesUser;
exit;
		}
		function tags(){
			$UserTable = new UserTable();
			$user = $UserTable->getByName('user');	

			// prbando
			
			$tagTable = new TagTable();
			$tag = new Tag();
			$tag->setTipo('tema');// (evento lugar persona grupo tema) (enum)
			$tag->setNombre('cumple');
			$tag->setIdUsuario(21);
						


			//$tagsFromUser = $tagTable->getTagsFromUser($user);
			//$nameTags['nombre']=[];
			//foreach ($tagsFromUser as $tag) {
			//	$nameTags['nombre'][]=$tag->getNombre();
			//}
			//$ImageTable = new ImageTable();
			//$images = $ImageTable->getImagesFromUser($user,['count'=>1]);

//var_dump($images);
			//$image = $images[0];
			//$tagsFromImage = $tagTable->getTagsFromImage($image);
//var_dump($tagsFromImage);

			$imagen = new Image();
			$imagen->setIdImagen('5e177e33b5db6');
			echo "_____";
			$tag1 = new Tag();
			$tag1->setTipo('tema');// (evento lugar persona grupo tema) (enum)
			$tag1->setNombre('flores');
			$tag1->setIdUsuario(21);
		//	$tagTable->existTag($tag1);
		//	$tagTable->insert($tag1);
		//	var_dump($tagTable->existTag($tag1));
			$tagTable->insertTagImage($tag1,$imagen);

exit;
		}
	}
