<?php
	class News {
		private $id;
		private $id_usuario;
		private $titulo;
		private $bajada;
		private $cuerpo;
		private $fecha_publicacion;
		private $path_imagen;
		private $path_audio;
		private $borrador;
		private $autores = array();
		private $authorCad;
 
		function __construct(){}
 
		public function getId(){
			return $this->id;
		}

		public function setId($id){
			$this->id = $id;
		}
		
		public function getId_usuario(){
    		return $this->id_usuario;
    	}

    	public function setId_usuario($id_usuario){
    		$this->id_usuario = $id_usuario;
    	}
    
    	public function getTitulo(){
    		return $this->titulo;
    	}
    
    	public function setTitulo($titulo){
    		$this->titulo = $titulo;
    	}
    
    	public function getBajada(){
    		return $this->bajada;
    	}
    
    	public function setBajada($bajada){
    		$this->bajada = $bajada;
    	}
    	
    	public function getCuerpo(){
    		return $this->cuerpo;
    	}
    
    	public function setCuerpo($cuerpo){
    		$this->cuerpo = $cuerpo;
    	}
    
    	public function getFecha_publicacion(){
    		return $this->fecha_publicacion;
    	}
    
    	public function setFecha_publicacion($fecha_publicacion){
    		$this->fecha_publicacion = $fecha_publicacion;
    	}
    
    	public function getPath_imagen(){
    		return $this->path_imagen;
    	}
    
    	public function setPath_imagen($path_imagen){
    		$this->path_imagen = $path_imagen;
    	}
    
    	public function getPath_audio(){
    		return $this->path_audio;
    	}
    
    	public function setPath_audio($path_audio){
    		$this->path_audio = $path_audio;
    	}
    
    	public function getBorrador(){
    		return $this->borrador;
    	}
    
    	public function setBorrador($borrador){
    		$this->borrador = $borrador;
    	}
    	
    	public function getFrontEndPublishDate() {
            $dtField = $this->fecha_publicacion;
            
            if($dtField) {
                $dtArray = explode(" ", $dtField);
                $dt = $dtArray[0];
                $dtArray = explode("-",$dt);
                
                $day = $dtArray[2];
                $month = $dtArray[1];
                $year = $dtArray[0];
            
                return $month."/".$day."/".$year;
            }
            
            return null;
    	}
    	
    	public function setAutores($autores) {
    	    $this->autores = $autores;
    	}
    	
    	public function getAutores() {
    	    return $this->autores;
    	}
    	
    	public function setAuthorCad($autores) {
    	    $this->authorCad = $autores;
    	}
    	
    	public function getAuthorCad() {
    	    return $this->authorCad;
    	}
	}
?>