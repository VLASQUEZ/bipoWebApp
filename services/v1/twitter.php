<?
error_reporting(E_ALL);
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
//require("../libs/requires.php");
require("../libs/twitter-api-php/TwitterAPIExchange.php");

	function createTwitter($content){


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
			$postfields = array('status' => $content.'@AndreyVlasquez');

			// instancia de la conexion con twitter
			$twitter = new TwitterAPIExchange ($settings);

			// enviamos el tweet
			$response = $twitter->buildOauth($url, $requestMethod)
								->setPostfields($postfields)
								->performRequest();

								return true;
			}catch(exception $e)
			{
				 echo $e->getTraceAsString();
			}
	}



?>