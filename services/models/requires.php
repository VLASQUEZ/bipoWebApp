<?php
error_reporting(E_ALL);
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);

date_default_timezone_set('America/Bogota');

global $prefijo;
global $path;
$path=(isset($_SERVER["DOCUMENT_ROOT"]) && $_SERVER["DOCUMENT_ROOT"]!="") ? $_SERVER["DOCUMENT_ROOT"]."/" : "/var/www/html/";

//para desarrollo
$path=$path."/bipo/services/";
//para pruebas/produccion
//$path=$path."/services/";
require($path."libs/Slim/Slim.php");
require_once $path ."libs/Facebook/autoload.php";
require_once $path ."libs/twitter-api-php/TwitterAPIExchange.php";
require($path."include/config.php");
require($path."include/utilities.php");
require($path."include/encrypt.php");
require($path."models/jsonResponse.php");
require($path."models/requires.ini.php");
require($path."controllers/requires.controllers.php");


\Slim\Slim::registerAutoloader();
global $app;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
$app = new \Slim\Slim();

	function echoResponse($status_code, $response) {
		$app = \Slim\Slim::getInstance();
		// Http response code
		$app->status($status_code);
 
		// setting response content type to json
		$app->contentType('application/json');
		echo json_encode($response,JSON_UNESCAPED_UNICODE|JSON_PARTIAL_OUTPUT_ON_ERROR);
	}
	function authenticate(\Slim\Route $route) {
		// Getting request headers
		$headers = apache_request_headers();
		$response = array();
		$app = \Slim\Slim::getInstance();

		// Verifying Authorization Header
		if (isset($headers['Authorization'])) {
			//$db = new DbHandler(); //utilizar para manejar autenticacion contra base de datos

			// get the api key
			$token = $headers['Authorization'];

			// validating api key
			if (!($token == API_KEY)) { //API_KEY declarada en Config.php

			// api key is not present in users table
			$response["error"] = true;
			$response["message"] = "Acceso denegado. Token inválido";
			echoResponse(401, $response);
			$app->stop(); //Detenemos la ejecución del programa al no validar

			} 
			else {
			//procede utilizar el recurso o metodo del llamado
			}
		} 
		else {
			// api key is missing in header
			$response["error"] = true;
			$response["message"] = "Falta token de autorización";
			echoResponse(400, $response);

			$app->stop();
		}
	}
?>