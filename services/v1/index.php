<?php 
header("Access-Control-Allow-Origin: *");
header('Access-Control-Allow-Credentials: true');
header('Access-Control-Allow-Methods: PUT, GET, POST,');
header("Access-Control-Allow-Headers: X-Requested-With");
header('Content-Type: text/html; charset=utf-8');
header('P3P: CP="IDC DSP COR CURa ADMa OUR IND PHY ONL COM STA"');

require("../models/requires.php");
$app->get('/login','authenticate', function() {
   try{
   	$userResponse=new UserAPI();
    $response=array();
    $response=$userResponse->login($_GET["email"],$_GET["password"]);
    if($response["error"]==false){
    	echoResponse(200,$response);
    }
    else{
    	echoResponse(500,$response);
    }
  }
  catch(exception $e)
  {
  	$response=array('error' =>true,'message'=>$e->getMessage() );
  	echoResponse(500,$response);	
  }
  
});
$app->post('/register','authenticate', function() {
	try{
	    $userResponse=new UserAPI();
	    $response=array();
	    $response=$userResponse->registerUser($_POST["name"],$_POST["lastName"],$_POST["email"],
			$_POST["birthdate"],$_POST["cellphone"],$_POST["document"],$_POST["password"]);

	    if($response["error"]==false)
	    {
			echoResponse(200,$response);
	    }
	    else{
    		echoResponse(500,$response);
	    }

	}
  catch(exception $e)
  {
  	$response=array('error' =>true,'message'=>$e->getMessage() );
  	echoResponse(500,$response);	
  }
  
});
$app->run();

?>