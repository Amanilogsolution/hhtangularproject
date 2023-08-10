<?php
	ini_set("display_errors", 1);
	require './vendor/autoload.php';
	use \Firebase\JWT\JWT;
	include_once("./headers/swim_eprontoexpress.php");
	include_once("./classes/api_class.php");
	$secret_key = "owt125";
	$jwt = null;
	$skuUpdateObj = new Api;
	if($_SERVER['REQUEST_METHOD'] === "POST"){
		$headers = getallheaders();
		$jwt = $headers["authorization"];
		$token = explode(" ", $jwt)[1];
		$skuUpdateInputs = json_decode(file_get_contents("php://input"));

		if(!empty($skuUpdateInputs->wh) && !empty($skuUpdateInputs->dnno) && !empty($skuUpdateInputs->sku)  && !empty($skuUpdateInputs->qty))
		{
		
			try{
				$decoded_data = JWT::decode($token, $secret_key, array('HS512'));
				$page = isset($_GET['page']) ? $_GET['page'] : 1;
				$records_per_page = $page;
				$numer_of_records =10;
				$skuUpdateData = $skuUpdateObj->getskuUpdateData($skuUpdateInputs->wh, $skuUpdateInputs->dnno, $skuUpdateInputs->sku, $skuUpdateInputs->batchno, $skuUpdateInputs->qty, $skuUpdateInputs->whlocation);
				
				//echo $skuUpdateData;exit;
				//$rackLocationData = $skuUpdateObj->getrackLocationData($skuUpdateInputs->wh, $skuUpdateInputs->dnno, $skuUpdateInputs->whlocation, $records_per_page, $numer_of_records);
				$rowCount = $skuUpdateObj->rackLocationCount($skuUpdateInputs->wh, $skuUpdateInputs->dnno, $skuUpdateInputs->whlocation);
				$rackLocationArray = array();
				$rackLocationArray["records"]=array();
				$rackLocationArray["numberOfPage"]=array();
				$rackLocationArray["numberOfRecords"]=array();
				// while($row = sqlsrv_fetch_array($rackLocationData, SQLSRV_FETCH_ASSOC))
				// {
					// $rackLocationArr = array(
						// "skuCode" => $row['sku'],
						// "skudesc" => $row['skudesc'],
						// "batchno" => $row['batchno'],
						// "inv" 	  => $row['InvQty'],
						// "pck" 	  => $row['PickQTy'],
					// );
				// array_push($rackLocationArray["records"], $rackLocationArr);
				// }
				$rackLocationArray["numberOfPage"]=$records_per_page;
				$rackLocationArray["numberOfRecords"]=$rowCount;
				if($skuUpdateData==='Record updated successfully'){
					http_response_code(200);
					echo json_encode(array(
						"message" => $skuUpdateData,
						'success' => true,
						"status" => 1,
						//"rackLocation" => $rackLocationArray
					));
				}else{
					http_response_code(200);
					echo json_encode(array(
						"message" => $skuUpdateData,
						'failed' => true,
						"status" => 0,
						//"rackLocation" => $rackLocationArray
					));
				}
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