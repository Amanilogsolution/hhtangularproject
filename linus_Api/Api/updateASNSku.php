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
		
		if(!empty($asnSkuInputs->asnNo) && !empty($asnSkuInputs->WHLocation) && !empty($asnSkuInputs->sku) && !empty($asnSkuInputs->custId) && !empty($asnSkuInputs->invoiceNo) && !empty($asnSkuInputs->qty) && !empty($asnSkuInputs->scannedQty) && !empty($asnSkuInputs->whid))
		{
			try{
				$decoded_data = JWT::decode($token, $secret_key, array('HS512'));
				$asnSkuData = $asnSkuObj->InsertUpdateupdateASNSku($asnSkuInputs->asnNo, $asnSkuInputs->WHLocation, $asnSkuInputs->sku, $asnSkuInputs->custId, $asnSkuInputs->invoiceNo, $asnSkuInputs->qty, $asnSkuInputs->scannedQty, $decoded_data->data->name, $asnSkuInputs->whid);
				http_response_code(200); // Ok
				echo json_encode($asnSkuData);
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