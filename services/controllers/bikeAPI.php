<?php

class BikeAPI {
	protected $response;
	public function __constructor(){
		$this->response= array();
	}

	//Obtiene todas las marcas disponibles
	function getBrands(){
		try{
			$db = new Bikes();
	        $this->response["error"]=false;
	        $this->response["brands"] = $db->getBrands();
	        //print_r($this->response);
	        return $this->response; 
		}
		catch(exception $e){
			$this->response["error"]=true;
			$this->response["message"] = $e->getMessage();
		}
               
    }
    //Obtiene todas las marcas disponibles
	function getBikeStates(){
		try{
			$db = new Bikes();
	        $this->response["error"]=false;
	        $this->response["bikeStates"] = $db->getBikeStates();
	        //print_r($this->response);
	        return $this->response; 
		}
		catch(exception $e){
			$this->response["error"]=true;
			$this->response["message"] = $e->getMessage();
		}
		              
    }
    //Obtiene todas los tipos de bicicletas disponibles
	function getBikeTypes(){
		try{
			$db = new Bikes();
	        $this->response["error"]=false;
	        $this->response["biketypes"] = $db->getBikeTypes();
	        //print_r($this->response);
	        return $this->response; 
		}
		catch(exception $e){
			$this->response["error"]=true;
			$this->response["message"] = $e->getMessage();
		}
		              
    }
    //Obtiene los estados de bicicleta disponibles
	function getBikeColor(){
		try{
			$db = new Bikes();
	        $this->response["error"]=false;
	        $this->response["bikeColor"] = $db->getBikeColor();
	        //print_r($this->response);
	        return $this->response; 
		}
		catch(exception $e){
			$this->response["error"]=true;
			$this->response["message"] = $e->getMessage();
		}
		              
    }
	//Obtiene las bicicletas por usuario
	function getBikesByUser($token){
		try{
			if($token!=null && $token!=""){
				$db = new Bikes();
		        $this->response["error"]=false;
		        $bikes = $db->getBikesByUser($token);

		        if(count($bikes)){
		        	foreach ($bikes as $pos => $bike) {
	        			$db = new Bikes();	
			        	$bikes[$pos]["bikePhotos"]=$db->getBikePhotos($bike["id"]);
		        	}	        		
			        $this->response["bikes"]=$bikes;
			    }
			    else
			    {
			    	$this->response["message"]="No se encontraron registros";
			    }
			}
			else{
		        $this->response["error"]=false;
		        $this->response["bikes"] = "Usuario no valido";
			}

	        //print_r($this->response);
	        return $this->response; 
		}
		catch(exception $e){
			$this->response["error"]=true;
			$this->response["message"] = $e->getMessage();
		}
		              
    }
    function getBikeById($bikeId,$userName){
    	try{
    		$db = new Bikes();
        	$this->response["error"]=false;
	        $bikes = $db->getBikeById($bikeId,$userName);

	        if(count($bikes)){
	    		$db = new Bikes();	
	   			$this->response["error"]=false;

		        $bikes[0]["bikePhotos"]=$db->getBikePhotos($bikes[0]["id"]);
		        $this->response["bikes"]=$bikes;
		    }
		    else
		    {
		    	$this->response["error"]=true;
		    	$this->response["message"]="No se encontraron registros";
		    }
		    return $this->response;
    	}
    	catch(Exception $e){
    		$this->response["error"]=true;
			$this->response["message"] = $e->getMessage();
    	}
		
    }
	//Obtiene las bicicletas por usuario
	function getBikeByUser($token,$bikeName){
		try{
			$error="";
			if($bikeName==null || $bikeName==""){
				$error="El nombre de la bicicleta es obligatorio /n";
				
			}
			if($token==null || $token==""){
				$error="El token es obligatorio /n";
				
			}
			if(strcasecmp($error,"")==0)
			{
				$db = new Bikes();
		        $this->response["error"]=false;
		        $bikes = $db->getBikeByUser($bikeName,$token);
		        
		        if(count($bikes)){
	        		$db = new Bikes();	
			        $bikes[0]["bikePhotos"]=$db->getBikePhotos($bikes[0]["id"]);
			        $this->response["bikes"]=$bikes;
			    }
			    else
			    {
			    	$this->response["message"]="No se encontraron registros";
			    }
	        	
			}
	   		else
	   		{
	   			$this->response["error"]=true;
	   			$this->response["message"]=$error;
	   		}
	        return $this->response; 
		}
		catch(exception $e){
			$this->response["error"]=true;
			$this->response["message"] = $e->getMessage();
		}
		              
    }
    //elimina una bicicleta
	function deleteBike($bikeId,$token){
		try{
			$error="";
			if($bikeId==null || $bikeId==""){
				$error="El id de la bicicleta es obligatorio /n";
				
			}
			if($token==null || $token==""){
				$error="El token es obligatorio /n";
				
			}
			if(strcasecmp($error,"")==0)
			{
				$db = new Bikes();
		        $this->response["error"]=false;
		        $bikes = $db->deleteBike($bikeId,$token);

		        if($bikes[0][0]<=0){
		        	$this->response["status"]=false;
		        	$this->response["message"]="No se han efectuado cambios";
		        }
		        else{
	        		$this->response["status"]=true;
		        	$this->response["message"]="Se ha eliminado la bicicleta";
		        }
	        	
			}
	   		else
	   		{
	   			$this->response["error"]=true;
	   			$this->response["message"]=$error;
	   		}
	        return $this->response; 
		}
		catch(exception $e){
			$this->response["error"]=true;
			$this->response["message"] = $e->getMessage();
		}
		              
    }
    //actualiza el estado default de una bicicleta
	function defaultBike($bikeId,$token){
		try{
			$error="";
			if($bikeId==null || $bikeId==""){
				$error="El id de la bicicleta es obligatorio /n";
				
			}
			if($token==null || $token==""){
				$error="El token es obligatorio /n";
				
			}
			if(strcasecmp($error,"")==0)
			{
				$db = new Bikes();
		        $this->response["error"]=false;
		        $bikes = $db->defaultBike($bikeId,$token);

		        if($bikes[0][0]<=0){
		        	$this->response["status"]=false;
		        	$this->response["message"]="No se han efectuado cambios";
		        }
		        else{
	        		$this->response["status"]=true;
		        	$this->response["message"]="Bicicleta seleccionada como principal";
		        }
	        	
			}
	   		else
	   		{
	   			$this->response["error"]=true;
	   			$this->response["message"]=$error;
	   		}
	        return $this->response; 
		}
		catch(exception $e){
			$this->response["error"]=true;
			$this->response["message"] = $e->getMessage();
		}
		              
    }
    //actualiza el estado default de una bicicleta
	function updateBike($token,$bikeId,$idColor,$bikeFeatures){
		try{
			$error="";
			if($bikeId==null || $bikeId==""){
				$error="El id de la bicicleta es obligatorio /n";
				
			}
			if($token==null || $token==""){
				$error="El token es obligatorio /n";
				
			}
			if($idColor==null || $idColor==""){
				$error="La bicicleta debe tener un color /n";
				
			}
			if($bikeFeatures==null ||$bikeFeatures==""){
				$bikeFeatures="";
			}
			if(strcasecmp($error,"")==0)
			{
				$db = new Bikes();
		        $this->response["error"]=false;
		        $bikes = $db->updateBike($token,$bikeId,$idColor,$bikeFeatures);

		        if($bikes[0][0]<=0){
		        	$this->response["status"]=false;
		        	$this->response["message"]="No se han efectuado cambios";
		        }
		        else{
	        		$this->response["status"]=true;
		        	$this->response["message"]="Datos de la bicicleta actualizados";
		        }
	        	
			}
	   		else
	   		{
	   			$this->response["error"]=true;
	   			$this->response["message"]=$error;
	   		}
	        return $this->response; 
		}
		catch(exception $e){
			$this->response["error"]=true;
			$this->response["message"] = $e->getMessage();
		}
		              
    }
        //actualiza el estado default de una bicicleta
	function updateBikeState($bikeState,$bikeId){
		try{
			$error="";
			if($bikeState==null || $bikeState==""){
				$error="El id de la bicicleta es obligatorio /n";
				
			}
			if(strcasecmp($error,"")==0)
			{
				$db = new Bikes();
		        $this->response["error"]=false;
		        $bikes = $db->updateBikeState($bikeState,$bikeId);

		        if($bikes<=0){
		        	$this->response["status"]=false;
		        	$this->response["message"]="No se han efectuado cambios";
		        }
		        else{
	        		$this->response["status"]=true;
		        	$this->response["message"]="Datos de la bicicleta actualizados";
		        }
	        	
			}
	   		else
	   		{
	   			$this->response["error"]=true;
	   			$this->response["message"]=$error;
	   		}
	        return $this->response; 
		}
		catch(exception $e){
			$this->response["error"]=true;
			$this->response["message"] = $e->getMessage();
		}
		              
    }
    //Registra una bicicleta
	function registerBike($bikeName,$idBrand,$idColor,$idFrame,
    	$idType,$bikeFeatures,$idBikeState,$token){
	   try{

	   		$error="";
   			if($token==null || $token==""){
				$error.="El token es obligatorio \n";
   			}
	   		if($idBrand==null){
	   			$error.="No se ha seleccionado ninguna marca \n ";
	   		}
	   		if($idColor==null){
	   			$error.="Debe seleccionar un color de bicicleta \n ";
	   		}
	   		if($idFrame==null){
	   			$error.="El id del marco es obligatorio \n ";
	   		}
	   		if($idType==null){
	   			$error.="No ha seleccionado ningun tipo de bicicleta \n ";
	   		}
	   		if($idBikeState==null){
	   			$error.="No ha seleccionado el estado actual de la bicicleta \n ";
	   		}
	   		$user=new UserAPI();
	   		$id=$user->getUserIdByToken($token);
   			if(count($id["user"])>0){
   				if($id["user"][0]["id"]=="" ||$id["user"][0]["id"]==null ){
				$error.="Token no valido \n";
				}
   			}
   			else{
   				$error.="Token no valido \n";
   			}
	   		$db = new Bikes();
	   		$dbIdFrame = $db->getBikeIdByFrameId($idFrame);
	   		if(count($dbIdFrame)>0){
	   			sendEmail($dbIdFrame[0]['owner_email'],'duplicateFrame',$idFrame);
	   			$error.="No se pudo registrar la bicicleta, por favor verifica la información ingresada o contáctanos. \n";
	   		}
	  		if(strcmp($error,"")==0){
	   			$this->response["error"]=false;
	   			$bikeId=$db->registerBike($bikeName,$idBrand,$idColor,$idFrame,$idType,$bikeFeatures,$idBikeState,$id["user"][0]["id"]);
	            $this->response["bikeId"] = $bikeId;
	   		}
	   		else{
	   			$this->response["error"]=true;
	   			$this->response["message"]=$error;
	   		}
        
	        return $this->response;
	   }
	   catch(exception $e){
	   		$this->response["error"]=true;
	        $this->response["message"] = $e->getmessage();
	        return $this->response;
	   }

	}
	//Almacena una foto de la bicicleta
	function savephoto($bikeId=null,$token=null,$file=null){
		try{
			//$path=(isset($_SERVER["DOCUMENT_ROOT"]) && $_SERVER["DOCUMENT_ROOT"]!="") ? $_SERVER["DOCUMENT_ROOT"]."/" : "/var/www/html/";
			//$path="bipo/public/bikeImages/";
			//Produccion
			$path="../../public/bikeImages/";
			$error="";
			if($token==null){
			$error.="Falta token de acceso \n";
			}
			if($bikeId==null){
				$error.="El id es obligatorio \n";
			}
			if($file==null){
				$error.="Debe incluir una imagen \n";
			}
			$userAPI=new UserAPI();
			$userName=$userAPI->getUserNameByToken($token);
			//throw new Exception($userName["message"]." user: ".$userName["user"], 1);
			if($userName["message"]!=""){
				$error.=$userName["message"];
				//throw new Exception($userName["message"], 1);
								
			}
			if(strcasecmp($error,"")==0){
		      	$errors= array();
		    	$file_name = $file['name'];
			  	$file_size =$file['size'];
			    $file_tmp =$file['tmp_name'];
			    $file_type=$file['type'];
			    $tmp=explode('.',$file_name);
			    $file_ext=strtolower(end($tmp));
			    
			    $expensions= array("jpeg","jpg","png");
			     
			    if(in_array($file_ext,$expensions)=== false){
			       $errors[]="extension de archivo no valida, solo se permite png o jpeg.";
			    }

			    if(empty($errors)==true){
			    	$errors=createUserDirectory($userName["user"][0]["nickname"]);
			    	if(!$errors["error"]){
			    		$imagePath=$path.$userName["user"][0]["nickname"]."/".$file_name;
			    		move_uploaded_file($file_tmp,$imagePath);
			    		chmod($imagePath,0766);

		    			$db=new Bikes();
		    			$bike=$db->getBikeById($bikeId,$token);
		    			if(count($bike,0)>0){
		    				$db=new Bikes();
		    				$imagePath="public/bikeImages/".$userName["user"][0]["nickname"]."/".$file_name;
		    				$this->response["error"]=false;
		    				$this->response["message"] = $db->InsertPhotoBike($bike[0]["id"],$imagePath);
		    			}
		    			else{
		    				$this->response["error"]=true;
	            			$this->response["message"] = "La bicicleta no existe";
		    			}
		    				   					
			    	}
			    	else{
			    		return $this->response=array('error' => true,'message'=>$errors["message"]);
			    	}    
			    }else{
			       return $this->response=array('error' => true,'message'=>$errors);
			    }
			}
			else
	   		{
	   			$this->response["error"]=true;
	   			$this->response["message"]=$error;
	   		}
	   		return $this->response; 
		}
		catch(Exception $e){
			$this->response["error"]=true;
			$this->response["message"] = $e->getMessage()." Stacktrace: ".$e->getTraceAsString()." token: ".$token." bicicleta: ".$bikeName;
			return $this->response;
		}
		
    }

} 

 ?>