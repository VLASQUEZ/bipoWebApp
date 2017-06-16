<?php

class UserAPI {
	protected $response;
	public function __constructor(){
		$this->response= array();
	}    
    /**
	  * función que segun el valor de "action" e "id":
	  *  - mostrara una array con todos los registros de personas
	  *  - mostrara un solo registro 
	  *  - mostrara un array vacio
	  */
	function getUser($id=null){
	    if($id!=null){
	    	//muestra 1 solo registro si es que existiera ID                 
	        $db = new Users();
            $this->response["error"]=false;
            $this->response["user"] = $db->getUser($id);
            return $this->response;                
	    }
	    else{ //muestra todos los registros                   
	        $this->response["error"]=false;
	        $this->response["message"]="no se encontraron registros";
            return $this->response;              
        }
    }

    //comprueba si existe el usuario en base de datos
    function userExist($email=null){
    	try{
			$error="";
   	
	   		if($email==null){
	   			$error="El email es obligatorio\n ";
	   		}
	   		$this->response=validateField($email,"email");
   			if($this->response["message"]!=null){
   				$error.=$this->response["message"];
   			}
	   		if(strcmp($error,"")==0){
	   			$db = new Users();
	   			$this->response["error"]=false;
	        	$this->response["userExist"] =$db->userExist($email);
	   		}
	   		else{
	   			$this->response["error"]=true;
	        	$this->response["message"] = $error;
	   		}
	        return $this->response;

    	}
    	catch(Execption $e){
    		$this->response["error"]=true;
	        $this->response["message"] = $e->getmessage();
	        return $this->response;
    	}
    }
	//obtiene el id del usuario asociado
	function getUserIdByToken($token=null){
	    if($token!=null && $token!=""){
	    	//muestra 1 solo registro si es que existiera ID                 
	        $db = new Users();
	        $this->response["error"]=false;
	        $this->response["user"] = $db->getUserId($token);
	        return $this->response;                
	    }
	    else{ //muestra todos los registros                   
	        $this->response["error"]=false;
	        $this->response["user"]=null;
	        $this->response["message"]="no se encontraron registros";
	        return $this->response;              
	    }
    }
	function getUserNameByToken($token=null){
	    if($token!=null && $token!=""){
	    	//muestra 1 solo registro si es que existiera ID                 
	        $db = new Users();
	        $this->response["error"]=false;
	        $this->response["user"] = $db->getUserName($token);
	        return $this->response;                
	    }
	    else{ //muestra todos los registros                   
	        $this->response["error"]=false;
	        $this->response["user"]=null;
	        $this->response["message"]="no se encontraron registros";
	        return $this->response;              
	    }
    }

	function login($email,$password){
		try{
			$error="";
   	
	   		if(strlen($email)<=1){
	   			$error="El email es obligatorio\n ";
	   		}
	   		if(strlen($password)<=1){
	   			$error.="El password es obligatorio \n";
	   		}
	   		if(strcmp($error,"")==0){
	   			$db = new Users();
	   			$response["error"]=false;
	   			$dbpass=$db->getPassword($email);
	   			
	   			if($dbpass!=null){
	   				$dbpass=decrypt($dbpass["0"]["password"]);
	   				if(strcmp($dbpass,$password)==0){
	   					$timezone = date("Y-m-d H:i:s");
	   					
	   					$token=createToken($email.$password.$timezone);
	   					$db->setTokenByUser($email,$token);
   						$this->response["error"]=false;
	   					$this->response["user"]=$db->login($email);
	   				}
	   				else{
	   					$this->response["error"]=true;
	   					$this->response["message"]="Usuario o contraseña no validos";
	   				}
	   			}
	   			else{
	   				$this->response["error"]=true;
	   				$this->response["message"]="Usuario o contraseña no validos";
	   			}
	            //$response["user"] = decrypt($user["password"]);
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
    /**
	  * Funcion encargada de validar y enviar a la entidad los datos
	  * correspondientes para el registro del usuario
	  */
    function registerUser($name,$lastname,$email,$birthdate,$cellphone,
    	$document,$password){
	   try{

	   		$error="";
	   	
	   		if(strlen($name)<=1){
	   			$error="El nombre es obligatorio\n ";
	   		}
	   		if(strlen($lastname)<=1){
	   			$error.="los apellidos son obligatorios\n ";
	   		}
	   		if(strlen($email)<=1){
	   			$error.="El correo es obligatorio\n ";
	   		}
	   		if(strlen($birthdate)<=1){
	   			$error.="La fecha de nacimiento es obligatoria\n ";
	   		}
	   		if(strlen($cellphone)<=1){
	   			$error.="El número de celular es obligatorio\n ";
	   		}
	   		if(strlen($document)<=1){
	   			$error.="El documento de identificacion es obligatorio\n ";
	   		}
	   		if(strlen($password)<=1){
	   			$error.="password es obligatorio\n ";
	   		}
	   		$this->response=validateField($email,"email");
   			if($this->response["message"]!=null){
   				$error.=$this->response["message"];
   			}
   			$this->response=validateField($document,"document");
   			if($this->response["message"]!=null){
   				$error.=$this->response["message"];
   			}
   			$this->response=validateField($cellphone,"cellphone");
   			if($this->response["message"]!=null){
   				$error.=$this->response["message"];
   			}
   			$this->response=validateField($name,"names");
   			if($this->response["message"]!=null){
   				$error.=$this->response["message"];
   			}
   			$this->response=validateField($lastname,"names");
   			if($this->response["message"]!=null){
   				$error.=$this->response["message"];
   			}
	   		if(strcmp($error,"")==0){
	   			$db = new Users();
	   			$nickname=createNickName($name,$lastname);
	   			$password=encrypt($password);
	   			$this->response["error"]=false;
	            $this->response["message"] = $db->registerUser($name,$lastname,$nickname,$email,
                $birthdate,$cellphone,$document,$password);
	   		}
	   		else{
	   			$this->response["error"]=true;
	   			$this->response["message"]=$error;
	   		}
   			/*if(isset($id)){
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
	        }*/
	        
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