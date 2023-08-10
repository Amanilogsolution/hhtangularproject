<?php
	ini_set("display_errors", 1);
	require './vendor/autoload.php';
	use \Firebase\JWT\JWT;
	include_once("./headers/swim_eprontoexpress.php");
	include_once("./classes/api_class.php");
	$secret_key = "owt125";
	$jwt = null;
	$pendingBoxsObj = new Api;
	if($_SERVER['REQUEST_METHOD'] === "POST"){
		$headers = getallheaders();
		$jwt = $headers["authorization"];
		$token = explode(" ", $jwt)[1];
		$pendingBoxInputs = json_decode(file_get_contents("php://input"));
		if(!empty($pendingBoxInputs->locationId))
		{
			try{
				$decoded_data = JWT::decode($token, $secret_key, array('HS512'));
				$page = isset($_GET['page']) ? $_GET['page'] : 1;
				$records_per_page = $page;
				$numer_of_records =3;
				$pendingBoxData = $pendingBoxsObj->getPendingBoxData($pendingBoxInputs->locationId, $pendingBoxInputs->dnno ?? null, $records_per_page, $numer_of_records);
				$pendingBoxArray = array();
				$pendingBoxArray["records"]=array();
				$pendingBoxArray["numberOfPage"]=array();
				$pendingBoxArray["numberOfRecords"]=array();
				//echo json_encode($pendingBoxData); exit;
				while($row = sqlsrv_fetch_array($pendingBoxData, SQLSRV_FETCH_ASSOC))
				{
					$pendingBoxs= array(
						"dnno" 		 => $row['dnno'],
						"custid" 	 => $row['custid'],
						"custinvno"  => $row['custinvno'],
						"InvQty"	 => $row['InvQty'],
						"PickQTy"    => $row['PickQTy']
					);
					array_push($pendingBoxArray["records"], $pendingBoxs);
				}
				$pendingBoxArray["numberOfPage"]=$records_per_page;
				$pendingBoxArray["numberOfRecords"]=$pendingBoxsObj->PendingBoxCount($pendingBoxInputs->locationId);
				http_response_code(200); // Ok
				echo json_encode(array(
					"status" => 1,
					"pendingBoxlist" => $pendingBoxArray
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
				"message" => "Please Send Location Id"
			));
		}
	}	
?>	