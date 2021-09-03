<?php
	class Programa {
		private $id;
		private $nombre;
		private $descripcion;
		private $id_imagen;
		private $link_vivo;
 
		function __construct(){}
 
		public function getNombre(){
			return $this->nombre;
		}
 
		public function setNombre($nombre){
			$this->nombre = $nombre;
		}
 
		public function getDescripcion(){
			return $this->descripcion;
		}
 
		public function setDescripcion($descripcion){
			$this->descripcion = $descripcion;
		}
 
		public function getIdImagen(){
			return $this->id_imagen;
		}
 
		public function setIdImagen($id_imagen){
			$this->id_imagen = $id_imagen;
		}
		public function getId(){
			return $this->id;
		}
 
		public function setId($id){
			$this->id = $id;
		}

		public function getLinkVivo(){
			return $this->link_vivo;
		}
 
		public function setLinkVivo($link_vivo){
			$this->link_vivo = $link_vivo;
		}
	}
?>