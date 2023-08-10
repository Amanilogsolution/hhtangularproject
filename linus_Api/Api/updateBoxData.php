<?php
	ini_set("display_errors", 1);
	require './vendor/autoload.php';
	use \Firebase\JWT\JWT;
	include_once("./headers/swim_eprontoexpress.php");
	include_once("./classes/api_class.php");
	$secret_key = "owt125";
	$jwt = null;
	$pickingSubObj = new Api;
	if($_SERVER['REQUEST_METHOD'] === "POST")
	{
		$headers = getallheaders();
		$jwt = $headers["authorization"];
		$token = explode(" ", $jwt)[1];
		$pickingSubInputs = json_decode(file_get_contents("php://input"));
		
		if(!empty($pickingSubInputs->wh) && !empty($pickingSubInputs->custid) && !empty($pickingSubInputs->dnno) && !empty($pickingSubInputs->custinvno) && !empty($pickingSubInputs->boxno) && !empty($pickingSubInputs->sku)  && !empty($pickingSubInputs->qty) && !empty($pickingSubInputs->whlocation))
		{
			try{
				$decoded_data = JWT::decode($token, $secret_key, array('HS512'));
				$pickingSubData = $pickingSubObj->InsertUpdatePickingSub($pickingSubInputs->wh, $pickingSubInputs->custid, $pickingSubInputs->dnno, $pickingSubInputs->custinvno, $pickingSubInputs->boxno, $pickingSubInputs->sku, $pickingSubInputs->batchno, $pickingSubInputs->qty, $pickingSubInputs->whlocation, $decoded_data->data->name);
				http_response_code(200); // Ok
				echo json_encode(array(
					"status" => 1,
					"message" => $pickingSubData
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