<?php

class ReportAPI {
	protected $response;
	public function __constructor(){
		$this->response= array();
	}

	//Genera reporte de bicicletas robadas
	function registerReport($tokenUser,$reportName,$reportType,$coordinates=null,$idBike,$ReportDetails=null){
		try{
			$error="";
			if($tokenUser==null || $tokenUser==""){
				$error.="El token es obligatorio \n ";
   			}
   			if($reportName==null|| $reportName==""){
	   			$reportName.="Debe indicar un nombre para el reporte \n ";
	   		}
	   		if($reportType==null|| $reportType==""){
	   			$reportType.="No se ha seleccionado ningún tipo de reporte \n ";
	   		}
	   		if($coordinates==null|| $coordinates==""){
	   			$error.="Debe seleccionar una ubicación \n ";
	   		}
	   		if($idBike==null|| $idBike==""){
	   			$error.="Debe seleccionar una bicicleta \n ";
	   		}
	   		
	   		$user=new UserAPI();
	   		$id=$user->getUserNameByToken($tokenUser);
			//echo($id);
   			if(count($id["user"])>0){
   				if($id["user"][0]["id"]=="" ||$id["user"][0]["id"]==null ){
				$error.="Token no valido \n";
				}
   			}
   			else{
   				$error.="Token no valido \n";
   			}

   			if(strcmp($error,"")==0){
   				$currentDate=date("Ymd",time());
   				
   				$reportName=$currentDate."_".$id["user"][0]["nickname"]."_".$idBike;
   				
	  			$db=new Reports();
	   			
	            $reportId = $db->insertReport($tokenUser,$reportName,$reportType,
	            	$coordinates,$idBike,$ReportDetails);
	            $this->response["error"]=false;
	            $this->response["reportId"]=(int)$reportId[0]["LAST_INSERT_ID()"];
            	//Actualizar estado de bicicleta
	        	$UpdateBike=new BikeAPI();
	        	$UpdateBike->updateBikeState($reportType,$idBike);
				$bikeById=new BikeAPI();
	        	$bike=$bikeById->getBikeById($idBike,null);
	        	$network=$this->publishNetwork($reportType,$reportId[0]["LAST_INSERT_ID()"],$bike["bikes"][0]["owner_email"]);
	        	if(!strcmp($network,"")==0){
	        		$error.=$network;
	        	}
	        	if (!strcmp($error,"")==0) {
	        		$this->response["error"]=true;
	        		$this->response["message"]="Reporte generado satisfactoriamente \n ".$error;
	        	}
	   		}
	   		else{
	   			$this->response["error"]=true;
	   			$this->response["message"]=$error;
	   		}

	        return $this->response;
		}
		catch(Exception $e){
			$this->response["error"]=true;
			$this->response["message"] = $e->getMessage();
		}
		              
    }

