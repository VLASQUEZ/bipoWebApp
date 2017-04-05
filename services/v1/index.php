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
    	echoResponse(400,$response);
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
    		echoResponse(400,$response);
	    }

	}
  catch(exception $e)
  {
  	$response=array('error' =>true,'message'=>$e->getMessage() );
  	echoResponse(500,$response);	
  }
  
});

$app->post('/bike','authenticate', function() {
  try{
      $bikeResponse=new BikeAPI();
      $response=array();
      $response=$bikeResponse->registerBike($_POST["bikeName"],$_POST["idBrand"],$_POST["idColor"],$_POST["idFrame"],$_POST["idType"],$_POST["bikeFeatures"],$_POST["idBikeState"],$_POST["token"]);

      if($response["error"]==false)
      {
      echoResponse(200,$response);
      }
      else{
        echoResponse(400,$response);
      }

  }
  catch(exception $e)
  {
    $response=array('error' =>true,'message'=>$e->getMessage() );
    echoResponse(500,$response);  
  }
  
});
//fachada para obtener las marcas
$app->get('/brands','authenticate', function() {
  try{
    $bikeResponse=new bikeAPI();
    $response=array();
    $response=$bikeResponse->getBrands();
   
    if($response["error"]==false){
      echoResponse(200,$response);
    }
    else{
      echoResponse(400,$response);
    }
  }
  catch(exception $e)
  {
    $response=array('error' =>true,'message'=>$e->getMessage() );
    echoResponse(500,$response);  
  }
});
//fachada para obtener los estados de la bicicleta
$app->get('/bikeStates','authenticate', function() {
  try{
    $bikeResponse=new bikeAPI();
    $response=array();
    $response=$bikeResponse->getBikeStates();

    if($response["error"]==false){
      echoResponse(200,$response);
    }
    else{
      echoResponse(400,$response);
    }
  }
  catch(exception $e)
  {
    $response=array('error' =>true,'message'=>$e->getMessage() );
    echoResponse(500,$response);  
  }
  
});
//fachada para obtener los tipos de bicicletas
$app->get('/bikeTypes','authenticate', function() {
  try{
    $bikeResponse=new bikeAPI();
    $response=array();
    $response=$bikeResponse->getBikeTypes();

    if($response["error"]==false){
      echoResponse(200,$response);
    }
    else{
      echoResponse(400,$response);
    }
  }
  catch(exception $e)
  {
    $response=array('error' =>true,'message'=>$e->getMessage() );
    echoResponse(500,$response);  
  }
  
});
//fachada para obtener los colores de bicicleta
$app->get('/bikeColors','authenticate', function() {
  try{
    $bikeResponse=new bikeAPI();
    $response=array();
    $response=$bikeResponse->getBikeColor();

    if($response["error"]==false){
      echoResponse(200,$response);
    }
    else{
      echoResponse(400,$response);
    }
  }
  catch(exception $e)
  {
    $response=array('error' =>true,'message'=>$e->getMessage() );
    echoResponse(500,$response);  
  }
  
});
//fachada para obtener todas las bicicletas por usuario
$app->get('/bikes/:userName','authenticate', function($userName) {
  try{
    $bikeResponse=new bikeAPI();
    $response=array();
    $response=$bikeResponse->getBikesByUser($userName);

    if($response["error"]==false){
      echoResponse(200,$response);
    }
    else{
      echoResponse(400,$response);
    }
  }
  catch(exception $e)
  {
    $response=array('error' =>true,'message'=>$e->getMessage() );
    echoResponse(500,$response);  
  }
  
});
//fachada para obtener todas las bicicletas por usuario
$app->get('/bike/:userName/:bikeName','authenticate', function($userName,$bikeName) {
  try{
    $bikeResponse=new bikeAPI();
    $response=array();
    $response=$bikeResponse->getBikeByUser($userName,$bikeName);

    if($response["error"]==false){
      echoResponse(200,$response);
    }
    else{
      echoResponse(400,$response);
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