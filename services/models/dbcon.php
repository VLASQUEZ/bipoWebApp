<?php 

class DbCon{
    protected $mysqli;
    //Conexion para la base de datos de pruebas
    //Conexion para la base de datos de produccion
    /*const LOCALHOST = '127.0.0.1';
    const USER = 'root';
    const PASSWORD = '';
    const DATABASE = 'dbTest';*/
    /**
     * Constructor de clase
     */
    public function __construct() {           
        try{
            //conexión a base de datos
            $this->mysqli = new mysqli(DB_HOST,DB_USERNAME,DB_PASSWORD,DB_NAME);
        }catch (mysqli_sql_exception $e){
            //Si no se puede realizar la conexión
            http_response_code(500);
            //return $e;
        }     
    }
    public function open(){
        return $this->mysqli;
    }
    public function close(){
        try{
            $this->mysqli->close();
            return true;
        }catch(mysqli_sql_exception $e){
            return $e;
        }
    }
}
 ?>