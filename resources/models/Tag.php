<?php
	class TagTable extends DataBase{
		function insert(Tag $tag){
			$mysqli = $this->conn();
			$stmt = $mysqli->prepare("INSERT INTO etiqueta (nombre, tipo, idusuario) VALUES (?,?,?)");
			$tagNombre=$tag->getNombre();
//echo '----------------->'.$tagNombre.'<br/>';

			$tagTipo = $tag->getTipo();
//echo '----------------->'.$tagTipo.'<br/>';
			$tagIdUsuario = $tag->getIdUsuario();
			$stmt->bind_param("ssi", $tagNombre, $tagTipo, $tagIdusuario);
			$stmt->execute();
		}

		function insertTagImage(Tag $tag, Image $image){
			$this->insert($tag);
			$stmt = $mysqli->prepare("INSERT INTO etiqueta_imagen (idetiqueta, idimagen) VALUES (?,?)");
			$idetiqueta = $tag->getIdEtiqueta();
			$idimagen = $image->getIdImagen();
			$stmt->bind_param("is", $idetiqueta,$idimagen);
			$stmt->execute();
		}

		function existTag(Tag $tag): bool{
			$stmt = $mysqli->prepare("SELECT * from etiqueta WHERE idusuario=? AND nombre= ? LIMIT 1");
			$idusuario = $tag->getIdusuario();
			$nombre->$tag->getNombre();
			$stmt->bind_param("is",$idusuario,$nombre);
			$stmt->execute();
			return boolval($stmt->get_result());

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
		    $stmt = $mysqli->prepare('SELECT * FROM etiqueta INNER JOIN etiqueta_imagen ON etiqueta.idetiqueta=etiqueta_imagen.idetiqueta WHERE etiqueta_imagen.idimagen= ?');
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
		public function getTagsFromUser(User $user): ?array{
		    $mysqli = $this->conn();
		    $stmt = $mysqli->prepare('SELECT * FROM etiqueta WHERE etiqueta.idusuario= ?');
		    $idusuario = $user->getIdusuario();		    
//var_dump($idusuario);
			//if($stmt ===false){ return false;}
		    $stmt->bind_param("i",$idusuario);
		    $stmt->execute();
		    $result = $stmt->get_result();
    		$tags = array();
    		while ($obj = $result->fetch_array()) {
                $tag = $this->__assign($obj);
    			$tags[] = $tag;
    		}
    		return $tags;
		}
		function getById(int $idetiqueta){
			$mysqli = $this->conn();
			$stmt = $mysqli->prepare('SELECT * FROM etiqueta WHERE etiqueta.idetiqueta=? LIMIT 1');
			$stmt->bind_param("i",$idetiqueta);
			$stmt->execute();
			$result = $stmt->get_result();
			$obj = $result->fetch_array();
			if (empty($obj)) {
				return null;
			}
	        return $this->__assign($obj);
		}
		function __assign($array): Tag{
		    $tag = new Tag();
		    $tag->setIdEtiqueta($array['idetiqueta']);
		    $tag->setNombre($array['nombre']);
		    $tag->setTipo($array['tipo']);
		    $tag->setIdUsuario($array['idusuario']);
		    return $tag;
		}
		public function tagExists(Tag $tag): bool{
		    $name = $user->getNombre();
		    return boolval($this->getByName($name));
		    ///mirar
		}


	}

	class Tag{

		private $idetiqueta;
		private $nombre;
		private $tipo;
		private $idusuario;

		static $tipos = ['evento','lugar','persona','grupo','tema'];

		public function getIdEtiqueta(){
			return $this->idetiqueta;
		}
		public function setIdEtiqueta($idetiqueta){
			$this->idetiqueta = $idetiqueta;
		}

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
		public function getIdUsuario(){
			return $this->idusuario;
		}
		public function setIdUsuario($idusuario){
			$this->idusuario= $idusuario;
		}

		function toArray(){
			return [
				'nombre'=>$this->nombre,
				'tipo'=>$this->tipo,
				'idetiqueta'=>$this->idetiqueta
			];	
		}

	}
