<?php
	ini_set("display_errors", 1);
	require './vendor/autoload.php';
	use \Firebase\JWT\JWT;
	include_once("./headers/swim_eprontoexpress.php");
	include_once("./classes/api_class.php");
	$secret_key = "owt125";
	$jwt = null;
	$pickListDetailsDataObj = new Api;
	if($_SERVER['REQUEST_METHOD'] === "POST"){
		$headers = getallheaders();
		$jwt = $headers["authorization"];
		$token = explode(" ", $jwt)[1];
		$pickListInputs = json_decode(file_get_contents("php://input"));
		if(!empty($pickListInputs->wh) && !empty($pickListInputs->dnno)){
			try{
				$decoded_data = JWT::decode($token, $secret_key, array('HS512'));
				$pickListDetailsData = $pickListDetailsDataObj->getPickListDetailsData($pickListInputs->wh, $pickListInputs->dnno);
				$pickListDetailsArray = array();
				while($row = sqlsrv_fetch_array($pickListDetailsData, SQLSRV_FETCH_ASSOC))
				{
					$pickListDetailsArray[] = array(
						"location" => $row['whlocation'],
						"skuCode" => $row['sku'],
						"description" => $row['skudesc'],
						"inv" => $row['InvQty'],
						"pck" => $row['PickQTy'],
					);
				}
				
				http_response_code(200);
				echo json_encode(array(
				"status" => 1,
				"pickListDetails" => $pickListDetailsArray
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
				"message" => "All Input needed"
			));
		}
	}	
?>	