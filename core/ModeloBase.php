<?php
class ModeloBase extends EntidadBase{
	private $table;
	private $fluent;

	public function __construct($table, $adapter) {
		$this->table=(string) $table;
		parent::__construct($table, $adapter);
		$this->fluent=$this->getConetar()->startFluent();
	}

	public function fluent(){
		return $this->fluent;
	}

	public function ejecutarSql($query){
		$query=$this->db()->query($query);
		if($query==true){
			if($query->num_rows>1){
				while($row = $query->fetch_object()) {
					$resultSet[]=$row;
				}
			}elseif($query->num_rows==1){
				if($row = $query->fetch_object()) {
					$resultSet=$row;
				}
			}else{
				$resultSet=true;
			}
		}else{
			$resultSet=false;
		}
		return $resultSet;
	}


    public function nombreReporteId($numero)
    {
        switch ($numero) {
            case 0:
                $reporteNombre = "Reporte";
                break;
            case 1:
                $reporteNombre = "Incidencia";
                break;
            case 2:
                $reporteNombre = "Ubicación";
                break;
            case 3:
                $reporteNombre = "Inventario";
                break;
            case 4:
                $reporteNombre = "Seguimiento a Incidencia";
                break;
        }
        return $reporteNombre;
    }
}
?>
