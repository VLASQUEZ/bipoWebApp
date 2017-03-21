<?php

	class UserAPI {
		protected $response;
		public function __constructor(){
			$this->$response= array();
		}    
	    /**
		  * función que segun el valor de "action" e "id":
		  *  - mostrara una array con todos los registros de personas
		  *  - mostrara un solo registro 
		  *  - mostrara un array vacio
		  */
		function getUser($id=null){
		   
		    if(isset($id)){
		    	//muestra 1 solo registro si es que existiera ID                 
		        $db = new Users();
	            $response["error"]=false;
	            $response["message"] = $db->getUser($id);
	            return $response;                
		    }
		    else{ //muestra todos los registros                   
		        $response["error"]=false;
		        $response["message"]="no se encontraron registros";
	            return $response;              
	        }
        }    
	}
	

 ?>