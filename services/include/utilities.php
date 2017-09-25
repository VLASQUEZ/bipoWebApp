<?php 
//require("../models/requires.php");
//require("../libs/twitter-api-php/TwitterAPIExchange.php");
	function validateField($value,$type){
		$result=array();
		switch ($type) {
			case 'email':
				$regex="/^[_A-Za-z0-9-\+]+(\.[_A-Za-z0-9-]+)*@".
                        "[A-Za-z0-9-]+(\.[A-Za-z0-9]+)*(\.[A-Za-z]{2,})$/";
				$result["error"]=preg_match($regex, $value);

				if($result["error"]==1){
					$result["error"]=false;
					$result["message"]=null;
				}else{
					$result["error"]=true;
					$result["message"]="El correo electrónico no es valido \n";
				}
				return $result;
				break;
			case 'document':
				$regex="/^[0-9]{10,12}$/";
				$result["error"]=preg_match($regex, $value);

				if($result["error"]==1){
					$result["error"]=false;
					$result["message"]=null;
				}else{
					$result["error"]=true;
					$result["message"]="El documento no es valido \n";
				}
				return $result;
				break;
			case 'cellphone':
				$regex="/^[0-9]{10}$/";
				$result["error"]=preg_match($regex, $value);

				if($result["error"]==1){
					$result["error"]=false;
					$result["message"]=null;
				}else{
					$result["error"]=true;
					$result["message"]="El número de celular no es valido \n";
				}
				return $result;
				break;
				case 'names':
				$regex="/^([a-zA-Zñáéíóú]+[\s]*)+$/";
				$result["error"]=preg_match($regex, $value);

				if($result["error"]==1){
					$result["error"]=false;
					$result["message"]=null;
				}else{
					$result["error"]=true;
					$result["message"]="El nombre no debe contener números o caractéres especiales \n";
				}
				return $result;
				break;
			default:
				# code...
				break;
		}
	}
	function createNickName($name,$lastname){
		$randID=rand(0,999);
		$nickname=substr($name,0,3).substr($lastname,0,3).$randID;
		return $nickname;

	}
	function createUserDirectory($dirName){
		try
		{
			//$path=(isset($_SERVER["DOCUMENT_ROOT"]) && $_SERVER["DOCUMENT_ROOT"]!="") ? $_SERVER["DOCUMENT_ROOT"]."/" : "/var/www/html/";
			//$path="bipo/public/bikeImages/";

			//Produccion
			$path="../../public/bikeImages/".$dirName;
			if(!file_exists($path)){
				mkdir($path,0777);
				chmod($path,0777);
			}
			chmod($path,0777);
			return array("error"=>false,"message"=>null);
		}
		catch(Exception $e){
			return array("error"=>true,"message"=>$e->getMessage());
		}
	}
	function createReportDirectory($dirName){
		try
		{
			//$path=(isset($_SERVER["DOCUMENT_ROOT"]) && $_SERVER["DOCUMENT_ROOT"]!="") ? $_SERVER["DOCUMENT_ROOT"]."/" : "/var/www/html/";
			//$path="bipo/public/bikeImages/";

			//Produccion
			$path="../../public/reports/".$dirName;
			if(!file_exists($path)){
				mkdir($path,0777);
				chmod($path,0777);
			}
			chmod($path,0777);
			return array("error"=>false,"message"=>null);
		}
		catch(Exception $e){
			return array("error"=>true,"message"=>$e->getMessage());
		}
	}
	function sendEmail($to,$type,$Content=null){
		try{
			switch ($type) {
			case 'changePassword':
				$from='registro@bipoapp.com'. "\r\n";
				$headers="From:".$from;
				$headers .= "MIME-Version: 1.0" . "\r\n";
				$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
				$subject='Cambio de contraseña';
				$message="Tu contraseña ha sido cambiada satisfactoriamente";
				mail($to,$subject,$message,$headers);
				break;
			
			case 'contact':
				# code...
				break;
			case 'recoverPassword':
				$from='registro@bipoapp.com'. "\r\n";
				$headers="From:".$from;
				$headers .= "MIME-Version: 1.0" . "\r\n";
				$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
				$subject='Solicitaste un cambio de contraseña';
				$message="<html>
							<head>
							<title>Recuperación de contraseña</title>
							</head>
							<body>
							<p>Ingresa al siguiente <a href=".$Content.">link</a> para continuar</p>
							</body>
							</html>";
				mail($to,$subject,$message,$headers);
				break;
			
			default:
				# code...
				break;
			}	
		return true;
		}
		catch(exception $e){
			return $e->getMessage();
		}
		
	}

	function CreateFacebookPost($content,$link){
    $config = array();
    $config['appId'] = '1734546943514239';
    $config['secret'] = '9ce5d149a777bef76dfd55c012ecff84';
    $config['fileUpload'] = false; // optional
     
    $fb = new Facebook\Facebook ([
    'app_id' => '1734546943514239',
    'app_secret' => '9ce5d149a777bef76dfd55c012ecff84',
    'default_graph_version' => 'v2.4'
    ]);
     
    // define your POST parameters (replace with your own values)
    $params = array(
      "access_token" => "EAAYpj65qFn8BADfZBuO7WlalDNbyIYfsdouPmsh0y75ZB38iqmRV1sT7rUaTVPGsQPbhIegL5ixZAMhkxB6qlgpjD3V1i3wVlX5h6PUuOZCfloavnCfEBQUCePTwUAjJZBuVaeCFIk9ZBZBZB1bBkbPDHfXdJmoqxFWOZCF0BhBvxWeK6EKpYSE6GlGxIbI6pZCZATYgVQ5DHc1NwZDZD", // see: https://developers.facebook.com/docs/facebook-login/access-tokens/
      "message" => $content,
      "tags"=>"Policianacionaldeloscolombianos",
      "link" => $link,
      "name" => 'Reporte generado en la plataforma bipo',
      "caption" => "www.bipoapp.com",
      "description" => "Ingresa a nuesta pagina para más información"
    );
     
    // post to Facebook
    // see: https://developers.facebook.com/docs/reference/php/facebook-api/
    try {
      	$ret = $fb->post('/me/feed', $params);
    	$fb->sendRequest('POST', "109837433020784/feed",$params );

      return true;
    } catch(Exception $e) {
      echo $e->getMessage();
    }


	}
		function CreateTweet($content){
			try{


			$settings = array(
				'oauth_access_token' => '906614341671284736-mTLSK7EKLOx65ZjtwlPYfyXooIBAeDM',
				'oauth_access_token_secret' => 'DNosGYTwRfrnjFt0HZsnN1xzeDqZdO0URlEdkLcooUGEE',
				'consumer_key' => 'yB29f3JCLbblHgoUbrKCSjueg',
				'consumer_secret' => '5EkjtWVe5K2kbBpUY0LMYW5STsGtUHsIvKG2cgfSnUvWtlWPfs',
			);

			// url
			$url = "https://api.twitter.com/1.1/statuses/update.json";

			// tipo de metodo
			$requestMethod = 'POST';

			//tweet
			$postfields = array('status' => $content." ".'@AndreyVlasquez');

			// instancia de la conexion con twitter
			$twitter = new TwitterAPIExchange ($settings);

			// enviamos el tweet
			$response = $twitter->buildOauth($url, $requestMethod)
								->setPostfields($postfields)
								->performRequest();

								return true;
			}catch(exception $e)
			{
				return $e->getTraceAsString();
			}


	}
	?>