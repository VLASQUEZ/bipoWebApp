<?php

global $key;



// note the spaces
function encrypt($str)
{
	$iv = mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC),MCRYPT_DEV_URANDOM);
	$key = md5('b1p0BdUs3R');
	$encrypted = base64_encode($iv.mcrypt_encrypt(MCRYPT_RIJNDAEL_128,hash('sha256', $key, true),$str,MCRYPT_MODE_CBC,$iv));
	//echo $encrypted;
	
	return $encrypted;
}

function decrypt($str)
{ 
	$data = base64_decode($str);
	$iv = substr($data, 0, mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC));
	$key = md5('b1p0BdUs3R');
	$decrypted = rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_128,hash('sha256', $key, true),substr($data, mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC)),
      MCRYPT_MODE_CBC,$iv),"\0");
	
	return $decrypted;
}
function createToken($data){
	$token =hash('sha256',$data);
	return $token;
}

?>