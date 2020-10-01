<?php
//AYUDAS PARA LAS VISTAS
class AyudaVistas{
	public function url($controlador=CONTROLADOR_DEFECTO,$accion=ACCION_DEFECTO){
		$urlString="index.php?controller=".$controlador."&action=".$accion;
		//$urlString="index.php?controller=Principal&action=index";
		
		return $urlString;
	}
	//Helpers para las visitas
}
?>