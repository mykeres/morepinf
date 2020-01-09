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

    public function getId(User $user){
        $mysqli = $this->conn();
        $stmt = $mysqli->prepare('SELECT `idusuario` FROM `usuario` WHERE `nombre`=? LIMIT 1');
        $stmt->bindParam("s",$user->getName());
        $stmt->execute();
        $result = $stmt->get_result();

        $obj = $result->fetch_array();
        print_r($obj);
        return $obj;
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
		$stmt->bind_param("sss", $user->getNombre(), $hash , $user->getEmail());
		$stmt->execute();

		//FIXME: comprobar
	}

    function deleteByName(string $name){
        $mysqli = $this->conn();
        $stmt = $mysqli->prepare("DELETE FROM `usuario` WHERE `nombre`= ?");
        $stmt->bind_param("s", $name);
        $stmt->execute();
        // comprobacion de existencia        
    }

    public function userExists(User $user): bool{
        $name = $user->getNombre();
        return boolval($this->getByName($name));
    }

    public function userMatches(User $user, string $pass): bool{
        return ($user->getPassword() === sha1($pass));
    }
    
    
    
}

class User{
    private $idusuario;
	private $nombre;
	private $password;
	private $email;
    public function __construct(){
       //echo "construyo user"; 
       // sleep(3);
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

    public function setIdusuario($idusuario){
        $this->idusuario = $idusuario;
    }
	public function setNombre($nombre){
		$this->nombre = $nombre;
	}

    public function setPassword($password){
        $this->password = $password;
        //$hash = password_hash($this->password, PASSWORD_BCRYPT);
        //$this->password = $hash;
    }

    public function setEmail($email){
        $this->email = $email;
    }
}

class UserOld extends DataBase{
/*
    private $nombre;
    private $password;
    private $email;

    public function __construct($nombre){
        parent::__construct();
        $this->nombre = $nombre;
    }

    public function getNombre(){
        return $this->nombre;
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

  */  

    /*public function insertUser(): User{
        $hash = password_hash($this->password, PASSWORD_BCRYPT);
        $query = $this->conectar()->prepare('INSERT INTO `usuario` (`nombre`, `password`,`email`) VALUES (?,?,?)');
        $query->bindParam(1,$this->nombre,PDO::PARAM_STR);
        $query->bindParam(2,$hash,PDO::PARAM_STR);
        $query->bindParam(3,$this->email,PDO::PARAM_STR);

        $query->execute();
        return $this;
        //$query->execute([$nombre,  $hash]);
    }*/

    /*public function deleteUser(){
        $query = $this->conectar()->prepare('DELETE FROM `usuario` WHERE `nombre`= :nombre');
        $query->bindParam(':nombre',$this->nombre,PDO::PARAM_STR);
        $query->execute();
    }*/
    
    

    
    /*$mysqli = $this->conn();
	$stmt = $mysqli->prepare("SELECT usuario.password FROM usuario WHERE usuario.nombre=? LIMIT 1");
	$stmt->bind_param("s", $this->nombre);

	$stmt->execute();
	$result = $stmt->get_result();

	$obj = $result->fetch_array();
    */

    

    
}