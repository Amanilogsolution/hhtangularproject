<?php
	ini_set("display_errors", 1);
	require './vendor/autoload.php';
	use \Firebase\JWT\JWT;
	include_once("./headers/swim_eprontoexpress.php");
	include_once("./classes/api_class.php");
	$secret_key = "owt125";
	$jwt = null;
	$trackDataObj = new Api;
	if($_SERVER['REQUEST_METHOD'] === "POST"){
		$headers = getallheaders();
		$jwt = $headers["authorization"];
		$token = explode(" ", $jwt)[1];
		$trackDataInputs = json_decode(file_get_contents("php://input"));
		if(!empty($trackDataInputs->wh) && !empty($trackDataInputs->custid) && !empty($trackDataInputs->custinvno) && !empty($trackDataInputs->zoneBarcode) && !empty($trackDataInputs->aisles)){
			try{
				$decoded_data = JWT::decode($token, $secret_key, array('HS512'));
				$page = isset($_GET['page']) ? $_GET['page'] : 1;
				$records_per_page = $page;
				$numer_of_records =3;
				
				$trackData = $trackDataObj->getTrackData($trackDataInputs->wh, $trackDataInputs->custid, $trackDataInputs->custinvno, $trackDataInputs->zoneBarcode, $trackDataInputs->aisles, $records_per_page, $numer_of_records);

				//ECHO $trackData; exit;

				$trackDataArray = array();
				$trackDataArray["records"]=array();
				$trackDataArray["numberOfPage"]=array();
				$trackDataArray["numberOfRecords"]=array();
				while($row = sqlsrv_fetch_array($trackData, SQLSRV_FETCH_ASSOC))
				{
					$pendingBoxsDetails =  array(
						"whLocation" 		=> $row['WHLOCATION'],
						"totalQty" 			=> $row['TotalQty'],
						"totalScanned" 	    => $row['TotalScanned'],
						"totalSku" 	        => $row['TOTALSKU']
					);
					array_push($trackDataArray["records"], $pendingBoxsDetails);
				}
				$trackDataArray["numberOfPage"]=$records_per_page;
				$trackDataArray["numberOfRecords"]=$trackDataObj->getTrackDataCount($trackDataInputs->wh, $trackDataInputs->custid, $trackDataInputs->custinvno, $trackDataInputs->zoneBarcode, $trackDataInputs->aisles);
				http_response_code(200);
				echo json_encode(array(
					"status" => 1,
					"gettrackData" => $trackDataArray
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