<?php
	class _controller_index extends _controller{
		function main(...$params){
			$this->_render('main');
		}
		function registro(){
			if(!empty($_POST)){
				print_r($_POST);
				$nombre = $_POST['nombre'];
				$userTable = new UserTable();
				$currentUser = $userTable->getByName($nombre);
				if (!empty($currentUser)) {
					echo $nombre." esta pillado escoge otro";
					return $this->_render('login.invalid');
					exit;
				}
				$password = $_POST['password'];
				$email = $_POST['email'];
				$newUser = new User();
				$newUser->setPassword($password);
				$newUser->setEmail($email);
				$newUser->setNombre($nombre);
				$userTable = new UserTable();
				$userTable->insert($newUser);

				// probaturas
				echo "<h1>prueba</h1>";
				$getbyname=$userTable->getByName($nombre);
				print_r($getbyname);
				exit;
			}
			$this->_render('registro');
			// echo 'hola';
			// exit;
		}
		function wall(){
			if (!_app::isLogged()) {
				$this->login();// enviamos al login
				exit;
			}
			if(!empty($_FILES['imagen'])){
				$tipo=$_FILES["imagen"]["type"];
				if($tipo=="image/jpeg"){
					$exif = exif_read_data($_FILES['imagen']['tmp_name']);
					print_r($exif);
				}
				exit;
			}
			$this->_render('wall');
		}

		function login(){
			// echo "llego aqui";
			if (_app::isLogged()) {
				echo "is loged";
				$user = _app::getUser();
				$this->output['text'] = 'Buenos días';
				$this->output['text2'] = [
							'pos1'=>'valor'
						];
				$this->output['debo_pintar_mensaje'] = false;
				$this->output['user'] = $user;
				return $this->_render('welcome');
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
					return $this->_render('welcome');
				}
				return $this->_render('login.invalid');
			}

			$this->_render('login');
		}

		function formulario(){
//			echo "llego aqui:welcome</br>";
			if (!_app::isLogged()) {
			 	echo "llego aqui:logeo";
				return $this->render('registro');
			}
			$user = _app::getUser();
			if(!empty($_FILES)){
print_r($_POST);
print_r($_FILES);
				if (isset($_FILES['imagen'])) {
        			$errors = [];
			        $path = '../imageUpload/'.$user->getIdusuario().'/';
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
						echo '</br>';
						var_dump($imageTable);
						echo '</br>';
						$image = new Image();
						$image->setIdImagen($nameHash);
						$image->setNombre($fileName);
						$image->setExtension($fileType);
						$image->setIdusuario($user->getIdusuario());
						echo '</br>';
						var_dump($image);
						echo '</br>';
						$imageTable->insert($image);
					}
					if ($errors) print_r($errors);
				}
			}
			return $this->_render('form.imagen');
		}

        function ver($id = '',$img = ''){
            $path = '../imageUpload/'.$id.'/'.$img;
            if (!file_exists($path)) {
                exit;
            }
            $prop = getimagesize($path);
		    header('Content-Type: '.$prop['mime']);
		    readfile($path);exit;
        }


}
