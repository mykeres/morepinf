<?php
	class _controller_index extends _controller  {
		function main(...$params){
			if (!_app::isLogged()) {
				$this->_render_common_r('/login');
			}
			$this->login();
			$this->_render('login');
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

			$ImageTable = new ImageTable();
			$images = $ImageTable->getImagesFromUser($user);

			$arrimages = [];
			foreach ($images as $image) {
				$img = $image->toArray();
				$img['idusuario'] = $user->getIdUsuario();
				$arrimages[] = $img;
			}

			$this->output['images'] = $arrimages;

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
			$this->output['tags'] = $nameTags;

			if(!empty($_POST)){

				$nombre = $_POST['nombre'];
				$nombre = trim($nombre);
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
				if (!$TagTable->existTag($tag) && !empty($nombre)){
					$TagTable->insertTagImage($tag,$image);
					$this->_render_common_r('/imagen/'.$id.'/'.$img);
				}
			}


			if (_app::isLogged()) {
				$logged_user = _app::getUser();
				if ($user->getIdUsuario() == $logged_user->getIdUsuario()) {
					$this->output['can_edit'] = true;
				}
			}

			$this->output['domain'] = $_SERVER['SERVER_NAME'];
			$this->_render('imagen');
		}

		


		function registro(){
			if(!empty($_POST)){
				$nombre = $_POST['nombre'];
				$userTable = new UserTable();
				$currentUser = $userTable->getByName($nombre);
				if (!empty($currentUser)) {
					$this->output['nombreInvalido']=$nombre;
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
				$this->_render_common_r('welcome');
			}

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
					return $this->_render_common_r('/welcome');
				}
				return $this->_render('login.invalid');
			}

			$this->_render('login');
		}

		function subir() {
			if (!_app::isLogged()) {
			 	$this->_render_common_r('/login');
			}
			$user = _app::getUser();
			if(!empty($_FILES)){
				if (isset($_FILES['imagen'])) {
        			$errors = [];
			        $path = '../imageUpload/'.$user->getIdUsuario().'/';
			        if (!file_exists($path)){
			        	mkdir ($path);
			        }
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
						$imageTable = new ImageTable();
						$image = new Image();
						$image->setIdImagen($nameHash);
						$image->setNombre($fileName);
						$image->setExtension($fileType);
						$image->setIdusuario($user->getIdUsuario());
						echo '</br>';
						var_dump($image);
						echo '</br>';
						$imageTable->insert($image);
					}
					if ($errors) print_r($errors);
				}
			}

			$this->output['link_return'] = '/welcome';
			return $this->_render('subir');
		}

		function ver($id = '',$img = ''){
			$path = '../imageUpload/'.$id.'/'.$img;
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

			$this->output['images'] = $arrimages;
			$this->output['tag'] = $tag->toArray();

			$this->_render('galeria');
			
		}
		
	}
