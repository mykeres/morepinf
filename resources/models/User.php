<?php
class UserTable extends DataBase{
    public function __construct(){
        parent::__construct();
    }
	function getByName(string $nombre): ?User{
		$mysqli = $this->conn();
		$stmt = $mysqli->prepare("SELECT * FROM usuario WHERE usuario.nombre=? LIMIT 1");
		$stmt->bind_param("s", $nombre);
		$stmt->execute();
		$result = $stmt->get_result();
		$obj = $result->fetch_array();
		if (empty($obj)) {
			return null;
		}
    	return $this->__assign($obj);
	}

	function getById(int $idusuario){
		$mysqli = $this->conn();
		$stmt = $mysqli->prepare('SELECT * FROM usuario WHERE usuario.idusuario=? LIMIT 1');
		$stmt->bind_param("i",$idusuario);
		$stmt->execute();
		$result = $stmt->get_result();
		$obj = $result->fetch_array();
		if (empty($obj)) {
			return null;
		}
        return $this->__assign($obj);
	}

	function getAllNames(): array{
		$mysqli = $this->conn();
		$stmt = $mysqli->prepare("SELECT * FROM usuario");
		$stmt->execute();
		$result = $stmt->get_result();

		$users = array();
		while ($obj = $result->fetch_array()) {
            $user = $this->__assign($obj);
			//$user = new User();
			//$user->setNombre($obj['nombre']);
            
			$users[] = $user;
		}
		return $users;
	}

    function __assign($array): User{
        $user = new User();
        $user->setNombre($array['nombre']);
        $user->setPassword($array['password']);
        $user->setIdusuario($array['idusuario']);
        $user->setEmail($array['email']);
        return $user;
    }

	function insert(User $user){
		$mysqli = $this->conn();
		$stmt = $mysqli->prepare("INSERT INTO `usuario` (`nombre`, `password`,`email`) VALUES (?,?,?)");
        $hash = sha1($user->getPassword());
        $userNombre = $user->getNombre();
        $userEmail = $user->getEmail();
		$stmt->bind_param("sss", $userNombre, $hash , $userEmail);
		$stmt->execute();
	}

    function deleteByName(string $name){
        $mysqli = $this->conn();
        $stmt = $mysqli->prepare("DELETE FROM `usuario` WHERE `nombre`= ?");
        $stmt->bind_param("s", $name);
        $stmt->execute();
        // comprobacion de existencia        
    }
	function deleteMultipleById(array $users){
		$mysqli = $this->conn();
		$str = implode(",",$users);
		$query = "DELETE FROM `usuario` WHERE idusuario in (".$str.")";
		$stmt = $mysqli->query($query);

		$ImageTable = new ImageTable();
		$ImageTable->removeByUsers($users);


		return true;
	}
    public function userExists(User $user): bool{
        $name = $user->getNombre();
        return boolval($this->getByName($name));
    }

    public function userMatches(User $user, string $pass): bool{
        return ($user->getPassword() === sha1($pass));
    }
    function removeAllImageFilesByUser(User $user){
			if(!$this->userExists($user)){
				return;
			}

			$mask= 'db/imageUpload/'.$idUsuario.'/*';
			array_map('unlink',glob($mask));
		}
    }

    
    

class User{
    private $idusuario;
	private $nombre;
	private $password;
	private $email;
    public function __construct(){
    }
    public function getIdusuario(){
        return $this->idusuario;
    }

	public function getNombre(){
		return $this->nombre;
	}
	public function getPassword(){
		return $this->password;
	}
	public function getEmail(){
		return $this->email;
	}

    public function setIdUsuario($idusuario){
        $this->idusuario = $idusuario;
    }
	public function setNombre($nombre){
		$this->nombre = $nombre;
	}

    public function setPassword($password){
        $this->password = $password;
    }

    public function setEmail($email){
        $this->email = $email;
    }
    public function eraseImages(){

    }

	public function toArray(){
		return [
			'idusuario'=>$this->idusuario,
			'nombre'=>$this->nombre,
			'email'=>$this->email,
			'password'=>$this->password
		];
	}
}

