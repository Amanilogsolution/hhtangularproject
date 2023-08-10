<?php
	ini_set("display_errors", 1);
	require './vendor/autoload.php';
	use \Firebase\JWT\JWT;
	include_once("./headers/swim_eprontoexpress.php");
	include_once("./classes/api_class.php");
	$secret_key = "owt125";
	$jwt = null;
	$inventoryBoxDataObj = new Api;
	if($_SERVER['REQUEST_METHOD'] === "POST"){
		$headers = getallheaders();
		$jwt = $headers["authorization"];
		$token = explode(" ", $jwt)[1];
		$cycleCount = json_decode(file_get_contents("php://input"));
		if(!empty($cycleCount->BOXNO)){
			try{
				$decoded_data = JWT::decode($token, $secret_key, array('HS512'));
                
                $page = isset($_GET['page']) ? $_GET['page'] : 1;
				$records_per_page = $page;
				$numer_of_records = 10;

				$inventoryBoxData = $inventoryBoxDataObj->getInventroyBox($cycleCount->BOXNO);

                $inventoryBoxDataArray = array();
				$inventoryBoxDataArray["records"]=array();
				$inventoryBoxDataArray["numberOfPage"]=array();
				$inventoryBoxDataArray["numberOfRecords"]=array();
                $zoneWiseData = $inventoryBoxDataObj->getInventroyBoxTable($cycleCount->BOXNO, $records_per_page, $numer_of_records);
                while($row = sqlsrv_fetch_array($zoneWiseData, SQLSRV_FETCH_ASSOC))
				{
					$pendingBoxsDetails =  array(
						"SKU" 	=> $row['SKU'],
					    "QTY"   => $row['QTY']
						
					);
					array_push($inventoryBoxDataArray["records"], $pendingBoxsDetails);
				}
				$inventoryBoxDataArray["numberOfPage"]=$records_per_page;
				$inventoryBoxDataArray["numberOfRecords"]=$inventoryBoxDataObj->getInventroyBoxTableCount($cycleCount->BOXNO);
				
				http_response_code(200);
				echo json_encode(array(
					"status" => 1,
					"BoxHeader" => $inventoryBoxData,
                    "BoxTable" => $inventoryBoxDataArray
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