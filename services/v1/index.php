<?php 
header("Access-Control-Allow-Origin: *");
header('Access-Control-Allow-Credentials: true');
header('Access-Control-Allow-Methods: PUT, GET, POST,');
header("Access-Control-Allow-Headers: X-Requested-With");
header('Content-Type: text/html; charset=utf-8');
header('P3P: CP="IDC DSP COR CURa ADMa OUR IND PHY ONL COM STA"');

require("../models/requires.php");
$app->get('/users','authenticate', function() {
    $userResponse=new UserAPI();
    $response=array();
    if(isset($_GET["id"])){
    	$response=$userResponse->getUser($_GET["id"]);
    }
    else{
    	$response=$userResponse->getUser();
    }
  try{
  	echoResponse(200,$response);
  }
  catch(exception $e)
  {
  	echoResponse(500,$e);	
  }
  
});
$app->run();

?>