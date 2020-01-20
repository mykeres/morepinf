<?php
	class _controller_index extends _controller  {
		function main(...$params){
			if (!_app::isLogged()) {
				$this->_render_common_r('/login');
			}
			$this->login();
			$this->_render('login');
		}
		function _datos_genericos(){
			if (_app::isLogged()) {
				$user = _app::getUser();
				$this->output['connected_user'] = $user->toArray();
			}
		}
		function welcome(){
			if (!_app::isLogged()) {
				$this->_render_common_r('/login');
			}

			$user = _app::getUser();
			$imageTable = new ImageTable();
			$imageTable->countImagesFromUser($user);
			$this->output['user'] = $user->toArray();
			$this->output['numeroImagenes'] = $imageTable->countImagesFromUser($user);

			$this->_datos_genericos();
			$this->_render('welcome');
		}
		function galeria($id = ''){
			if (empty($id)) {
				echo 'no puedo pintar nada';
				exit;
			}

			$UserTable = new UserTable();
			$user = $UserTable->getById(intval($id));
			if ($user === null) {
				echo 'el usuario no existe';
				exit;
			}

			if (!empty($_POST['command'])) {
				switch ($_POST['command']) {
					case 'etiqueta':
						if (empty($_POST['idetiqueta'])) {
							$this->_render_common_r('/galeria/'.$id);
							break;
						}

						$TagTable = new TagTable();
						$ImageTable = new ImageTable();

						$tag = $TagTable->getById(intval($_POST['idetiqueta']));
						if ($tag === null) {
							$this->_render_common_r('/galeria/'.$id);
							break;
						}

						if ($tag->getIdUsuario() !== $user->getIdUsuario()) {
							/* el id de la tag no corresponde con el id del dueño 
							 * de la galería */
							$this->_render_common_r('/galeria/'.$id);
							break;
						}

						foreach ($_POST['seleccion'] as $idimage) {
							$image = $ImageTable->getById($idimage);
							if ($image === null) {
								continue;
							}

							$TagTable->insertTagImage($tag,$image);
						}

						$this->_render_common_r('/galeria/'.$id);
						break;
					case 'borrar':
						$ImageTable = new ImageTable();
						foreach ($_POST['seleccion'] as $idimage) {
							$ImageTable->removeById($idimage);
						}
						$this->_render_common_r('/galeria/'.$id);
						break;
				}
			}

			$ImageTable = new ImageTable();
			$images = $ImageTable->getImagesFromUser($user);

			$arrimages = [];
			foreach ($images as $image) {
				$img = $image->toArray();
				$arrimages[] = $img;
			}

			$this->output['images'] = $arrimages;

			$TagTable = new TagTable();
			$tags = $TagTable->getTagsFromUser($user);
			$arrtags = [];
			foreach ($tags as $tag) {
				$tg = $tag->toArray();
				$arrtags[] = $tg;
			}

			$this->output['tags'] = $arrtags;

			$tagTipos=[];

			foreach($arrtags as $value){
				$tgNombre = $value['nombre'];
				$tgId =$value['idetiqueta'];
				$tagTipos[$value['tipo']]['nombre'] = $value['tipo'];
				$tagTipos[$value['tipo']]['valores'][] = ['nombre'=>$tgNombre, 'idetiqueta'=>$tgId];
			}

			$this->output['tagnube'] = $tagTipos;

			/*foreach(Tag::$tipos as $value){
				if(array_key_exists($value, $tagTipos)){
					$this->output[$value]= $tagTipos[$value];
				}
			}*/

			if (_app::isLogged()) {
				$logged_user = _app::getUser();
				if ($user->getIdUsuario() == $logged_user->getIdUsuario()) {
					$this->output['can_edit'] = true;
				}
			}
			$this->output['link_return'] = '/welcome';
			$this->_datos_genericos();
			$this->_render('galeria');
		}


		function imagen($id = '',$img = ''){
			if (empty($id)) {
				echo 'no puedo pintar nada';
				exit;
			}

			$UserTable = new UserTable();
			$user = $UserTable->getById(intval($id));
			if ($user === null) {
				echo 'el usuario no existe';
				exit;
			}

			$ImageTable = new ImageTable();
			$image = $ImageTable->getById($img);
			if ($image === null) {
				echo 'la imagen no existe';
				exit;
			}

			$this->output['user'] = $user->toArray();
			$this->output['image'] = $image->toArray();

			$this->output['link_return'] = '/galeria/'.$user->getIdUsuario();
			
			$TagTable = new TagTable();
			$tags = $TagTable->getTagsFromImage($image);
			$nameTags=[];
			foreach ($tags as $tag) {
				$tagArray = $tag->toArray();
				$nameTags[]= $tagArray;
			}
			//var_dump($nameTags);
			$this->output['tiene_tags'] = !empty($nameTags);
			$this->output['tags'] = $nameTags;

			if (!empty($_POST['command'])) {
				switch ($_POST['command']) {
					case 'etiqueta':
						$nombre = $_POST['nombre'];
						$nombre = trim($nombre);
						if (empty($nombre)) {
							echo 'nombre invalido';
							exit;
						}
						$tipo = $_POST['tipo'];
						if (!in_array($tipo,Tag::$tipos)){
							echo "intruso";
							//eliminar cookie y echarlo a registro
							exit;
						}
						$tag = new Tag();
						$intid = intval($id);
						$tag->setIdUsuario($intid);
						$tag->setNombre($nombre);
						$tag->setTipo($tipo);

						$idetiqueta = $TagTable->existTag($tag);
						if ($idetiqueta === null) {
							$idetiqueta = $TagTable->insert($tag);
							if ($idetiqueta === null) {
								echo 'error';
								exit;
							}
							$tag->setIdEtiqueta($idetiqueta);
						} else {
							$tag->setIdEtiqueta($idetiqueta);
						}

						$TagTable->insertTagImage($tag,$image);
						$this->_render_common_r('/imagen/'.$id.'/'.$img);
						break;
					case 'borrar':
						foreach ($_POST['seleccion'] as $idtag) {
							$tag = $TagTable->getById($idtag);
							if ($tag === null) {
								continue;
							}
							$TagTable->removeTagImage($tag,$image);
						}
						$this->_render_common_r('/imagen/'.$id.'/'.$img);
						break;
					case 'renombrar':
						$nombre = $_POST['nombreImagen'];
						if (empty($nombre)) {
							echo 'nombre invalido';
							exit;
						}
						$image = new Image();
						$image->setIdImagen($img);//mirar
						$ImageTable->updateName($image,$nombre);
						$this->_render_common_r('/imagen/'.$id.'/'.$img);
						break;

				}
			}


			if (_app::isLogged()) {
				$logged_user = _app::getUser();
				if ($user->getIdUsuario() == $logged_user->getIdUsuario()) {
					$this->output['can_edit'] = true;
				}
			}
			$this->output['domain'] = $_SERVER['SERVER_NAME'];
			$this->_datos_genericos();
			$this->_render('imagen');
		}

		function registro(){
			if(!empty($_POST)){
				$nombre = $_POST['nombre'];
				$userTable = new UserTable();
				$currentUser = $userTable->getByName($nombre);
				if (!empty($currentUser)) {
					$this->output['nombreInvalido']=$nombre;
					$this->output['hide_search'] = true;
					return $this->_render('registro');
				}
				$password = $_POST['password'];
				$email = $_POST['email'];
				$newUser = new User();
				$newUser->setPassword($password);
				$newUser->setEmail($email);
				$newUser->setNombre($nombre);
				$userTable = new UserTable();
				$userTable->insert($newUser);
				$this->_render_common_r('/welcome');
			}

			$this->output['hide_search'] = true;
			$this->_render('registro');
		}

		function login(){
			if (_app::isLogged()) {
				$this->_render_common_r('/welcome');
			}
			if(!empty($_POST)){
				$nombre = $_POST['nombre'];
				$password = $_POST['password'];
				$userTable = new UserTable();
				$currentUser = $userTable->getByName($nombre);
				if (empty($currentUser)) {
					return $this->_render('login.invalid');
				}
				if ($userTable->userMatches($currentUser, $password)){
					setcookie('user',$nombre,time() + 360000,'/');
					if ($nombre==='admin'){
						return $this->_render_common_r('/admin/usuarios');
					}
					return $this->_render_common_r('/welcome');
				}
				return $this->_render('login.invalid');
			}

			$this->output['hide_search'] = true;
			$this->_render('login');
		}

		function subir() {
			if (!_app::isLogged()) {
			 	$this->_render_common_r('/login');
			}
			$user = _app::getUser();
			if(!empty($_FILES['imagen'])){
				$errors = [];
				$path = _app::getImageFolder($user->getIdUsuario());
				$extensions = ['jpg', 'jpeg', 'png', 'gif'];

				$fileName = $_FILES['imagen']['name'];
				$fileTmp = $_FILES['imagen']['tmp_name'];
				$fileType = $_FILES['imagen']['type'];
				$fileSize = $_FILES['imagen']['size'];
				$fileType= str_replace('image/', '', $fileType);
				$nameHash = uniqid();
				$file = $path . $nameHash;
				if (!in_array($fileType, $extensions)) {
					$errors[] =  $fileName . ' ' . $fileType . " No es una imagen.";
				}
				if ($fileSize > (2 * 1024 * 1024)){
					$errors[] =  $fileName . ' ' . $fileType . " Excede el tamaño máximo.";
				}
				if (empty($errors)) {
					move_uploaded_file($fileTmp, $file);
					//subir a bbdd.
					$nombre = $_POST['nombre'];
					$nombre = trim($nombre);
					if (!empty($nombre)) {
						$fileName=$nombre;
					}
					$imageTable = new ImageTable();
					$image = new Image();
					$image->setIdImagen($nameHash);
					$image->setNombre($fileName);
					$image->setExtension($fileType);
					$image->setIdusuario($user->getIdUsuario());
					$imageTable->insert($image);
				}
				return $this->_render_common_r('/subir?correcto=1');
			}

			if (!empty($_GET['correcto'])) {
				$this->output['imagen_subida'] = true;
				$this->output['link_galeria'] = '/galeria/'.$user->getIdUsuario();
			}

			$this->output['link_return'] = '/welcome';
			$this->_datos_genericos();
			return $this->_render('subir');
		}

		function search(){
			if (!_app::isLogged()) {
				$this->_render_common_r('/login');
			}
			$user = _app::getUser();

			$ImageTable = new ImageTable();
			if (!empty($_GET['search'])) {
				$images = $ImageTable->search($user,$_GET['search']);

				$arrimages = [];
				foreach ($images as $image) {
					$img = $image->toArray();
					//$img['idusuario'] = $user->getIdUsuario();
					$arrimages[] = $img;
				}

				$this->output['images'] = $arrimages;
			}

			$this->_datos_genericos();
			$this->_render('galeria');
		}

		function ver($id = '',$img = ''){
			$path = _app::getImageFolder($id).$img;
			if (!file_exists($path)) {
				exit;
			}
			$prop = getimagesize($path);
			header('Content-Type: '.$prop['mime']);
			readfile($path);
			exit;
		}


		function tag($id = ''){
			if (empty($id)){
				exit;
			}
			$TagTable = new TagTable();
			$tag = $TagTable->getById($id);
			if ($tag === null) {
				exit;
			}

			$ImageTable = new ImageTable();
			$images = $ImageTable->getImagesFromTag($tag);

			$arrimages = [];
			foreach ($images as $image) {
				$img = $image->toArray();
				$img['idusuario'] = $tag->getIdUsuario();
				$arrimages[] = $img;
			}
			$this->output['link_return'] = '/galeria/'.$tag->getIdUsuario();
			$this->output['images'] = $arrimages;
			$this->output['tag'] = $tag->toArray();
			$this->_datos_genericos();
			$this->_render('galeria');
			
		}

		function logout(){
			if(isset($_COOKIE['user'])){
				setcookie('user','',time()-7000000,'/');
			}
			header("location: /");
			$this->_render('logout');
		}
		
	}
