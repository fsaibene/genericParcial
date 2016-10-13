<?php
	require_once("BeforeValidException.php");
	require_once("SignatureInvalidException.php");
	require_once("JWT.php");
	require_once("ExpiredException.php");
	require_once("usuario.php");

	$postData = file_get_contents("php://input");
	$respuesta = json_decode($postData);
//print_r($respuesta);die();
$correcto = false;
//	$user;
$usuarios=Usuario::ToArray();

foreach($usuarios as $usuario) {
		if($usuario["correo"]== $respuesta->correo &&  $usuario["clave"]==$respuesta->clave){
			$correcto = true;
			$user=$usuario;
			break;
		}
	}
	if($correcto){
		$token=Array(
			"exp"=>time()+10000,
			"id"=>$user["id"],
			//"pass"=>$user["pass"], No se pasa el pass para no comprometer la cuenta del usuario.
			"correo"=>$user["correo"]);
		$token=Firebase\JWT\JWT::encode($token, "LOL123");
		$array["tokenTest"]=$token;
		echo json_encode($array);
	}else{
		echo false;
	}
?>