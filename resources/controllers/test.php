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
exit;
		}
		function tags(){
			$UserTable = new UserTable();
			$user = $UserTable->getByName('user');	

			// prbando
			
			$tagTable = new TagTable();
			$tag = new Tag();
			$tag->setTipo('evento');// (evento lugar persona grupo tema) (enum)
			$tag->setNombre('cumpleanos');
			$tag->setIdUsuario(21);

			//$tagTable->insert($tag);
//var_dump($tag);	
			$tagsFromUser = $tagTable->getTagsFromUser($user);
			$nameTags['nombre']=[];
			foreach ($tagsFromUser as $tag) {
				$nameTags['nombre'][]=$tag->getNombre();
			}
//var_dump($tagsFromUser);
//var_dump($nameTags);
			$ImageTable = new ImageTable();
			$images = $ImageTable->getImagesFromUser($user,['count'=>1]);

var_dump($images);
			$image = $images[0];
			$tagsFromImage = $tagTable->getTagsFromImage($image);
var_dump($tagsFromImage);

exit;
		}
	}
