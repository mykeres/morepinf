<?php
	ini_set('display_errors', 1);
	error_reporting(E_ALL);

	spl_autoload_register(function ($name) {
		switch ($name) {
			case 'User':
			case 'UserTable':
			case 'UserOld':                          	include('models/User.php');break;
			case 'Image':
			case 'ImageTable':							include('models/Image.php');break;
			case 'Tag':
			case 'TagTable':							include('models/Tag.php');break;
			case 'DataBase':                          	include('models/db.php');break;
		}
	});


	class _app{
		public static $folder_images = 'db/imageUpload/';
		private static $user = null;
		public static function getUser(){
			return self::$user;
		}
		public static function isLogged(): bool{
                        if(!empty(self::$user)){
                                return true;
                        }
			if (empty($_COOKIE['user'])){
                                return false;
                        }
                        $userTable = new UserTable();
                        $user = $userTable->getByName($_COOKIE['user']);
                        if(empty($user)){
                                return false;
                        }
                        self::$user = $user; //cacheamos 
                        return true;
		}
		public static function getImageFolder(string $idusuario){
			$path = self::$folder_images.$idusuario.'/';
			if (!file_exists($path)){
				mkdir ($path);
			}
			return $path;
		}
	}
