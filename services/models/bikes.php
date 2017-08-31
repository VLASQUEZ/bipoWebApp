<?php 
//require("dbcon.php");

class Bikes{

	public function __construct() {           
		$this->mysqcon= new mysqli(DB_HOST,DB_USERNAME,DB_PASSWORD,DB_NAME);;
		}

    //Obtiene todas las marcas disponibles desde base de datos
	public function getBrands(){ 
        $stmt=$this->mysqcon->prepare("SELECT * FROM tb_brands");
        $stmt->execute();
        $result = $stmt->get_result();        
        $brands = $result->fetch_all(MYSQLI_ASSOC); 
        $stmt->close();
        return $brands;              
    }

    //Obtiene todas los estados de la bicicleta desde base de datos
    public function getBikeStates(){ 
        $stmt=$this->mysqcon->prepare("SELECT id,bikeState FROM tb_bikeState");
        $stmt->execute();
        $result = $stmt->get_result();        
        $bikeStates = $result->fetch_all(MYSQLI_ASSOC); 
        $stmt->close();

        return $bikeStates;              
    }
    //Obtiene todas los tipos de bicicleta desde base de datos
    public function getBikeTypes(){ 

        $stmt=$this->mysqcon->prepare("SELECT id,type FROM tb_bikeType UTF8");
        $stmt->execute();
        $result = $stmt->get_result();        
        $bikeTypes = $result->fetch_all(MYSQLI_ASSOC);
        //print_r($bikeStates);         //  $bikeState["type"]=utf8_encode($item);
        $stmt->close();
        
        return $bikeTypes;              
    }
    //Obtiene todas los tipos de bicicleta desde base de datos
    public function getBikeColor(){ 

        $stmt=$this->mysqcon->prepare("SELECT * FROM tb_colors UTF8");
        $stmt->execute();
        $result = $stmt->get_result();        
        $bikeColors = $result->fetch_all(MYSQLI_ASSOC);
        //print_r($bikeStates);         //  $bikeState["type"]=utf8_encode($item);
        $stmt->close();
        
        return $bikeColors;              
    }
    //Obtiene todas las bicicletas desde base de datos
    public function getBikesByUser($userName){ 

        //$stmt = $this->mysqcon->open();
        $stmt=$this->mysqcon;
        $stmt=$this->mysqcon->prepare('SET @userName := ?');
        $stmt->bind_param('s', $userName);
        $stmt->execute();
        //print_r($userName);
        $stmt=$this->mysqcon->query("call sp_getBikesByUser(@userName)");
        //print_r($stmt);
        $bikes = $stmt->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $bikes;              

    }
    //Obtiene una bicicleta por id
    public function getBikeByUser($bikeName,$userName){ 

        //$stmt = $this->mysqcon->open();
        $stmt=$this->mysqcon;
        $stmt=$this->mysqcon->prepare('SET @userName := ?');
        $stmt->bind_param('s', $userName);
        $stmt->execute();

        $stmt=$this->mysqcon->prepare('SET @bike := ?');
        $stmt->bind_param('s', $bikeName);
        $stmt->execute();
        //print_r($userName);
        $stmt=$this->mysqcon->query("call sp_getbikebyuser(@userName,@bike)");
        //print_r($stmt);
        $bikes = $stmt->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $bikes;              

    }
    //Elimina una bicicleta
    public function deleteBike($bikeId,$token){ 

        $stmt=$this->mysqcon;
        $stmt=$this->mysqcon->prepare('SET @bikeId := ?');
        $stmt->bind_param('i', $bikeId);
        $stmt->execute();

        $stmt=$this->mysqcon->prepare('SET @token := ?');
        $stmt->bind_param('s', $token);
        $stmt->execute();
        //print_r($userName);
        $stmt=$this->mysqcon->query("call sp_deleteBike(@bikeId,@token)");
        //print_r($stmt);
        $bikes = $stmt->fetch_all();
        $stmt->close();
        return $bikes;             

    }
    //Elimina una bicicleta
    public function defaultBike($bikeId,$token){ 

        $stmt=$this->mysqcon;
        $stmt=$this->mysqcon->prepare('SET @bikeId := ?');
        $stmt->bind_param('i', $bikeId);
        $stmt->execute();

        $stmt=$this->mysqcon->prepare('SET @token := ?');
        $stmt->bind_param('s', $token);
        $stmt->execute();
        //print_r($userName);
        $stmt=$this->mysqcon->query("call sp_defaultBike(@bikeId,@token)");
        //print_r($stmt);
        $bikes = $stmt->fetch_all();
        $stmt->close();
        return $bikes;             

    }
    //Almacena la foto de la bicicleta
    public function InsertPhotoBike($idBike,$url){ 
        try{
        //$stmt = $this->mysqcon->open();
            $stmt=$this->mysqcon->prepare("insert into tb_bikePhotos(url,thumbUrl,idBike)values(?,?,?)");
            $thumb=$url;
            $stmt->bind_param('ssi',$url,$thumb,$idBike);
            $r = $stmt->execute(); 
            $stmt->close();
            return $r;
        }
        catch(Exception $e)
        {
            
        }
    }
    //Obtiene todas las fotos asociadas a la bicicleta
    public function getBikePhotos($idBike){
        try{
            $stmt=$this->mysqcon->prepare("SELECT url, thumbUrl FROM tb_bikePhotos where idBike =?;");
            $stmt->bind_param('i',$idBike);
            $stmt->execute();
            $result = $stmt->get_result();        
            $bikePhotos = $result->fetch_all(MYSQLI_ASSOC);
            //print_r($bikeStates);         //  $bikeState["type"]=utf8_encode($item);
            $stmt->close();
            
            return $bikePhotos;
        }
        catch(Exception $e)
        {
            return $e->getMessage();
        }
                          
    }
    //Registra al usuario
    public function registerBike($bikeName,$idBrand,$idColor,$idFrame,
        $idType,$bikeFeatures,$idBikeState,$idUser){
        
        $stmt=$this->mysqcon->prepare("INSERT INTO tb_bikes(bikeName,idUser,idBrand,idColor,
                        idFrame,idType,bikeFeatures,idBikeState)
                        VALUES (?,?,?,?,?,?,?,?)");
        $bikeName=strtoupper($bikeName);
        $bikeFeatures=strtoupper($bikeFeatures);
        $stmt->bind_param('siiisisi', $bikeName,$idUser,$idBrand,$idColor,$idFrame,$idType,$bikeFeatures,$idBikeState);
        $r = $stmt->execute(); 
        $stmt->close();
        return $r;        
    }

    private function close(){
        try{
            $this->mysqcon->close();
            return true;
        }
        catch(mysqli_sql_exception $e){
            return $e;
        }
    }
}
?>