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
    // inicia el proceso de recuperacion de password
    function recoverPass($email=null){
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
	        	$userExist=$db->userExist($email);
	        	
	        	if($userExist[0]['id']!=null){
	        		$tokenRecover=encrypt($email);
        			$db=new Users();
        			$user=$db->setTokenRecoverPass($tokenRecover,$userExist[0]['id']);
        			$url="http://www.bipoapp.com/page/recoverPass?uid=".$tokenRecover;
        			sendEmail($email,'recoverPassword',$url);
	        	}else{
	        		$this->response["message"] = "No se pudo verificar la dirección de correo";
	        	}
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
        // inicia el proceso de recuperacion de password
    function setPassword($password,$token){
		try{
			$error="";
		
	   		if($token==null){
	   			$error="El token es obligatorio\n ";
	   		}
	   		if($password==null){
	   			$error="La contraseña es obligatoria\n ";
	   		}
	   		if(strcmp($error,"")==0){
	   			$db = new Users();
	   			$this->response["error"]=false;
	        	$userExist=$db->userExistToken($token);
	        	
	        	if($userExist[0]['id']!=null){
	        		$password=encrypt($password);
        			$db=new Users();
        			$user=$db->setPassword($password,$userExist[0]['id']);
        			$this->response["message"]="Contraseña actualizada satisfactoriamente";
	        	}else{
	        		$this->response["message"] = "No se pudo verificar la dirección de correo";
	        	}
	   		}
	   		else{
	   			$this->response["error"]=true;
	        	$this->response["message"] = $error;
	   		}
	        return $this->response;

		}
		catch(Execption $e){
			$this->response["error"]=true;
	        $this->response["message"] = "No se pudo verificar la dirección de correo";
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
	      	$this->response["message"] = "";
	        return $this->response;                
	    }
	    else{ //muestra todos los registros                   
	        $this->response["error"]=false;
	        $this->response["user"]=null;
	        $this->response["message"]="no se encontraron registros";
	        return $this->response;              
	    }
    }

	function login($email,$password,$loggedWeb,$loggedMobile){
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
	   					$db->setTokenByUser($email,$token,$loggedWeb,$loggedMobile);

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
	function logout($token,$loggedWeb,$loggedMobile){
		try{
			$error="";
   	
	   		if(strlen($token)<=1){
	   			$error="El token es obligatorio\n ";
	   		}
	   		$db = new Users();
	   		$id=$db->getUserId($token);

   			if(count($id)>0){
   				if($id[0]["id"]=="" ||$id[0]["id"]==null ){
					$error.="Token no valido \n";
				}
   			}
   			else{
   				$error.="Token no valido \n";
   			}
	   		
	   		if(strcmp($error,"")==0){
	   			$db = new Users();
	   			$response["error"]=false;
	   			
				$this->response["error"]=false;
				$db->logout($token,$loggedWeb,$loggedMobile);	
				$this->response["user"]="Tu sesión en Bipo ha finalizado.";
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
    //guarda las preferencias del usuario
    function setPreferences($token,$emailReceiver,$photoPublication,$enableReportUbication,$enableLocationUbication){
	   try{

	   		$error="";
	   	
	   		if(strlen($token)<=1){
	   			$error="El token es obligatorio\n ";
	   		}
	   		if($emailReceiver==null){
	   			$error.="la preferencia es obligatoria\n ";
	   		}
	   		if($photoPublication==null){
	   			$error.="la preferencia es obligatoria\n ";
	   		}
	   		if($enableLocationUbication==null){
	   			$error.="la preferencia es obligatoria\n ";
	   		}

	   		if(strcmp($error,"")==0){
	   			$db = new Users();
	   			$this->response["error"]=false;
	            $this->response["message"] = $db->setPreferences($token,$emailReceiver,$photoPublication,$enableReportUbication,$enableLocationUbication);
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
    function updatePassword($email,$password,$newPassword){
		try{
			$error="";
   	
	   		if(strlen($email)<=1){
	   			$error="El email es obligatorio\n ";
	   		}
	   		if(strlen($password)<=1){
	   			$error.="El password es obligatorio \n";
	   		}
	   		if(strlen($newPassword)<=1){
	   			$error.="El nuevo password es obligatorio \n";
	   		}
	   		if(strcmp($error,"")==0){
	   			$db = new Users();
	   			$response["error"]=false;
	   			$dbpass=$db->getPassword($email);
	   			
	   			if($dbpass!=null){
	   				$passdecrypt=decrypt($dbpass["0"]["password"]);
	   				if(strcmp($passdecrypt,$password)==0){
	   					$newPassword=encrypt($newPassword);
	   					
   						$update= $db->updatePassword($dbpass[0]["id"],$newPassword);
   						$this->response["error"]=false;
	   					$this->response["user"]=$update;
   						//Envio de email personalizado
	   					sendEmail($email,"changePassword");	
	   					//$token=createToken($email.$password.$timezone);
	   					/*$db->setTokenByUser($email,$token);
   						$this->response["error"]=false;
	   					$this->response["user"]=$db->login($email);*/
	   				}
	   				else{
	   					$this->response["error"]=true;
	   					$this->response["message"]="La contraseña no corresponde.";
	   				}
	   			}
	   			else{
	   				$this->response["error"]=true;
	   				$this->response["message"]="El usuario no se encuentra registrado";
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
}
	

 ?>