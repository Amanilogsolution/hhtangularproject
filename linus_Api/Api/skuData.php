<?php
	ini_set("display_errors", 1);
	require './vendor/autoload.php';
	use \Firebase\JWT\JWT;
	include_once("./headers/swim_eprontoexpress.php");
	include_once("./classes/api_class.php");
	$secret_key = "owt125";
	$jwt = null;
	$skuObj = new Api;
	if($_SERVER['REQUEST_METHOD'] === "POST")
	{
		$headers = getallheaders();
		$jwt = $headers["authorization"];
		$token = explode(" ", $jwt)[1];
		$skuInputs = json_decode(file_get_contents("php://input"));
		if(!empty($skuInputs->skuCode) && !empty($skuInputs->wh) && !empty($skuInputs->dnno) && !empty($skuInputs->whlocation)){
			try{
				$decoded_data = JWT::decode($token, $secret_key, array('HS512'));
				$page = isset($_GET['page']) ? $_GET['page'] : 1;
				$records_per_page = $page;
				$numer_of_records =10;
				$skuData = $skuObj->skuData($skuInputs->skuCode, $skuInputs->wh, $skuInputs->dnno, $skuInputs->whlocation, $records_per_page, $numer_of_records);
				$rowCount = $skuObj->skuCount($skuInputs->skuCode, $skuInputs->wh, $skuInputs->dnno, $skuInputs->whlocation);
				if($rowCount > 0){
					$skuArray = array();
					$skuArray["records"]=array();
					$skuArray["numberOfPage"]=array();
					$skuArray["numberOfRecords"]=array();
					while($row = sqlsrv_fetch_array($skuData, SQLSRV_FETCH_ASSOC))
					{
						$skuArr   = array(
							"skuCode" => $row['sku'],
							"skudesc" => $row['skudesc'],
							"batchno" => $row['batchno'],
							"inv" 	  => $row['InvQty'],
							"pck"     => $row['PickQTy'],
						);
						array_push($skuArray["records"], $skuArr);
					}
					$skuArray["numberOfPage"]=$records_per_page;
					$skuArray["numberOfRecords"]=$rowCount;
					http_response_code(200);
					echo json_encode(array(
						"status" => 1,
						"skudata" => $skuArray,
					));
				}else{
					http_response_code(200);
					echo json_encode(array(
						"message" => 'No record found for racklocation ".$skuInputs->whlocation." And sku code ".$skuInputs->skuCode."',
						'failed' => true,
						"status" => 0,
						"skudata" => array(),
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