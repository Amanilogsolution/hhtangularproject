<?php
	ini_set("display_errors", 1);
	require './vendor/autoload.php';
	use \Firebase\JWT\JWT;
	include_once("./headers/swim_eprontoexpress.php");
	include_once("./classes/api_class.php");
	$secret_key = "owt125";
	$jwt = null;
	$rackLocationObj = new Api;
	if($_SERVER['REQUEST_METHOD'] === "POST")
	{
		$headers = getallheaders();
		$jwt = $headers["authorization"];
		$token = explode(" ", $jwt)[1];
		$rackLocationInputs = json_decode(file_get_contents("php://input"));
		if(!empty($rackLocationInputs->wh) && !empty($rackLocationInputs->dnno) && !empty($rackLocationInputs->whlocation) && !empty($rackLocationInputs->custid) && !empty($rackLocationInputs->zoneBarcode) && !empty($rackLocationInputs->aisles) ){
			try{
				$decoded_data = JWT::decode($token, $secret_key, array('HS512'));
				$page = isset($_GET['page']) ? $_GET['page'] : 1;
				$records_per_page = $page;
				$numer_of_records =1;
				
				$rackLocationData = $rackLocationObj->getNewRackLocation($rackLocationInputs->wh, $rackLocationInputs->dnno, $rackLocationInputs->whlocation, $rackLocationInputs->custid, $rackLocationInputs->zoneBarcode, $rackLocationInputs->aisles,  $records_per_page, $numer_of_records);
				$rowCount = $rackLocationObj->rackLocationCountt($rackLocationInputs->wh, $rackLocationInputs->dnno, $rackLocationInputs->whlocation);
				$totalAislesScan = $rackLocationObj->totalAislesScan($rackLocationInputs->wh, $rackLocationInputs->custid, $rackLocationInputs->dnno, $rackLocationInputs->zoneBarcode, $rackLocationInputs->aisles);
				$totalScan = $rackLocationObj->totalScan($rackLocationInputs->wh, $rackLocationInputs->custid, $rackLocationInputs->dnno);
			if($rowCount>0)
			{
				$rackLocationArray = array();
				$rackLocationArray["records"]=array();
				$rackLocationArray["numberOfPage"]=array();
				$rackLocationArray["numberOfRecords"]=array();
				while($row = sqlsrv_fetch_array($rackLocationData, SQLSRV_FETCH_ASSOC))
				{
					$rackLocationArr = array(
						"skuCode" => $row['sku'],
						"skudesc" => $row['skudesc'],
						"batchno" => $row['batchno'],
						"inv" => $row['InvQty'],
						"pck" => $row['PickQTy'],
					);
					array_push($rackLocationArray["records"], $rackLocationArr);
				}
				$rackLocationArray["numberOfPage"]=$records_per_page;
				$rackLocationArray["numberOfRecords"]=$rowCount;
				$rackLocationArray["totalAislesScan"]=$totalAislesScan;
				$rackLocationArray["totalScan"]=$totalScan;
				http_response_code(200);
				echo json_encode(array(
					"status" => 1,
					"rackLocation" => $rackLocationArray
				));
			}else{
				http_response_code(200);
					echo json_encode(array(
						"message" => 'Record could not found',
						'failed' => true,
						"status" => 0,
						"rackLocation"=>array(),
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