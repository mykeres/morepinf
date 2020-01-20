<?php
	class _controller_admin extends _controller{
		function main(...$params){
			
		}
		function _datos_genericos(){
			if (_app::isLogged()) {
				$user = _app::getUser();
				$this->output['connected_user'] = $user->toArray();
			}
		}
		function usuarios(){
			if (!_app::isLogged()) {
				$this->_render_common_r('/login');
			}
			$user = _app::getUser();
			if ($user->getNombre()!=='admin'){
				$this->_render_common_r('/welcome');
			}
			$userTable = new UserTable();
			$allUsers = $userTable->getAllNames();
			
			$allUsersArray = [];
			foreach ($allUsers as $user){
				$allUsersArray[] = $user->toArray(); 
			}
			if(!empty($_POST)){
				$idUsuarios = $_POST['seleccion'];
				$idUsuarios = array_map ('intval',$idUsuarios);
				//var_dump($idUsuarios);
				$viewUsers=$userTable->deleteMultipleById($idUsuarios);
				//var_dump($viewUsers);
				//seleccion0=idusuario,1=idusuario,2 
			}

			$this->output['borrados'] = $allUsersArray;
			$this->_datos_genericos();
			$this->_render('admin/usuarios');
		}
	}
