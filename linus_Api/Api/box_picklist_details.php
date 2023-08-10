<?php
	ini_set("display_errors", 1);
	require './vendor/autoload.php';
	use \Firebase\JWT\JWT;
	include_once("./headers/swim_eprontoexpress.php");
	include_once("./classes/api_class.php");
	$secret_key = "owt125";
	$jwt = null;
	$boxPickListDetailsObj = new Api;
	if($_SERVER['REQUEST_METHOD'] === "POST"){
		$headers = getallheaders();
		$jwt = $headers["authorization"];
		$token = explode(" ", $jwt)[1];
		$boxPickListInputs = json_decode(file_get_contents("php://input"));
		if(!empty($boxPickListInputs->wh) && !empty($boxPickListInputs->dnno)){
			try{
				$decoded_data = JWT::decode($token, $secret_key, array('HS512'));
				$page = isset($_GET['page']) ? $_GET['page'] : 1;
				$records_per_page = $page;
				$numer_of_records =5;
				$boxPickListDetails = $boxPickListDetailsObj->getBoxPickListDetails($boxPickListInputs->wh, $boxPickListInputs->dnno, $records_per_page, $numer_of_records);
				//echo json_encode($boxPickListDetails);exit;
				$boxPickListDetailsArray = array();
				$boxPickListDetailsArray["records"]=array();
				$boxPickListDetailsArray["numberOfPage"]=array();
				$boxPickListDetailsArray["numberOfRecords"]=array();
				while($row = sqlsrv_fetch_array($boxPickListDetails, SQLSRV_FETCH_ASSOC))
				{
					$pendingBoxsDetails =  array(
						"location" 		=> $row['whlocation'],
						"skuCode" 		=> $row['sku'],
						"description" 	=> $row['skudesc'],
						"inv" 			=> $row['InvQty'],
						"pck" 			=> $row['PickQTy'],
					);
					array_push($boxPickListDetailsArray["records"], $pendingBoxsDetails);
				}
				$boxPickListDetailsArray["numberOfPage"]=$records_per_page;
				$boxPickListDetailsArray["numberOfRecords"]=$boxPickListDetailsObj->PendingBoxDetailsCount($boxPickListInputs->wh, $boxPickListInputs->dnno);
				http_response_code(200);
				echo json_encode(array(
					"status" => 1,
					"boxPickListDetails" => $boxPickListDetailsArray
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