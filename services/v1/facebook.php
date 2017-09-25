<?php
// initialize Facebook class using your own Facebook App credentials
    // see: https://developers.facebook.com/docs/php/gettingstarted/#install
error_reporting(E_ALL);
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
//require("../libs/Facebook/Facebook.php");
require_once "../libs/Facebook/autoload.php";

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
      "access_token" => "EAAYpj65qFn8BAP45vastGXwDzBgTBlUChexg7Ag89TT74NTrxYOMkcpp6esNs1xRc9ZBVNcTG7olI74qJmTuKOyJtEM9wVnWMtZBP6EkN3K8yVWEoqkQHtZAGUfsG0iWZBzTIphE78Dsf18CPuhUyyuwjkbr2a3jiLXvYFSZB9nGJ27vGf9iKRVZAZBIJs0RZCIZD", // see: https://developers.facebook.com/docs/facebook-login/access-tokens/
      "message" => "Here is a blog post about auto posting on Facebook using PHP #php #facebook @Policianacionaldeloscolombianos",
      "tags"=>"Policianacionaldeloscolombianos",
      "link" => "http://www.pontikis.net/blog/auto_post_on_facebook_with_php",
      "name" => "How to Auto Post on Facebook with PHP",
      "caption" => "www.pontikis.net",
      "description" => "Automatically post on Facebook with PHP using Facebook PHP SDK. How to create a Facebook app. Obtain and extend Facebook access tokens. Cron automation."
    );
     
    // post to Facebook
    // see: https://developers.facebook.com/docs/reference/php/facebook-api/
    try {
      $ret = $fb->post('/me/feed', $params);
      $fb->sendRequest('POST', "109837433020784/feed",$params );
      echo 'Successfully posted to Facebook';
    } catch(Exception $e) {
      echo $e->getMessage();
    }
?>