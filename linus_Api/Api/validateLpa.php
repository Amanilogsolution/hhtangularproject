<?php
	ini_set("display_errors", 1);
	require './vendor/autoload.php';
	use \Firebase\JWT\JWT;
	include_once("./headers/swim_eprontoexpress.php");
	include_once("./classes/api_class.php");
	$secret_key = "owt125";
	$jwt = null;
	$lpaObj = new Api;
	if($_SERVER['REQUEST_METHOD'] === "POST")
	{
		$headers = getallheaders();
		$jwt = $headers["authorization"];
		$token = explode(" ", $jwt)[1];
		$lpaInputs = json_decode(file_get_contents("php://input"));
		if(!empty($lpaInputs->wh) && !empty($lpaInputs->WhLocation))
		{
			try{
				$decoded_data = JWT::decode($token, $secret_key, array('HS512'));
				$validLpaData = $lpaObj->ValidateLpa($lpaInputs->wh, $lpaInputs->WhLocation);
				count($validLpaData['records'])==0?http_response_code(400):http_response_code(200); // Ok
				echo json_encode(array(
					"status" => count($validLpaData['records'])==0?0:1,
					"lpalist" => $validLpaData
				));
			}catch(Exception $ex){
				http_response_code(500); // no data found
				echo json_encode(array(
					"status" => 0,
					"message" => $ex->getMessage()
				));
			}
		}else{
			http_response_code(404);
			echo json_encode(array(
				"status" => 0,
				"message" => "Required parameters missing"
			));
		}
	}	
?>	