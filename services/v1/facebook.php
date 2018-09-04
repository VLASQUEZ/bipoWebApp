<?php
// initialize Facebook class using your own Facebook App credentials
    // see: https://developers.facebook.com/docs/php/gettingstarted/#install
error_reporting(E_ALL);
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
//require("../libs/Facebook/Facebook.php");
require_once "../libs/Facebook/autoload.php";
session_start();

    $config = array();
    $config['appId'] = '1734546943514239';
    $config['secret'] = '9ce5d149a777bef76dfd55c012ecff84';
    $config['fileUpload'] = false; // optional
     
    $fb = new Facebook\Facebook ([
    'app_id' => '1734546943514239',
    'app_secret' => '9ce5d149a777bef76dfd55c012ecff84',
    'default_graph_version' => 'v2.4'
    ]);
     
    $helper = $fb->getRedirectLoginHelper();
try {
  $accessToken = $helper->getAccessToken();
} catch(Facebook\Exceptions\FacebookResponseException $e) {
  // When Graph returns an error
  echo 'Graph returned an error: ' . $e->getMessage();
  exit;
} catch(Facebook\Exceptions\FacebookSDKException $e) {
  // When validation fails or other local issues
  echo 'Facebook SDK returned an error: ' . $e->getMessage();
  exit;
}

if (isset($accessToken)) {

  // Esta token está muy bien, pero es de corta duración
  $_SESSION['facebook_access_token'] = (string) $accessToken;

  // Gestor de autorizaciones super inteligente
  $oAuth2Client = $fb->getOAuth2Client();

  // Intercambiazo para obtener una token de larga duración
  $super_token = (string) $oAuth2Client->getLongLivedAccessToken( $_SESSION['facebook_access_token'] );
  $_SESSION['facebook_access_token'] = $super_token;
  $file = fopen("../include/tokenFacebook.txt", "w");
    fwrite($file, $accessToken);
    fclose($file);
  header("Location: ./loginFacebook.php");

}
?>