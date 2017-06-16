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
				$db = new Bikes();
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
	   		
	  		if(strcmp($error,"")==0){
	  			$db=new Bikes();
	   			$this->response["error"]=false;
	            $this->response["message"] = $db->registerBike($bikeName,$idBrand,$idColor,$idFrame,$idType,$bikeFeatures,$idBikeState,$id["user"][0]["id"]);
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
	function savephoto($bikeName=null,$file=null,$token=null){
		try{
			//$path=(isset($_SERVER["DOCUMENT_ROOT"]) && $_SERVER["DOCUMENT_ROOT"]!="") ? $_SERVER["DOCUMENT_ROOT"]."/" : "/var/www/html/";
			//$path="bipo/public/bikeImages/";

			//Produccion
			$path="../../public/bikeImages/";
			$error="";
			if($token==null){
			$error.="Falta token de acceso \n";
			}
			if($bikeName==null){
				$error.="El nombre de la bicicleta es obligatorio \n";
			}
			if($file==null){
				$error.="Debe incluir una imagen \n";
			}
			$userAPI=new UserAPI();
			$userName=$userAPI->getUserNameByToken($token);
			//print_r($userName["user"][0]["nickname"]);
			//exit(0);
			if(strcasecmp($error,"")==0){
				//print_r($file);
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
		    			$bike=$db->getBikeByUser($bikeName,$token);
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
		catch(exception $e){
			$this->response["error"]=true;
			$this->response["message"] = $e->getMessage();
			return $this->response;
		}
		
    }
} 

 ?>