    //Crea publicaciones de robos en las redes sociales
    function publishNetwork($reportType,$reportId,$owner_email){
    	try{
	    	$reportUrl="http://www.bipoapp.com/page/report?reportId=".$reportId;
	    	$Text="";
	    	$error="";
	    	switch ($reportType) {
	    		case '1':	
	    			$Text="#BicicletaRobada \n ";
				break;
	    		case '2':
	    			$Text="#BicicletaRecuperada \n ";
					
	    			break;
				case '4':
	    			$Text="#BicicletaVista \n ";
	    			sendEmail($owner_email,'foundBike',$reportUrl);
	    			break;
	    		default:
	    			# code...
	    			break;
	    	}

			$ResultTwitter=CreateTweet($Text.$reportUrl);
			$ResultFacebook=CreateFacebookPost($Text.' @Policianacionaldeloscolombianos',$reportUrl);


			if(!$ResultFacebook){
				$error.="Ocurrió un error al generar el reporte en Facebook"."\n";
			}
			if(!$ResultTwitter){
				$error.="Ocurrió un error al generar el reporte en Twitter"."\n";
			}
			return $error;
    	}
    	catch(Exception $e)
    	{
			$log = new Log();
    		$log->writeLog('Reportes',$e->getTraceAsString());
    	}


    }
    //Almacena una foto del reporte
	function savephoto($reportId=null,$file=null,$token=null){
		try{
						//$path=(isset($_SERVER["DOCUMENT_ROOT"]) && $_SERVER["DOCUMENT_ROOT"]!="") ? $_SERVER["DOCUMENT_ROOT"]."/" : "/var/www/html/";
			//$path="bipo/public/reports/";

			//Produccion
			$path="../../public/reports/";
			$error="";
			if($token==null){
			$error.="Falta token de acceso \n";
			}
			if($reportId==null){
				$error.="No se encontró el reporte asociado \n";
			}
			if($file==null){
				$error.="Debe incluir una imagen \n";
			}
			$userAPI=new UserAPI();
			$userName=$userAPI->getUserNameByToken($token);
			if($userName==null || $userName==""){
				$error.="token no valido";
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

		    		$db=new Reports();
	    			$report=$db->getReportById($reportId);
	    			if(count($report,0)>0){
	    				$reportName=$report[0]["reportName"];
				    	$errors=createReportDirectory($reportName);
		    			if(!$errors["error"]){
		    				$imagePath=$path.$reportName."/".$file_name;
		    				move_uploaded_file($file_tmp,$imagePath);
		    				chmod($imagePath,0766);
		    				$db=new Reports();
		    				$imagePath="public/reports/".$reportName."/".$file_name;
		    				$this->response["error"]=false;
		    				$this->response["message"] = $db->InsertPhotoReport($report[0]["id"],$imagePath);
		    			}
		    			else{
		    				return $this->response=array('error' => true,'message'=>$errors["message"]);
		    			}    
	    			}
	    			else{
	    				$this->response["error"]=true;
            			$this->response["message"] = "La bicicleta no existe";
	    			}
			    }
			    else{
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
	//Obtiene un reporte por Nombre y dueño del reporte
	function getReportByName($token,$reportName){
		try{
			$error="";
			if($reportName==null || $reportName==""){
				$error="El nombre del reporte es obligatorio /n";
				
			}
			if($token==null || $token==""){
				$error="El token es obligatorio /n";
				
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
			if(strcasecmp($error,"")==0)
			{
				$db = new Reports();
		        $this->response["error"]=false;
		        $report = $db->getReportByName($reportName,$token);
		        
		        if(count($report)){
		        	$bike=new Bikes();
	        		$reports[$pos]["bikePhotos"]=$bike->getBikePhotos($reports[$pos]["idBike"]);
	        		$db = new Reports();	
			        $report[0]["reportPhotos"]=$db->getPhotoReport($report[0]["id"]);
			        $this->response["report"]=$report;
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
    	//Obtiene un reporte por Nombre y dueño del reporte
	function getReportsByToken($token){
		try{

			$error="";
			if($token==null || $token==""){
				$error="El token es obligatorio /n";
				
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
			if(strcasecmp($error,"")==0)
			{
				$db = new Reports();
		        $this->response["error"]=false;
		        $reports = $db->getReportsByToken($token);

		        if(count($reports)){
		        	foreach ($reports as $pos => $report) {
		        		$bike=new Bikes();
		        		$reports[$pos]["bikePhotos"]=$bike->getBikePhotos($reports[$pos]["idBike"]);
	        			$db = new Reports();	
			        	$reports[$pos]["reportPhotos"]=$db->getPhotoReport($report["id"]);
			    	}
			    	$this->response["reports"]=$reports;
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
	//Obtiene un reporte por Nombre y dueño del reporte
	function getReportById($id){
		try{
			$error="";
			if($id==null || $id<=0){
				$error="No se pudo verificar la autenticidad de la consulta /n";
				
			}
			if(strcasecmp($error,"")==0)
			{
				$db = new Reports();
		        $this->response["error"]=false;
		        $reports = $db->getReportById($id);

		        if(count($reports)){
		        			        
		        foreach ($reports as $pos => $report) {
		        		$bike=new Bikes();
		        		$reports[$pos]["bikePhotos"]=$bike->getBikePhotos($reports[$pos]["idBike"]);
	        			$db = new Reports();	
			        	$reports[$pos]["reportPhotos"]=$db->getPhotoReport($report["id"]);
		        	}
		        $this->response["reports"]=$reports[0];
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
    //Obtiene todos los reportes por tipo
    function getReports($idReportType,$fhInicio=null,$fhFinal=null){
		try{
			$error="";
			if($idReportType==null || $idReportType==""){
				$error="Debe especificar un tipo \n";
			}
			if($fhInicio==null||$fhInicio==""){
				$error.="Debe indicar una fecha de inicio \n";
			}
			if($fhFinal==null||$fhFinal==""){
				$error.="Debe indicar una fecha de final \n";
			}
			if(strcasecmp($error,"")==0)
			{
				$db = new Reports();
		        $this->response["error"]=false;
		        $reports = $db->getReports($idReportType,$fhInicio,$fhFinal);

		        if(count($reports)){
		        	foreach ($reports as $pos => $report) {
		        		list($latitude,$longitude)=explode(",",$reports[$pos]["googlemapscoordinate"]);
	        			$reports[$pos]["latitude"]=$latitude;
	        			$reports[$pos]["longitude"]=$longitude;
		        		$bike=new Bikes();
		        		$reports[$pos]["bikePhotos"]=$bike->getBikePhotos($reports[$pos]["idBike"]);
	        			$db = new Reports();	
			        	$reports[$pos]["reportPhotos"]=$db->getPhotoReport($report["id"]);
		        	}	        		
			        $this->response["reports"]=$reports;
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
	        //print_r($this->response);
	        return $this->response; 
		}
		catch(exception $e){
			$this->response["error"]=true;
			$this->response["message"] = $e->getMessage();
		}
		              
    }
        //Obtiene todos los reportes de robo con coordenadas
    function getReportsMaps(){
		try{
			$error="";
			if(strcasecmp($error,"")==0)
			{
				$db = new Reports();
		        $this->response["error"]=false;
		        $reports = $db->getReportsMaps();

		        if(count($reports)){
		        	foreach ($reports as $pos => $report) {
	        			$db = new Reports();
	        			list($latitude,$longitude)=explode(",",$reports[$pos]["googlemapscoordinate"]);
	        			$reports[$pos]["latitude"]=$latitude;
	        			$reports[$pos]["longitude"]=$longitude;
    					unset($reports[$pos]["googlemapscoordinate"]);
			        	$reports[$pos]["reportPhotos"]=$db->getPhotoReport($report["id"]);
		        	}	        		
			        $this->response["reports"]=$reports;
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
	        //print_r($this->response);
	        return $this->response; 
		}
		catch(exception $e){
			$this->response["error"]=true;
			$this->response["message"] = $e->getMessage();
		}
		              
    }
    //Obtiene los ultimos 10 reportes
    function getLastReports(){
		try{
			$db = new Reports();
	        $this->response["error"]=false;
	        $reports = $db->getLastReports();

	        if(count($reports)){
		        	foreach ($reports as $pos => $report) {
		        		$bike=new Bikes();
		        		$reports[$pos]["bikePhotos"]=$bike->getBikePhotos($reports[$pos]["idBike"]);
	        			$db = new Reports();	
			        	$reports[$pos]["reportPhotos"]=$db->getPhotoReport($report["id"]);
		        	}	        		
			        $this->response["reports"]=$reports;
			    }
			    else
			    {
			    	$this->response["message"]="No se encontraron registros";
			    }
	        //print_r($this->response);
	        return $this->response; 
		}
		catch(exception $e){
			$this->response["error"]=true;
			$this->response["message"] = $e->getMessage();
		}
		              
    }
    function getReportType(){
    	try{
			$db = new Reports();
	        $this->response["error"]=false;
	        $this->response["brands"] = $db->getReportType();
	        //print_r($this->response);
	        return $this->response; 
		}
		catch(exception $e){
			$this->response["error"]=true;
			$this->response["message"] = $e->getMessage();
		}
    }
    function sendEmail($token,$type){
    	try{

			$error="";
			if($token==null || $token==""){
				$error="El token es obligatorio /n";
			}
			$db =new UserAPI();
	        $user=$db->getUserNameByToken($token);
	        if(count($user["user"])>0){
				if($user["user"][0]["email"]=="" ||$user["user"][0]["email"]==null ){
					$error.="Token no valido \n";
				}
			}
			else{
				$error.="Token no valido \n";
			}	
			if(strcasecmp($error,"")==0)
			{
	    		
		        $this->response["error"]=false;
				$this->response["message"]=sendEmail($user["user"][0]["email"],$type);
		    }
		    return $this->response;
    	}
    	catch(exception $e){
			$this->response["error"]=true;
			$this->response["message"] = $e->getMessage();
    	}
    }

}
?>