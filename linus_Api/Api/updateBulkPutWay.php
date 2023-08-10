<?php
	ini_set("display_errors", 1);
	require './vendor/autoload.php';
	use \Firebase\JWT\JWT;
	include_once("./headers/swim_eprontoexpress.php");
	include_once("./classes/api_class.php");
	$secret_key = "owt125";
	$jwt = null;
	$asnSkuObj = new Api;
	if($_SERVER['REQUEST_METHOD'] === "POST")
	{
		$headers = getallheaders();
		$jwt = $headers["authorization"];
		$token = explode(" ", $jwt)[1];
		$asnSkuInputs = json_decode(file_get_contents("php://input"));
		if(!empty($asnSkuInputs->wh) && !empty($asnSkuInputs->custId) && !empty($asnSkuInputs->custInv) && !empty($asnSkuInputs->boxId) && !empty($asnSkuInputs->location) && !empty($asnSkuInputs->skuData))
		{
           
			try{
				$decoded_data = JWT::decode($token, $secret_key, array('HS512'));
				$tempPutWayData = $asnSkuObj->InsertUpdateBulkPutWay($asnSkuInputs->wh, $asnSkuInputs->custId, $asnSkuInputs->custInv, $asnSkuInputs->boxId, $asnSkuInputs->location, $asnSkuInputs->skuData, $decoded_data->data->name);
				http_response_code(200); // Ok
				echo json_encode($tempPutWayData);
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