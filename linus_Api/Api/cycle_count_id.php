<?php
	ini_set("display_errors", 1);
	require './vendor/autoload.php';
	use \Firebase\JWT\JWT;
	include_once("./headers/swim_eprontoexpress.php");
	include_once("./classes/api_class.php");
	$secret_key = "owt125";
	$jwt = null;
	$cycleCountDataObj = new Api;
	if($_SERVER['REQUEST_METHOD'] === "POST"){
		$headers = getallheaders();
		$jwt = $headers["authorization"];
		$token = explode(" ", $jwt)[1];
		$cycleCount = json_decode(file_get_contents("php://input"));
		if(!empty($cycleCount->wh) && !empty($cycleCount->Custid)){
			try{
				$decoded_data = JWT::decode($token, $secret_key, array('HS512'));

				$cycleCountData = $cycleCountDataObj->cycleCountId($cycleCount->wh, $cycleCount->Custid);
                //echo json_encode($clientData); exit;
				$cycleCountDataArray = array();
                $cycleCountDataArray["records"]=array();
				while($row = sqlsrv_fetch_array($cycleCountData, SQLSRV_FETCH_ASSOC))
				{
					$cycleCountDetails =  array(
                        "Batchid" =>$row['Batchid']
                       
					);
					array_push($cycleCountDataArray["records"], $cycleCountDetails);
				}
				http_response_code(200);
				echo json_encode(array(
					"status" => 1,
					"cycleCountBatchid" => $cycleCountDataArray
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