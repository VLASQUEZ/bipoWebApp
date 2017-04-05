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
	function getBikesByUser($userName){
		try{
			if($userName!=null && $userName!=""){
				$db = new Bikes();
		        $this->response["error"]=false;
		        $this->response["bikes"] = $db->getBikesByUser($userName);
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
	function getBikeByUser($userName,$bikeName){
		try{
			$error="";
			if($bikeName==null || $bikeName==""){
				$error="El nombre de la bicicleta es obligatorio /n";
				
			}
			if($userName==null || $userName==""){
				$error="El token es obligatorio /n";
				
			}
			if(strcasecmp($error,"")==0)
			{
				$db = new Bikes();
		        $this->response["error"]=false;
		        $this->response["bikes"] = $db->getBikeByUser($userName,$bikeName);
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
} 

 ?>