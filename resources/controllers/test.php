<?php
	class _controller_test extends _controller{
		function main(...$params){
			
		}
		function images(){
			$UserTable = new UserTable();
			$user = $UserTable->getByName('user');

			$ImageTable = new ImageTable();
			$images = $ImageTable->getImagesFromUser($user,['count'=>30]);
print_r($images);
exit;
		}
		function tags(){
			$UserTable = new UserTable();
			$user = $UserTable->getByName('user');	

			// prbando
			
			$tagTable = new TagTable();
			$tag = new Tag();
			$tag->setTipo('evento');// (evento lugar persona grupo tema) (enum)
			$tag->setNombre('cumpleaños');
			$tagTable->insert($tag);
var_dump($tag);	
			$tagsFromUser = $tagTable->getTagsFromUser($user);
		
print_r($tags);
exit;
		}
	}
