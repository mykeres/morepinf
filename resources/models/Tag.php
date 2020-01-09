<?php
	class TagTable extends DataBase{
		function insert(Tag $tag){
			$mysqli = $this->conn();
			$stmt = $mysqli->prepare("INSERT INTO `etiqueta` (`nombre`, `tipo`) VALUES (?,?)");
			$tagNombre=$tag->getNombre();
			$tagTipo = $tag->getTipo();
			$stmt->bind_param("ss", $tagNombre, $tagTipo);
			$stmt->execute();
		}

		public function getTagsFromClass($tipo): array{
			$mysqli = $this->conn();
		    $stmt = $mysqli->prepare('SELECT * FROM etiqueta WHERE etiqueta.tipo=?');
		    $stmt->bind_param("s",$tipo);
		    $stmt->execute();
		    $result = $stmt->get_result();
		    $tags = [];
		    while (($obj = $result->fetch_array(MYSQLI_ASSOC)) !== null) {
		        $tags[] = $this->__assign($obj);
		    }
		    return $tags;
		    //MIRAR
		}
		public function getTagsFromImage(Image $image): array{
			$mysqli = $this->conn();
		    $stmt = $mysqli->prepare('SELECT * FROM etiqueta INNER JOIN etiqueta_imagen ON etiqueta.idetiqueta=etiqueta_imagen.idetiqueta) WHERE imagen.idimagen= ?');
		    $idimage = $image->getIdImagen();
		    $stmt->bind_param("s",$idimage);
			$stmt->execute();
			$result = $stmt->get_result();
			$tags = [];
			while (($obj = $result->fetch_array(MYSQLI_ASSOC)) !== null) {
			    $tags[] = $this->__assign($obj);
			}
			return $tags;
		    ///TODO  
		}
		public function getTagsFromUser(User $user): array{
		    $mysqli = $this->conn();
		    $stmt = $mysqli->prepare('SELECT nombre FROM etiqueta INNER JOIN usuario ON etiqueta.idusuario=usuario.idusuario WHERE usuario.idusuario= ?');
		    $idusuario = $user->getIdusuario();		    
var_dump($idusuario);
			//if($stmt ===false){ return false;}
		    $stmt->bind_param("i",$idusuario);
		    $stmt->execute();
		    $result = $stmt->get_result();
		    $obj = $result->fetch_array();
		    return $obj['etiqueta.nombre'];
		}

		function __assign($array): Tag{
		    $tag = new Tag();
		    $tag->setName($array['nombre']);
		    $tag->setPassword($array['tipo']);
		    return $tag;
		}
		public function tagExists(Tag $tag): bool{
		    $name = $user->getNombre();
		    return boolval($this->getByName($name));
		}


	}

	class Tag{

		private $nombre;
		private $tipo;

		public function getNombre(){
			return $this->nombre;
		}
		public function setNombre($nombre){
			$this->nombre = $nombre;
		}
		public function getTipo(){
			return $this->tipo;
		}
		public function setTipo($tipo='tema'){
			$this->tipo = $tipo;
		}

	}