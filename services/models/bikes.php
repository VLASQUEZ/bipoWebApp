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
        //Obtiene una bicicleta por id
    public function getBikeById($bikeId,$userName){ 

        $stmt=$this->mysqcon->prepare("SELECT b.id,b.bikename,m.brand,c.color,c.hexcolor,
            b.idframe,t.type,b.bikefeatures,s.bikestate,b.isDefault,u.email as 'owner_email',u.nickname as 'owner_nickname'
            from tb_bikes b
            inner join tb_brands m on b.idBrand=m.id
            inner join tb_colors c on b.idColor=c.id
            inner join tb_bikeType t on b.idType=t.id
            inner join tb_bikeState s on b.idBikeState=s.id
            inner join tb_users u on b.idUser=u.id
            where b.id=? and b.state=1 and b.idBikeState!=6;");
        $stmt->bind_param('i',$bikeId);
        $stmt->execute();
        $result = $stmt->get_result();        
        $bike = $result->fetch_all(MYSQLI_ASSOC);
        //print_r($bikeStates);         //  $bikeState["type"]=utf8_encode($item);
        $stmt->close();
        
        return $bike;               

    }
        //Obtiene todas los estados de la bicicleta desde base de datos
    public function getBikeIdByFrameId($frameId){ 
        $stmt=$this->mysqcon->prepare("SELECT b.idFrame,u.email as 'owner_email' 
            FROM tb_bikes b
            INNER JOIN tb_users u on b.idUser = u.id
            WHERE b.idFrame like ? and (b.idBikeState!=6 or b.idBikeState!=4 or b.idBikeState!=5);");
        $stmt->bind_param('s',$frameId);

        $stmt->execute();
        $result = $stmt->get_result();        
        $bikes = $result->fetch_all(MYSQLI_ASSOC); 
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
        $bikes = $stmt->insert_id;
        $stmt->close();
        return $bikes;        
    }
    //Actualiza los datos de la bicicleta
    public function updateBike($token,$bikeId,$idColor,$bikeFeatures){
                $stmt=$this->mysqcon;
        $stmt=$this->mysqcon->prepare('SET @bikeId := ?');
        $stmt->bind_param('i', $bikeId);
        $stmt->execute();

        $stmt=$this->mysqcon->prepare('SET @token := ?');
        $stmt->bind_param('s', $token);
        $stmt->execute();

        $stmt=$this->mysqcon->prepare('SET @idColor := ?');
        $stmt->bind_param('i', $idColor);
        $stmt->execute();
        
        $stmt=$this->mysqcon->prepare('SET @bikeFeatures := ?');
        $stmt->bind_param('s', $bikeFeatures);
        $stmt->execute();
        //print_r($userName);
        $stmt=$this->mysqcon->query("call sp_updateBike(@bikeId,@token,@idColor,@bikeFeatures)");
        //print_r($stmt);
        $bikes = $stmt->fetch_all();
        $stmt->close();
        return $bikes;  
    }
    //Actualiza el estado de la bicicleta
    public function updateBikeState($bikeState,$bikeId){
        try{
            //print_r($userName);
            $stmt=$this->mysqcon;
            $stmt=$this->mysqcon->prepare("UPDATE tb_bikes set idBikeState=? WHERE id=?");
            $stmt->bind_param('ii', $bikeState,$bikeId);
            $stmt->execute();
            $bikes = $stmt->affected_rows;
            $stmt->close();
            return $bikes;  
        }
        catch(mysqli_sql_exception $e){
            return $e;
        }

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