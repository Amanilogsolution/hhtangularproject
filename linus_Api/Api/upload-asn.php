<?php
	ini_set("display_errors", 1);
	require './vendor/autoload.php';
	use \Firebase\JWT\JWT;
	include_once("./headers/swim_eprontoexpress.php");
	include_once("./classes/api_class.php");
	$secret_key = "owt125";
	$jwt = null;
	$saveUplodDataObj = new Api;
	if($_SERVER['REQUEST_METHOD'] === "POST"){
		$headers = getallheaders();
		$jwt = $headers["authorization"];
		$token = explode(" ", $jwt)[1];
		$saveUplodDataInputs = json_decode(file_get_contents("php://input"));
		if(!empty($saveUplodDataInputs->userId) && !empty($saveUplodDataInputs->locationId) && !empty($saveUplodDataInputs->asnno) && !empty($saveUplodDataInputs->data))
		{
			try{
				$decoded_data = JWT::decode($token, $secret_key, array('HS512'));
				$saveUplodData = $saveUplodDataObj->uploadFileData($saveUplodDataInputs->userId, $saveUplodDataInputs->locationId, $saveUplodDataInputs->asnno, $saveUplodDataInputs->data);
				http_response_code(200); // Ok
				echo json_encode(array(
					"status" => 1,
					"messsage" => $saveUplodData
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
				"message" => "All inputes needed"
			));
		}
	}	
?>	