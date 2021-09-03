<?php
	class Horario {
		private $id;
		private $id_programa;
		private $dia;
		private $horaInicio;
		private $horaFin;
 
		function __construct(){}
 
		public function getId(){
			return $this->id;
		}

		public function setId($id){
			$this->id = $id;
		}
 
		public function getIdPrograma(){
			return $this->id_programa;
		}

		public function setIdPrograma($id_programa){
			$this->id_programa = $id_programa;
		}
 
		public function getDia(){
			return $this->dia;
		}

		public function getNombreDia(){
			switch ($this->dia) {
			    case "1":
			        return "Lunes";
			        break;
			    case "2":
			        return "Martes";
			        break;
			    case "3":
			        return "Mi&eacute;rcoles";
			        break;
			    case "4":
			        return "Jueves";
			        break;
			    case "5":
			        return "Viernes";
			        break;
			    case "6":
			        return "S&aacute;bado";
			        break;
			    default:
    				return "Domingo";
			}
		}
 
		public function setDia($dia){
			$this->dia = $dia;
		}
		
		public function getHorario(){
			return $this->horaInicio." a ".$this->horaFin;
		}
 
		public function setHorario($horario){
			$this->horario = $horario;
		}
		
		public function getHoraInicio() {
		    return $this->horaInicio;
		}
		
		public function getHoraFin() {
		   return $this->horaFin;
		}
		
		public function setHoraInicio($horaInicio){
			$this->horaInicio = $horaInicio;
		}
		
		public function setHoraFin($horaFin){
			$this->horaFin = $horaFin;
		}
	}
?>