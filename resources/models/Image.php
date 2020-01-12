<?php

	class ImageTable extends DataBase{
		function insert(Image $image){
			$mysqli = $this->conn();
			$stmt = $mysqli->prepare("INSERT INTO `imagen` (`idimagen`,`fecha`, `nombre`, `camara`, `idusuario`, `extension`) VALUES (?,?,?,?,?,?)");
			$image->readMetadata();
			$idImagen = $image->getIdImagen();
			$fecha = $image->getFecha();
			$nombre = $image->getNombre();
			$camara = $image->getCamara();
			$idUsuario = $image->getIdUsuario();
			$extension = $image->getExtension();
			$stmt->bind_param("ssssis", $idImagen, $fecha, $nombre, $camara, $idUsuario, $extension);
			$stmt->execute();
			//var_dump($stmt->execute());

		}

		function getById(string $idimagen){
			$mysqli = $this->conn();
			$stmt = $mysqli->prepare('SELECT * FROM imagen WHERE imagen.idimagen=? LIMIT 1');
			$stmt->bind_param("s",$idimagen);
			$stmt->execute();
			$result = $stmt->get_result();
			$obj = $result->fetch_array();
			if (empty($obj)) {
				return null;
			}
			return $this->__assign($obj);
		}
		
		function getImagesFromUser(User $user, array $params= null):array{
			if (!isset($params['count'])){
				$params['count'] = 0;
			}
			if (!isset($params['page'])){
				$params['page'] = 0;
			}
			$first = ($params['page'] * $params['count']);
			$count = $params['count'];

			$idusuario = $user->getIdUsuario();

			$mysqli = $this->conn();
			// LIMIT ? OFFSET ?
			$stmt = $mysqli->prepare('SELECT * FROM `imagen` WHERE idusuario = ?');
			$stmt->bind_param("i", $idusuario);
			$stmt->execute();
			$result = $stmt->get_result();

			$images = [];
			while (($obj = $result->fetch_array(MYSQLI_ASSOC)) !== null) {
				$images[] = $this->__assign($obj);
			}
			return $images;
		}
		function getImagesFromTag(Tag $tag, array $params= null):array{
			if (!isset($params['count'])){
				$params['count'] = 0;
			}
			if (!isset($params['page'])){
				$params['page'] = 0;
			}
			$first = ($params['page'] * $params['count']);
			$count = $params['count'];

			$idusuario = $tag->getIdUsuario();
			$idetiqueta = $tag->getIdEtiqueta();

			$mysqli = $this->conn();
			// LIMIT ? OFFSET ?
			$stmt = $mysqli->prepare('SELECT * FROM `imagen` WHERE idusuario = ? AND imagen.idimagen IN (SELECT etiqueta_imagen.idimagen FROM etiqueta_imagen WHERE etiqueta_imagen.idetiqueta = ?)');
			$stmt->bind_param("ii", $idusuario,$idetiqueta);
			$stmt->execute();
			$result = $stmt->get_result();

			$images = [];
			while (($obj = $result->fetch_array(MYSQLI_ASSOC)) !== null) {
				$images[] = $this->__assign($obj);
			}
			return $images;
		}
		function __assign($array): Image{
			$image = new Image();
			$image->setIdImagen($array['idimagen']);
			$image->setNombre($array['nombre']);
			$image->setExtension($array['extension']);
			$image->setCamara($array['camara']);
			$image->setFecha($array['fecha']);
			$image->setIdUsuario($array['idusuario']);
			return $image;
		}
		function countImagesFromUser(User $user): int{
			$mysqli = $this->conn();
			$stmt = $mysqli->prepare('SELECT * FROM `imagen` WHERE `idusuario` = (SELECT `idusuario` FROM `usuario` WHERE `nombre`=? LIMIT 1)');
			$username = $user->getNombre();
			$stmt->bind_param("s",$username);
			$stmt->execute();
			$stmt->store_result();
			$numRows = $stmt->num_rows;
			return $numRows;
		}
	}

	class Image{

		private $idImagen;
		private $nombre;
		private $extension;
		private $camara;
		private $fecha;
		private $idUsuario;

		function __construct(){

		}
		public function getIdImagen()
		{
		    return $this->idImagen;
		}

		public function setIdImagen($idImagen)
		{
		    $this->idImagen = $idImagen;
		    return $this;
		}

	
		public function getNombre()
		{
		    return $this->nombre;
		}

		
		public function setNombre($nombre)
		{
		    $this->nombre = $nombre;
		    return $this;
		}

		public function getExtension(){
			return $this->extension;
		}

		public function setExtension($extension){
			$this->extension = $extension;
			return $this;
		}

	
		public function getCamara()
		{
		    return $this->camara;
		}

	
		public function setCamara($camara)
		{
		    $this->camara = $camara;
		    return $this;
		}

	
		public function getFecha()
		{
		    return $this->fecha;
		}
	
		public function setFecha($fecha)
		{
		    $this->fecha = $fecha;
		    return $this;
		}

		public function getIdUsuario()
		{
		    return $this->idUsuario;
		}

		public function setIdUsuario($idUsuario)
		{
		    $this->idUsuario = $idUsuario;
		    return $this;
		}	

		function readMetadata(){
			// esta funcion solo se puede utilizar una vez que se ha subido la foto
			$path = '../imageUpload/'.$this->idUsuario.'/'.$this->idImagen;
			$camMake = "No disponible.";
			$camModel = "";
			$camDate = "No disponible.";
			if (exif_read_data($path,'IFD0')) {
			    $exif_ifd0 = exif_read_data($path,0,true);
			     
			    if (isset($exif_ifd0['Make'])) {
			    	$camMake = $exif_ifd0['Make'];
			    } 
			     
			    if (isset($exif_ifd0['Model'])) {
			        $camModel = $exif_ifd0['Model'];
			    } 
			     
			    if (isset($exif_ifd0['DateTime'])) {
			        $camDate = $exif_ifd0['DateTime'];
			    } 
			}
			$this->camara = $camMake." ".$camModel;
			$this->fecha = $camDate; 
		}
	
		function toArray(){
			return [
				'idimagen'=>$this->idImagen,
				'nombre'=>$this->nombre
			];
		}
	}
