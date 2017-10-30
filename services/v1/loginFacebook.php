<?php
session_start();
 
error_reporting(E_ALL);
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
//require("../libs/Facebook/Facebook.php");
require_once "../libs/Facebook/autoload.php";
if(empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] === 'off'){
$protocolo = 'http://';
}else{
$protocolo = 'https://';
}
$fb = new Facebook\Facebook ([
    'app_id' => '1734546943514239',
    'app_secret' => '9ce5d149a777bef76dfd55c012ecff84',
    'default_graph_version' => 'v2.4'
    ]);
$super_url = $protocolo . $_SERVER['SERVER_NAME'] . dirname($_SERVER['PHP_SELF']);

$helper = $fb->getRedirectLoginHelper();
$permissions = ['email','user_tagged_places', 'publish_actions', 'manage_pages', 'publish_pages'];
$loginUrl = $helper->getLoginUrl($super_url . '/facebook.php', $permissions);

//$helper = $fb->getPageTabHelper();
//$helper = $fb->getJavaScriptHelper();
 
 
if ( isset($_SESSION['facebook_access_token']) ) {
  try {
 
    // Get the access token
    echo file_get_contents("../include/tokenFacebook.txt");
  } catch( Facebook\Exceptions\FacebookSDKException $e ) {
 
    // There was an error communicating with Graph
    echo $e->getMessage();
    exit;
  }
}else{
	echo '<a href="' . $loginUrl . '">Conectar con mi super Facebook</a>';
}


?>