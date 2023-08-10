<?php
	ini_set("display_errors", 1);
	require './vendor/autoload.php';
	use \Firebase\JWT\JWT;
	include_once("./headers/swim_eprontoexpress.php");
	include_once("./classes/api_class.php");
	$secret_key = "owt125";
	$jwt = null;
	$zoneWiseDataObj = new Api;
	if($_SERVER['REQUEST_METHOD'] === "POST"){
		$headers = getallheaders();
		$jwt = $headers["authorization"];
		$token = explode(" ", $jwt)[1];
		$zoneWiseDataInputs = json_decode(file_get_contents("php://input"));
		if(!empty($zoneWiseDataInputs->wh) && !empty($zoneWiseDataInputs->custid) && !empty($zoneWiseDataInputs->custinvno)){
			try{
				$decoded_data = JWT::decode($token, $secret_key, array('HS512'));
				$page = isset($_GET['page']) ? $_GET['page'] : 1;
				$records_per_page = $page;
				$numer_of_records =3;
				
				$zoneWiseData = $zoneWiseDataObj->getZoneWiseData($zoneWiseDataInputs->wh, $zoneWiseDataInputs->custid, $zoneWiseDataInputs->custinvno, $records_per_page, $numer_of_records);

				$zoneWiseDataArray = array();
				$zoneWiseDataArray["records"]=array();
				$zoneWiseDataArray["numberOfPage"]=array();
				$zoneWiseDataArray["numberOfRecords"]=array();
				while($row = sqlsrv_fetch_array($zoneWiseData, SQLSRV_FETCH_ASSOC))
				{
					$pendingBoxsDetails =  array(
						"zone" 				=> $row['ZONE'],
						"zoneBarCode" 		=> $row['ZONEBARCODE'],
						"totalQty" 			=> $row['TotalQty'],
						"totalScanned" 	    => $row['TotalScanned']
					);
					array_push($zoneWiseDataArray["records"], $pendingBoxsDetails);
				}
				$zoneWiseDataArray["numberOfPage"]=$records_per_page;
				$zoneWiseDataArray["numberOfRecords"]=$zoneWiseDataObj->GetZoneWiseDataCount($zoneWiseDataInputs->wh, $zoneWiseDataInputs->custid, $zoneWiseDataInputs->custinvno);
				$zoneWiseDataArray["totalScan"] = $zoneWiseDataObj->totalScan($zoneWiseDataInputs->wh, $zoneWiseDataInputs->custid, $zoneWiseDataInputs->dnno);
				http_response_code(200);
				echo json_encode(array(
					"status" => 1,
					"getZoneWiseData" => $zoneWiseDataArray
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