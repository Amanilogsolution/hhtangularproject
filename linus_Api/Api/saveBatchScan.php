<?php
	ini_set("display_errors", 1);
	require './vendor/autoload.php';
	use \Firebase\JWT\JWT;
	include_once("./headers/swim_eprontoexpress.php");
	include_once("./classes/api_class.php");
	$secret_key = "owt125";
	$jwt = null;
	$BinDataObj = new Api;
	if($_SERVER['REQUEST_METHOD'] === "POST"){
		$headers = getallheaders();
		$jwt = $headers["authorization"];
		$token = explode(" ", $jwt)[1];
		$cycleCount = json_decode(file_get_contents("php://input"));
		if(!empty($cycleCount->wh) && !empty($cycleCount->Custid) && !empty($cycleCount->Batchid) && !empty($cycleCount->WHLocation)){
			try{
				$decoded_data = JWT::decode($token, $secret_key, array('HS512'));

				$BinData = $BinDataObj->saveBatchScanBin($cycleCount->wh, $cycleCount->Custid, $cycleCount->Batchid, $cycleCount->WHLocation, $cycleCount->CCQTY, $cycleCount->data, $decoded_data->data->name);
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