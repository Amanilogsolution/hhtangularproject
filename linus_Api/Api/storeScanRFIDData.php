<?php
	ini_set("display_errors", 1);
	require './vendor/autoload.php';
	use \Firebase\JWT\JWT;
	include_once("./headers/swim_eprontoexpress.php");
	include_once("./classes/api_class.php");
	$secret_key = "owt125";
	$jwt = null;
	$ASNRFIDDataObj = new Api;
	if($_SERVER['REQUEST_METHOD'] === "POST"){
		$headers = getallheaders();
		$jwt = $headers["authorization"];
		$token = explode(" ", $jwt)[1];
		$rfidData = json_decode(file_get_contents("php://input"));
		if(!empty($rfidData->wh) && !empty($rfidData->BoxNo) && !empty($rfidData->P_MRN_NO) && !empty($rfidData->CUSTID) && !empty($rfidData->INVOICENO)){
			try{
				$decoded_data = JWT::decode($token, $secret_key, array('HS512'));

				$BinData = $ASNRFIDDataObj->storeScanRFIDData($rfidData->wh, $rfidData->BoxNo, $rfidData->P_MRN_NO, $rfidData->CUSTID, $rfidData->INVOICENO, $rfidData->store, $decoded_data->data->name);
                echo json_encode($BinData); 
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
				"message" => "All Input needed"
			));
		}
	}	
?>	