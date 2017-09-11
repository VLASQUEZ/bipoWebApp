<?php
date_default_timezone_set('America/Bogota');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


require("../models/requires.php");
if (!session_id()) {
    session_start();
}
$fb = new Facebook\Facebook(array(
  'app_id'  => $config['App_ID'],
  'app_secret' => $config['App_Secret'],
    'default_graph_version' => 'v2.10',
    'persistent_data_handler'=>'session'
));
echo $config['App_ID'];
$helper = $fb->getRedirectLoginHelper();

try {
	$accessToken = $helper->getAccessToken();
	$linkData = [
		'link' => 'bipoapp.com',
		'message' => "Reporte generado por un bot en la fecha: ".date("Y/m/d H:m:s"),
	];
	var_dump($fb->post('/feed', $linkData, $accessToken));
	
} catch (Facebook\Exceptions\FacebookResponseException $e) {
	// When Graph returns an error
	echo 'Graph returned an error: ' . $e->getMessage();
} catch (Facebook\Exceptions\FacebookSDKException $e) {
	// When validation fails or other local issues
	echo 'Facebook SDK returned an error: ' . $e->getMessage();
}

?>
