<?php 
header('Access-Control-Allow-Credentials: true');
header("Access-Control-Allow-Headers: X-Requested-With");
header('Content-Type: text/html; charset=utf-8');
header('P3P: CP="IDC DSP COR CURa ADMa OUR IND PHY ONL COM STA"');
require("../models/requires.php");
$app->get('/login','authenticate', function() {
   try{
   	$userResponse=new UserAPI();
    $response=array();
    $response=$userResponse->login($_GET["email"],$_GET["password"],$_GET["loggedWeb"],$_GET["loggedMobile"]);
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
$app->post('/logout','authenticate', function() {
   try{
    $userResponse=new UserAPI();
    $response=array();
    $response=$userResponse->logout($_POST["token"],$_POST["loggedWeb"],$_POST["loggedMobile"]);
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
$app->post('/setPreferences','authenticate', function() {
  try{
      $userResponse=new UserAPI();
      $response=array();
      $response=$userResponse->setpreferences($_POST["token"],$_POST["emailReceiver"],$_POST["photoPublication"],$_POST["enableReportUbication"],$_POST["enableLocationUbication"]);

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
$app->get('/getPreferences','authenticate', function() {
  try{
      $userResponse=new UserAPI();
      $response=array();
      $response=$userResponse->getPreferences
      ();

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
//fachada para poner como default una bicicleta
$app->post('/defaultBike','authenticate', function() {
  try{
      $bikeResponse=new BikeAPI();
      $response=array();
      $response=$bikeResponse->defaultBike($_POST["bikeId"],$_POST["token"]);

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
//fachada para obtener los tipos de reporte
$app->get('/reportType','authenticate', function() {
  try{
    $reportResponse=new ReportAPI();
    $response=array();
    $response=$reportResponse->getReportType();
   
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
//fachada para obtener los tipos de reporte
$app->get('/getReportsMaps','authenticate', function() {
  try{
    $reportResponse=new ReportAPI();
    $response=array();
    $response=$reportResponse->getReportsMaps();
   
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
$app->get('/bikes/:token','authenticate', function($token) {
  try{
    $bikeResponse=new bikeAPI();
    $response=array();
    $response=$bikeResponse->getBikesByUser($token);

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
//fachada para obtener una bicicleta por nombre
$app->get('/bike/:token/:bikeName','authenticate', function($token,$bikeName) {
  try{
    $bikeResponse=new bikeAPI();
    $response=array();
    $response=$bikeResponse->getBikeByUser($token,$bikeName);

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
//fachada para almacenar la foto de la bicicleta en el servidor
$app->post('/bikePhoto','authenticate', function() {
  try{
      $bikeResponse=new bikeAPI();
      
      $response=$bikeResponse->savephoto($_POST['bikeId'],$_POST['token'],$_FILES['file']);

      if($response["error"]==false){
        echoResponse(200,$response);
      }
      else{
        echoResponse(400,$response);
      }
    
  }
  catch(exception $e)
  {
    $response=array('error' =>true,'message'=>$e->getMessage());
    echoResponse(500,$response);  
  }
  
});
//fachada para almacenar la foto de la bicicleta en el servidor
$app->post('/setPassword','authenticate', function() {
  try{
      $userResponse=new UserAPI();
      
      $response=$userResponse->setPassword($_POST['password'],$_POST['token']);

      if($response["error"]==false){
        echoResponse(200,$response);
      }
      else{
        echoResponse(400,$response);
      }
    
  }
  catch(exception $e)
  {
    $response=array('error' =>true,'message'=>"No se pudo verificar la cuenta ");
    echoResponse(500,$response);  
  }
  
});
//Actualizar bicicleta
$app->post('/updateBike','authenticate', function() {
  try{
      $bikeResponse=new BikeAPI();
      
      $response=$bikeResponse->updateBike($_POST['token'],$_POST['bikeId'],$_POST['idColor'],$_POST['bikeFeatures']);

      if($response["error"]==false){
        echoResponse(200,$response);
      }
      else{
        echoResponse(400,$response);
      }
    
  }
  catch(exception $e)
  {
    $response=array('error' =>true,'message'=>$e->getMessage());
    echoResponse(500,$response);  
  }
  
});
//fachada para eliminar una bicicleta
$app->post('/deleteBike','authenticate', function() {
  try{
      $bikeResponse=new bikeAPI();
      
      $response=$bikeResponse->deleteBike($_POST['bikeId'],$_POST['token']);

      if($response["error"]==false){
        echoResponse(200,$response);
      }
      else{
        echoResponse(400,$response);
      }
    
  }
  catch(exception $e)
  {
    $response=array('error' =>true,'message'=>$e->getMessage());
    echoResponse(500,$response);  
  }
  
});
//fachada para registrar un reporte 
$app->post('/report','authenticate', function() {
  try{
      $reportResponse=new ReportAPI();
      
      $response=$reportResponse->registerReport($_POST['token'],$_POST['reportName'],$_POST['reportType'],
                        $_POST['coordinates'],$_POST['idBike'],$_POST['reportDetails']);

      if($response["error"]==false){
        echoResponse(200,$response);
      }
      else{
        echoResponse(400,$response);
      }
    
  }
  catch(exception $e)
  {
    $response=array('error' =>true,'message'=>$e->getMessage());
    echoResponse(500,$response);  
  }
  
});
// fachada para obtener un reporte por usuario y por nombre
$app->get('/report/:token/:reportName','authenticate', function($token,$reportName) {
  try{
    $reportResponse=new ReportAPI();
    $response=array();
    $response=$reportResponse->getReportByName($token,$reportName);

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
// fachada para obtener todos los reportes de un usuario
$app->get('/reportsUser/:token','authenticate', function($token) {
  try{
    $reportResponse=new ReportAPI();
    $response=array();
    $response=$reportResponse->getReportsByToken($token);

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
// fachada para obtener un reporte por id
$app->get('/report/:reportId', function($id) {
  try{
    $reportResponse=new ReportAPI();
    $response=array();
    $response=$reportResponse->getReportById($id);

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
//fachada para almacenar la foto del reporte en el servidor
$app->post('/reportPhoto','authenticate', function() {
  try{
      $reportResponse=new ReportAPI();
      
      $response=$reportResponse->savephoto($_POST['reportId'],$_FILES['image'],$_POST['token']);

      if($response["error"]==false){
        echoResponse(200,$response);
      }
      else{
        echoResponse(400,$response);
      }
    
  }
  catch(exception $e)
  {
    $response=array('error' =>true,'message'=>$e->getMessage());
    echoResponse(500,$response);  
  }
  
});
//enviar email
$app->post('/sendEmail','authenticate', function() {
  try{
      $reportResponse=new ReportAPI();
      
      $response=$reportResponse->sendEmail($_POST['token'],$_POST['type']);

      if($response["error"]==false){
        echoResponse(200,$response);
      }
      else{
        echoResponse(400,$response);
      }
    
  }
  catch(exception $e)
  {
    $response=array('error' =>true,'message'=>$e->getMessage());
    echoResponse(500,$response);  
  }
  
});
//fachada para obtener todos los reportes por tipo
$app->get('/reports/:reportType','authenticate', function($idReportType) {
  try{
    $reportResponse=new ReportAPI();
    $response=array();
    $response=$reportResponse->getReports($idReportType,$_GET['fhInicio'],$_GET['fhFin']);
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
//fachada para obtener los ultimos 10 reportes
$app->get('/lastReports','authenticate', function() {
  try{
    $reportResponse=new ReportAPI();
    $response=array();
    $response=$reportResponse->getLastReports();
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
//fachada para verificar si ya existe un usuario
$app->get('/user/:email','authenticate', function($email) {
  try{
    $userResponse=new UserAPI();
    $response=array();
    $response=$userResponse->userExist($email);
   
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

//Cambiar Password
$app->post('/updatePassword','authenticate', function() {
  try{
      $reportResponse=new UserAPI();
      
      $response=$reportResponse->updatePassword($_POST['email'],$_POST['password'],$_POST['newPassword']);

      if($response["error"]==false){
        echoResponse(200,$response);
      }
      else{
        echoResponse(400,$response);
      }
    
  }
  catch(exception $e)
  {
    $response=array('error' =>true,'message'=>$e->getMessage());
    echoResponse(500,$response);  
  }
  
});
//Cambiar Password
$app->post('/recoverPass','authenticate', function() {
  try{
      $reportResponse=new UserAPI();
      
      $response=$reportResponse->recoverPass($_POST['email']);

      if($response["error"]==false){
        echoResponse(200,$response);
      }
      else{
        echoResponse(400,$response);
      }
    
  }
  catch(exception $e)
  {
    $response=array('error' =>true,'message'=>$e->getMessage());
    echoResponse(500,$response);  
  }
  
});
//Facebook
$app->get('/twitter', function() {
  try{
      $reportResponse=new ReportAPI();
      
      $response=CreateTweet($_GET["content"]);

      if($response["error"]==false){
        echoResponse(200,$response);
      }
      else{
        echoResponse(400,$response);
      }
    
  }
  catch(exception $e)
  {
    $response=array('error' =>true,'message'=>$e->getMessage()." ".$e->getTraceAsString());
    echoResponse(500,$response);  
  }
  
});
//Facebook
$app->get('/facebook', function() {
  try{
      $reportResponse=new ReportAPI();
      
      $response=CreateFacebookPost($_GET["content"]);

      if($response["error"]==false){
        echoResponse(200,$response);
      }
      else{
        echoResponse(400,$response);
      }
    
  }
  catch(exception $e)
  {
    $response=array('error' =>true,'message'=>$e->getMessage()." ".$e->getTraceAsString());
    echoResponse(500,$response);  
  }
  
});
$app->run();

?>