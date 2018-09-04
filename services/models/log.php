<?php 
//require("dbcon.php");

class Log{
	public function __construct() {           
		$this->mysqcon= new mysqli(DB_HOST,DB_USERNAME,DB_PASSWORD,DB_NAME);;
	}

	    //Obtiene todas las marcas disponibles desde base de datos
	public function writeLog($type,$content){ 
        try {
        $stmt=$this->mysqcon->prepare("INSERT INTO tb_log(type,log) values(?,?) ");
        $stmt->bind_param('ss',$type,$content);
        $stmt->execute();
        $result = $stmt->get_result();        
        $stmt->close();
                } 

        catch (Exception $e) {
        }
             
    }


}
?>